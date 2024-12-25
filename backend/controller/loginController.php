<?php
// Inclui o arquivo de configuração do banco de dados
require_once '../config/db.php';

class LoginController {
    private $pdo; // Variável $pdo que irá armazenar a conexão com o banco de dados

    // Construtor para inicializar a conexão com o banco
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para verificar login do usuário
    public function login($email, $senha) {
        try {
            // Criação da query SQL para buscar o usuário pelo email
            $query = "SELECT * FROM users WHERE email = :email";
            // Preparação da query, protege contra injeções SQL
            $stmt = $this->pdo->prepare($query);

            // Execute a consulta passando o email fornecido
            $stmt->execute([':email' => $email]);

            // Recupera o registro de usuário
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica se o usuário ou a senha não corresponde com a do banco de dados
            if (!$usuario || !password_verify($senha, $usuario['senha'])) {
                echo "Email ou senha incorretos.";
                exit();
            }

            // Login bem-sucedido, cria a sessão
            session_start();
            $_SESSION['user_id'] = $usuario['id']; // Salva o ID do usuário na sessão 
            $_SESSION['user_name'] = $usuario['name']; // Salva o nome do usuário na sessão
            header('Location: ../../frontend/pages/home.php'); // Redireciona para 'home.php'
            exit(); 
        } catch (PDOException $e) {
            echo "Erro ao tentar logar: ". $e->getMessage();
        }
    }
}

// Verifica se os dados do formulário foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; // Obtém o email enviado pelo formulário
    $senha = $_POST['senha']; // Obtém a senha enviada pelo formulário

    // Instancia o controlador de login
    $loginController = new LoginController($pdo);

    // Chama o método de login
    $loginController->login($email, $senha);
}
?>