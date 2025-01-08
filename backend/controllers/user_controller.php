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
        $usuarioAlterado = false;

        // Verifica se algum dos campos foi alterado
        if ($dados['nome'] !== null && $dados['nome'] !== $this->userModel->getUserField($userId, 'nome')) {
            // Atualiza nome se diferente
            $usuarioAlterado = true;
            $this->userModel->updateField($userId, 'nome', $dados['nome']);
        }

        if ($dados['email'] !== null && $dados['email'] !== $this->userModel->getUserField($userId, 'email')) {
            // Atualiza o e-mail se diferente
            $usuarioAlterado = true;
            $this->userModel->updateField($userId, 'email', $dados['email']);
        }

        if ($dados['telefone'] !== null && $dados['telefone'] !== $this->userModel->getUserField($userId, 'telefone')) {
            // Atualiza telefone se diferente
            $usuarioAlterado = true;
            $this->userModel->updateField($userId, 'telefone', $dados['telefone']);
        }

        if ($dados['sexo'] !== null && $dados['sexo'] !== $this->userModel->getUserField($userId, 'sexo')) {
            // Atualiza sexo se diferente
            $usuarioAlterado = true;
            $this->userModel->updateField($userId, 'sexo', $dados['sexo']);
        }

        if ($foto && $foto['size'] > 0) {
            // Verifica se o tamanho da foto está dentro do limite (2MB)
            if ($foto['size'] > 2 * 1024 * 1024) { // Limite de 2MB
                throw new Exception("O tamanho da foto não pode exceder 2MB.");
            }
            
            // Verifica se o usuário já tem uma foto no banco
            $fotoAntiga = $this->userModel->getUserPhoto($userId);
        
            // Faz o upload da nova foto
            try {
                $fotoNome = $this->uploadFoto($foto); // Agora com verificação de extensão e tipo
                $usuarioAlterado = true;  // Marca que o usuário foi alterado
            } catch (Exception $e) {
                // Lança a exceção com a mensagem de erro
                throw new Exception("Erro ao fazer upload da foto: " . $e->getMessage());
            }
        
            // Atualiza a foto do usuário no banco
            $this->userModel->updatePhoto($userId, $fotoNome);
        
            // Se o usuário já tinha uma foto, exclui a foto antiga do servidor
            if ($fotoAntiga) {
                $this->deleteOldPhoto($fotoAntiga);
            }
        }

        if (!$usuarioAlterado) {
            // Se não houver nenhuma alteração, não faz nada
            return;
        }
        
    }

    // Função para excluir a foto antiga do servidor
    private function deleteOldPhoto($fotoAntiga) {
        // Caminho completo para a foto antiga
        $diretorio = '../../uploads/';
        $caminhoFoto = $diretorio . $fotoAntiga;

        // Verifica se a foto existe no servidor e a exclui
        if (file_exists($caminhoFoto)) {
            unlink($caminhoFoto); // Exclui o arquivo
        }
    }

    // Função para fazer o upload da foto
    private function uploadFoto($foto) {
        // Diretório onde as fotos serão armazenadas
        $diretorio = '../../uploads/';

        // Gera um nome único para a foto
        $extensao = pathinfo($foto['name'], PATHINFO_EXTENSION);

        // Definimos as extensões válidas
        $extensoesValidas = ['jpeg', 'jpg', 'png'];

        // Verifica se a extensão é válida
        if (!in_array(strtolower($extensao), $extensoesValidas)) {
            throw new Exception("A foto precisa ser uma imagem válida com extensão .jpeg, .jpg ou .png.");
        }

        // Verifica se o arquivo é realmente uma imagem
        $imagem = getimagesize($foto['tmp_name']);
        if ($imagem === false) {
            throw new Exception("O arquivo enviado não é uma imagem válida.");
        }

        // Gera um nome único para a foto   
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

// Inicia a sessão apenas se não estiver ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Caso o usuário não esteja logado(Cadastrou agora)
if (!isset($_SESSION['user_id'])) {
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
}
?>
