<?php 
include("../verificar_aut.php");
include("../conexao-pdo.php");

$pagina_ativa = 'meu-perfil';

//verifica se está vindo informações VIA POST
if ($_POST){
    //verifica campos obrigatórios
    if (empty($_POST["nome"]) | empty($_POST["email"])) {
        $_SESSION["tipo"] = "warning";
        $_SESSION["title"] = "Ops!";
        $_SESSION["msg"] = "Por favor, preencha os campos vazios!";
        header("Location: ./");
        exit;
    } else {
        # recupera informações preenchidas pelo usuário
        $pk_usuario = trim($_POST["pk_usuario"]);
        $nome = trim($_POST["nome"]);
        $email = trim($_POST["email"]);
        $senha = trim($_POST["senha"]);
        $foto = $_FILES["foto"];

        try {
            if(empty($senha)) {
                $sql ="
                UPDATE usuario SET
                nome = :nome
                email = :email
                WHERE pk_usuario = :pk_usuario
                (:servico)
                ";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':pk_usuario', $pk_usuario);
            } else {
                $sql = "
                UPDATE usuario SET 
                nome = :nome
                email = :email
                senha = :senha
                WHERE pk_usuario = :pk_usuario
                ";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':senha', $senha);
                $stmt->bindParam(':pk_usuario', $pk_usuario);

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