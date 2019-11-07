<?php
// Check for Friend ID
if(isset($_GET['friendid']))
{
if($_GET['friendid'] !== $_SESSION["id"]){
$friendid = $_GET['friendid'];
$user = $friendid;
} else {
$user = $_SESSION["id"];
$friendid = '';
}
}
else {
$user = $_SESSION["id"];
$friendid = '';
}
// Set Main Var
$filterwords = "(Ã Å›Å¡ fÃ¢ÄÃ«|anal|anus|arse|ass|ass fuck|ass hole|assfucker|asshole|assshole|bastard|bitch|black cock|bloody hell|boong|cock|cockfucker|cocksuck|cocksucker|coon|coonnass|crap|cunt|cyberfuck|damn|dick|douche|erect|erection|erotic|fag|faggot|fuck|Fuck off|fuck you|fuckass|fuckhole|god damn|gook|homoerotic|hore|lesbian|lesbians|mother fucker|motherfuck|motherfucker|negro|nigger|orgasim|orgasm|penis|penisfucker|piss|piss off|porn|porno|pornography|pussy|retard|sadist|sex|sexy|shit|slut|son of a bitch|suck|tits|viagra|whore|thong)";
$loguser = $_SESSION["id"];
$logname = $_SESSION["username"];
$sql = "SELECT *, TIMESTAMPDIFF(second, lastlog, NOW()) AS TimeDiff, DATE_FORMAT(daycare, '%m-%d-%Y %r') AS DayOut FROM `users` JOIN `veeps` WHERE users.username = veeps.username AND id='$user'";
$lsql = "SELECT * FROM `users` JOIN `veeps` WHERE users.username = veeps.username AND id='$loguser'";
$result = mysqli_query($link, $sql);
$lresult = mysqli_query($link, $lsql);
$row = mysqli_fetch_assoc($result);
$lrow = mysqli_fetch_assoc($lresult);
$firstlog = $lrow["firstlog"];
$username = $row["username"];
$tdaycare = $row["daycare"];
$daycareout = $row["daycare"];
$email = $lrow["email"];
$gender = $row["gender"];
$country = $row["country"];
$emailsub = $lrow["emailsub"];
$wallet = $lrow["wallet"];
$userbio = $row["bio"];
$birthday = $lrow["birth"];
$ohealth = $row["health"];
$hungermod = $row["hungermod"];
$happinessmod = $row["happinessmod"];
$hygienemod = $row["hygienemod"];
$energymod = $row["energymod"];
$veepid = $row["veepid"];
$veepnum = $row["veepnum"];
$lveepid = $lrow["veepid"];
$veepname = $row["veepname"];
$lveepname = $lrow["veepname"];
$ohunger = $row["hunger"];
$ohappiness = $row["happiness"];
$ohygiene = $row["hygiene"];
$hat = $row["hat"];
$oexperience = $lrow["experience"];
$experience = $row["experience"];
$level = ceil($experience);
$oenergy = $row["energy"];
$otokens = $lrow["tokens"];
$uid = $lrow["id"];
$stataffect = $row["TimeDiff"];
$status = '';
$lastlog = $row["lastlog"];
$loglevel = $lrow["loginlevel"];

// In Daycare
if($lastlog < $tdaycare && $daycareout !== '0000-00-00 00:00:00'){
$daycare = 'yes';
$stataffect = '0';
} elseif ($daycareout !== '0000-00-00 00:00:00'){
$daycare = '';
$outofdaycaresql = "SELECT *, TIMESTAMPDIFF(second, daycare, NOW()) AS TimeDiff FROM `users` JOIN `veeps` WHERE users.username = veeps.username AND id='$user'";
$resetdaycare = "UPDATE `users` JOIN `veeps` SET daycare='0000-00-00 00:00:00' WHERE users.username = veeps.username AND id='$user'";
$oodresult = mysqli_query($link, $outofdaycaresql);
$oodrow = mysqli_fetch_assoc($oodresult);
$stataffect = $oodrow["TimeDiff"];
mysqli_query($link, $resetdaycare);
}
// Set Time Var
$time = new DateTime('NOW');
$timelog = $time->format('YmdHi');
$currentyear = $time->format('Y');

