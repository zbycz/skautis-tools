<?php

require "SkautisAuth/auth.php";
SkautisAuth::start();
//tady uz je prihlaseny uzivatel --> 


if($_GET['logout']) SkautisAuth::logout();

echo "<pre><a href='?logout=1'>logout</a>";
echo "<p>SkautisAuth::getData('ltoi'): " . SkautisAuth::getData('ltoi');
echo "<p>SkautisAuth::getUserInfo('jmeno'): " . SkautisAuth::getUserInfo('jmeno');

echo "<p>SkautisAuth::getUserInfo()<br>";
print_r( SkautisAuth::getUserInfo() );


$isdata[jmeno] = SkautisAuth::getUserInfo('jmeno');
echo("<hr>writlog_here<hr>|||jmeno: ".$isdata[jmeno]);



