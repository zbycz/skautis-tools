<?php

require_once 'SkautisAuth/SkautIS.php';
$skautis = SkautIS::getInstance();

if(!$skautis->isLogged()){
	echo $skautis->getLoginForm();
}
else {
	$person = $skautis->getLoggedPerson();
	echo "You are logged as $person->FirstName $person->LastName<br>\n";
	echo "<a href='logout.php'>logout</a>\n";
	echo "<pre>";

	echo "<hr>person<br>";
	print_r($person);
	
	echo "<hr>user<br>";
	$user = $skautis->getLoggedUser();
	$user->getRoles();
	print_r($user);
}

?>

LoginUpdateRefresh(skautIS_Token)
skautIS_IDRole - číslo role, do které je uživatel ve skautISu rovnou přihlášen (v této roli naposledy ve skautISu pracoval). Uživatele lze přehlásit do jiné role pomocí 
---- WS UserManagement => LoginUpdate(skautIS_Token, idNoveRole)  ---

skautIS_IDUnit - číslo jednotky, ke které se váže role, do které je uživatel přihlášen

Například jedinečné číslo uživatele získáte pomocí UserManagement => UserDetail(skautIS_Token) -

