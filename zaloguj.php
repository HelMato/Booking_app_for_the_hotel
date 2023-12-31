
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
   
</head>
<body>
   
<div class="container my-3 text-center"> 

<?php
/*
CRUD według instrukcji ze strony
https://www.youtube.com/watch?v=72U5Af8KUpA&ab_channel=StepbyStep
 */

session_start();

require_once "connect.php";

$polaczenie= @new mysqli($host, $db_user, $db_password, $db_name);

if ($polaczenie->connect_errno!=0){
    echo "Error:".$polaczenie->connect_errno;
}
else {
if (isset($_POST['login'])&&$_POST['haslo']){

    $login=$_POST['login'];
    $haslo=$_POST['haslo'];


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
$wiersz=$result->fetch_assoc();
$_SESSION['login']= $wiersz['login'];

$result->close(); 
if ($_SESSION['login']=='admin'){
    header('Location: panel_admina.php');

}
else {
header('Location: panel_goscia.php');
}
}else{
echo "Logowanie nie powiodło się, spróbuj ponownie";
}

}
    
    $polaczenie->close();
}
else if(isset($_SESSION['login'])){
header('Location: panel_goscia.php');
}
}
?>
</div>
</body>
</html>