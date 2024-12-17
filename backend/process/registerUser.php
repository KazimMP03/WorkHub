<?php
// Inicializa a sessão
session_start(); 
require_once '../config/db.php';

class Usuario {
    private $pdo;

    // Construtor que recebe a instância do PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para registrar um novo usuário
    public function registrar($nomeCompleto, $email, $senha) {
        try {
            $query = "INSERT INTO users (nome, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':nome' => $nomeCompleto,
                ':email' => $email,
                ':senha' => $senha
            ]);

            // Limpa a sessão após o registro
            session_unset();
            session_destroy();

            echo "Usuário registrado com sucesso!";
        } catch (PDOException $e) {
            echo "Erro ao registrar o usuário: " . $e->getMessage();
        }
    }
}

// Recupera os dados da sessão
$nome = $_SESSION['nome'];
$sobrenome = $_SESSION['sobrenome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

// Verifica se todos os dados estão presentes
if (!$email || !$senha || !$nome || !$sobrenome) {
    echo "Erro: Dados incompletos!";
    exit();
}

// Combina o nome e sobrenome para o nome completo
$nomeCompleto = $nome . " " . $sobrenome;

// Cria uma nova instância da classe Usuario e registra o usuário
$usuario = new Usuario($pdo);
$usuario->registrar($nomeCompleto, $email, $senha);
?>