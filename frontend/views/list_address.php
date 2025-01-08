<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../frontend/pages/login.html');
    exit();
}

// Inclui a conexão com o banco de dados e o modelo Address
require_once '../../backend/config/database.php';
require_once '../../backend/models/address.php';

$userId = $_SESSION['user_id']; // Obtém o ID do usuário logado

try {
    // Cria uma instância do modelo Address
    $addressModel = new Address($pdo);

    // Busca os endereços do usuário logado
    $addresses = $addressModel->getAddressesByUserId($userId);
} catch (Exception $e) {
    die("Erro ao carregar endereços: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Endereços</title>
    <link rel="stylesheet" href="../../frontend/assets/css/list_address.css">
</head>
<body>
    <!-- Header -->
    <?php include '../../frontend/include/header.php';?>

    <h2>Meus Endereços</h2>

    <!-- Botão de Adicionar Endereço como o primeiro cartão -->
    <div class="address-list">
        <div class="address-item add-address-card">
            <a href="../../frontend/pages/register_address.html">
                <button>
                    <i class="fas fa-plus plus-icon"></i> Adicionar endereço
                </button>
            </a>
        </div>

        <!-- Lista de endereços -->
        <?php if (!empty($addresses)): ?>
            <?php foreach ($addresses as $address): ?>
                <div class="address-item">
                    <strong><?php echo htmlspecialchars($address['rua']); ?>, <?php echo htmlspecialchars($address['numero']); ?></strong><br>
                    Bairro: <?php echo htmlspecialchars($address['bairro']); ?><br>
                    Cidade: <?php echo htmlspecialchars($address['cidade']); ?> - <?php echo htmlspecialchars($address['estado']); ?><br>
                    CEP: <?php echo htmlspecialchars($address['cep']); ?><br>
                    <?php echo !empty($address['complemento']) ? 'Complemento: ' . htmlspecialchars($address['complemento']) : ''; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum endereço cadastrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
