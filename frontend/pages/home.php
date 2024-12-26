<?php
session_start();  // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['user_name'])) {
    header('Location: login.html'); // Redireciona para a página de login se não estiver logado
    exit();
}

// Obtém o nome completo da sessão e extrai o primeiro nome
$fullName = $_SESSION['user_name'];
$firstName = explode(' ', $fullName)[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - WorkHub</title>
    <!-- Link para os ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>
    <header class="header">
        <!-- Logo -->
        <div class="logo">
            <img src="../img/logo.png" alt="logo">
            <h1>WorkHub</h1>
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
            <img src="../img/teste-perfil.png" alt="Foto do Usuário" class="profile-img"/>
            <span class="profile-name"><?php echo htmlspecialchars($firstName); ?></span>
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
</body>
</html>
