<?php

/**
 * Script, ktery zpracovava prihlaseni predane SkautISem
 */

require_once 'SkautIS.php';
$skautis = SkautIS::getInstance();
$login_ok = $skautis->loginHelper->doLogin();

if(!$login_ok)
	die("Chyba: skautis nepredal ocekavane parametry - kontaktujte admin@blanik.info");


$person = $skautis->getLoggedPerson();
$data = array(
	'rc' => $person->IdentificationCode,
	'jmeno' => $person->DisplayName,
	'email' => $person->Email,
	);

//přihlášení do SkautisAuth
require_once 'auth.php';
SkautisAuth::register($data);


$return = $_REQUEST['ReturnUrl'];
header("Location: http://is.blanik.info/$return");
exit;
