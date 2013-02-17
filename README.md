SkautIS tools
=============
Kontakt na autora: pif@skaut.cz

Vizte též: http://casopisy.skaut.cz a www.blanik.info/gskautis

AnonymSkautis - výpis info o oddílu
-------------
Viz https://raw.github.com/zbycz/skautis-tools/master/AnonymSkautis/oddily.htm

nebo živá verze na www.blanik.info/oddily


SkautisAuth - přihlášení na vlastní web
-----------
Nejdříve je potřeba zažádat o [registraci aplikace](http://is.skaut.cz/napoveda/programatori.1-zadost-o-registraci-vytvareneho-webu-v-testovacim-skautISu.ashx) do [test-is.skaut.cz](http://test-is.skaut.cz) API.
 * URL webu: `http://server/path/to/index.php`
 * URL tokenu: `http://server/path/to/SkautisAuth/token.php`
 * HTML stránka při přihlášení: `http://server/path/to/SkautisAuth/login_motd.php`


1. **Příklad použití:**
	```php
	require "SkautisAuth/auth.php";
	SkautisAuth::start();
	//Po tomto volání už projde pouze člověk, který má záznam v lokálním seznamu uživatelů.
	
	//ověříme konkrétní přístup
	if(SkautisAuth::getData('ltoi') != 'ano') die('Sem nemůžeš');
	```
	
2. Odhlášení možno zavoláním `SkautisAuth::logout();`
	
3. Osobní údaje ze skautisu jsou k dispozici ve `SkautisAuth::getUserInfo()`:
	```
	Array:
    [rc] => 891227/0004
    [jmeno] => Střediskopřivničák Jiří (Slon)
    [email] => slon@koprivnice.cz
    [key] => 891227#19
    [data] => Array:
            [rč#hash] => 891227#19
            [nazev] => testis
            [oddil] => 0020
            [inv] => admin
            [ltoi] => 0
            [rop] => 0
	```
	
	
4. Databáze lokálních uživatelů je ve `skautis/skautisauth_users.csv`
	* Řádek přihlášeného uživatele je dostupný přes `SkautisAuth::getUserInfo('data')`, resp. aliasem `SkautisAuth::getData()`.
	* První řádek souboru se použije jako klíče tohoto asociativního datového pole.
	* Identita uživatele je ověřena porovnáním prvního sloupce s klíčem (první část RČ + hash).
	* Pro vypsání tohoto klíče v excelu je možné využít formule:
	  `=CONCATENATE(MID(C9;1;6); "#"; 2^MID(C9;8;1)+2^MID(C9;9;1)+2^MID(C9;10;1)+2^MID(C9;11;1))`
	* V reálném skautisu lze zvolit vlastní export členů střediska a zaškrtnout RČ.


**Testovací údaje do [test-skautisu](http://test-is.skaut.cz):**
- člověk se záznamem v lokální databázi:

  jméno: stredisko.koprivnice

  heslo: koprivnice.Web5

- bez záznamu v lokální databázi:

  jméno: snem.sneznik.uc

  heslo: ucastnik1



