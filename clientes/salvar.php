<?php 
include("../verificar_aut.php");
include("../conexao-pdo.php");

$pagina_ativa = 'clientes';

//verifica se está vindo informações VIA POST
if ($_POST){
    //verifica campos obrigatórios
    if (empty($_POST["nome"]) ||empty($_POST["cpf"]) ||empty($_POST["email"]) || empty($POST["whatsapp"]) ) {
        $_SESSION["tipo"] = "warning";
        $_SESSION["title"] = "Ops!";
        $_SESSION["msg"] = "Por favor, preencha os campos vazios!";
        header("Location: ./");
        exit;
    } else {
        # recupera informações preenchidas pelo usuário
        $pk_cliente = trim($_POST["pk_cliente"]);
        $nome = trim($_POST["nome"]);
        $cpf = trim($_POST["cpf"]);
        $whatsapp = trim($_POST["whatsapp"]);
        $email = trim($_POST["email"]);
        
        try {
            if(empty($pk_cliente)) {
                $sql ="
                INSERT INTO clientes (nome, cpf, whatsapp, email) VALUES
                (:nome, :cpf, :whatsapp, :email)
                ";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':whatsapp', $whatsapp);
                $stmt->bindParam(':email', $email);
            } else {
                $sql = "
                UPDATE clientes SET 
                nome = :nome, cpf = :cpf, whatsapp = :whatsapp, email = :email
                WHERE pk_cliente = :pk_cliente";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':pk_cliente', $pk_cliente);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':whatsapp', $whatsapp);
                $stmt->bindParam(':email', $email);
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