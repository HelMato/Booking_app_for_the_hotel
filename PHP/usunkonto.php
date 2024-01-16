<!DOCTYPE html>
<html lang="pl"><head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="viewport" content="width-device-width, initial-scale=1.0">
<title>Usuwanie konta</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
</head>
<body>
    
<div class="container my-3 text-center"> 


<?php

session_start();
if(isset($_SESSION['login'])){

require_once "connect.php";

$polaczenie= @new mysqli($host, $db_user, $db_password, $db_name);

if ($polaczenie->connect_errno!=0){
    echo "Error:".$polaczenie->connect_errno;
}
else {
if (isset($_POST['haslo'] )){

    $haslo=$_POST['haslo'];
    $login=$_SESSION['login'];


$sql="SELECT * FROM person WHERE login=? and pass=?";
$stmt=$polaczenie->prepare($sql);
$stmt->bind_param('ss', $login, $haslo);
$stmt->execute();
$result=$stmt->get_result();
if ($result)
{
$ilu_userow=$result->num_rows;
if($ilu_userow>0)
{
$sql="DELETE FROM person WHERE login=? and pass=?";
$stmt=$polaczenie->prepare($sql);
$stmt->bind_param('ss', $login, $haslo);
$stmt->execute();
$_SESSION['usunkonto']=1;
header('Location: wyloguj.php');
}else{
echo "Niepoprawne hasło, nie udało się usunąć konta";
}
}
    $polaczenie->close();
}

else {
    ?>
    <form action="usunkonto.php" method="post">
    Wpisz hasło, aby potwierdzić usunięcie konta, tej operacji nie można cofnąć!<br/><input type="password" name="haslo"/><br/><br/>
    <input type="submit" value="Usuń konto" />
    </form>
    <?php
}
}
} else{
    header ('Location: index.php');
}
?>
</div>
</body>
</html>