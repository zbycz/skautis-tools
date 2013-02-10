<?php

define("applicationAdress", "https://is.skaut.cz/");
define("appId", "e40c2f40-6a97-46f2-8c8b-e3e36b102cdc"); //ostrý is.blanik.info

require "../SkautisAuth/auth.php";
$skautis = SkautIS::getInstance();

echo "<pre>";


/** /
//parametry:  DisplayName, IC, RegistrationNumber, Location, ParentDisplayName, ParentRegistrationNumber, isValid
$obj = $skautis->anonymServiceHelper->searchUnit(array('RegistrationNumber'=>'411.01'));
$obj = $obj[0];
/*/
// hledání konkrétního oddílu
//$obj = $skautis->anonymServiceHelper->getUnit(24217);
$obj = $skautis->anonymServiceHelper->getUnit(24218);
print_r($obj);
//*/

echo "<hr>getAdvertisingSummary<br>";
print_r( $obj->getAdvertisingSummary()  );

echo "<hr>getMembersCount<br>";
print_r($obj->getMembersCount());

echo "<hr>getFunctionsAnonym(Statutory)<br>";
print_r($obj->getFunctionsAnonym("Statutory"));

echo "<hr>getFunctionsAnonym(Assistant)<br>";
print_r($obj->getFunctionsAnonym("Assistant"));

echo "<hr>getFunctionsAnonym(Contact)<br>";
print_r($obj->getFunctionsAnonym("Contact"));

echo "<hr>getContactsAnonym<br>";
print_r($obj->getContactsAnonym());

echo "<hr>getSlavesUnits<br>";
print_r($obj->getSlavesUnits());


echo "<hr>";
