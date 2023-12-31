
<?php
require_once "connect.php";

$polaczenie= new mysqli($host, $db_user, $db_password, $db_name);

session_start();
if ($_SESSION['login']!='admin'){
    echo "brak uprawnień!";
  }
else {  
    if (isset($_GET['odrzuc'])){
    $sql="DELETE from booking WHERE Booking_ID=?";
    $stmt= $polaczenie->prepare($sql);
    $stmt->execute([$_GET['odrzuc']]);
    }
    elseif (isset($_GET['potwierdz'])){
            $sql="UPDATE booking SET Booking_confirmed=1 WHERE Booking_ID=?";
            $stmt= $polaczenie->prepare($sql);
            $stmt->execute([$_GET['potwierdz']]);
    }

$sql="SELECT*from Booking where Booking_confirmed=false";
$stmt= $polaczenie->prepare($sql);

$stmt->execute();
$result=$stmt->get_result();
while($row=$result->fetch_assoc()) {
print_r($row);
echo "<br>";

?>
<a href='?potwierdz=<?php
echo $row['Booking_ID']
?>
'><button type="submit" class="btn btn-primary" name="potwierdzrezerwacje">Potwierdz </button> </a>, <br>;
<a href='?odrzuc=<?php
echo $row ['Booking_ID']
?>'><button type="submit" class="btn btn-primary" name="odrzucrezerwacje">Odrzuc</button></a>;

<br>;
<?php
}
}

?>
