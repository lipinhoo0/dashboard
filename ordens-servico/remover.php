<?php 
include("../verificar_aut.php");
include("../conexao-pdo.php");
$pagina_ativa = 'ordens_servico';

if (empty($_GET["ref"])) {
    header("Location: ./");
    exit;
} else {
    $pk_ordem_servico = base64_decode($_GET["ref"]);

    $sql = "
    DELETE FROM ordens_servicos
    WHERE pk_ordem_servico = :pk_ordem_servico
    " ;
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':pk_ordem_servico', $pk_ordem_servico);
        $stmt->execute();

        $_SESSION["tipo"] = "success";
        $_SESSION["title"] = "Oba!";
        $_SESSION["msg"] = "Registro removido com sucesso!";
        header("Location: ./");
        exit;

    } catch (PDOException $ex) {
        $_SESSION["tipo"] = "warning";
        $_SESSION["title"] = "Ops!";

        if ($ex ->getCode() == 23000){
            $_SESSION["msg"] = "Não é possível excluir este registro, pois ele está em uso!";
        } else {
        $_SESSION["msg"] = $ex -> getMessage();
        }

        header("Location: ./");
        exit;
    }

}

?>