<?php
// Inclui a configuração do banco de dados e o modelo de endereço
require_once '../../backend/config/database.php';
require_once '../../frontend/models/Address.php'; // Modelo responsável por manipular endereços

class AddressController {
    private $addressModel; // Armazena a instância do modelo de endereço

    // Construtor que inicializa o modelo de endereço
    public function __construct($pdo) {
        $this->addressModel = new Address($pdo); // Cria o modelo de endereço com a conexão ao banco
    }

    // Método para registrar o endereço e vinculá-lo ao usuário
    public function registerAddressAndLinkToUser($userId, $dadosEndereco) {
        try {
            // Registra o endereço no banco e obtém o ID gerado
            $addressId = $this->addressModel->create($dadosEndereco);

            // Vincula o endereço ao usuário com base nos IDs
            $this->addressModel->linkToUser($userId, $addressId);

            // Redireciona para a página de sucesso ou home
            header('Location: ../../frontend/views/ListAddress.php');
            exit(); // Garante que o código pare após o redirecionamento
        } catch (Exception $e) {
            // Exibe a mensagem de erro caso algo dê errado
            echo $e->getMessage();
        }
    }
}

// Inicia a sessão para acessar os dados do usuário
session_start();

// Verifica se o ID do usuário está presente na sessão
if (!isset($_SESSION['user_id'])) {
    // Exibe erro caso o ID do usuário não esteja na sessão
    echo "Erro: ID do usuário não encontrado na sessão!";
    exit();
}

// Recupera o ID do usuário da sessão
$userId = $_SESSION['user_id'];

// Verifica se os dados do formulário foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados do formulário de endereço
    $dadosEndereco = [
        'cep' => $_POST['cep'],
        'rua' => $_POST['rua'],
        'numero' => $_POST['numero'],
        'bairro' => $_POST['bairro'],
        'cidade' => $_POST['cidade'],
        'estado' => $_POST['estado'],
        'complemento' => $_POST['complemento'] ?? null // 'null' caso não tenha complemento
    ];

    // Cria o controlador de endereço e registra o endereço vinculado ao usuário
    $addressController = new AddressController($pdo);
    $addressController->registerAddressAndLinkToUser($userId, $dadosEndereco);
}
?>

