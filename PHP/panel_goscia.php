

<?php
require_once "connect.php";

$polaczenie= new mysqli($host, $db_user, $db_password, $db_name);

session_start();
?>

<!DOCTYPE html>
<html lang="pl"><head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="viewport" content="width-device-width, initial-scale=1.0">

<title>Witaj w Kiszkowiance!</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
<style>
    #images {
      top: 10px;

      display: flex;
      justify-content: center;
      height: 50vh; 
      padding: 10px 10px 10px 10px;
      border: 2.66667px;
      margin: 10px 10px; 
    }
    .boxy {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 50vh;
    padding: 10px;
    margin: 10px;
    }
  </style>
</head>
<body>

<div id="images" class="boxy">
<script>
    // z tutoriala ze strony https://www.youtube.com/watch?v=4YQ4svkETS0&ab_channel=TraversyMedia
    var i=0; //indeks startowy
    var images=[]; //pusta tablica
    var time= 2000; //ustawia milisekundy miedzy wyswietlaniem obrazkow, czyli 2 sekundy

    images[0]= 'zdj1.jpg';
    images[1]= 'zdj2.jpg';
    images[2]= 'zdj3.jpg';
    images[3]= 'zdj4.jpg';
// funkcja do zmieniania obrazkow

function changeImg(){
document.slide.src=images[i];
if (i<images.length-1){
i++;
} else {
i=0;
}
setTimeout("changeImg()", time)
}
window.onload=changeImg;

</script>

<img name="slide" width="200" height="200">
</div>


<div class="container my-3 text-center"> 
<?php
if(isset($_SESSION['login'])){
$login=$_SESSION['login'];
echo"<p>Witaj"." ".$login."!</p>";
?>
Twoja rezerwacja
<table class="table">
  <thead>
    <tr>
      <th scope="col">Liczba Gości</th>
      <th scope="col">Data przyjazdu</th>
      <th scope="col">Data wyjazdu</th>
      <th scope="col">Usługi dodatkowei</th>
      <th scope="col">Rodzaj płatność</th>
      <th scope="col">Kwota</th>
      <th scope="col">Rodzaj rachunku</th>
      <th scope="col">Kolor domku</th>
      <th scope="col">Rezerwacja potwierdzona</th>
    </tr>
  </thead>

  <tbody>

  <?php




$sql="SELECT*from Booking JOIN person on Booking.Person_ID=Person.Person_ID WHERE Person.login=?";
$stmt= $polaczenie->prepare($sql);
$stmt->execute([$login]);
$result=$stmt->get_result();
while($row=$result->fetch_assoc()) {
    echo "<tr>";
    echo "<th scope='row'>" .$row['Number_of_persons']. "</th>";
    echo "<td>" . $row['Schedule_arrival'] . "</td>";
    echo "<td>" . $row['Schedule_departure'] . "</td>";
    echo "<td>" . $row['Special_service'] . "</td>";
    echo "<td>" . $row['Payment_name'] . "</td>";
    echo "<td>" . $row['Price'] . "</td>";
    echo "<td>" . $row['Bill_type'] . "</td>";
    echo "<td>" . $row['Color_of_the_cabin'] . "</td>";
    echo "<td>" . $row['Booking_confirmed'] . "</td>";

    echo "<td>";

echo "</td>";
    echo "</tr>";
}
?>
  </tbody>
</table>
<?php
$sql="SELECT Person_ID from person where login=?";
$stmt= $polaczenie->prepare($sql);
$stmt->execute([$login]);
$personid= $result=$stmt->get_result()->fetch_assoc()['Person_ID'];
echo "<button style='margin-right:25px' class='btn btn-primary'> <a href='aktualizujmojedane.php' class='text-light'> Aktualizuj dane osobowe </a> </button>";
echo "<button style='margin-right:25px' class='btn btn-primary'> <a href='wyloguj.php' class='text-light'> Wyloguj się </a> </button>";
echo "<button style='margin-right:25px' class='btn btn-primary'> <a href='usunkonto.php' class='text-light'> Usuń konto </a> </button>";

?>
<p><a href='rezerwacjapobytu.php'>Zarezerwuj pobyt w wybranym terminie</a></p>
<?php
}
else {
  header('Location: index.php');
}
?>
</div>




</body>

</head>
</html>
