<?php
/*
CRUD według instrukcji ze strony
https://www.youtube.com/watch?v=72U5Af8KUpA&ab_channel=StepbyStep
 */

include 'connect.php';

$polaczenie= new mysqli($host, $db_user, $db_password, $db_name);

if ($polaczenie->connect_errno){
    die("Błąd połączenia:".$polaczenie->connect_error);
}
if(isset($_GET['deleteid'])){
    $id=$_GET['deleteid'];
    $sql="delete from person where Person_ID=?";
$stmt=$polaczenie->prepare($sql);
$stmt->bind_param("i", $id);
if($stmt->execute()){
    header('location:panel_admina.php');
}
else {
    die ("Nie udało się usunąć rekordu:".$stmt->error);
}

}


?>