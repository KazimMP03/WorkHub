<?php
// Inicializa a sessão para acessar variáveis da sessão (dados temporários)
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../config/db.php';

class AddressController {
    private $pdo; // Atributo para armazenar a conexão com o banco de dados

    // Construtor da classe, recebe o PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para registrar o endereço no banco de dados
    public function registerAddress($dados) {
        try {
            // Query SQL para inserir o endereço
            $query = "INSERT INTO address (cep, rua, numero, bairro, cidade, estado, complemento) 
                      VALUES (:cep, :rua, :numero, :bairro, :cidade, :estado, :complemento)";

            // Prepara a consulta SQL
            $stmt = $this->pdo->prepare($query);

            // Executa a consulta passando os dados do endereço
            $stmt->execute([
                ':cep' => $dados['cep'],
                ':rua' => $dados['rua'],
                ':numero' => $dados['numero'],
                ':bairro' => $dados['bairro'],
                ':cidade' => $dados['cidade'],
                ':estado' => $dados['estado'],
                ':complemento' => $dados['complemento']
            ]);

            // Retorna o ID do endereço recém-criado
            return $this->pdo->lastInsertId();

        } catch (PDOException $e) {
            // Caso ocorra algum erro, exibe a mensagem de erro
            echo "Erro ao registrar o endereço: " . $e->getMessage();
        }
    }

    // Método para vincular o endereço ao usuário
    public function linkUserAddress($userId, $addressId) {
        try {
            // Query SQL para inserir na tabela de relação
            $query = "INSERT INTO user_addresses (user_id, address_id) VALUES (:user_id, :address_id)";

            // Prepara a consulta SQL
            $stmt = $this->pdo->prepare($query);

            // Executa a consulta passando os IDs do usuário e do endereço
            $stmt->execute([
                ':user_id' => $userId,
                ':address_id' => $addressId
            ]);
        } catch (PDOException $e) {
            // Caso ocorra algum erro, exibe a mensagem de erro
            echo "Erro ao vincular o endereço ao usuário: " . $e->getMessage();
        }
    }
}

// Verifica se os dados do usuário estão na sessão
if (!isset($_SESSION['user_id'])) {
    echo "Erro: ID do usuário não encontrado na sessão!";
    exit();
}

// Recupera o ID do usuário da sessão
$userId = $_SESSION['user_id'];

// Verifica se os dados do formulário foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados do formulário
    $dadosEndereco = [
        'cep' => $_POST['cep'],
        'rua' => $_POST['rua'],
        'numero' => $_POST['numero'],
        'bairro' => $_POST['bairro'],
        'cidade' => $_POST['cidade'],
        'estado' => $_POST['estado'],
        'complemento' => $_POST['complemento'] ?? null
    ];

    // Cria um objeto da classe AddressController, passando o PDO (conexão com o banco) para o construtor
    $addressController = new AddressController($pdo);

    // Registra o endereço no banco de dados e obtém o ID gerado
    $addressId = $addressController->registerAddress($dadosEndereco);

    // Vincula o endereço ao usuário
    $addressController->linkUserAddress($userId, $addressId);

    // Redireciona para uma página de sucesso ou para o próximo passo
    header('Location: ../../frontend/pages/home.html');
    exit();
}
?>
