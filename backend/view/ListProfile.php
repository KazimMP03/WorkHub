<?php
// Inicia a sessão e verifica se o usuário está logado
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

require_once '../../backend/config/db.php';
require_once '../../backend/controllers/UserController.php';

$userController = new UserController($pdo);

// Carrega os dados do usuário
$query = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $_SESSION['user_id']]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $dados = [
        'nome_completo' => $_POST['nome_completo'],
        'email' => $_POST['email'],
        'telefone' => $_POST['telefone'],
        'sexo' => $_POST['sexo'],
        // Outros campos que você permitir editar
    ];

    // Recebe a foto, caso tenha sido enviada
    $foto = isset($_FILES['foto']) ? $_FILES['foto'] : null;

    // Edita o perfil
    try {
        $userController->editProfile($_SESSION['user_id'], $dados, $foto);
        echo "Perfil atualizado com sucesso!";
    } catch (Exception $e) {
        echo "Erro ao atualizar o perfil: " . $e->getMessage();
    }
}

// Função para formatar o número de telefone
// Necessário para aplicar a máscara para exibir para o usuário
function formatarTelefone($telefone) {
    // Remove todos os caracteres não numéricos
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    
    // Verifica se o número tem 11 dígitos (como no exemplo: 11940385156)
    if (strlen($telefone) == 11) {
        // Adiciona a máscara (XX) XXXXX-XXXX
        $telefoneFormatado = '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7, 4);
        return $telefoneFormatado;
    }
    
    // Se o número não tiver 11 dígitos, retorna o número sem máscara (você pode modificar isso conforme necessidade)
    return $telefone;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../../frontend/css/listProfile.css">
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
                <input type="file" name="foto" id="foto" class="foto-input">
            </div>

            <!-- Nome e Telefone -->
            <div class="profile-info">
                <p class="p-nome"><?php echo htmlspecialchars($userData['nome']); ?></p>
                <p><?php echo htmlspecialchars(formatarTelefone($userData['telefone'])); ?></p>
            </div>
        </div>

        <!-- Parte Direita (Formulário de Edição) -->
        <div class="form-container">
            <h1>Editar Perfil</h1>

            <form method="POST" enctype="multipart/form-data">
                <label for="foto">Nova Foto de Perfil:</label>
                <input type="file" name="foto" id="foto">

                <label for="nome_completo">Nome Completo:</label>
                <input type="text" name="nome_completo" id="nome_completo" value="<?php echo htmlspecialchars($userData['nome']); ?>" required>

                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>

                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($userData['telefone']); ?>" required>

                <label for="sexo">Sexo:</label>
                <select name="sexo" id="sexo">
                    <option value="masculino" <?php echo $userData['sexo'] == 'masculino' ? 'selected' : ''; ?>>Masculino</option>
                    <option value="feminino" <?php echo $userData['sexo'] == 'feminino' ? 'selected' : ''; ?>>Feminino</option>
                    <option value="outro" <?php echo $userData['sexo'] == 'outro' ? 'selected' : ''; ?>>Outro</option>
                </select>

                <button type="submit">Salvar Alterações</button>
            </form>
        </div>
    </div>
</body>
</html>