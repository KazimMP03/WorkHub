<?php
// Inclui a configuração do banco de dados e o modelo de endereço
require_once '../../backend/config/database.php';
require_once '../../frontend/models/address.php'; // Modelo responsável por manipular endereços

class AddressController {
    private $address_model; // Armazena a instância do modelo de endereço

    // Construtor que inicializa o modelo de endereço
    public function __construct($pdo) {
        $this->address_model = new Address($pdo); // Cria o modelo de endereço com a conexão ao banco
    }

    // Método para registrar o endereço e vinculá-lo ao usuário
    public function register_address_and_link_to_user($user_id, $dados_endereco) {
        try {
            // Registra o endereço no banco e obtém o ID gerado
            $address_id = $this->address_model->create($dados_endereco);

            // Vincula o endereço ao usuário com base nos IDs
            $this->address_model->link_to_user($user_id, $address_id);

            // Redireciona para a página de sucesso ou home
            header('Location: ../../frontend/views/list_address.php');
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
$user_id = $_SESSION['user_id'];

// Verifica se os dados do formulário foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados do formulário de endereço
    $dados_endereco = [
        'cep' => $_POST['cep'],
        'rua' => $_POST['rua'],
        'numero' => $_POST['numero'],
        'bairro' => $_POST['bairro'],
        'cidade' => $_POST['cidade'],
        'estado' => $_POST['estado'],
        'complemento' => $_POST['complemento'] ?? null // 'null' caso não tenha complemento
    ];

    // Cria o controlador de endereço e registra o endereço vinculado ao usuário
    $address_controller = new AddressController($pdo);
    $address_controller->register_address_and_link_to_user($user_id, $dados_endereco);
}
?>
