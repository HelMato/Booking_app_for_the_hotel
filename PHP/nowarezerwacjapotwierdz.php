<?php
require_once "connect.php";

$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-3 text-center"> 

    <?php
    if ($_SESSION['login'] != 'admin') {
        echo "brak uprawnień!";
    } else {
        if (isset($_GET['odrzuc'])) {
            $sql = "DELETE from booking WHERE Booking_ID=?";
            $stmt = $polaczenie->prepare($sql);
            $stmt->execute([$_GET['odrzuc']]);
        } elseif (isset($_GET['potwierdz'])) {
            $sql = "UPDATE booking SET Booking_confirmed=1 WHERE Booking_ID=?";
            $stmt = $polaczenie->prepare($sql);
            $stmt->execute([$_GET['potwierdz']]);
        }

        $sql = "SELECT * FROM Booking WHERE Booking_confirmed=false";
        $stmt = $polaczenie->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    ?>
    <table class="table">
        <thead>
            <tr>                
              
                <th scope="col">Booking ID</th>
                <th scope="col">ID Osoby</th>
                <th scope="col">Liczba Gości</th>
                <th scope="col">Data przyjazdu</th>
                <th scope="col">Data wyjazdu</th>
                <th scope="col">Usługi dodatkowe</th>
                <th scope="col">Rodzaj płatności</th>
                <th scope="col">Kwota</th>
                <th scope="col">Rodzaj rachunku</th>
                <th scope="col">Kolor domku</th>
                <th scope="col">Rezerwacja potwierdzona</th>
                <th scope="col">Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row['Booking_ID']; ?></td>
                    <td><?php echo $row['Person_ID']; ?></td>
                    <td><?php echo $row['Number_of_persons']; ?></td>
                    <td><?php echo $row['Schedule_arrival']; ?></td>
                    <td><?php echo $row['Schedule_departure']; ?></td>
                    <td><?php echo $row['Special_service']; ?></td>
                    <td><?php echo $row['Payment_name']; ?></td>
                    <td><?php echo $row['Price']; ?></td>
                    <td><?php echo $row['Bill_type']; ?></td>
                    <td><?php echo $row['Color_of_the_cabin']; ?></td>
                    <td><?php echo $row['Booking_confirmed']; ?></td>
                    <td>
                        <a href='?potwierdz=<?php echo $row['Booking_ID']; ?>'>
                            <button type="submit" class="btn btn-primary" name="potwierdzrezerwacje">Potwierdź</button>
                        </a>
                        <a href='?odrzuc=<?php echo $row['Booking_ID']; ?>'>
                            <button type="submit" class="btn btn-primary" name="odrzucrezerwacje">Odrzuć</button>
                        </a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <?php
    }
    ?>
</div>
</body>
</html>
