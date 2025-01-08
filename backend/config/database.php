<?php
// Define a classe Database para gerenciar a conexão com o banco de dados
class Database {
    private $pdo;  // Atributo privado para armazenar a instância PDO

    // Construtor da classe, que recebe parâmetros para estabelecer a conexão com o banco
    public function __construct($host, $port, $dbname, $user, $password) {
        try {
            // Cria uma nova instância PDO para conectar ao banco de dados PostgreSQL
            // A string de DSN (Data Source Name) contém as informações de conexão
            $this->pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
            
            // Configura o PDO para lançar exceções em caso de erro
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Caso ocorra algum erro ao tentar se conectar ao banco de dados, a exceção será capturada e uma mensagem de erro será exibida
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    // Método para obter a instância PDO criada na conexão
    public function getPDO() {
        return $this->pdo;  // Retorna o objeto PDO para poder ser utilizado fora da classe
    }
}

// O arquivo de configuração (config.php) é incluído e seus valores são carregados
$config = require_once '../../backend/config/config.php';

// Cria uma nova instância da classe Database, passando as informações de configuração como parâmetros
$db = new Database($config['host'], $config['port'], $config['dbname'], $config['user'], $config['password']);

// Obtém a instância PDO da classe Database para realizar operações no banco de dados
$pdo = $db->getPDO(); 

?>