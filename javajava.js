// Fuctions for actions
function action(act){
    console.log(act);
$.ajax({
type: "POST",
    url: "functions.php",
		cache: false,
		data: {
			action: act,
			user: user,
			loguser: loguser,
			username: username,
			logname: logname,
			veepname: veepname
		}
})
.done (function() {
location.reload();
})
.fail (function(jqXHR, textStatus, errorThrown) {
alert(errorThrown);
});
 }
function noti(){
window.location = 'noti.php';
}
function mail(){
window.location = 'mail.php';
}
function adopt(veepnum, vtc){
    console.log(veepnum, vtc);
$.ajax({
type: "POST",
    url: "functions.php",
		cache: false,
		data: {
			veep: veepnum,
			vtc: vtc,
			user: user,
			loguser: loguser,
			username: username,
			logname: logname
		}
})
.done (function() {
window.location = 'home.php';
})
.fail (function(jqXHR, textStatus, errorThrown) {
alert(errorThrown);
});
 }
function veeptocoin(vtc){
    console.log(vtc);
var r = confirm("Are you sure you want to sell your veep for " + vtc + " Veep Coin?\n\nThis can not be undone.");
if (r == true) {
$.ajax({
type: "POST",
    url: "functions.php",
		cache: false,
		data: {
			sell: vtc,
			user: user,
			loguser: loguser,
			username: username,
			logname: logname
		}
})
.done (function() {
window.location = 'home.php';
})
.fail (function(jqXHR, textStatus, errorThrown) {
alert(errorThrown);
});
}
}
function friend(act){
    console.log(act);
$.ajax({
type: "POST",
    url: "functions.php",
		cache: false,
		data: {
			friend: act,
			user: user,
			loguser: loguser,
			username: username,
			logname: logname
		}
})
.done (function() {
location.reload();
})
.fail (function(jqXHR, textStatus, errorThrown) {
alert(errorThrown);
});
}
function frienddeny(uid){
    console.log(uid);
$.ajax({
type: "POST",
    url: "functions.php",
		cache: false,
		data: {
			friend: 2,
			user: uid,
			loguser: loguser
		}
})
.done (function() {
location.reload();
})
.fail (function(jqXHR, textStatus, errorThrown) {
alert(errorThrown);
});
}
function friendconfirm(uid){
    console.log(uid);
$.ajax({
type: "POST",
    url: "functions.php",
		cache: false,
		data: {
			friend: 3,
			user: uid,
			loguser: loguser
		}
})
.done (function() {
location.reload();
})
.fail (function(jqXHR, textStatus, errorThrown) {
alert(errorThrown);
});
}
function item(uid,itemid,itemcost,durability,consumable){
    console.log(uid,itemid,itemcost,durability,consumable);
var r = confirm("Are you sure you want to buy this item for " + itemcost + " Veep Coin?\n\nAll sales are final.");
if (r == true) {
$.ajax({
type: "POST",
    url: "functions.php",
		cache: false,
		data: {
			user: uid,
			itemid: itemid,
			cost: itemcost,
			durability: durability,
			consumable: consumable,
		}
})
.done (function() {
location.reload();
})
.fail (function(jqXHR, textStatus, errorThrown) {
alert(errorThrown);
}); 
}
}
function useitem(itemkey,veepnum){
    console.log(itemkey,veepnum);
var itemtype=document.getElementById(itemkey).value;
var itemname=document.getElementById('itemname' + itemkey).value;
$.ajax({
type: "POST",
    url: "functions.php",
		cache: false,
		data: {
			itemkey: itemkey,
			itemtype: itemtype,
			itemname: itemname,
			veepnum: veepnum,
			user: user,
			loguser: loguser,
			username: username,
			logname: logname,
			veepname: veepname,
		}
})
.done (function() {
location.reload();
})
.fail (function(jqXHR, textStatus, errorThrown) {
alert(errorThrown);
}); 
}