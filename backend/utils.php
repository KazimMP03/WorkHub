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

// Função para iniciar a sessão apenas se não estiver ativa
function iniciar_sessao() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function redirect_with_alert($mensagem, $caminho) {
    echo "<script>
            alert('" . addslashes($mensagem) . "');
            window.location.href = '" . addslashes($caminho) . "';
          </script>";
    exit();
}

// Função para formatar o telefone com máscara (XX) XXXXX-XXXX
function formatar_telefone($telefone) {
    // Remove caracteres não numéricos
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    
    // Verifica se o número tem 11 dígitos (como no exemplo: 11940385156)
    if (strlen($telefone) == 11) { // Se o número tiver 11 dígitos...
        // Adiciona a máscara (XX) XXXXX-XXXX
        $telefone_formatado = '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7, 4);
        return $telefone_formatado; // Retorna o número sem formatação se não tiver 11 dígitos
    }
}
?>
