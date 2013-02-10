<?php
/**
 * Script, ktery obsluhuje odhlaseni
 */
require_once 'SkautIS.php';

$skautis = SkautIS::getInstance();
$token = $skautis->loginHelper->getLoginId();
$skautis->loginHelper->logout();

header("location: ".applicationAdress."login/LogOut.aspx?AppID=".appId."&token=".$token);
