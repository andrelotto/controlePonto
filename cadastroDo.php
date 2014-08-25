<?php
require_once('config/database.php');
echo "<pre>";
var_dump($_POST);

$arrCadastro = array();



$arrCadastro['tipoAtiv'] = $_POST['tipo'];
$arrCadastro['descAtiv'] = $_POST['motivo'];
$arrCadastro['horaAtiv'] = $_POST['hora'];
$arrCadastro['dataAtiv'] = $_POST['data'];

$cadastro = ORM::for_table('controlehoras')->create();

$cadastro->descAtiv = $arrCadastro['descAtiv'];
$cadastro->dataAtiv = $arrCadastro['dataAtiv'];
$cadastro->horaAtiv = $arrCadastro['horaAtiv'];
$cadastro->tipoAtiv = $arrCadastro['tipoAtiv'];

$cadastro->save();


header('Location: cadastro.php');