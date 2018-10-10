<?php

require_once("../../classes/Produto.php");

$produto = new Produto($conexao);
try{
switch ($_SERVER['REQUEST_METHOD']) {
    //obter produtos
    case 'GET':
        $retorno = $produto->getProduto(isset($_GET['id']) ? $_GET['id'] : null);
        echo json_encode( $retorno);
        break;
    // Adicionar produto
    case 'POST':
    $body = (array)json_decode(file_get_contents('php://input'));
    if(isset($body['descricao']) && isset($body['preco']) && isset($body['categoria_id']) && isset($body['estoque'])){
        echo $produto->postProduto($body);
        return;
    }
    echo json_encode(array(
        "status"=>"erro",
        "mensagem"=>"parametros invalidos "
    ));
    break;
    //Alterar produto
    case 'PUT':
    $body = (array)json_decode(file_get_contents('php://input'));
    if(isset($body['descricao']) && isset($body['preco']) && isset($body['categoria_id']) && isset($body['estoque'])){
        echo $produto->putProduto($body);
        return;
    }
    echo json_encode(array(
        "status"=>"erro",
        "mensagem"=>"parametros invalidos "
    ));
    break;


    default:
        echo json_encode(array('status'=>'erro','mensagem'=>'Metodo invalido'));
        break;
} 
}catch (PDOException $e){
    echo json_encode(array(
        "status"=>"erro",
        "mensagem"=>"Não foi possivel obter produtos : ".$e->getMessage(),
    ));
   
}


?>