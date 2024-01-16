<?php
/*
CRUD według instrukcji ze strony
https://www.youtube.com/watch?v=72U5Af8KUpA&ab_channel=StepbyStep
 */

require_once "connect.php";

$polaczenie= @new mysqli($host, $db_user, $db_password, $db_name);

$id=$_GET['aktualizujid'];

if(isset($_POST['submit'])) {

$name=$_POST['name'];
$surname=$_POST['surname'];
$ID_card=$_POST['ID_card'];
$PESEL=$_POST['PESEL'];
$Phone_number=$_POST['Phone_number'];
$email=$_POST['email'];
$login=$_POST['login'];
$pass=$_POST['pass'];


$sql="update person set name=?, surname=?, ID_card=?, PESEL=?, Phone_number=?, email=?, login=?, pass=?
 WHERE Person_ID=$id";
$stmt=$polaczenie->prepare($sql);
$result=$stmt->execute([$name, $surname, $ID_card, $PESEL, $Phone_number, $email, $login, $pass]);

if($result){
    header('location:panel_admina.php');
?>
<form action="index.php" method="post">
<button type="submit" class="btn btn-primary" name="zaloguj">Zaloguj</button>
</form>
<?php
}
else {
    die(mysqli_error($polaczenie));
}

} else {
$sql= "SELECT * from person WHERE Person_ID=?";
$stmt= $polaczenie->prepare($sql);
$stmt->execute([$id]);
$result=$stmt->get_result();
while($row=$result->fetch_assoc()) {


?>

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
  <form method="POST" >
  <div class="form-group">
    <label >Imię</label>
    <?php
    echo '<input type="text" class="form-control"
    placeholder="Wprowadź imię" name="name" value="'.$row['Name'].'"autocomplete="off">';
    ?>
  </div>
  <div class="form-group">
    <label >Nazwisko</label>
    <?php
    echo '<input type="text" class="form-control"
    placeholder="Wprowadź nazwisko" name="surname" value="'.$row['Surname'].'"autocomplete="off">';
    ?>
  </div>
  <div class="form-group">
    <label >Dowód tożsamości</label>
    <?php
    echo '<input type="text" class="form-control"
    placeholder="Wprowadź dane dowodu tożsamości" name="ID_card" value="'.$row['ID_card'].'"autocomplete="off">';
    ?>
  </div>
  <div class="form-group">
    <label >PESEL</label>
    <?php
        echo '<input type="text" class="form-control"
    placeholder="Wprowadź PESEL" name="PESEL" value="'.$row['PESEL'].'"autocomplete="off">';
    ?>
  </div>
  <div class="form-group">
    <label >Numer telefonu</label>
    <?php
    echo '<input type="text" class="form-control"
    placeholder="Wprowadź numer telefonu" name="Phone_number" value="'.$row['Phone_number'].'"autocomplete="off">';
    ?>
  </div>
  <div class="form-group">
    <label >Email</label>
    <?php
    echo '<input type="text" class="form-control"
    placeholder="Wprowadź email" name="email" value="'.$row['email'].'"autocomplete="off">';
    ?>
  </div>
  <button type="submit" class="btn btn-primary" name="submit">Aktualizuj</button>
</form>
</body>
</html>

<?php

}
}
?>