// Figure out age
if(ereg("^([0-9]{4})-([0-9]{2})-([0-9]{2})", $birthday, $parts)){
$userage = ($currentyear - intval($parts[1]));
}

//Notifications 
$notisql = "SELECT  max(`id`) as max_id, `post`, `logger`, `unread`, `direction`, DATE_FORMAT(time, '%r') FROM `log` WHERE direction='$logname' AND unread='true' GROUP BY post ORDER BY `max_id`  DESC";
$notiresult = mysqli_query($link, $notisql);
$onoticount = mysqli_num_rows($notiresult);
$allnotisql = "SELECT max(`id`) as max_id, `post`, `logger`, `unread`, `direction`, DATE_FORMAT(time, '%r') FROM `log` WHERE direction='$logname' GROUP BY post ORDER BY `max_id` DESC LIMIT 0, 30";
$allnotiresult = mysqli_query($link, $allnotisql);
$getfriendrequest = "SELECT * FROM `friends` WHERE friend_one=$loguser AND status = 1";
$friendrequestresult = mysqli_query($link, $getfriendrequest);
$friendrequestnum = mysqli_num_rows($friendrequestresult);
$noticount = $onoticount;

// Mail
$mailsql = "SELECT  `id`, `subject`, `post`, `logger`, `unread`, `direction`, DATE_FORMAT(time, '%r') FROM `mail` WHERE direction='$logname' AND unread='true' ORDER BY `id`  DESC";
$mailresult = mysqli_query($link, $mailsql);
$omailcount = mysqli_num_rows($mailresult);
$allmailsql = "SELECT `id`, `subject`, `post`, `logger`, `unread`, `direction`, DATE_FORMAT(time, '%r') FROM `mail` WHERE direction='$logname' ORDER BY `id`  DESC LIMIT 0, 30";
$allmailresult = mysqli_query($link, $allmailsql);
$mailcount = $omailcount;

