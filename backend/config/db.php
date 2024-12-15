<?php
// Variáveis para configurar o Banco de Dados(DB)
$host = "localhost"; // Endereço do servidor(localhost está rodando na mesma máquina)
$port = "5432"; // Porta do DB(o padrão é 5432)
$dbname = "nicejobs"; // Nome do BD que será acessado
$user = "postgres"; // Nome do usuário do BD
$password = "123"; // Senha do BD

// Tenta acessar o BD usando o $pdo(PHP Data Objects)
try {
    // Criando uma nova instância $pdo com as configurações
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    // Configura o $pdo para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Se houver um erro ao conectar, exibe uma mensagem e encerra a execução 
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
