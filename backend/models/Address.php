<?php
// Classe responsável pelas operações relacionadas a endereços (criação e vinculação ao usuário)
class Address {
    private $pdo; // Armazena a instância de PDO para comunicação com o banco de dados

    // Construtor que recebe a conexão com o banco e a armazena na propriedade $pdo
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método responsável por criar um novo endereço no banco de dados
    public function create($dados) {
        try {
            // Prepara a query SQL para inserir um novo endereço
            $query = "INSERT INTO address (cep, rua, numero, bairro, cidade, estado, complemento) 
                      VALUES (:cep, :rua, :numero, :bairro, :cidade, :estado, :complemento)";
            $stmt = $this->pdo->prepare($query); // Prepara a consulta
            $stmt->execute([ // Executa a consulta passando os dados do endereço
                ':cep' => $dados['cep'],
                ':rua' => $dados['rua'],
                ':numero' => $dados['numero'],
                ':bairro' => $dados['bairro'],
                ':cidade' => $dados['cidade'],
                ':estado' => $dados['estado'],
                ':complemento' => $dados['complemento']
            ]);
            // Retorna o ID do endereço recém-criado, gerado automaticamente pelo banco
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // Se ocorrer algum erro, lança uma exceção com a mensagem do erro
            throw new Exception("Erro ao registrar o endereço: " . $e->getMessage());
        }
    }

    // Método responsável por vincular o endereço a um usuário específico
    public function linkToUser($userId, $addressId) {
        try {
            // Prepara a query SQL para vincular o endereço ao usuário
            $query = "INSERT INTO user_addresses (user_id, address_id) VALUES (:user_id, :address_id)";
            $stmt = $this->pdo->prepare($query); // Prepara a consulta
            $stmt->execute([ // Executa a consulta passando o ID do usuário e o ID do endereço
                ':user_id' => $userId,
                ':address_id' => $addressId
            ]);
        } catch (PDOException $e) {
            // Se ocorrer algum erro, lança uma exceção com a mensagem do erro
            throw new Exception("Erro ao vincular o endereço ao usuário: " . $e->getMessage());
        }
    }
}
?>

