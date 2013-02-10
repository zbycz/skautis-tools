<?php

define("ZAPNI_NETTE_DEBUG", true); //kvůli šablonám

define("applicationAdress", "https://is.skaut.cz/");
define("appId", "e40c2f40-6a97-46f2-8c8b-e3e36b102cdc"); //ostrý is.blanik.info

require_once '../SkautisAuth/SkautIS.php';
$skautis = SkautIS::getInstance();

$tpl = '
<h2>{$oddil[nazev]} <small>(logo ručně - není anonymně v IS)</small></h2>
<h4>Členská základna</h4>
 Typ oddílu: koedukovaný/chlapecký/dívčí  - není v IS<br>
 Věková kategorie: mladší člení/starší členi/roveři/oldskauti - není v IS<br>
 Počet dětí: {$oddil[clenove_deti]}, dospělých: {$oddil[clenove_dospeli]}

<h4>Kontakt</h4>
 vedoucí oddílu: {$oddil[vedouci]}<br> 
 email: {$oddil[email]} <small n:if="$oddil[email_clovek]">({$oddil[email_clovek]})</small><br>
 telefon: {$oddil[telefon]} <small n:if="$oddil[telefon_clovek]">({$oddil[telefon_clovek]})</small><br>
 adresa webu: <a href="http://{$oddil[web]}">{$oddil[web]}</a>

<h4>Schůzky</h4>
{foreach $oddil[schuzky] as $schuzka}
 <p>{$schuzka[pohlavi]} {$schuzka[vek]} let
 		&mdash; {$schuzka[den]} {$schuzka[cas]}, 
		<small>pozn: {$schuzka[periodicita]}{if $schuzka[note]}, {$schuzka[note]}{/if}</small>
		<br>
 
 Klubovna: <a href="http://maps.google.com/maps?q={$schuzka[latlon]}">{$schuzka[adresa]}</a>,
   {$schuzka[note_klubovna]}, {$schuzka[popis]}<br>
{/foreach}
<!-- {print_r($oddil[schuzky_is])} -->

<h4>Krátce o oddílu (pole poznámka v skautisu)</h4> 
{$oddil[note]}
';

$template = new /*Nette\Templating\*/NTemplate();
$template->registerFilter(new /*Nette\Latte\Engine*/NLatteFilter);
$template->setSource($tpl);



$obj = $skautis->anonymServiceHelper->searchUnit(array('RegistrationNumber'=>'114.07'));
$oddily = $obj[0]->getSlavesUnits();
foreach($oddily as $o){
	$template->oddil = ziskejNaboroveInformace($o->ID);
	echo $template;
}




//id oddílu  (24218 == havrani)
function ziskejNaboroveInformace($id_oddilu){
	global $skautis;
	$obj = $skautis->anonymServiceHelper->getUnit($id_oddilu);
	
	$oddil = array();
	$udaje = $obj;
	$oddil['id'] = $id_oddilu;
	$oddil['nazev'] = substr(strstr($udaje->DisplayName, ','),  2);  //Junák - svaz skautů a skautek ČR, Havrani
	$oddil['note'] = $udaje->Note;
	
	//členové
	$clenove = $obj->getMembersCount();
	$oddil['clenove'] = array(
		'0-6'   => $clenove->RegularMembersTo6,
		'7-15'  => $clenove->RegularMembersTo15,
		'16-18' => $clenove->RegularMembersTo18,
		'19-26' => $clenove->RegularMembersTo26,
		'26+'   => $clenove->RegularMembersFrom26,
		);
	$oddil['clenove_rok'] = $clenove->Year;
	$oddil['clenove_deti'] = $clenove->RegularMembersTo6 + $clenove->RegularMembersTo15 + $clenove->RegularMembersTo18;
	$oddil['clenove_dospeli'] = $clenove->RegularMembersTo26 + $clenove->RegularMembersFrom26;
	
	
	//vedoucí
	$oddil['vedouci'] = $obj->getFunctionsAnonym("Statutory");

	//kontakty	
	$kontakty = $obj->getContactsAnonym();
	$oddil['email'] = @$kontakty['email_hlavni']->Value;
	$oddil['email_clovek'] = @$kontakty['email_hlavni']->Note;
	$oddil['telefon'] = substr(@$kontakty['telefon_hlavni']->DisplayValue, 5); //+420 773 109 100
	$oddil['telefon_clovek'] = @$kontakty['telefon_hlavni']->Note;
	$oddil['web'] = str_replace('http://','', @$kontakty['web']->Value);
	if($oddil['vedouci'] == $oddil['email_clovek']) $oddil['email_clovek'] = "";
	if($oddil['vedouci'] == $oddil['telefon_clovek']) $oddil['telefon_clovek'] = "";

	//schůzky
	$oddil['schuzky_is'] = $schuzky = $obj->getAdvertisingSummary();
	if(!is_array($schuzky)) $schuzky = array($schuzky);
	$oddil['schuzky'] = array();
	foreach($schuzky as $s){
		$cas = "$s->MeetingDate_TimeFrom - $s->MeetingDate_TimeTo";
		$cas = str_replace(array('PT','M'),'',$cas);
		$cas = str_replace('H',':',$cas);
		$cas = preg_replace('~:([^0-9]|$)~',':00\\1',$cas);
		
		$oddil['schuzky'][] = array(
			'ID_AdvertisingCategory' => $s->ID_AdvertisingCategory,
			'pohlavi' => $s->AdvertisingCategory_ID_Sex=='male' ? 'kluci' : 'holky',
			'vek' =>  "$s->AdvertisingCategory_AgeFrom - $s->AdvertisingCategory_AgeTo",
			'den' => $s->MeetingDate_WeekDay,
			'cas' =>  $cas,
			'periodicita' => $s->MeetingDate_Periodicity,
			'note' => $s->AdvertisingCategory_Note,
			
			'adresa' =>  "$s->Realty_Street, $s->Realty_City",
			'latlon' =>  "$s->Realty_GpsLatitude,$s->Realty_GpsLongitude",
			'adresa' =>  "$s->Realty_Street, $s->Realty_City",
			'popis' =>  $s->Realty_Description,
			'note_klubovna' => $s->Occupation_Note,
		);
	}
	
	return $oddil;
}

