<?php
DEFINE('DS', DIRECTORY_SEPARATOR);
require_once dirname(__DIR__) . DS . 'constants.php'; ?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="<?= constant('URL') . 'assets/img/logo1.png'; ?>">
<title>Versatil Seguros</title>

<!-- FONT AWESOME -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="<?= constant('URL') . 'assets/css/bootstrap.css'; ?>">
<!-- BOOTSTRAP SELECT CSS -->
<link rel="stylesheet" href="<?= constant('URL') . 'assets/css/bootstrap-select.css'; ?>">
<!-- Material Design Bootstrap -->
<link rel="stylesheet" href="<?= constant('URL') . 'assets/css/mdb.css'; ?>">
<!-- DataTables CSS -->
<link rel="stylesheet" href="<?= constant('URL') . 'assets/css/addons/datatables.css'; ?>">
<!-- DataTables Select CSS -->
<link rel="stylesheet" href="<?= constant('URL') . 'assets/css/addons/datatables-select.css'; ?>">

<!-- Cards Extended Pro CSS -->
<link rel="stylesheet" href="<?= constant('URL') . 'assets/css/addons-pro/cards-extended.css'; ?>">

<!-- Presonalize CSS -->
<link rel="stylesheet" href="<?= constant('URL') . 'assets/styles.css'; ?>">
<!--     Excel Tables     -->
<script src="<?= constant('URL') . 'assets/tableToExcel.js'; ?>"></script>

<!-- Alertify -->
<link rel="stylesheet" type="text/css" href="<?= constant('URL') . 'assets/css/alertify.css'; ?>">
<link rel="stylesheet" type="text/css" href="<?= constant('URL') . 'assets/css/themes/bootstrap.css'; ?>">
<script src="<?= constant('URL') . 'assets/js/alertify.js'; ?>"></script>

<style type="text/css">
    #carga {
        height: 80vh;
    }

    /*::-webkit-scrollbar {
        width: 5px;
    }*/

    body::-webkit-scrollbar {
        width: 10px;
    }

    /* Estilos barra (thumb) de scroll */
    body::-webkit-scrollbar-thumb {
        background: #00bcd4;
        border-radius: 4px;
    }

    body::-webkit-scrollbar-thumb:hover {
        background: #0097a7;
        box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.2);
    }

    /* Estilos track de scroll */
    body::-webkit-scrollbar-track {
        background: rgb(190, 189, 189);
        border-radius: 4px;
    }

    body::-webkit-scrollbar-track:hover,
    body::-webkit-scrollbar-track:active {
        background: rgb(153, 152, 152);
    }
</style>