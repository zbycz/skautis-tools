<?php

/**
 * Konfiguracni script. Zde je mozne nadefinovat jednotlive parametry.
 * 
 */

/**
 * Adresa na niz bezi skautis (adresa testovaciho nebo produkcniho skautisu)
 * + id aplikace, ktere vam vytvori administratori skauisu
 */
if(!defined("applicationAdress"))
	define("applicationAdress", "http://test-is.skaut.cz/");


if(!defined("appId"))
	define("appId", "f69e9a7e-4ac5-4942-8672-39085c9ca99b");



/**
 * do techto promennych nesahejte, pouze pokud se nezmeni nastaveni serveru
 */
define("skautisPath", applicationAdress."Junak/");
define("wsdlAdress", applicationAdress."JunakWebservice/");
define("loginFormAddres", applicationAdress."Login/?appid=".appId);
//http://test-is.skaut.cz/Login/?appid=f69e9a7e-4ac5-4942-8672-39085c9ca99b


