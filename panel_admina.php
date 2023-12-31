<?php


/*
CRUD według instrukcji ze strony
https://www.youtube.com/watch?v=72U5Af8KUpA&ab_channel=StepbyStep
 */


require_once "connect.php";

$polaczenie= new mysqli($host, $db_user, $db_password, $db_name);
session_start();


if ($polaczenie->connect_errno){
    die("Błąd połączenia".$polaczenie->connect_error);
}

if ($_SESSION['login']!='admin'){
  echo "brak uprawnień!";
}

?>


<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE-edge">
<meta name="viewport" content="width=device-width,
initial-scale=1.0">
<title>Panel administratora</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">

</head>


<body>
<?php if ($_SESSION['login']!='admin'){
  echo "brak uprawnień!";
}
else {
  ?>


<div class="container">
    <button class="btn btn-primary my-5">
    <a href="formularz_rejestracji.php" class="text-light">Dodaj użytkownika</a><br>

</button>
<button class="btn btn-primary my-5">
    <a href="rezerwacjapobytu.php" class="text-light">Dodaj rezerwację</a><br>
</button>
<button class="btn btn-primary my-5">
    <a href="nowarezerwacjapotwierdz.php" class="text-light">Lista rezerwacji</a><br>
</button>
<p>Lista zarejestrowanych użytkowników</p>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Numer</th>
      <th scope="col">Imię</th>
      <th scope="col">Nazwisko</th>
      <th scope="col">Dowód tożsamości</th>
      <th scope="col">PESEL</th>
      <th scope="col">Numer telefonu</th>
      <th scope="col">Email</th>
      <th scope="col">Login</th>
      <th scope="col">Opcje </th>
    </tr>
  </thead>

  <tbody>
  <?php

$sql="SELECT * from person ";
$stmt=$polaczenie->prepare($sql);
$stmt->execute();
if ($stmt->execute()){
    $result = $stmt->get_result();
while ($row=$result->fetch_assoc()){
 
    echo "<tr>";
    echo "<th scope='row'>" .$row['Person_ID']. "</th>";
    echo "<td>" . $row['Name'] . "</td>";
    echo "<td>" . $row['Surname'] . "</td>";
    echo "<td>" . $row['ID_card'] . "</td>";
    echo "<td>" . $row['PESEL'] . "</td>";
    echo "<td>" . $row['Phone_number'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>" . $row['login'] . "</td>";
    echo "<td>";
  echo "<button class='btn btn-primary'> <a href='aktualizuj.php?aktualizujid=".$row['Person_ID']."'class='text-light'>Aktualizuj dane</a> </button>";
echo "<button class='btn btn-danger'><a href='usun.php?deleteid=".$row['Person_ID']."' class='text-light'>Usuń dane</a> </button>";

echo "</td>";
    echo "</tr>";

}
}
else {
die ("Błąd wykonania zapytania:".$stmt->error);
}
$stmt->close();
$polaczenie->close();

?>



  </tbody>
</table>  

</div>
<?php
}
?>
</body>

</head>

</html>