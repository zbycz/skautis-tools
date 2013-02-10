<?php

require "SkautisAuth/auth.php";
SkautisAuth::start();

$hash = md5(uniqid(rand()));

file_put_contents($hash, serialize(SkautisAuth::getUserInfo()) );

?>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Přesměrování</title>

<form action="http://www.blanik.info/?do=SkautisAuthPlugin-login" method="post" id="form">
	<input type="hidden" name="hash" value="<?php echo $hash; ?>">
	<input type="submit" value="Pokračovat do administrace" id="submit">
</form>

<script>
document.getElementById("submit").value = "Probíhá přesměrování...";
document.getElementById("submit").click();
</script>

