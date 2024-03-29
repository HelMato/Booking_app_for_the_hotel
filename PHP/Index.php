
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link href="kalendarz.css" type="text/css" rel="stylesheet"/>
    <script src="validation.js"></script>
</head>
<body>
   
<div class="container my-3 text-center"> 

<div> Witaj w Kiszkowiance! </div>  
<div class="container my-3 text-center"> 
Wolne domki:
</div>  

<?php

include 'connect.php';

include 'kalendarz.php';
include 'booking.php';
include 'komorkadorezerwacji.php';

class SprawdzWolneDomkiWBazieDanych implements InfowKalendarzu
{
    private $_polaczenie;
    private $_kolory = array();
    private $_stmt;

    private $cena;

    public function sprawdzwolnedomki(DateTimeImmutable $data_od, DateTimeImmutable $data_do) {
        $wolne = array();

        if ($this->_stmt->execute([$data_od->format("Y-m-d"), $data_do->format("Y-m-d")])) {
            $result = $this->_stmt->get_result();

            // wypelnij tabele $wolne ustawiajac wszystkie domki wolne we wszystkie dni
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($data_od, $interval, $data_do->add($interval));
            foreach ($period as $dt)
                $wolne[$dt->format('Y-m-d')] = $this->_kolory;

            // dla kazdej rezerwacji usun z tabeli $wolne wszystkie dni w ktorych dany
            // domek jest zajety.
            while ($row = $result->fetch_assoc()) {
                $kolor = $row['Color_of_the_cabin'];
                $rezerwacja_od = new DateTimeImmutable($row['Schedule_arrival']);
                $rezerwacja_do = new DateTimeImmutable($row['Schedule_departure']);
                if ($rezerwacja_od < $data_od)
                    $rezerwacja_od = $data_od;
                if ($rezerwacja_do > $data_do)
                    $rezerwacja_do = $data_do->add($interval);
                $period = new DatePeriod($rezerwacja_od, $interval, $rezerwacja_do);
                foreach ($period as $dt)
                    $wolne[$dt->format('Y-m-d')] = array_diff($wolne[$dt->format('Y-m-d')], [$kolor]);
            }
        }
        return $wolne;
    }

    public function __construct($polaczenie) {
        $this->_polaczenie = $polaczenie;
        if ($result = $this->_polaczenie->query("SELECT * FROM color_of_the_cabin")) {
            while ($row = $result->fetch_assoc()) {
                array_push($this->_kolory, $row["Color"]);
            }
        }
        $sql = "SELECT * FROM booking WHERE ? < Schedule_departure AND ? >= Schedule_arrival";
        $this->_stmt = $polaczenie->prepare($sql);
        $sql= "SELECT price, start_date, end_date from Price_schedule JOIN Price on Price_schedule.Price_ID = Price.Price_ID WHERE ? <= End_date and ? >= Start_date ";
        $this->cena=$polaczenie->prepare($sql);

        
    }
    public function sprawdzceny(DateTimeImmutable $data_od, DateTimeImmutable $data_do) {
        $ceny = array();

        if ($this->cena->execute([$data_od->format("Y-m-d"), $data_do->format("Y-m-d")])) {
            $result = $this->cena->get_result();

            $interval = DateInterval::createFromDateString('1 day');
        
            while ($row = $result->fetch_assoc()) {
               
                $cena = $row['price'];
                $cena_od = new DateTimeImmutable($row['start_date']);
                $cena_do = new DateTimeImmutable($row['end_date']);
                if ($cena_od < $data_od)
                    $cena_od = $data_od;
                if ($cena_do > $data_do)
                    $cena_do = $data_do->add($interval);
                $period = new DatePeriod($cena_od, $interval, $cena_do->add($interval));
                foreach ($period as $dt)
                    $ceny[$dt->format('Y-m-d')] = $cena;
            }
        }
        return $ceny;
    }

}


$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
$rezerwacja = new Booking($polaczenie);
$komorkarezerwacji = new Komorkadorezerwacji($rezerwacja);
$sprawdzwolnedomki = new SprawdzWolneDomkiWBazieDanych($polaczenie);
$kalendarz = new Kalendarz($sprawdzwolnedomki);
$kalendarz->dodajobserwatora('pokazkomorke', $komorkarezerwacji);
$komorkarezerwacji->opcje();

echo $kalendarz->pokazdate();

?>
<br>
<div>Jeżeli chcesz zarezerwować pobyt, <a href='formularz_rejestracji.php'> zarejestruj się :)</a></div> <br>
<div> Mam już konto, zaloguj </div>
<form action="zaloguj.php" method="post">
    Login:<br/><input type="text" name="login"/><br/>
    Hasło:<br/><input type="password" name="haslo"/><br/><br/>
    <input type="submit" value="Zaloguj się" />
    </form>
</div>


</body>

</html>