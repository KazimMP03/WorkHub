<?php
// Inclui a configuração do banco de dados e o modelo de usuário
require_once '../config/db.php';
require_once '../models/User.php';

class UserController {
    private $userModel; // Armazena a instância do modelo de usuário

    // Construtor que inicializa o modelo de usuário
    public function __construct($pdo) {
        $this->userModel = new User($pdo); // Cria o modelo de usuário com a conexão ao banco
    }

    // Método para registrar um novo usuário
    public function register($dados) {
        try {
            // Tenta criar o usuário chamando o método 'create' do modelo
            if ($this->userModel->create($dados)) {
                // Limpa a sessão após o registro
                session_unset();
                session_destroy();

                // Redireciona o usuário para a página de login
                header('Location: ../../frontend/pages/login.html');
                exit(); // Garante que o código pare após o redirecionamento
            }
        } catch (Exception $e) {
            // Exibe uma mensagem de erro caso algo dê errado
            echo $e->getMessage();
        }
    }
}

// Inicia a sessão para acessar os dados do usuário
session_start();

// Verifica se todos os dados necessários estão presentes na sessão
if (!isset($_SESSION['nome'], $_SESSION['sobrenome'], $_SESSION['data_nascimento'], 
          $_SESSION['sexo'], $_SESSION['cpf'], $_SESSION['telefone'], $_SESSION['email'], $_SESSION['senha'])) {
    // Exibe erro se algum dado estiver faltando
    echo "Erro: Dados incompletos na sessão!";
    exit();
}

// Combina o nome e sobrenome para criar o nome completo
$nomeCompleto = $_SESSION['nome'] . ' ' . $_SESSION['sobrenome'];

// Prepara os dados do usuário para registrar no banco
$dadosUsuario = [
    'nome_completo' => $nomeCompleto,
    'cpf' => $_SESSION['cpf'],
    'data_nascimento' => $_SESSION['data_nascimento'],
    'telefone' => $_SESSION['telefone'],
    'email' => $_SESSION['email'],
    'senha' => $_SESSION['senha'],
    'sexo' => $_SESSION['sexo']
];

// Cria um controlador de usuário e registra os dados
$usuarioController = new UserController($pdo);
$usuarioController->register($dadosUsuario);
?>