// Country's
$countrylist = array("US" => "United States",
"AF" => "Afghanistan",
"AX" => "Åland Islands",
"AL" => "Albania",
"DZ" => "Algeria",
"AS" => "American Samoa",
"AD" => "Andorra",
"AO" => "Angola",
"AI" => "Anguilla",
"AQ" => "Antarctica",
"AG" => "Antigua and Barbuda",
"AR" => "Argentina",
"AM" => "Armenia",
"AW" => "Aruba",
"AU" => "Australia",
"AT" => "Austria",
"AZ" => "Azerbaijan",
"BS" => "Bahamas",
"BH" => "Bahrain",
"BD" => "Bangladesh",
"BB" => "Barbados",
"BY" => "Belarus",
"BE" => "Belgium",
"BZ" => "Belize",
"BJ" => "Benin",
"BM" => "Bermuda",
"BT" => "Bhutan",
"BO" => "Bolivia",
"BA" => "Bosnia and Herzegovina",
"BW" => "Botswana",
"BV" => "Bouvet Island",
"BR" => "Brazil",
"IO" => "British Indian Ocean Territory",
"BN" => "Brunei Darussalam",
"BG" => "Bulgaria",
"BF" => "Burkina Faso",
"BI" => "Burundi",
"KH" => "Cambodia",
"CM" => "Cameroon",
"CA" => "Canada",
"CV" => "Cape Verde",
"KY" => "Cayman Islands",
"CF" => "Central African Republic",
"TD" => "Chad",
"CL" => "Chile",
"CN" => "China",
"CX" => "Christmas Island",
"CC" => "Cocos (Keeling) Islands",
"CO" => "Colombia",
"KM" => "Comoros",
"CG" => "Congo",
"CD" => "Congo, The Democratic Republic of The",
"CK" => "Cook Islands",
"CR" => "Costa Rica",
"CI" => "Cote D'ivoire",
"HR" => "Croatia",
"CU" => "Cuba",
"CY" => "Cyprus",
"CZ" => "Czech Republic",
"DK" => "Denmark",
"DJ" => "Djibouti",
"DM" => "Dominica",
"DO" => "Dominican Republic",
"EC" => "Ecuador",
"EG" => "Egypt",
"SV" => "El Salvador",
"GQ" => "Equatorial Guinea",
"ER" => "Eritrea",
"EE" => "Estonia",
"ET" => "Ethiopia",
"FK" => "Falkland Islands (Malvinas)",
"FO" => "Faroe Islands",
"FJ" => "Fiji",
"FI" => "Finland",
"FR" => "France",
"GF" => "French Guiana",
"PF" => "French Polynesia",
"TF" => "French Southern Territories",
"GA" => "Gabon",
"GM" => "Gambia",
"GE" => "Georgia",
"DE" => "Germany",
"GH" => "Ghana",
"GI" => "Gibraltar",
"GR" => "Greece",
"GL" => "Greenland",
"GD" => "Grenada",
"GP" => "Guadeloupe",
"GU" => "Guam",
"GT" => "Guatemala",
"GG" => "Guernsey",
"GN" => "Guinea",
"GW" => "Guinea-bissau",
"GY" => "Guyana",
"HT" => "Haiti",
"HM" => "Heard Island and Mcdonald Islands",
"VA" => "Holy See (Vatican City State)",
"HN" => "Honduras",
"HK" => "Hong Kong",
"HU" => "Hungary",
"IS" => "Iceland",
"IN" => "India",
"ID" => "Indonesia",
"IR" => "Iran, Islamic Republic of",
"IQ" => "Iraq",
"IE" => "Ireland",
"IM" => "Isle of Man",
"IL" => "Israel",
"IT" => "Italy",
"JM" => "Jamaica",
"JP" => "Japan",
"JE" => "Jersey",
"JO" => "Jordan",
"KZ" => "Kazakhstan",
"KE" => "Kenya",
"KI" => "Kiribati",
"KP" => "Korea, Democratic People's Republic of",
"KR" => "Korea, Republic of",
"KW" => "Kuwait",
"KG" => "Kyrgyzstan",
"LA" => "Lao People's Democratic Republic",
"LV" => "Latvia",
"LB" => "Lebanon",
"LS" => "Lesotho",
"LR" => "Liberia",
"LY" => "Libyan Arab Jamahiriya",
"LI" => "Liechtenstein",
"LT" => "Lithuania",
"LU" => "Luxembourg",
"MO" => "Macao",
"MK" => "Macedonia, The Former Yugoslav Republic of",
"MG" => "Madagascar",
"MW" => "Malawi",
"MY" => "Malaysia",
"MV" => "Maldives",
"ML" => "Mali",
"MT" => "Malta",
"MH" => "Marshall Islands",
"MQ" => "Martinique",
"MR" => "Mauritania",
"MU" => "Mauritius",
"YT" => "Mayotte",
"MX" => "Mexico",
"FM" => "Micronesia, Federated States of",
"MD" => "Moldova, Republic of",
"MC" => "Monaco",
"MN" => "Mongolia",
"ME" => "Montenegro",
"MS" => "Montserrat",
"MA" => "Morocco",
"MZ" => "Mozambique",
"MM" => "Myanmar",
"NA" => "Namibia",
"NR" => "Nauru",
"NP" => "Nepal",
"NL" => "Netherlands",
"AN" => "Netherlands Antilles",
"NC" => "New Caledonia",
"NZ" => "New Zealand",
"NI" => "Nicaragua",
"NE" => "Niger",
"NG" => "Nigeria",
"NU" => "Niue",
"NF" => "Norfolk Island",
"MP" => "Northern Mariana Islands",
"NO" => "Norway",
"OM" => "Oman",
"PK" => "Pakistan",
"PW" => "Palau",
"PS" => "Palestinian Territory, Occupied",
"PA" => "Panama",
"PG" => "Papua New Guinea",
"PY" => "Paraguay",
"PE" => "Peru",
"PH" => "Philippines",
"PN" => "Pitcairn",
"PL" => "Poland",
"PT" => "Portugal",
"PR" => "Puerto Rico",
"QA" => "Qatar",
"RE" => "Reunion",
"RO" => "Romania",
"RU" => "Russian Federation",
"RW" => "Rwanda",
"SH" => "Saint Helena",
"KN" => "Saint Kitts and Nevis",
"LC" => "Saint Lucia",
"PM" => "Saint Pierre and Miquelon",
"VC" => "Saint Vincent and The Grenadines",
"WS" => "Samoa",
"SM" => "San Marino",
"ST" => "Sao Tome and Principe",
"SA" => "Saudi Arabia",
"SN" => "Senegal",
"RS" => "Serbia",
"SC" => "Seychelles",
"SL" => "Sierra Leone",
"SG" => "Singapore",
"SK" => "Slovakia",
"SI" => "Slovenia",
"SB" => "Solomon Islands",
"SO" => "Somalia",
"ZA" => "South Africa",
"GS" => "South Georgia and The South Sandwich Islands",
"ES" => "Spain",
"LK" => "Sri Lanka",
"SD" => "Sudan",
"SR" => "Suriname",
"SJ" => "Svalbard and Jan Mayen",
"SZ" => "Swaziland",
"SE" => "Sweden",
"CH" => "Switzerland",
"SY" => "Syrian Arab Republic",
"TW" => "Taiwan, Province of China",
"TJ" => "Tajikistan",
"TZ" => "Tanzania, United Republic of",
"TH" => "Thailand",
"TL" => "Timor-leste",
"TG" => "Togo",
"TK" => "Tokelau",
"TO" => "Tonga",
"TT" => "Trinidad and Tobago",
"TN" => "Tunisia",
"TR" => "Turkey",
"TM" => "Turkmenistan",
"TC" => "Turks and Caicos Islands",
"TV" => "Tuvalu",
"UG" => "Uganda",
"UA" => "Ukraine",
"AE" => "United Arab Emirates",
"GB" => "United Kingdom",
"UM" => "United States Minor Outlying Islands",
"UY" => "Uruguay",
"UZ" => "Uzbekistan",
"VU" => "Vanuatu",
"VE" => "Venezuela",
"VN" => "Viet Nam",
"VG" => "Virgin Islands, British",
"VI" => "Virgin Islands, U.S.",
"WF" => "Wallis and Futuna",
"EH" => "Western Sahara",
"YE" => "Yemen",
"ZM" => "Zambia",
"ZW" => "Zimbabwe");

