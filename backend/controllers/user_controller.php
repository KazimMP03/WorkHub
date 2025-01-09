<?php
// Inclui a configuração do banco de dados, modelo de usuário e utils.php
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

        // Verifica e atualiza os campos de dados do usuário
        $usuario_alterado = $this->update_user_fields($user_id, $dados);

        // Se foto for fornecida, processa o upload
        if ($foto && $foto['size'] > 0) {
            $usuario_alterado = $this->handle_photo_upload($user_id, $foto);
        }

        // Se não houver nenhuma alteração, não faz nada
        if (!$usuario_alterado) {
            return;
        }
    }

    // Atualiza os campos de dados do usuário
    private function update_user_fields($user_id, $dados) {
        $usuario_alterado = false;

        // Campos a serem verificados e atualizados
        $campos = ['nome', 'email', 'telefone', 'sexo'];
        
        foreach ($campos as $campo) {
            if (isset($dados[$campo]) && $dados[$campo] !== null && $dados[$campo] !== $this->user_model->get_user_field($user_id, $campo)) {
                $usuario_alterado = true;
                $this->user_model->update_field($user_id, $campo, $dados[$campo]);
            }
        }

        return $usuario_alterado;
    }

    // Processa o upload da foto de perfil
    private function handle_photo_upload($user_id, $foto) {
        // Verifica se o tamanho da foto está dentro do limite (2MB)
        if ($foto['size'] > 2 * 1024 * 1024) { // Limite de 2MB
            redirect_with_alert('O tamanho da foto não pode exceder 2MB.', '../../frontend/views/list_profile.php');
        }

        // Verifica se o usuário já tem uma foto no banco
        $foto_antiga = $this->user_model->get_user_photo($user_id);

        // Faz o upload da nova foto
        try {
            $foto_nome = $this->upload_foto($foto); // Agora com verificação de extensão e tipo
        } catch (Exception $e) {
            redirect_with_alert('O tamanho da foto não pode exceder 2MB.', '../../frontend/views/list_profile.php');
        }

        // Atualiza a foto do usuário no banco
        $this->user_model->update_photo($user_id, $foto_nome);

        // Se o usuário já tinha uma foto, exclui a foto antiga do servidor
        if ($foto_antiga) {
            $this->delete_old_photo($foto_antiga);
        }

        return true;
    }

    // Função para excluir a foto antiga do servidor
    private function delete_old_photo($foto_antiga) {
        $diretorio = '../../uploads/';
        $caminho_foto = $diretorio . $foto_antiga;

        // Verifica se a foto existe no servidor e a exclui
        if (file_exists($caminho_foto)) {
            unlink($caminho_foto); // Exclui o arquivo
        }
    }

    // Função para fazer o upload da foto
    private function upload_foto($foto) {
        $diretorio = '../../uploads/';

        // Gera um nome único para a foto
        $extensao = pathinfo($foto['name'], PATHINFO_EXTENSION);

        // Definimos as extensões válidas
        $extensoes_validas = ['jpeg', 'jpg', 'png'];

        // Verifica se a extensão é válida
        if (!in_array(strtolower($extensao), $extensoes_validas)) {
            redirect_with_alert('O tamanho da foto não pode exceder 2MB.', '../../frontend/views/list_profile.php');
        }   

        // Verifica se o arquivo é realmente uma imagem
        $imagem = getimagesize($foto['tmp_name']);
        if ($imagem === false) {
            redirect_with_alert('O tamanho da foto não pode exceder 2MB.', '../../frontend/views/list_profile.php');
        }

        // Gera um nome único para a foto   
        $nome_foto = uniqid() . '.' . $extensao;

        // Move o arquivo para o diretório de uploads
        if (move_uploaded_file($foto['tmp_name'], $diretorio . $nome_foto)) {
            return $nome_foto;
        } else {
            redirect_with_alert('O tamanho da foto não pode exceder 2MB.', '../../frontend/views/list_profile.php');
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

                // Redireciona o usuário para a página de login
                redirect_with_alert('O tamanho da foto não pode exceder 2MB.', '../../frontend/views/list_profile.php');
            }
        } catch (Exception $e) {
            redirect_with_alert('O tamanho da foto não pode exceder 2MB.', '../../frontend/pages/cadastro1.html');
        }
    }
}
?>
