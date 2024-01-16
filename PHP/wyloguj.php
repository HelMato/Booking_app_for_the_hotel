<!DOCTYPE html>
<html lang="pl"><head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="viewport" content="width-device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">

</head>
<body>
<?php
session_start();
if (isset($_SESSION['usunkonto'])){
    echo 'Konto zostało usunięte';
}
else {
    echo 'Wylogowano';
}
session_destroy();
?>
</body>

</head>
</html>