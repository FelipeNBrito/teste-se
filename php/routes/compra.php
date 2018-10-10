<?php

/**
 * Arquivo responsável por aplicar a venda
 */

$produto = new Produto($conexao);
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $body = (array)json_decode(file_get_contents('php://input'));

    $conexao->beginTransaction();
    #============= Registrar venda ===============================
    $conexao->exec("Insert into notas (cliente_id,tipo_id) values(1,1)");
    $idNota = $conexao->lastInsertId(); 

    $itens = array();
    foreach ($body as $key => $item) {
        $retorno = $produto->getProduto($item->id);
        array_push($itens,$retorno[0]);
    }

    foreach ($itens as $key => $item) {
        $stm = $conexao->prepare("Insert into notas_detalhe (produto_id,valor,perc_tributacao,notas_id,quantidade) values(:id,:preco,:tributacao,:idnota,:quantidade)");
        $item->perc_tributacao = (float)$item->perc_tributacao ;
        $item->preco = (float)$item->preco ;
        $stm->bindParam(':id',$item->id);
        $stm->bindParam(':preco',$item->preco);
        $stm->bindParam(':tributacao',$item->perc_tributacao);
        $stm->bindParam(':idnota',$idNota);
        $stm->bindParam(':quantidade',$body[$key]->quantidade);
        $stm->execute();


        $query = "Update produtos set estoque = :estoque where id = :id";
        $novo_estoque = ($item->estoque - (int)$body[$key]->quantidade);


        $stm = $conexao->prepare($query);
        $stm->bindParam(':estoque',$novo_estoque);
        $stm->bindParam(':id',$item->id);
        $stm->execute();

        if($stm->errorInfo()[0] != '00000'){
            echo json_encode(array("status"=>"erro","mensagem"=>"Não foi possivel  finalizar compra : ".$stm->errorInfo()[2]));
            $conexao->rollback();
        }
    }
    $conexao->commit();

    echo json_encode(array("status"=>"ok"));
    return true;
}
?>