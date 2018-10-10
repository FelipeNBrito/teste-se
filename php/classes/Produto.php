<?php
/**
 * Classe responsável por gerenciar produtos
 */
class Produto
{
    private $conexao = null;
    function __construct($conexao) {
        $this->conexao = $conexao;
    }
   /*
    Obtem uma ou todas as categorias ou pelo seu id
   */ 
   public function getProduto($id=null){
        $query = 'select produtos.*, produtos_categoria.perc_tributacao from produtos,produtos_categoria where produtos.categoria_id = produtos_categoria.id  ';
        if($id != null){
            $query .= ' and produtos.id = :id';
        }

        $query .= " order by produtos.id desc limit 100";
        $stm = $this->conexao->prepare($query);
        if($id != null){
            $stm->bindParam(':id',$id);
        }
        $stm->execute();
        //print_r($stm->errorInfo());
        $result = $stm->fetchAll(PDO::FETCH_OBJ);
        return $result;
   }
    /**
     * Pesquisa um produto por sua descrição
     */
   public function pesquisarProduto($descricao=''){
        $query = "select * from produtos where LOWER(descricao) like LOWER(:descricao) order by id desc limit 100 ";
        $descricao = "%$descricao%";
        $stm = $this->conexao->prepare($query);
        $stm->bindParam(':descricao',$descricao);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_OBJ);
        echo json_encode( $result);
   }

   /**
    * Adiciona novos produtos
    */
   public function postProduto($produto){
        $query = "insert into produtos (descricao,preco,categoria_id,estoque) values (:descricao,:preco,:categoria,:estoque)";
        try{
            $stm = $this->conexao->prepare($query);
            $stm->bindParam(':descricao',$produto['descricao']);
            $stm->bindParam(':preco',$produto['preco']);
            $stm->bindParam(':categoria',$produto['categoria_id']);
            $stm->bindParam(':estoque',$produto['estoque']);
            $stm->execute();
            if($stm->errorInfo()[0] != '00000'){
                echo json_encode(array("status"=>"erro","mensagem"=>"Não foi possivel adicionar produto : ".$stm->errorInfo()[2]));
                return;
            }
            echo json_encode(array("status"=>"ok","id"=>$this->conexao->lastInsertId()));
        }
        catch( PDOException $Exception ) {
            echo json_encode(array("status"=>"erro","mensagem"=>"Não foi possivel adicionar produto". $Exception ));
        }
   }

   /**
    * Edita um produto
   */
   public function putProduto($produto){
        $query = "Update produtos set descricao = :descricao, preco = :preco, categoria_id = :categoria, estoque = :estoque where id = :id";
        $stm = $this->conexao->prepare($query);
        $stm->bindParam(':descricao',$produto['descricao']);
        $stm->bindParam(':preco',$produto['preco']);
        $stm->bindParam(':categoria',$produto['categoria_id']);
        $stm->bindParam(':estoque',$produto['estoque']);
        $stm->bindParam(':id',$produto['id']);
        $stm->execute();
        if($stm->errorInfo()[0] != '00000'){
            echo json_encode(array("status"=>"erro","mensagem"=>"Não foi possivel adicionar produto : ".$stm->errorInfo()[2]));
            return;
        }
        echo json_encode(array("status"=>"ok"));
   }
   

}



?>