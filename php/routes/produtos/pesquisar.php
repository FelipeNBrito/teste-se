<?php
$produto = new Produto($conexao);
try{
switch ($_SERVER['REQUEST_METHOD']) {
    //Ao tentar obter produtos
    case 'GET':
        $produto->pesquisarProduto(isset($_REQUEST['q']) ? $_REQUEST['q'] : null);
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