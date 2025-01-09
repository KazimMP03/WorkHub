<?php
// Função para verificar se o usuário está logado
function verificar_login() {
    // Verifica se a variável de sessão 'user_id' está definida
    if (!isset($_SESSION['user_id'])) {
        // Se não estiver logado, redireciona para a página de login
        header('Location: ../../frontend/pages/login.html');
        exit();  // Interrompe a execução do script para garantir o redirecionamento
    }
}
?>
