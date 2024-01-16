//nasluchiwacz czeka az wszystkie zdarzenia DOM sie zrealizuja,
//czyli caly content z html sie zaladuje, a DOM zbuduje
//DOM sie buduje na podstawie znaczników HTML
//Drzewo DOM jest obiektową strukturą strony internetowej
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function (event) {

//querySelector znajduje pierwszy element formularza. 
//drugi sluchacz czeka na klikniecie submita
// event tutaj jest to obiekt, który będzie zawierał informacje o zdarzeniu submit.
//Działa to tak, że przeglądarka przekazuje ten obiekt do funkcji obsługi, gdy zdarzenie wystąpi

            // Sprawdzanie loginu
            var login = form.querySelector('input[name="login"]').value;
            if (login.trim() === '') { // trim ucina biale znaki i jak jest pusto to
                alert('Login jest wymagany');
                event.preventDefault();
                return;
            }
//guerySelector znajduje pierwszy element formularza, to jest pola ktore sie nazywa login
//.value pobiera wartosc pola formularza; .val to pobranie wartosci pola


            // Sprawdzanie hasła
            var haslo = form.querySelector('input[name="haslo"]').value;
            if (haslo.trim() === '') {
                alert('Hasło jest wymagane!');
                event.preventDefault(); //zatrzymuje dzialanie formularza i konczy funkcje
                return;
            }

            if (haslo.length > 10) {
                alert('Hasło nie może mieć więcej niż 10 znaków!');
                event.preventDefault();
                return;
            }
        });
    });
});
