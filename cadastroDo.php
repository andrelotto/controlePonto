<?php
require_once('config/database.php');

error_reporting(E_ALL);

echo "<pre>";
var_dump($_POST);

$arrCadastro = array();

$arrCadastro['tipoAtiv'] = $_POST['tipo'];
$arrCadastro['descAtiv'] = $_POST['motivo'];
$arrCadastro['horaAtiv'] = $_POST['hora'];
$arrCadastro['dataAtiv'] = $_POST['data'];

$teste = $mysqli->query("CALL `sp_in_controlehoras`('".$arrCadastro['descAtiv']."', '".$arrCadastro['dataAtiv']."', '".$arrCadastro['horaAtiv']."', '".$arrCadastro['tipoAtiv']."')");

$mysqli->close();

header('Location: cadastro.php');