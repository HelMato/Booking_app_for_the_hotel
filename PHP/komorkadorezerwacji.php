<?php
/*
autor oryginalnego kodu: The-Di-Lab
email   thedilab@gmail.com
strona http://www.the-di-lab.com */

class Komorkadorezerwacji {

    private $rezerwacja;
    private $currentURL;

    public function __construct(Booking $rezerwacja){
        $this->rezerwacja=$rezerwacja;
        $this->currentURL= htmlentities($_SERVER['REQUEST_URI']);
    }
public function aktualizuj(Kalendarz $kalendarz)
{
    if ($this->czyzarezerwowanadata($kalendarz->pobierzobecnadate())) {
        return $kalendarz->zawartosckomorki =
            $this->zarezerwowanakomorka($kalendarz->pobierzobecnadate());
    }

    if (!$this->czyzarezerwowanadata($kalendarz->pobierzobecnadate())) {
        return $kalendarz->zawartosckomorki =
            $this->otworzkomorke($kalendarz->pobierzobecnadate());
    }
}

public function opcje()
{
    if (isset($_POST['delete'])) {
        $this->usunrezerwacje($_POST['id']);
    }

    if (isset($_POST['add'])) {
        $this->dodajrezerwacje($_POST['date']);
    }
}

private function otworzkomorke($date)
{
    return '<div class="open">' . $this->formularzrezerwacji($date) . '</div>';
}

private function zarezerwowanakomorka($date)
{
    return '<div class="booked">' . $this->usunformularz($this->IDrezerwacji($date)) . '</div>';
}

private function czyzarezerwowanadata($date)
{
    return in_array($date, $this->zarezerwowanedaty());
}

private function zarezerwowanedaty()
{
    return array_map(function ($record) {
        return $record['booking_date'];
    }, $this->rezerwacja->lista_rezerwacji());
}

private function IDrezerwacji($date)
{
    $booking = array_filter($this->rezerwacja->lista_rezerwacji(), function ($record) use ($date) {
        return $record['data_rezerwacji'] == $date;
    });

    $result = array_shift($booking);

    return $result['id'];
}

private function usunrezerwacje($id)
{
    $this->rezerwacja->usun($id);
}

private function dodajrezerwacje($date)
{
    $date = new DateTimeImmutable($date);
    $this->rezerwacja->dodaj($date);
}

private function formularzrezerwacji($date)
{
    return
        '<form  method="post" action="' . $this->currentURL . '">' .
        '<input type="hidden" name="add" />' .
        '<input type="hidden" name="date" value="' . $date . '" />' .
        '<input class="submit" type="submit" value="Book" />' .
        '</form>';
}

private function usunformularz($id)
{
    return
        '<form onsubmit="return confirm(\'Czy jesteś pewien, że chcesz zrezygnować?\');" method="post" action="' . $this->currentURL . '">' .
        '<input type="hidden" name="delete" />' .
        '<input type="hidden" name="id" value="' . $id . '" />' .
        '<input class="submit" type="submit" value="Usuń" />' .
        '</form>';
}
}



?>