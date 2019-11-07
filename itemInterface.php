<?php

require_once "config.php";

class Item
{
    // Basic item info
    public $id;             // item ID
    public $name;           // item name
    public $imageLink;      // link to image on server
    public $cost;           // item cost (when in store)
    public $durability;     // item starting durability (if applicable)
    public $curDurability;  // item current durability (if applicable)
    public $consumable;     // true if item durability is applicable, false otherwise
    public $category;       // item category (food, toy, etc.)
	public $shop;       // item shop
    public $description;    // item description (if applicable)

    // Item stats -- these may be 0 if the item doesn't affect the stat, or negative if it reduces the stat
    public $happiness;
    public $hunger;
    public $hygiene;
    public $health;
    public $energy;
    public $experience;

    public function useItem($userID, $veepnum)
    {
        global $link;

        // Decrease durability (if applicable)
        if($this->consumable)
        {
            $newDurability = $this->curDurability - 1;

            // Update current durability in inventory
            if($stmt = $link->prepare("UPDATE inventory SET current_dur = ? WHERE item_ID = ? AND user_ID = ? AND current_dur = ? LIMIT 1"))
            {
                $stmt->bind_param('iiii', $newDurability, $this->id, $userID, $this->curDurability);

                $stmt->execute();

                $stmt->close();
            }

            // Update users inventory by clearing out all consumed items
            if ($stmt = $link->prepare("DELETE FROM inventory WHERE user_ID = ? AND consumable = TRUE AND current_dur <= 0"))
            {
                $stmt->bind_param('i', $userID);

                $stmt->execute();

                $stmt->close();
            }
        }

        // Update veep stats
        if ($stmt = $link->prepare("UPDATE veeps SET happiness = happiness + $this->happiness, 
                                           hunger = hunger + $this->hunger, hygiene = hygiene + $this->hygiene, 
                                           health = health + $this->health, energy = energy + $this->energy, 
                                           experience = experience + $this->experience WHERE veepnum = ?"))
        {
            $stmt->bind_param('s', $veepnum);

            $stmt->execute();

            $stmt->fetch();

            $stmt->close();
        }
    }
}

interface ItemInterface
{
    // =============================================================
    //                     Basic item functions
    // =============================================================

    // Retrieves all information for an item with given item ID
    public function get($id);

    // Returns an array of all the items a user has in their inventory
    public function getInventory($userID);

    // =============================================================
    //             Adding/Removing/Buying/Selling/Gifting
    // =============================================================

    // Inserts an item into a user's inventory -- returns 0 if successful, 1 if DB error, 2 if inventory full
    public function addItemToInventory($userID, $itemID, $durability, $consumable);

    // Deletes an item with the given ID and durability from user's inventory
    public function deleteItem($userID, $itemID, $curDurability);

    // Subtracts tokens from player and calls addItemToInventory -- returns 0 if successful
    // returns 1 in case of DB error, 2 if inventory full, 3 if lack of tokens
    public function buyItem($userID, $itemID, $itemCost, $durability, $consumable);

    // Sell an item to another player (item from user1 to user2, tokens from user2 to user1)
    // returns 0 on success, 1 in case of DB error, 2 if receiving player's inventory is full, 3 if buying player lacks tokens
    public function sellItem($user1ID, $user2ID, $itemID, $curDurability, $sellPrice);

    // Gifts an item from user1 to user2
    // returns 0 on success, 1 in case of DB error, 2 if receiving player's inventory is full
    public function giftItem($user1ID, $user2ID, $itemID, $curDurability);
}

class ItemMapper implements ItemInterface
{
    public function get($id)
    {
        $item = new Item;

        global $link;

        if ($stmt = $link->prepare("SELECT * FROM items WHERE id = ?"))
        {
            $stmt->bind_param('s', $id);

            $stmt->execute();

            $stmt->bind_result($item->id, $item->name, $item->description, $item->imageLink, $item->cost,
                $item->durability, $item->consumable, $item->category, $item->shop, $item->happiness,
                $item->hunger, $item->hygiene, $item->health, $item->energy, $item->experience);

            // Place holder durability -- actual current durability will  be checked from DB upon use
            $item->curDurability = 1;

            $stmt->fetch();

            $stmt->close();
        }

        return $item;
    }

    public function getInventory($userID)
    {
        $inventory = array();

        global $link;

        if ($stmt = $link->prepare("SELECT * FROM inventory WHERE user_ID = ?"))
        {
            $stmt->bind_param('s', $userID);

            $stmt->execute();

            $stmt->bind_result($userID, $id, $curDurability, $consumable);

            while($stmt->fetch())
            {
                // Get item info
                $item = New item();

		$item->curDurability = $curDurability;

                $item->id = $id;

                array_push($inventory, $item);
            }

            $stmt->close();

           for ($i = 0; $i <= count($inventory) - 1; $i++)
            {
                $curDurability = $inventory[$i]->curDurability;
                $inventory[$i] = $this->get($inventory[$i]->id);
                $inventory[$i]->curDurability = $curDurability;            
	    } 

            return $inventory;
        }
    }

    public function addItemToInventory($userID, $itemID, $durability, $consumable)
    {
        global $link;

        // Get current amount of items in players inventory
        if($stmt = $link->prepare("SELECT COUNT(*) FROM inventory JOIN items WHERE inventory.item_ID = items.id AND user_ID = ? AND category != 'Award' AND category != 'Accessory'"))
        {
            $stmt->bind_param('i', $userID);

            $stmt->execute();

            $stmt->bind_result($currentItemCount);

            $stmt->close();

            // Inventory full
            if($currentItemCount >= 100)
            {
                return 2;
            }

            else
            {
                if ($stmt = $link->prepare("INSERT INTO inventory (user_ID, item_ID, current_dur, consumable) VALUES
                                           (?, ?, ?, ?)"))
                {
                    $stmt->bind_param('iiii', $userID, $itemID, $durability, $consumable);

                    $stmt->execute();

                    $stmt->close();

                    return 0;
                }

                // DB Error
                else
                    return 1;
            }
        }

        // DB error
        else
            return 1;
    }

    public function buyItem($userID, $itemID, $itemCost, $durability, $consumable)
    {
        global $link;

        // Check users current tokens
        if($stmt = $link->prepare("SELECT tokens FROM users WHERE id = ?"))
        {
            $curTokens = 0;

            $stmt->bind_param('i', $userID);

            $stmt->execute();

            $stmt->bind_result($curTokens);

            $stmt->fetch();

            $stmt->close();

            // If they have enough, let them buy the item
            if($curTokens >= $itemCost)
            {
                $returnCode = $this->addItemToInventory($userID, $itemID, $durability, $consumable);

                // If item can be added to inventory, reduce token count -- if inventory is full or a DB error occurred
                // no tokens will be deducted.
                if($returnCode == 0)
                {
                    if ($stmt = $link->prepare("UPDATE users SET tokens = tokens - ? WHERE id = ?"))
                    {
                        $stmt->bind_param('ii', $itemCost,$userID);

                        $stmt->execute();

                        $stmt->close();
                    }
                }

                return $returnCode;
            }

            // Not enough tokens -- no tokens deducted
            else
                return 3;
        }

        // DB error
        else
            return 1;
    }

    public function deleteItem($userID, $itemID, $curDurability)
    {
        global $link;

        if ($stmt = $link->prepare("DELETE FROM inventory WHERE user_ID = ? AND item_ID = ? AND current_dur = ? LIMIT 1"))
        {
            $stmt->execute();

            $stmt->close();
        }
    }

    public function sellItem($user1ID, $user2ID, $itemID, $curDurability, $sellPrice)
    {
        global $link;

        // Check user2's current tokens
        if($stmt = $link->prepare("SELECT tokens FROM users WHERE id = ?"))
        {
            $stmt->bind_param('i', $user2ID);

            $stmt->execute();

            $stmt->bind_result($curTokens);

            $stmt->fetch();

            $stmt->close();

            if($curTokens >= $sellPrice)
            {
                // Deduct tokens from user2
                if($stmt = $link->prepare("UPDATE users SET tokens = tokens - ? WHERE id = ?"))
                {
                    $stmt->bind_param('ii', $sellPrice, $user2ID);

                    $stmt->execute();

                    $stmt->close();
                }

                // Add tokens to user1
                if($stmt = $link->prepare("UPDATE users SET tokens = tokens + ? WHERE id = ?"))
                {
                    $stmt->bind_param('ii', $sellPrice, $user1ID);

                    $stmt->execute();

                    $stmt->close();
                }

                $returnCode = $this->giftItem($user1ID, $user2ID, $itemID, $curDurability);

                return $returnCode;
            }

            // User 2 doesn't have enough tokens
            else
                return 3;
        }

        // DB Error
        else
            return 1;
    }

    public function giftItem($user1ID, $user2ID, $itemID, $curDurability)
    {
        global $link;

        // Get current number of items in receiving players inventory
        if($stmt = $link->prepare("SELECT COUNT(*) FROM inventory JOIN items WHERE inventory.item_ID = items.id AND user_ID = ? AND category != 'Award' AND category != 'Accessory'"))
        {
            $stmt->bind_param('i', $user2ID);

            $stmt->execute();

            $stmt->bind_result($currentItemCount);

            $stmt->fetch();

            $stmt->close();

            // Receiving player's inventory is full
            if($currentItemCount >= 100)
            {
                return 2;
            }

            else
            {
                if ($stmt = $link->prepare("UPDATE inventory SET user_ID = ? WHERE user_ID = ? AND item_ID = ? AND current_dur = ? LIMIT 1"))
                {
                    $stmt->bind_param('iiii', $user2ID, $user1ID, $itemID, $curDurability);

                    $stmt->execute();

                    $stmt->close();

                    return 0;
                }

                // DB Error
                else
                    return 1;
            }
        }

        // DB Error
        else
            return 1;
    }
}