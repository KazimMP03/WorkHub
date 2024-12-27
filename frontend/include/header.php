<?php
// Verifica se o usuário está logado (se 'user_name' ou 'user_id' não estiverem definidos)
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    // Se não estiver, redireciona para página de login
    header('Location: ../pages/login.html');
    exit();
}

// Busca o primeiro nome do usuário
$nomeCompleto = $_SESSION['user_name'];
$primeiroNome = explode(' ', $nomeCompleto);
?>
<head>
    <!-- Link para os ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../frontend/css/header.css">
    <header class="header">
        <!-- Logo -->
        <div class="logo">
            <a href="../../frontend/pages/home.php">
                <img src="../../frontend/img/logo.png" alt="logo">
            </a>
        </div>

        <!-- Endereços -->
        <a href="../../backend/view/listAddress.php" class="address">
            <i class="fas fa-map-marker-alt"></i>
            <span>Endereços</span>
        </a>

        <!-- Barra de pesquisa -->
        <div class="search-container">
            <input type="text" class="search-bar" placeholder="Buscar serviços, profissionais e mais...">
            <button class="search-button">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Perfil -->
        <a href="/perfil" class="profile">
            <img src="../../frontend/img/teste-perfil.png" alt="Foto do Usuário" class="profile-img"/>
            <span class="profile-name"><?php echo htmlspecialchars($primeiroNome[0]); ?></span>
        </a>

        <!-- Chat -->
        <a href="/chat" class="chat">
            <i class="fas fa-comment-alt"></i>
            <span>Chats</span>
        </a>

        <!-- Favoritos -->
        <a href="/favoritos" class="favorites">
            <i class="fas fa-star"></i>
            <span>Favoritos</span>
        </a>
    </header>
</head>