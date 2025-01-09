<?php
// Inclui o arquivo de funções utilitárias
require_once '../../backend/utils.php';

// Chama a função para verificar se o usuário está logado
verificar_login();

// Busca o primeiro nome do usuário
$nome_completo = $_SESSION['user_name'];
$primeiro_nome = explode(' ', $nome_completo);

// Conecta ao banco para pegar a foto do perfil do usuário
require_once '../../backend/config/database.php';

// Prepara a consulta para pegar a foto de perfil do usuário
$query = "SELECT foto FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$foto_perfil = $stmt->fetchColumn();

// Define a foto de perfil padrão caso o usuário não tenha uma
$foto_perfil_path = $foto_perfil ? "../../uploads/{$foto_perfil}" : "../../frontend/assets/images/logo.png";
?>
<head>
    <!-- Link para os ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../frontend/assets/css/header.css">
    <header class="header">
        <!-- Logo -->
        <div class="logo">
            <a href="../../frontend/pages/home.php">
                <img src="../../frontend/assets/images/logo.png" alt="logo">
            </a>
        </div>

        <!-- Endereços -->
        <a href="../../frontend/views/list_address.php" class="address">
            <i class="fas fa-map-marker-alt"></i>
            <span>Endereços</span>
        </a>

        <!-- Chat -->
        <a href="/chat" class="chat">
            <i class="fas fa-comment-alt"></i>
            <span>Chats</span>
        </a>

        <!-- Barra de pesquisa -->
        <div class="search-container">
            <input type="text" class="search-bar" placeholder="Buscar serviços, profissionais e mais...">
            <button class="search-button">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Perfil -->
        <a href="../../frontend/views/list_profile.php" class="profile">
            <img src="<?php echo $foto_perfil_path; ?>" alt="Foto do Usuário" class="profile-img"/>
            <span class="profile-name"><?php echo htmlspecialchars($primeiro_nome[0]); ?></span>
        </a>

        <!-- Favoritos -->
        <a href="/favoritos" class="favorites">
            <i class="fas fa-star"></i>
            <span>Favoritos</span>
        </a>

        <!-- Botão de Logout -->
        <a href="../../backend/logout.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Sair</span>
        </a>
    </header>
</head>