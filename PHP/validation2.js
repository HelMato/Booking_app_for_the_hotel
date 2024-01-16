document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form#formularz_rejestracji').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            validateForm(form);
        });
    });

    function validateForm(form) {
        var requiredFields = ['name', 'surname', 'ID_card', 'PESEL', 'Phone_number', 'email', 'login', 'pass'];
var fieldNames=['Imię', 'Nazwisko', 'Dowód osobisty', 'PESEL', 'Numer telefonu', 'email','Login', 'Hasło'];
        for (var i = 0; i < requiredFields.length; i++) {
            var fieldName = requiredFields[i];
            var fieldValue = form.querySelector('input[name="' + fieldName + '"]').value;

            if (fieldValue.trim() === '') {
                alert(fieldNames[i]+' jest wymagane');
                return;
            }
        }
        
        // walidacja dla imienia i nazwiska tylko litery
        var nameValue = form.querySelector('input[name="name"]').value;
        var surnameValue = form.querySelector('input[name="surname"]').value;

        function onlyLetters(value) {
            var lettersRegex = /^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+$/; 
            return lettersRegex.test(value);
        }

        if (!onlyLetters(nameValue) || !onlyLetters(surnameValue)) {
            alert('Imię i nazwisko muszą składać się wyłącznie z liter');
            return;
        }

        // walidacja dla ID_card dlugosc
        var idCardValue = form.querySelector('input[name="ID_card"]').value;
        if (idCardValue.length > 8) {
            alert('Numer dowodu nie może być dłuższy niż 8 znaków!');
            return;
        }

        //walidacja dla PESEL
        var peselValue = form.querySelector('input[name="PESEL"]').value;
        if (!onlyNumbers(peselValue) || peselValue.length !== 11) {
            alert('PESEL musi składać się z 11 cyfr');
            return;
        }

        function onlyNumbers(value) {
            var numberRegex = /^[0-9]+$/;
            return numberRegex.test(value);
        }

        // walidacja dla numeru telefonu dlugość i tylko cyfry
        var phoneNumberValue = form.querySelector('input[name="Phone_number"]').value;
        if (phoneNumberValue.length > 15 || !onlyNumbers(phoneNumberValue)) {
            alert('Numer telefonu musi składać się tylko z cyfr i nie może być dłuższy niż 15 znaków, usuń spacje');
            return;
        }

        // walidacja dla e-mail forma i typ znakow
        var emailValue = form.querySelector('input[name="email"]').value;
        var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (!emailRegex.test(emailValue)) {
            alert('Adres e-mail jest niepoprawny. Wprowadź poprawny adres e-mail.');
            return;
        }

        // walidacja dla loginu typ znakow
        var loginValue = form.querySelector('input[name="login"]').value;
        var loginRegex = /^[a-zA-Z0-9_]+$/;

        if (!loginRegex.test(loginValue)) {
            alert('Login może zawierać tylko litery, cyfry i znak podkreślenia');
            return;
        }

        // walidacja dla hasla dlugosc
        var password = form.querySelector('input[name="pass"]').value;
        if (password.length > 12) {
            alert('Hasło nie może mieć więcej niż 12 znaków');
            return;
        }

        // wyslij formularz jak walidacje przeszly
        form.submit();
    }
});
