<?php
session_start();

require_once "connect.php";

$polaczenie= @new mysqli($host, $db_user, $db_password, $db_name);




?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <style> .container div {margin: 25px ;}</style>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>Rezerwujesz pobyt w Kiszkowiance</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5"> 
        <?php
    if(isset($_POST['wyslijprosbeorezerwacje'])) {
 
        $liczbaosob=$_POST['liczbaosob'];
        $uslugidodatkowe=[isset($_POST['niechce']), isset($_POST['zwierze']), isset($_POST['lozeczkodladziecka']), 
        isset($_POST['karmidelko']), isset($_POST['posciel'])];
        $service=implode(",", $uslugidodatkowe);
$dataprzyjazdu=$_POST['Schedule_arrival'];
$dataodjazdu=$_POST['Schedule_departure'];
$formaplatnosci=$_POST['Payment_type'];
$formarachunku=$_POST['Bill_type'];
$kolordomku=$_POST['Color_of_the_cabin'];

$sql="SELECT Person_ID from person WHERE login=?";
$stmt=$polaczenie->prepare($sql);
$result=$stmt->execute([$_SESSION['login']]);
$Person_ID=$stmt->get_result()->fetch_assoc()["Person_ID"];

        $sql="INSERT into booking(Person_ID, Number_of_persons, Schedule_arrival, Schedule_departure, Special_service, Payment_name, Bill_type, Color_of_the_cabin)
        values (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt=$polaczenie->prepare($sql);
        $result=$stmt->execute([$Person_ID, $liczbaosob, $service, $dataprzyjazdu, $dataodjazdu, $formaplatnosci, $formarachunku, $kolordomku]);

?>
<p>Dziękujemy, Twoja prośba została wysłana do Kiszkowianki. Wkrótce się z Tobą skontaktujemy</p> <br>

<a href='panel_goscia.php'><button type="submit" class="btn btn-primary" name="powrót">Wróć do panelu Gościa</button></a>


<?php
} else {
   
if (isset($_GET['aktualizujid'])){
    $aktualizujid=$_GET['aktualizujid'];
    $sql="SELECT * from booking where Booking_ID=?";
    $stmt->prepare($sql);
    $stmt->execute($aktualizujid);
    $row=$stmt->get_result()->fetch_assoc();
  
}



    ?>

  <form method="post">
  <div class="form-group">

<label for="dane_goscia">Czy rezerwujesz pobyt tylko dla siebie?:</label>

<select name="potwierdz_dane_goscia" id="potwierdz_dane_goscia">
  <option value="tak">Tak</option>
  <option value="nie">Nie, poniżej wprowadzę dane Gości</option>
</select>
<input type="text" class="form-control" id="dane_gosci" name="dane_gosci" autocomplete="off">


</div>
<div>
<label for="liczbaosob">Podaj liczbę osób, które przyjadą do Kiszkowianki:</label>
<input type="text" class="form-control" id="Number_of_persons" name="liczbaosob" autocomplete="off">

  </div>
  
  <div class="form-group">

  <label for="Special_service"> Czy chcesz skorzystać z dodatkowego serwisu?</label><br>
<input type="checkbox" id="niechce" name="niechce" value="niechce">
<label for="niechce"> Nie, dziękuję </label><br> 

<input type="checkbox" id="zwierze" name="zwierze" value="zwierze">
<label for="zwierze"> Przyjazd ze zwięrzęciem 120 PLN </label><br>

<input type="checkbox" id="lozeczkodladziecka" name="lozeczkodladziecka" value="lozeczkodladziecka">
<label for="lozeczkodladziecka"> Łóżeczko dla dziecka gratis </label><br>

<input type="checkbox" id="karmidelko" name="karmidelko" value="karmidelko">
<label for="karmidelko"> Karmidełko dla dziecka gratis </label><br>

<input type="checkbox" id="posciel" name="posciel" value="posciel">
<label for="posciel"> Dodatkowe dwa komplety pościeli 200 PLN </label><br>
 
</select>
</div>

<div>
<label for="Schedule_arrival">Wpisz datę przyjazdu:</label>
<input type="date" class="form-control" id="Schedule_arrival" name="Schedule_arrival" autocomplete="off">

</div>
<div>
<label for="Schedule_departure">Wpisz datę wyjazdu:</label>
<input type="date" class="form-control" id="Schedule_departure" name="Schedule_departure" autocomplete="off">
</div>
<div>
<label for="Payment_type"> Zaznacz wybraną formę płatności</label>
<select name="Payment_type" id="Payment_type">
  <option value="przelew">Przelew</option>
  <option value="gotowka">Gotówka</option>
</select>
</div>
<div>
<label for="Bill_type"> Zaznacz wybraną formę rachunku</label>
<select name="Bill_type" id="Bill_type">
  <option value="paragon">Paragon</option>
  <option value="faktura">Faktura</option>
</select>
</div>
<div>
<label for="Color_of_the_cabin"> Możesz wybrać preferowany kolor domku, jeżeli będzie dostępny, to zarezerwujemy go dla Ciebie</label>
<select name="Color_of_the_cabin" id="Color_of_the_cabin">
  <option value="czarny">Czarny</option>
  <option value="niebieski">Niebieski</option>
  <option value="czerwony">Czerwony</option>

</select>
</div>
<label>Potwierdzam wprowadzone dane i wysyłam prośbę o rezerwację</label>
<button type="submit" class="btn btn-primary" name="wyslijprosbeorezerwacje">Wyślij</button>
</form>
<?php
}
?>
</div>

</body>

</html>

<?php


?>
