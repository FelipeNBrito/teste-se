<?php
//Dados de conexão com o banco
$host='localhost';
$db = 'softexperteduardo';
$username = 'postgres';
$password = '1234';
$dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";
 
//Tenta conextar ao banco
try{
 $conexao = new PDO($dsn);

}catch (PDOException $e){
 // report error message
 echo json_encode(array(
     "status"=>"erro",
     "mensagem"=>"Não foi possivel conectar ao banco: ".$e->getMessage(),
 ));

}


?>