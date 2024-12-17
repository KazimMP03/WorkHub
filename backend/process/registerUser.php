<?php
// Inicializa a sessão para acessar variáveis da sessão (dados temporários)
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../config/db.php';

class Usuario {
    private $pdo; // Atributo para armazenar a conexão com o banco de dados

    // Construtor da classe, recebe o PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para registrar o usuário no banco de dados
    public function register($dados) {
        try {
            // Query SQL para inserir os dados no banco
            $query = "INSERT INTO users (nome, cpf, data_nascimento, telefone, email, senha, sexo) 
                      VALUES (:nome, :cpf, :data_nascimento, :telefone, :email, :senha, :sexo)";
            
            // Prepara a consulta SQL
            $stmt = $this->pdo->prepare($query);

            // Executa a consulta passando os dados do usuário
            $stmt->execute([
                ':nome' => $dados['nome_completo'],
                ':cpf' => $dados['cpf'],
                ':data_nascimento' => $dados['data_nascimento'],
                ':telefone' => $dados['telefone'],
                ':email' => $dados['email'],
                ':senha' => $dados['senha'], 
                ':sexo' => $dados['sexo']
            ]);

            // Limpa a sessão após o registro
            session_unset();
            session_destroy();

            // Redireciona para a página de login
            header('Location: ../../frontend/pages/login.html');
        } catch (PDOException $e) {
            // Caso ocorra algum erro ao registrar o usuário, exibe a mensagem de erro
            echo "Erro ao registrar o usuário: " . $e->getMessage();
        }
    }
}

// Recupera os dados do usuário armazenados na sessão
$nome = $_SESSION['nome']; // Nome do usuário
$sobrenome = $_SESSION['sobrenome']; // Sobrenome do usuário
$data_nascimento = $_SESSION['data_nascimento']; // Data de nascimento do usuário
$sexo = $_SESSION['sexo']; // Sexo do usuário
$cpf = $_SESSION['cpf']; // CPF do usuário
$telefone = $_SESSION['telefone']; // Telefone do usuário
$email = $_SESSION['email']; // Email do usuário
$senha = $_SESSION['senha']; // Senha do usuário

// Verifica se todos os dados necessários estão presentes na sessão
if (!isset($nome, $sobrenome, $data_nascimento, $sexo, $cpf, $telefone, $email, $senha)) {
    // Se faltar algum dado, exibe uma mensagem de erro e interrompe a execução do script
    echo "Erro: Dados incompletos na sessão!";
    exit();
} 

// Combina o nome e sobrenome em uma única string (nome completo)
$nomeCompleto = $_SESSION['nome'] . ' ' . $_SESSION['sobrenome'];

// Cria um array associativo com todos os dados necessários para o registro do usuário
$dadosUsuario = [
    'nome_completo' => $nomeCompleto, // Nome completo
    'cpf' => $cpf, // CPF
    'data_nascimento' => $data_nascimento, // Data de nascimento
    'telefone' => $telefone, // Telefone
    'email' => $email, // Email
    'senha' => $senha, // Senha
    'sexo' => $sexo // Sexo
];

// Cria um objeto da classe Usuario, passando o PDO (conexão com o banco) para o construtor
$usuario = new Usuario($pdo);

// Chama o método 'register' para registrar o usuário no banco de dados
$usuario->register($dadosUsuario);

?>