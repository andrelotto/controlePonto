<?php
error_reporting(E_ERROR | E_PARSE);

require_once("idiorm.php");

ORM::configure('mysql:host=localhost;dbname=horas');
ORM::configure('username', 'root');
ORM::configure('password', '123qwe');


// Conecta ao banco de dados
$mysqli = new mysqli('localhost', 'root', '123qwe', 'horas');

if (mysqli_connect_errno()) {
    die('Não foi possível conectar-se ao banco de dados: ' . mysqli_connect_error());
    exit();
}
