<?php
//utworz plik db_config.json o nastepujacej strukturze:
//{
//"db_pass":"<tuwpiszswojehaslo>"
//}
$jstring=file_get_contents("../db_config.json");

$pass=json_decode($jstring, true);


$host="localhost";
$db_user="kiszkowianka";
$db_password=$pass["db_pass"];
$db_name="hotel";




?>