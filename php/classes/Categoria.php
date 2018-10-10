<?php
/**
 * Classe responsável por gerenciar categorias de produtos
 */
class Categoria
{
    private $conexao = null;
    function __construct($conexao) {
        $this->conexao = $conexao;
    }
   /*
    Otem uma ou todas as categorias pelo seu id
   */ 
   public function getCategoria($id=null){
        $query = 'select produtos_categoria.*,
        (case 
        when (select count(*) from produtos where categoria_id = produtos_categoria.id) >0 then true
        else false
        end
        ) as locked
         from produtos_categoria
        order by id';
        if($id != null){
            $query .= ' where id = :id';
        }
        $stm = $this->conexao->prepare($query);
        if($id != null){
            $stm->bindParam(':id',$id);
        }
        $stm->execute();

        //Verifica se não há erros no sql
        if($stm->errorInfo()[0] != '00000'){
            echo json_encode(array("status"=>"erro","mensagem"=>"Não foi possivel obter categorias : ".$stm->errorInfo()[2]));
            return;
        }
        $result = $stm->fetchAll(PDO::FETCH_OBJ);
        echo json_encode( $result);
   }

   /**
    * Adiciona categorias no banco de dados
    */
   public function addCategoria($categoria,$percentual){
       $query = "insert into produtos_categoria (categoria,perc_tributacao) values (:categoria,:percentual)";
       try{
        $stm = $this->conexao->prepare($query);
        $stm->bindParam(':categoria',$categoria);
        $stm->bindParam(':percentual',$percentual);
        $stm->execute();
        if($stm->errorInfo()[0] != '00000'){
            echo json_encode(array("status"=>"erro","mensagem"=>"Não foi possivel adicionar categoria : ".$stm->errorInfo()[2]));
            return;
        }
        echo json_encode(array("status"=>"ok","id"=>$this->conexao->lastInsertId()));

       }
        catch( PDOException $Exception ) {
            echo json_encode(array("status"=>"erro","mensagem"=>"Não foi possivel adicionar categoria". $Exception ));
        }

   }

   /*
    Altera uma categoria pelo seu id
   */
   public function putCategoria($id,$categoria,$percentual){
       $query = "update produtos_categoria set categoria = :categoria, perc_tributacao = :percentual where id = :id";
       try{
            $stm = $this->conexao->prepare($query);
            $stm->bindParam(':id',$id);
            $stm->bindParam(':categoria',$categoria);
            $stm->bindParam(':percentual',$percentual);
            $stm->execute();
            if($stm->errorInfo()[0] != '00000'){
                echo json_encode(array("status"=>"erro","mensagem"=>"Não foi possivel adicionar categoria : ".$stm->errorInfo()[2]));
                return;
            }
            echo json_encode(array("status"=>"ok"));

       }
        catch( PDOException $Exception ) {
            echo json_encode(array("status"=>"erro","mensagem"=>"Não foi possivel alterar categoria". $Exception ));
        }

   }


   public function deleteCategoria($id){
    $query = "delete from produtos_categoria where id = :id";
    try{
         $stm = $this->conexao->prepare($query);
         $stm->bindParam(':id',$id);
         $stm->execute();
         if($stm->errorInfo()[0] != '00000'){
             echo json_encode(array("status"=>"erro","mensagem"=>"Não foi possivel deletar categoria : ".$stm->errorInfo()[2]));
             return;
         }
         echo json_encode(array("status"=>"ok"));

    }
     catch( PDOException $Exception ) {
         echo json_encode(array("status"=>"erro","mensagem"=>"Não foi possivel deletar categoria". $Exception ));
     }

}




}



?>