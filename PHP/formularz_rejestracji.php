<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">

<title>Kiszkowianka wiejskie wakacje</title>
</head>
<body>
    <div class="container my-5"> 
<?php
/*
CRUD według instrukcji ze strony
https://www.youtube.com/watch?v=72U5Af8KUpA&ab_channel=StepbyStep
 */

require_once "connect.php";

$polaczenie= @new mysqli($host, $db_user, $db_password, $db_name);


if(isset($_POST['submit'])) {

$name=$_POST['name'];
$surname=$_POST['surname'];
$ID_card=$_POST['ID_card'];
$PESEL=$_POST['PESEL'];
$Phone_number=$_POST['Phone_number'];
$email=$_POST['email'];
$login=$_POST['login'];
$pass=$_POST['pass'];


$sql="insert into person (name, surname, ID_card, PESEL, Phone_number, email, login, pass)
values(?,?,?,?,?,?,?,?)";
$stmt=$polaczenie->prepare($sql);
$result=$stmt->execute([$name, $surname, $ID_card, $PESEL, $Phone_number, $email, $login, $pass]);

if($result){
echo "Konto użytkownika zostało pomyślnie utworzone";
?>
<br> 
<br>
<form action="index.php" method="post">
<button type="submit" class="btn btn-primary" name="zaloguj">Zaloguj</button>
</form>
<?php
}
else {
    die(mysqli_error($polaczenie));
}

} else {


?>


  <form method="POST">
  <div class="form-group">
    <label >Imię</label>
    <input type="text" class="form-control"
    placeholder="Wprowadź imię" name="name" autocomplete="off">
  </div>
  <div class="form-group">
    <label >Nazwisko</label>
    <input type="text" class="form-control"
    placeholder="Wprowadź nazwisko" name="surname" autocomplete="off">
  </div>
  <div class="form-group">
    <label >Dowód tożsamości</label>
    <input type="varchar" class="form-control"
    placeholder="Wprowadź numer dowodu tożsamości: dowód osobisty lub paszport" name="ID_card" autocomplete="off">
  </div>
  <div class="form-group">
    <label >PESEL</label>
    <input type="bigint" class="form-control"
    placeholder="Jeżeli jesteś obywatelem Polski, wprowadź numer PESEL" name="PESEL" autocomplete="off">
  </div>
  <div class="form-group">
    <label >Numer telefonu</label>
    <input type="char" class="form-control"
    placeholder="Wprowadź numer telefonu w formacie xx xxx xxx xxx" name="Phone_number" autocomplete="off">
  </div>
  <div class="form-group">
    <label >Email</label>
    <input type="email" class="form-control"
    placeholder="Wprowadź email" name="email" autocomplete="off">
  </div>
  <div class="form-group">
    <label >Login</label>
    <input type="varchar" class="form-control"
    placeholder="Wprowadź wybrany login" name="login" autocomplete="off">
  </div>
 
  <div class="form-group">
    <label >Hasło</label>
    <input type="password" class="form-control"
    placeholder="Wprowadź hasło: limit 12 znaków" name="pass" autocomplete="off">
  </div>

  <button type="submit" class="btn btn-primary" name="submit">Zarejestruj</button>
</form>
</body>

</html>





<?php

}
?>
