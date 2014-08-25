<?php
error_reporting(E_ERROR | E_PARSE);

require_once("idiorm.php");

ORM::configure('mysql:host=localhost;dbname=horas');
ORM::configure('username', 'root');
ORM::configure('password', '123qwe');