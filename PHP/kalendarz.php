<?php
/*
autor oryginalnego kodu: The-Di-Lab
email   thedilab@gmail.com
http://www.the-di-lab.com */

interface InfowKalendarzu
{
    function sprawdzwolnedomki(DateTimeImmutable $data_od, DateTimeImmutable $data_do);
    function sprawdzceny(DateTimeImmutable $data_od, DateTimeImmutable $data_do);
}

class Kalendarz
{
    public $zawartosckomorki = '';
    protected $obserwatorzy = array();

    public $_wolneDomki;

    public $ceny;

    private $dni = array("Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota", "Niedziela");

    private $miesiace = array("Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień");

    private $obecnyrok = 0;
    private $obecnymiesiac = 0;
    private $obecnydzien = 0;
    private $obecnadata = null;

    private $liczbadniwmiesiacu = 0;

    // Zmienna do przechowywania URL
    private $linkhref = null;

    private $infowkalendarzu= null;

    public function __construct(InfowKalendarzu $infowkalendarzu)
    {
        $this->linkhref = htmlentities($_SERVER['PHP_SELF']);
        $this->infowkalendarzu = $infowkalendarzu;
    }

    public function dodajobserwatora($typ, $obserwator)
    {
        $this->obserwatorzy[$typ][] = $obserwator;
    }

    public function powiadomobserwatora($typ)
    {
        if (isset($this->obserwatorzy[$typ])) {
            foreach ($this->obserwatorzy[$typ] as $obserwator) {
                $obserwator->update($this);
            }
        }
    }

 
    public function pobierzobecnadate()
    {
        return $this->obecnadata;
    }

  

    public function pokazdate($miesiac = null, $rok = null, $wlasciwosci = false)
    {
        if (null == $rok && isset($_GET['rok'])) {
            $rok = $_GET['rok'];
        } elseif (null == $rok) {
            $rok = date("Y", time());
        }

        if (null == $miesiac && isset($_GET['miesiac'])) {
            $miesiac = $_GET['miesiac'];
        } elseif (null == $miesiac) {
            $miesiac = date("m", time());
        }

        $this->obecnyrok = $rok;
        $this->obecnymiesiac = $miesiac;
        $this->liczbadniwmiesiacu = $this->_liczbadniwmiesiacu($miesiac, $rok);
        $dataod =  new DateTimeImmutable("{$this->obecnyrok}-{$this->obecnymiesiac}-1");
        $datado = new DateTimeImmutable("{$this->obecnyrok}-{$this->obecnymiesiac}-{$this->liczbadniwmiesiacu}");
        $this->_wolneDomki = $this->infowkalendarzu->sprawdzwolnedomki($dataod, $datado);

        /* Zawartość zawiera nagłówek, etykiety dni tygodnia, daty kalendarza i inne el.*/

        $this->ceny=$this->infowkalendarzu->sprawdzceny($dataod, $datado);

        $zawartosc = '<div id="calendar">' .
            '<div class="box">' .
            $this->_stworzheader() .
            '</div>' .
            '<div class="box-content">' .
            '<ul class="label">' . $this->_stworzetykiete() . '</ul>';
        $zawartosc .= '<div class="clear"></div>';
        $zawartosc .= '<ul class="dates">';
        for ($i = 0; $i < $this->_liczbatygodniwmiesiacu($miesiac, $rok); $i++) {
            for ($j = 1; $j <= 7; $j++) {
                $zawartosc .= $this->_pokazdzien($i * 7 + $j, $wlasciwosci);
            }
        }
        $zawartosc .= '</ul>';
        $zawartosc .= '<div class="clear"></div>';
        $zawartosc .= '</div>';
        $zawartosc .= '</div>';
        // $zawartosc .= '<div class="domki-container">';
        // $zawartosc .= '<img src="domek-czarny.png" alt="Domek czarny">';
        // $zawartosc .= '<img src="domek-niebieski.png" alt="Domek niebieski">';
        // $zawartosc .= '<img src="domek-czerwony.png" alt="Domek czerwony">';
        // $zawartosc .= '</div>';
        return $zawartosc;
    }

