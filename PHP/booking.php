<?php
/*
logika klasy na podastawie tutoriala ze strony https://startutorial.com/view/how-to-build-a-php-booking-calendar-with-mysql
 */ 
require_once "connect.php";

class Booking{

    private $polaczenie;

    private $tabelarezerwacji= 'rezerwacje';
public function __construct($polaczenie){
    try {
        $this->polaczenie = $polaczenie;
    }
    catch (PDOException $e){
        die($e->getMessage());
    }
}
public function lista_rezerwacji(){
    $result=$this->polaczenie->query('SELECT * FROM '. $this->tabelarezerwacji);
    return $result->fetch_all(MYSQLI_ASSOC);
}

public function dodaj(DateTimeImmutable $datarezerwacji) {
$stmt=$this->polaczenie->prepare('INSERT INTO '. $this->tabelarezerwacji.'(datarezerwacji) VALUES
(?)');
if (false==$stmt){
    throw new Exception('Błąd zapytania'.$this->polaczenie->error);
}
if (!$stmt->bind_param('s', $datarezerwacji->format('Y-m-d'))){
    throw new Exception('Błąd parametrów:'.$stmt->error);
}
if (!$stmt->execute()){
throw new Exception ('Błąd zapytania: '.$stmt->error);
}
$stmt->close();

}
public function usun($id){
    $stmt = $this->polaczenie->prepare(
        'DELETE from' . $this->tabelarezerwacji. 'WHERE id = ?'
    );
    if (false==$stmt){
        throw new Exception('Błąd zapytania');
    }
    if (false==$stmt->execute(['i', $id])){
        throw new Exception('Błąd zapytania: '.$stmt->error);
    }
    $stmt->close();

}
}

?>