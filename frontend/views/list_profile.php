<?php
session_start(); // Inicia a sessão

// Inclui o arquivo de funções utilitárias
require_once '../../backend/utils.php';

// Chama a função para verificar se o usuário está logado
verificar_login();

// Inclui os arquivos para conexão do banco e controle de usuário
require_once '../../backend/config/database.php';
require_once '../../backend/controllers/user_controller.php';

$user_controller = new UserController($pdo); // Cria o controlador de usuário

$query = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($query); // Prepara a consulta para pegar os dados do usuário
$stmt->execute([':id' => $_SESSION['user_id']]); // Executa a consulta usando o ID do usuário
$userData = $stmt->fetch(PDO::FETCH_ASSOC); // Armazena os dados do usuário

// Se o formulário for enviado...
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário para atualização
    $dados = [
        'nome' => $_POST['nome_completo'],
        'email' => $_POST['email'],
        'telefone' => $_POST['telefone'],
        'sexo' => $_POST['sexo'],
        // Novos campos, caso, queriamos mudar
    ];

    // Recebe a foto, se enviada
    $foto = isset($_FILES['foto']) ? $_FILES['foto'] : null;

    $alterado = false;

    // Verifica se houve alteração nos dados
    foreach ($dados as $campo => $value) {
        if ($value != $userData[$campo]) {
            $alterado = true;
            break;
        }
    }

    // Se não houve alteração e a foto não foi enviada, exibe uma mensagem
    if (!$alterado && (!isset($foto) || $foto['error'] !== UPLOAD_ERR_OK)) {
        redirect_with_alert('Nenhuma alteração detectada. Os dados atuais serão mantidos.', '../../frontend/views/list_profile.php');
    }    

    // Tenta atualizar o perfil do usuário
    try {
        $user_controller->edit_profile($_SESSION['user_id'], $dados, $foto);
        redirect_with_alert('Perfil atualizado com sucesso!', '../../frontend/views/list_profile.php');
    } catch (Exception $e) {
        redirect_with_alert('Erro ao atualizar o perfil:' . addslashes($e->getMessage()), '../../frontend/views/list_profile.php');
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../../frontend/assets/css/list_profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>
    <!-- Header -->
    <?php include '../../frontend/include/header.php'; ?>

    <div class="container">
        <!-- Parte Esquerda (Perfil do Usuário) -->
        <div class="profile-container">
            <h1>Perfil do Usuário</h1>

            <!-- Foto de perfil (clicável) -->
            <div class="profile-photo-container">
                <img src="../../uploads/<?php echo htmlspecialchars($userData['foto']); ?>" alt="Foto do Perfil" class="profile-photo" id="profile-photo">
                <label for="foto" class="edit-photo-icon">
                    <i class="fas fa-pencil-alt"></i>
                </label>
            </div>

            <!-- Nome e Telefone -->
            <div class="profile-info">
                <p class="p-nome"><?php echo htmlspecialchars($userData['nome']); ?></p>
                <p><?php echo htmlspecialchars(formatar_telefone($userData['telefone'])); ?></p>
            </div>
        </div>

        <!-- Parte Direita (Formulário de Edição) -->
        <div class="form-container">
            <h1>Editar Perfil</h1>

            <form method="POST" enctype="multipart/form-data">
                <!-- Campo escondido para chamar pelo JS ao clicar na foto de perfil -->
                <input type="file" name="foto" id="foto" style="display: none;">

                <!-- Edição do nome -->
                <label for="nome_completo">Nome Completo:</label>
                <input type="text" name="nome_completo" id="nome_completo" value="<?php echo htmlspecialchars($userData['nome']); ?>" required>

                <!-- Edição do email -->
                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>

                <!-- Edição do telefone -->
                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($userData['telefone']); ?>" required>

                <!-- Edição do gênero -->
                <label for="sexo">Gênero:</label>
                <select name="sexo" id="sexo">
                    <option value="masculino" <?php echo $userData['sexo'] == 'masculino' ? 'selected' : ''; ?>>Masculino</option>
                    <option value="feminino" <?php echo $userData['sexo'] == 'feminino' ? 'selected' : ''; ?>>Feminino</option>
                    <option value="outro" <?php echo $userData['sexo'] == 'outro' ? 'selected' : ''; ?>>Outro</option>
                </select>
                
                <!-- Botão para salvar as alterações -->
                <button type="submit">Salvar Alterações</button>
            </form>
        </div>
    </div>
    <!-- Script para upload de foto -->
    <script src="../../frontend/js/upload_foto.js"></script>
</body>
</html>