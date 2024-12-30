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

    // Método para editar a foto de perfil
    public function editProfile($userId, $dados, $foto = null) {
        // Ainda preciso adionar a lógica para editar todas as informações que podem ser edidatas

        if ($foto) {
            $fotoNome = $this->uploadFoto($foto); // Método para fazer upload da foto e salvar o nome do arquivo
            $this->userModel->updatePhoto($userId, $fotoNome);
        }
    }

    // Função para fazer o upload da foto
    private function uploadFoto($foto) {
        // Diretório onde as fotos serão armazenadas
        $diretorio = '../../uploads/';

        // Gera um nome único para a foto
        $extensao = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $nomeFoto = uniqid() . '.' . $extensao;

        // Move o arquivo para o diretório de uploads
        if (move_uploaded_file($foto['tmp_name'], $diretorio . $nomeFoto)) {
            return $nomeFoto;
        } else {
            throw new Exception("Erro ao fazer upload da foto.");
        }
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
