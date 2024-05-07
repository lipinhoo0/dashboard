<?php
include("../verificar_aut.php");
include("../conexao-pdo.php");

$pagina_ativa = 'meu_perfil';

//verifica se está vindo informações VIA POST
if ($_POST) {
    //verifica campos obrigatórios
    if (empty($_POST["nome"]) || empty($_POST["email"])) {
        $_SESSION["tipo"] = "warning";
        $_SESSION["title"] = "Ops!";
        $_SESSION["msg"] = "Por favor, preencha os campos vazios!";
        header("Location: ./");
        exit;
    } else {
        # recupera informações preenchidas pelo usuário
        $pk_usuario = $_SESSION["pk_usuario"];
        $nome = trim($_POST["nome"]);
        $email = trim($_POST["email"]);
        $senha = trim($_POST["senha"]);
        $foto = $_FILES["foto"];

        //verifica se existe uma foto a ser salva
        if ($foto["error"] != 4) {
            $ext_permitidos = array(
                "bmp",
                "jpg",
                "jpeg",
                "png",
                "jfiff",
                "tiff"
            );
            //$ext_permitidos 

            $extensao = pathinfo($foto["name"], PATHINFO_EXTENSION);
            if (in_array($extensao, $ext_permitidos)) {
                $novo_nome = hash("sha256", uniqid() . rand() . $foto["tmp_name"]) . "." . $extensao;

                //mover o arquivo para a pasta "fotos" com o novo nome
                move_uploaded_file($foto["tmp_name"], "fotos/$novo_nome");
                $update_foto = "foto='$novo_nome'";
                $_SESSION["foto_usuario"] = $novo_nome;
            } else {
                $_SESSION["tipo"] = "error";
                $_SESSION["title"] = "Ops!";
                $_SESSION["msg"] = "Arquivo de imagem NÃO permitido";
                header("Location: ./");
                exit;
            }
        } else {
            $update_foto = "foto=foto";
        }


        try {
            if (empty($senha)) {
                $sql = "
                UPDATE usuarios SET
                nome = :nome,
                email = :email,
                $update_foto
                WHERE pk_usuario = :pk_usuario
                ";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':pk_usuario', $pk_usuario);
            } else {
                $sql = "
                UPDATE usuarios SET 
                nome = :nome,
                email = :email,
                senha = :senha,
                $update_foto
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

            // transforma string em array, aonde tiver espaço
            $nome_usuario = explode(" ", $nome);

            //concatena o primeiro nome com  sobrenome do usuário
            $_SESSION["nome_usuario"] = $nome_usuario[0] . " " . end($nome_usuario);

            $_SESSION["tipo"] = "success";
            $_SESSION["title"] = "Oba!";
            $_SESSION["msg"] = "Registro salvo com sucesso!";
            header("Location: ./");
            exit;
        } catch (PDOException $ex) {
            $_SESSION["tipo"] = "warning";
            $_SESSION["title"] = "Ops!";
            $_SESSION["msg"] = $ex->getMessage();
            header("Location: ./");
            exit;
        }
    }
}
