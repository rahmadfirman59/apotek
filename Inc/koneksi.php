<?php
error_reporting(E_ALL);
$server	= "localhost";
$users	= "u1605741_bigbook";
$password = "bigbook123";
$db	= "u1605741_bigbook";

mysql_connect($server, $users, $password) or die(mysql_error());
mysql_select_db($db);

$no_error = mysql_errno();

if ($no_error == 1049) {
	echo "<meta http-equiv='refresh' content='0; url=install/'>";
} else {
	mysql_select_db($database);
}

function cek_install()
{
	$file = fopen("install/status.install", "r");
	$lineNum = 0;
	while (!feof($file)) {
		$isiFile .= fgets($file, 255);
		$line++;
	}
	fclose($file);

	$status_install = $isiFile;

	if ($status_install == "off") {
		echo "<meta http-equiv='refresh' content='0; url=install/index.php'>";
	} else {
		echo "<meta http-equiv='refresh' content='0; url=index.php'>";
	}
}
