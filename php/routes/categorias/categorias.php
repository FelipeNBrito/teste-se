<?php
$categoria = new Categoria($conexao);
//echo $_SERVER['REQUEST_METHOD'];
//print_r($_REQUEST);
//print_r(file_get_contents('php://input'));

try{
switch ($_SERVER['REQUEST_METHOD']) {
    //Obter categorias
    case 'GET':
        $categoria->getCategoria(isset($_REQUEST['id']) ? $_REQUEST['id'] : null);
        break;
    //Adicionar categorias
    case 'POST':
        $body = (array)json_decode(file_get_contents('php://input'));
        if(isset($body['categoria']) && isset($body['perc_tributacao'])){
            echo $categoria->addCategoria($body['categoria'],$body['perc_tributacao']);
            return;
        }
        echo json_encode(array('status'=>'erro','mensagem'=>'Parâmetros inválidos'));
    break;
    //Alterar categoria
    case 'PUT':
    $body = (array)json_decode(file_get_contents('php://input'));
    if(isset($body['id']) && isset($body['categoria']) && isset($body['perc_tributacao'])){
        echo $categoria->putCategoria($body['id'],$body['categoria'],$body['perc_tributacao']);
        return;
    }
    echo json_encode(array('status'=>'erro','mensagem'=>'Parâmetros inválidos'));
    break;
    case 'DELETE':
    if(!isset($_REQUEST['id'])){
        echo json_encode(array('status'=>'erro','mensagem'=>'id invalido'));
        return;
    }
    $categoria->deleteCategoria(isset($_REQUEST['id']) ? $_REQUEST['id'] : null);
    break;
    default:
        echo json_encode(array('status'=>'erro','mensagem'=>'Metodo invalido'));
        break;
} 
}catch (PDOException $e){
    echo json_encode(array(
        "status"=>"erro",
        "mensagem"=>"Não foi possivel obter categorias : ".$e->getMessage(),
    ));
   
}


?>