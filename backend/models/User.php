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
            // Verifica se o e-mail já está cadastrado
            if ($this->emailExists($dados['email'])) {
                throw new Exception("O e-mail informado já está cadastrado.");
            }

            // Verifica se o CPF já está cadastrado
            if ($this->cpfExists($dados['cpf'])) {
                throw new Exception("O CPF informado já está cadastrado.");
            }

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

    public function updatePhoto($userId, $foto) {
        try {
            // Prepara a query SQL para atualizar a foto de perfil
            $query = "UPDATE users SET foto = :foto WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':foto' => $foto,
                ':id' => $userId
            ]);
            // Retorna true se a atualização for bem-sucedida
            return true;
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar a foto: " . $e->getMessage());
        }
    }

    // Método para verificar se um email já existe no BD
    public function emailExists($email) {
        try {
            // Prepara a query SQL para verificar se o e-mail já está cadastrado
            $query = "SELECT 1 FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->pdo->prepare($query); // Prepara a consulta
            $stmt->execute([':email' => $email]); // Executa a consulta passando o e-mail
            return $stmt->fetchColumn(); // Retorna true se o e-mail existir, caso contrário, false
        } catch (PDOException $e) {
            throw new Exception("Erro ao verificar o e-mail: " . $e->getMessage());
        }
    }

    // Método para verificar se o CPF já existe no banco de dados
    public function cpfExists($cpf) {
        try {
            // Prepara a query SQL para verificar se o CPF já está cadastrado
            $query = "SELECT 1 FROM users WHERE cpf = :cpf LIMIT 1";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':cpf' => $cpf]); // Passa o valor de :cpf corretamente
            return $stmt->fetchColumn(); // Retorna true se o CPF existir, caso contrário, false
        } catch (PDOException $e) {
            throw new Exception("Erro ao verificar o CPF: " . $e->getMessage());
        }
    }
}
?>