    private function _pokazdzien($numerkomorki, $wlasciwosci = false)
    {
        if ($this->obecnydzien == 0) {
            $pierwszydzientygodnia = date('N', strtotime($this->obecnyrok . '-' . $this->obecnymiesiac . '-01'));
            if (intval($numerkomorki) == intval($pierwszydzientygodnia)) {
                $this->obecnydzien= 1;
            }
        }

        if (($this->obecnydzien != 0) && ($this->obecnydzien <= $this->liczbadniwmiesiacu)) {
            $this->obecnadata = date('Y-m-d', strtotime($this->obecnyrok . '-' . $this->obecnymiesiac . '-' . ($this->obecnydzien)));
            $zawartosckomorki = $this->_stworzzawartosckomorki($wlasciwosci);
            $this->obecnydzien++;
        } else {
            $this->obecnadata = null;
            $zawartosckomorki = null;
        }

        $domki = '';
        if ($zawartosckomorki != null) {
            if (array_key_exists($this->obecnadata, $this->ceny)){
                $cena=$this->ceny[$this->obecnadata];
            $domki.=$cena." PLN ";  
            }
            $wolne = $this->_wolneDomki[$this->obecnadata];
            foreach ($wolne as $kolor)
                $domki .= '<span class="domek-' . $kolor . '">⌂</span>';
            if (strlen($domki) > 0)
                $domki = ' <div class="wrapperdomkow"><div class="domki">' . $domki . '</div></div>';
        }

        return '<li id="li-' . $this->obecnadata . '" class="' .
            ($numerkomorki % 7 == 1 ? ' start ' : ($numerkomorki % 7 == 0 ? ' end ' : ' ')) .
            ($zawartosckomorki == null ? 'mask' : '') . '">' .
            $zawartosckomorki . $domki .
            '</li>';
    }

   
    private function _stworzheader()
    {
        $przyszlymiesiac = $this->obecnymiesiac == 12 ? 1 : intval($this->obecnymiesiac) + 1;
        $przyszlyrok = $this->obecnymiesiac == 12 ? intval($this->obecnyrok) + 1 : $this->obecnyrok;

        $poprzednimiesiac = $this->obecnymiesiac == 1 ? 12 : intval($this->obecnymiesiac) - 1;
        $poprzednirok = $this->obecnymiesiac == 1 ? intval($this->obecnyrok) - 1 : $this->obecnyrok;

        return
            '<div class="header">' .
            '<a class="prev" href="' . $this->linkhref. '?miesiac=' . sprintf('%02d', $poprzednimiesiac) . '&rok=' . $poprzednirok . '">Poprzedni miesiąc</a>' .
            '<span class="title">' . $this->miesiace[$this->obecnymiesiac-1]." ".$this->obecnyrok.
            '<a class="next" href="' . $this->linkhref . '?miesiac=' . sprintf("%02d", $przyszlymiesiac) . '&rok=' . $przyszlyrok . '">Następny miesiąc</a>' .
            '</div>';
    }

  
    private function _stworzetykiete()
    {

        $zawartosc = '';
        foreach ($this->dni as $index => $label) {
            $zawartosc .= '<li class="' . ($label == 6 ? 'end title' : 'start title') . ' title">' . $label . '</li>';
        }

        return $zawartosc;
    }

    private function _stworzzawartosckomorki($ustawienia = false)
    {
        $this->zawartosckomorki = '';

        $this->zawartosckomorki = $this->obecnydzien;

        $this->powiadomobserwatora('Pokaz komórkę');

        return $this->zawartosckomorki;
    }

    private function _liczbatygodniwmiesiacu($miesiac = null, $rok = null)
    {
        if (null == ($rok))
            $rok = date("Y", time());

        if (null == ($miesiac))
            $miesiac = date("m", time());

        $liczbadniwmiesiacu = $this->_liczbadniwmiesiacu($miesiac, $rok);

        $liczbatygodni = ($liczbadniwmiesiacu % 7 == 0 ? 0 : 1) + intval($liczbadniwmiesiacu / 7);
        $ostatnidzienmiesiaca = date('N', strtotime($rok . '-' . $miesiac . '-' . $liczbadniwmiesiacu));
        $pierwszydzienmiesiaca = date('N', strtotime($rok . '-' . $miesiac . '-01'));
        $ostatnidzienmiesiaca == 7 ? $ostatnidzienmiesiaca = 0 : '';
        $pierwszydzienmiesiaca == 7 ? $pierwszydzienmiesiaca = 0 : '';

        if ($ostatnidzienmiesiaca < $pierwszydzienmiesiaca) {
            $liczbatygodni++;
        }
        return $liczbatygodni;
    }

    private function _liczbadniwmiesiacu($miesiac = null, $rok = null)
    {
        if (null == ($rok))
            $rok = date("Y", time());

        if (null == ($miesiac))
            $miesiac = date("m", time());

        return date('t', strtotime($rok . '-' . $miesiac . '-01'));
    }
}