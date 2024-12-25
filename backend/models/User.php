<?php
// Classe responsável pelas operações relacionadas a usuários (criação)
class User {
    private $pdo; // Armazena a instância de PDO para comunicação com o banco de dados

    // Construtor que recebe a conexão com o banco e a armazena na propriedade $pdo
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método responsável por criar um novo usuário no banco de dados
    public function create($dados) {
        try {
            // Prepara a query SQL para inserir um novo usuário
            $query = "INSERT INTO users (nome, cpf, data_nascimento, telefone, email, senha, sexo) 
                      VALUES (:nome, :cpf, :data_nascimento, :telefone, :email, :senha, :sexo)";
            $stmt = $this->pdo->prepare($query); // Prepara a consulta
            $stmt->execute([ // Executa a consulta passando os dados do usuário
                ':nome' => $dados['nome_completo'],
                ':cpf' => $dados['cpf'],
                ':data_nascimento' => $dados['data_nascimento'],
                ':telefone' => $dados['telefone'],
                ':email' => $dados['email'],
                ':senha' => password_hash($dados['senha'], PASSWORD_DEFAULT), // Senha é criptografada
                ':sexo' => $dados['sexo']
            ]);
            return true; // Retorna true caso a inserção seja bem-sucedida
        } catch (PDOException $e) {
            // Se ocorrer algum erro, lança uma exceção com a mensagem do erro
            throw new Exception("Erro ao registrar o usuário: " . $e->getMessage());
        }
    }
}
?>
