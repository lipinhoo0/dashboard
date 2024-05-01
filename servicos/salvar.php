<?php 
include("../verificar_aut.php");
include("../conexao-pdo.php");

$pagina_ativa = 'servicos';

//verifica se está vindo informações VIA POST
if ($_POST){
    //verifica campos obrigatórios
    if (empty($_POST["servico"])) {
        $_SESSION["tipo"] = "warning";
        $_SESSION["title"] = "Ops!";
        $_SESSION["msg"] = "Por favor, preencha os campos vazios!";
        header("Location: ./");
        exit;
    } else {
        # recupera informações preenchidas pelo usuário
        $pk_servico = trim($_POST["pk_servico"]);
        $servico = trim($_POST["servico"]);

        try {
            if(empty($pk_servico)) {
                $sql ="
                INSERT INTO servicos (servico) VALUES
                (:servico)
                ";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':servico', $servico);
            } else {
                $sql = "
                UPDATE servicos SET 
                servico = :servico
                WHERE pk_servico = :pk_servico";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':pk_servico', $pk_servico);
                $stmt->bindParam(':servico', $servico);
            }

            //executa insert ou update acima
            $stmt->execute();
            $_SESSION["tipo"] = "success";
            $_SESSION["title"] = "Oba!";
            $_SESSION["msg"] = "Registro salvo com sucesso!";
            header("Location: ./");
            exit;
        
        } catch (PDOException $ex){
            $_SESSION["tipo"] = "warning";
            $_SESSION["title"] = "Ops!";
            $_SESSION["msg"] = "Por favor, preencha os campos vazios!";
            header("Location: ./");
            exit;
        }
    }
}
?>