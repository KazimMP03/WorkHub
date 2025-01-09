<?php
// Inclui a configuração do banco de dado, o modelo de usuário e utils.php
require_once '../../backend/config/database.php';
require_once '../../backend/models/user.php';
require_once '../../backend/utils.php';

class UserController {
    private $user_model; // Armazena a instância do modelo de usuário

    // Construtor que inicializa o modelo de usuário
    public function __construct($pdo) {
        $this->user_model = new User($pdo); // Cria o modelo de usuário com a conexão ao banco
    }

    // Método para editar a foto de perfil
    public function edit_profile($user_id, $dados, $foto = null) {
        $usuario_alterado = false;

        // Verifica se algum dos campos foi alterado
        if ($dados['nome'] !== null && $dados['nome'] !== $this->user_model->get_user_field($user_id, 'nome')) {
            // Atualiza nome se diferente
            $usuario_alterado = true;
            $this->user_model->update_field($user_id, 'nome', $dados['nome']);
        }

        if ($dados['email'] !== null && $dados['email'] !== $this->user_model->get_user_field($user_id, 'email')) {
            // Atualiza o e-mail se diferente
            $usuario_alterado = true;
            $this->user_model->update_field($user_id, 'email', $dados['email']);
        }

        if ($dados['telefone'] !== null && $dados['telefone'] !== $this->user_model->get_user_field($user_id, 'telefone')) {
            // Atualiza telefone se diferente
            $usuario_alterado = true;
            $this->user_model->update_field($user_id, 'telefone', $dados['telefone']);
        }

        if ($dados['sexo'] !== null && $dados['sexo'] !== $this->user_model->get_user_field($user_id, 'sexo')) {
            // Atualiza sexo se diferente
            $usuario_alterado = true;
            $this->user_model->update_field($user_id, 'sexo', $dados['sexo']);
        }

        if ($foto && $foto['size'] > 0) {
            // Verifica se o tamanho da foto está dentro do limite (2MB)
            if ($foto['size'] > 2 * 1024 * 1024) { // Limite de 2MB
                redirect_with_alert('O tamanho da foto não pode exceder 2MB', '../../frontend/views/list_profile');
            }
            
            // Verifica se o usuário já tem uma foto no banco
            $foto_antiga = $this->user_model->get_user_photo($user_id);
        
            // Faz o upload da nova foto
            try {
                $foto_nome = $this->upload_foto($foto); // Agora com verificação de extensão e tipo
                $usuario_alterado = true;  // Marca que o usuário foi alterado
            } catch (Exception $e) {
                redirect_with_alert('Erro ao fazer upload da foto:' . $e->getMessage(), '../../frontend/views/list_profile.php');
            }
        
            // Atualiza a foto do usuário no banco
            $this->user_model->update_photo($user_id, $foto_nome);
        
            // Se o usuário já tinha uma foto, exclui a foto antiga do servidor
            if ($foto_antiga) {
                $this->delete_old_photo($foto_antiga);
            }
        }

        if (!$usuario_alterado) {
            // Se não houver nenhuma alteração, não faz nada
            return;
        }
        
    }

    // Função para excluir a foto antiga do servidor
    private function delete_old_photo($foto_antiga) {
        // Caminho completo para a foto antiga
        $diretorio = '../../uploads/';
        $caminho_foto = $diretorio . $foto_antiga;

        // Verifica se a foto existe no servidor e a exclui
        if (file_exists($caminho_foto)) {
            unlink($caminho_foto); // Exclui o arquivo
        }
    }

    // Função para fazer o upload da foto
    private function upload_foto($foto) {
        // Diretório onde as fotos serão armazenadas
        $diretorio = '../../uploads/';

        // Gera um nome único para a foto
        $extensao = pathinfo($foto['name'], PATHINFO_EXTENSION);

        // Definimos as extensões válidas
        $extensoes_validas = ['jpeg', 'jpg', 'png'];

        // Verifica se a extensão é válida
        if (!in_array(strtolower($extensao), $extensoes_validas)) {
            redirect_with_alert('A foto precisa ser uma imagem válida com extensão .jpeg, .jpg ou .png.', '../../frontend/views/list_profile.php');        
        }

        // Verifica se o arquivo é realmente uma imagem
        $imagem = getimagesize($foto['tmp_name']);
        if ($imagem === false) {
            redirect_with_alert('O arquivo não é uma imagem válida.', '../../frontend/views/list_profile.php');
        }

        // Gera um nome único para a foto   
        $nome_foto = uniqid() . '.' . $extensao;

        // Move o arquivo para o diretório de uploads
        if (move_uploaded_file($foto['tmp_name'], $diretorio . $nome_foto)) {
            return $nome_foto;
        } else {
            redirect_with_alert('Erro ao fazer upload da foto.', '../../frontend/views/list_profile.php');
        }
    }

    // Método para registrar um novo usuário
    public function register($dados) {
        try {
            // Tenta criar o usuário chamando o método 'create' do modelo
            if ($this->user_model->create($dados)) {
                // Limpa a sessão após o registro
                session_unset();
                session_destroy();

                redirect_with_alert('Usuário criado com sucesso.', '../../frontend/pages/login.html');
            }
        } catch (Exception $e) {
            redirect_with_alert('Erro: ' . $e->getMessage(), '../../frontend/pages/cadastro1.html');
        }
    }
}

iniciar_sessao();

// Caso o usuário não esteja logado(Cadastrou agora)
if (!isset($_SESSION['user_id'])) {
    // Verifica se todos os dados necessários estão presentes na sessão
    if (!isset($_SESSION['nome'], $_SESSION['sobrenome'], $_SESSION['data_nascimento'], 
            $_SESSION['sexo'], $_SESSION['cpf'], $_SESSION['telefone'], $_SESSION['email'], $_SESSION['senha'])) {
        redirect_with_alert('Erro: Dados incompletos na sessão.', '../../frontend/pages/cadastro1.htnl');
    }

    // Combina o nome e sobrenome para criar o nome completo
    $nome_completo = $_SESSION['nome'] . ' ' . $_SESSION['sobrenome'];

    // Prepara os dados do usuário para registrar no banco
    $dados_usuario = [
        'nome_completo' => $nome_completo,
        'cpf' => $_SESSION['cpf'],
        'data_nascimento' => $_SESSION['data_nascimento'],
        'telefone' => $_SESSION['telefone'],
        'email' => $_SESSION['email'],
        'senha' => $_SESSION['senha'],
        'sexo' => $_SESSION['sexo']
    ];

    // Cria um controlador de usuário e registra os dados
    $usuario_controller = new UserController($pdo);
    
    $usuario_controller->register($dados_usuario);
}
?>