<?php


require_once "Conf.php";

/******************************
 *     SkautisAuth            *
/******************************

1) Použití:
	> require "skautis/auth.php";
	> SkautisAuth::start();
	
	Po tomto volání už projde pouze člověk, který má záznam v lokálním seznamu uživatelů. Následně je tedy možno ověřit jestli má přístup do konkrétního systému, např. if(SkautisAuth::getData('ltoi') != 'ano') die('Sem nemůžeš');

2) Odhlášení možno zavoláním SkautisAuth::logout();

3) Osobní údaje ze skautisu jsou k dispozici ve SkautisAuth::getUserInfo():
	Array:
    [rc] => 891227/0004
    [jmeno] => Střediskopřivničák Jiří (Slon)
    [email] => veronika.strnadova@junak.cz
    [key] => 891227#19
    [data] => Array:
            [rč#hash] => 891227#19
            [nazev] => testis
            [oddil] => 0020
            [inv] => admin
            [ltoi] => 0
            [rop] => 0


4) Databáze je ve skautis/skautisauth_users.csv a řádek přihlášeného uživatele je dostupný přes SkautisAuth::getUserInfo('data'), resp. aliasem SkautisAuth::getData().
	První řádek souboru se použije jako klíče tohoto datového pole.
	Identita uživatele je ověřena porovnáním prvního sloupce s klíčem (první část RČ + hash).
	Pro vypsání tohoto klíče v excelu je možné využít formule:
	  =CONCATENATE(MID(C9;1;6); "#"; 2^MID(C9;8;1)+2^MID(C9;9;1)+2^MID(C9;10;1)+2^MID(C9;11;1))
	V reálném skautisu lze zvolit vlastní export členů střediska a zaškrtnout RČ.

Testovací údaje do test-skautisu:
- člověk se záznamem v lokální databázi:
  jméno: stredisko.koprivnice
  heslo: koprivnice.Web5

- bez záznamu v blanické databázi:
  jméno: snem.sneznik.uc
  heslo: ucastnik1

*/

class SkautisAuth {
	static function register(Array $is_data){
		session_start();
		
		//klíč rodné číslo s hashem
		$rc = $is_data['rc'];
		$key = substr($rc, 0, 6).'#'. (pow(2,$rc{7}) +pow(2,$rc{8}) +pow(2,$rc{9}) +pow(2,$rc{10}));
		$is_data['key'] = $key;
		$is_data['data'] = false;
		$_SESSION['SkautisAuth'] = $is_data;


		//naplnění oprávnění
		$csv = self::_getCsv(dirname(__FILE__).'/skautisauth_users.csv');
		if(!$csv) die('Chyba: soubor opravneni chybi - kontaktujte admin@blanik.info');
		
		list($hdr, $data) = $csv;
		foreach($data as $r){
			if($r[0] == $key){
				if($_SESSION['SkautisAuth']['data'])
					die("Chyba: dva uzivatele se stejnym id $key - kontaktujte admin@blanik.info");
			
				$_SESSION['SkautisAuth']['data'] = array_combine($hdr, $r);
			}
		}
	}
	
	static function start(){
		session_start();
		
		//přihlášení skautisu
		if(!isset($_SESSION['SkautisAuth']) or !$_SESSION['SkautisAuth']){
			$return = "&ReturnUrl=" . urlencode(substr($_SERVER["REQUEST_URI"],1));
			header("Location: ".loginFormAddres.$return);
			exit;
		}
		
		//přihlášení pomocí CSVčka
		if(!self::getUserInfo('data')){
			echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
			echo "<title>CHYBA - IS střediska Blaník</title>";
			echo "<h1>CHYBA: Přihlášený uživatel nemá přístup do IS</h1>";
			echo "<p>Pro získání oprávnění zašlete, prosím, mail na: admin@blanik.info";
			echo "<p>Uveďte klíč: " . self::getUserInfo('key');
			echo "<br>a jméno: " . self::getUserInfo('jmeno');
			echo "<p><a href='?'>zkusit znovu</a>";
			$_SESSION['SkautisAuth'] = NULL;
			exit;
		}
	}
	
	static function logout(){
		session_start();
		$_SESSION['SkautisAuth'] = NULL;
		header("Location: http://is.blanik.info/");
		exit;
	}
	
	
	static function getUserInfo($key=NULL){
		if($key == NULL)
			return $_SESSION['SkautisAuth'];
		return $_SESSION['SkautisAuth'][$key];
	}
	
	static function getData($key=NULL){
		if($key == NULL)
			return $_SESSION['SkautisAuth']['data'];
		return $_SESSION['SkautisAuth']['data'][$key];
	}
	
	
	static function _getCsv($filename){
	  $hdr = false;
	  $data = array();
	  
	  $handle = fopen($filename, "r");
	  if(!$handle) return false;
	  
	  while (($r = fgetcsv($handle, 0, ";")) !== FALSE) {
	  	array_walk($r, create_function('&$val', '$val = trim($val);'));
	    if(!$hdr)
	      $hdr = $r;
	    else
	      $data[] = $r;
	  }
	  fclose($handle);
	  
	  return array($hdr, $data);
	}

}

