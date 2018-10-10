<?php

//url amigavel
$url = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$name = explode('/',$_SERVER['REQUEST_URI'])[1];

$rota = '';
// Função que captura as variaveis na url para tratar nas paginas do php
//utilizamos o explode para separar os valores depois de cada “/”
$rota= str_replace($_SERVER['HTTP_HOST'].'/'.$name.'/php/','',$url);
$rota = explode('?',$rota)[0];


   
?>
