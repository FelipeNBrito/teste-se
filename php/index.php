<?php
#Headers do cors
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

#Classes do sistema e configurações
require_once 'connection.php';
require_once 'config.php';
require_once 'classes/Categoria.php';
require_once 'classes/Produto.php';

#Aplicar rota
require_once './routes/'.$rota.'.php';
?>