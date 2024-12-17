<?php
// Configurações do Banco de Dados
class BancoDeDados {
    private $pdo;

    // Método construtor para inicializar a conexão
    public function __construct($host, $port, $dbname, $user, $password) {
        try {
            // Tenta criar uma conexão com o Banco de Dados usando PDO(PHP Data Objects)
            $this->pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
            // Configura para lançar exceções em caso de erro
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Se houver erro, exibe a mensagem e encerra a execução
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    // Método para obter a instância do PDO (conexão com o banco)
    public function getPDO() {
        return $this->pdo;
    }
}

// Configurações do Banco de Dados
$host = "localhost"; // Endereço do servidor(nesse caso, localhost)
$port = "5432"; // Porta(o padrão do PostgreSQL é 5432)
$dbname = "nicejobs"; // Nome do Banco de Dados
$user = "postgres"; // Nome do usuário
$password = "123"; // Senha 

// Cria uma nova instância da classe BancoDeDados
$db = new BancoDeDados($host, $port, $dbname, $user, $password);
$pdo = $db->getPDO(); // Obtém a instância PDO para ser usada em outros arquivos
?>