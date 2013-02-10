<?php

require "SkautisAuth/auth.php";
SkautisAuth::start();
//Po tomto volání už projde pouze èlovìk, který má záznam v lokálním seznamu uživatelù.


if($_GET['logout']) SkautisAuth::logout();

echo "<pre><a href='?logout=1'>logout</a>";
echo "<p>SkautisAuth::getData('ltoi'): " . SkautisAuth::getData('ltoi');
echo "<p>SkautisAuth::getUserInfo('jmeno'): " . SkautisAuth::getUserInfo('jmeno');

echo "<p>SkautisAuth::getUserInfo()<br>";
print_r( SkautisAuth::getUserInfo() );