//set java var
echo '<script>';
echo 'var user = ' . json_encode($user) . ';';
echo 'var tokens = ' . json_encode($otokens) . ';';
echo 'var veepname = ' . json_encode($veepname) . ';';
echo 'var loguser = ' . json_encode($loguser) . ';';
echo 'var logname = ' . json_encode($logname) . ';';
echo 'var username = ' . json_encode($username) . ';';
echo '</script>';

// Functions
function value($vid){
	include 'config.php';
$vcountsql = "SELECT SUM(owned) AS totalveeps FROM `aval_veeps`"; 
$vcountresult = $link->query($vcountsql);
$vcountrow = $vcountresult->fetch_assoc();
$total_veeps = $vcountrow["totalveeps"];
$avalsql = "SELECT * FROM `aval_veeps` WHERE veepid='$vid'";
$ovcountresult = mysqli_query($link, $avalsql);
$ovcountrow = mysqli_fetch_assoc($ovcountresult);
$ototal_veeps = $ovcountrow["owned"]+1;
$rareofveep = $ovcountrow["rare"];
$veepvalue = floor($rareofveep*($total_veeps/$ototal_veeps));
return($veepvalue);
}
function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
function btc($veepcoinamount) {
  $url = 'https://api.coinmarketcap.com/v1/ticker/bitcoin/?convert=USD';
  $data = file_get_contents($url);
  $priceInfo = json_decode($data);
$bitcoinprice = $priceInfo[0]->price_usd;
function getBalance($address) {
    return file_get_contents('https://blockchain.info/de/q/addressbalance/'. $address);
}
$bitcoinbalance = (getBalance('17oJ6QhBgTVsg8apAzFHNX5TvMpyiVzieK'));
$btcconvert = $bitcoinbalance/1000000;
$veepcoinvalue = number_format((float)$btcconvert, 10, '.', '');
$veepcoinv = $veepcoinamount * $veepcoinvalue;
$fveepcoinv = number_format((float)$veepcoinv, 2, '.', '');
return($fveepcoinv);
}
?>