<?php
session_start(); // Inicia a sessõa

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../frontend/pages/login.html');
    exit();
}

// Inclui a conexão com o banco de dados e o modelo Address
require_once '../config/db.php';
require_once '../models/Address.php';

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
    <link rel="stylesheet" href="../../frontend/css/listAddress.css">
</head>
<body>
    <h1>Meus Endereços</h1>
    
    <!-- Botão para adicionar um novo endereço -->
    <div class="add-address-button">
        <a href="../../frontend/pages/registerAddress.html">
            <button>Cadastrar novo endereço</button>
        </a>
    </div>

    <?php if (!empty($addresses)): ?>
        <ul class="address-list">
            <?php foreach ($addresses as $address): ?>
                <li class="address-item">
                    <strong><?php echo htmlspecialchars($address['rua']); ?>, <?php echo htmlspecialchars($address['numero']); ?></strong><br>
                    Bairro: <?php echo htmlspecialchars($address['bairro']); ?><br>
                    Cidade: <?php echo htmlspecialchars($address['cidade']); ?> - <?php echo htmlspecialchars($address['estado']); ?><br>
                    CEP: <?php echo htmlspecialchars($address['cep']); ?><br>
                    <?php echo !empty($address['complemento']) ? 'Complemento: ' . htmlspecialchars($address['complemento']) : ''; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nenhum endereço cadastrado.</p>
        <a href="../../frontend/pages/registerAddress.html">Cadastrar novo endereço</a>
    <?php endif; ?>
</body>
</html>
