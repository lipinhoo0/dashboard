<?php
session_start();
include('./conexao-pdo.php');

//arquivos para recuperar senha
require_once('dist/plugins/php-mailer/src/PHPMailer.php');
require_once('dist/plugins/php-mailer/src/SMTP.php');
require_once('dist/plugins/php-mailer/src/Exception.php');

//bibliotecas para recuperar senha
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_POST) {
    $email = trim($_POST["email"]);

    $sql = "
    SELECT pk_usuario, nome
    FROM usuarios
    WHERE email like :email
    ";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $dado = $stmt->fetch(PDO::FETCH_OBJ);

            //gerar uma nova senha aleatória
            $nova_senha = substr(hash('sha256', uniqid()), 0, 6);
            
            $mail = new PHPMailer(true);
            
            $mail->isSMTP();
            $mail->Host     ="mail.g1a.com.br";
            $mail->Username     ="aluno@g1a.com.br";
            $mail->Password     ="Senac@2024";
            $mail->SMTPSecure     = PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPAuth     = true;
            $mail->Port     = 465;

            $mail->setFrom("alunos@g1a.com.br", "Sistema Dashboard - OS");

            $mail->addAddress($email, $dado->nome);

            $mail->isHTML(true);
            $mail->Subject = "Recuperação de senha";
            $mail->CharSet = "UTF-8";
            $mail->Body = 
          "  <h2>RECUPERAÇÂO DE SENHA</h2>
            <p>Você solicitou uma alteração de senha em nosso painel dashboard.</p>
            <p>
                Segue abaixo dados do seu novo acesso:<br>
                <strong>URL:</strong>http://localhost/eugenio_gatinho012/dashboard/ <br>
                <strong>E-mail</strong>$email<br>
                <strong>Senha:</strong>$nova_senha<br>
            </p>
            <p>Enviado em".date("d/m/Y - H:i")."</p>
            ";
            $mail->send();

            $sql = "
            UPDATE cliente set
            senha = :senha
            WHERE pk_usuario = :pk_usuario
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':pk_usuario', $pk_usuario);
            $stmt->execute();

            $_SESSION["tipo"] = "success";
            $_SESSION["title"] = "Ops!";
            $_SESSION["msg"] = "Este e-mail não consta em nossa base de dados.";
    
        } else {
            $_SESSION["tipo"] = "error";
            $_SESSION["title"] = "Oba!";
            $_SESSION["msg"] = "Uma nova senha foi enciada para o seu e-mail.";
        }
    } catch (PDOException $ex) {
        $_SESSION["tipo"] = "error";
        $_SESSION["title"] = "Ops!";
        $_SESSION["msg"] = $e->getMessage();
    }
}

header("Location: ./login.php");
exit;
