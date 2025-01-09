<?php
// Incluir o arquivo utils.php para usar a função alert_message
include_once '../../backend/utils.php'; // Ajuste o caminho conforme necessário

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
            if ($this->email_exists($dados['email'])) {
                alert_message('O e-mail informado já está cadastrado.');
            }

            // Verifica se o CPF já está cadastrado
            if ($this->cpf_exists($dados['cpf'])) {
                alert_message('O CPF informado já está cadastrado.');
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
            // Exibe um alerta com a mensagem do erro
            alert_message("Erro ao registrar o usuário: " . $e->getMessage());
        }
    }

    // Método genérico para atualizar qualquer campo
    public function update_field($user_id, $campo, $valor) {
        try {
            $query = "UPDATE users SET $campo = :valor WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':valor' => $valor,
                ':id' => $user_id
            ]);
        } catch (PDOException $e) {
            // Exibe um alerta com a mensagem do erro
            alert_message("Erro ao atualizar o campo $campo: " . $e->getMessage());
        }
    }

    // Método para obter o valor de um campo específico
    public function get_user_field($user_id, $campo) {
        try {
            $query = "SELECT $campo FROM users WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $user_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result[$campo] : null;
        } catch (PDOException $e) {
            // Exibe um alerta com a mensagem do erro
            alert_message("Erro ao buscar o campo $campo: " . $e->getMessage());
        }
    }

    // Método para obter a foto do usuário
    public function get_user_photo($user_id) {
        try {
            // Prepara a query SQL para pegar a foto do usuário
            $query = "SELECT foto FROM users WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $user_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['foto'] : null; // Retorna o nome da foto ou null se não existir
        } catch (PDOException $e) {
            // Exibe um alerta com a mensagem do erro
            alert_message("Erro ao buscar foto do usuário: " . $e->getMessage());
        }
    }

    public function update_photo($user_id, $foto) {
        try {
            // Prepara a query SQL para atualizar a foto de perfil
            $query = "UPDATE users SET foto = :foto WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':foto' => $foto,
                ':id' => $user_id
            ]);
            // Retorna true se a atualização for bem-sucedida
            return true;
        } catch (PDOException $e) {
            // Exibe um alerta com a mensagem do erro
            alert_message("Erro ao atualizar a foto: " . $e->getMessage());
        }
    }

    // Método para verificar se um email já existe no BD
    public function email_exists($email) {
        try {
            // Prepara a query SQL para verificar se o e-mail já está cadastrado
            $query = "SELECT 1 FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->pdo->prepare($query); // Prepara a consulta
            $stmt->execute([':email' => $email]); // Executa a consulta passando o e-mail
            return $stmt->fetchColumn(); // Retorna true se o e-mail existir, caso contrário, false
        } catch (PDOException $e) {
            // Exibe um alerta com a mensagem do erro
            alert_message("Erro ao verificar o e-mail: " . $e->getMessage());
        }
    }

    // Método para verificar se o CPF já existe no banco de dados
    public function cpf_exists($cpf) {
        try {
            // Prepara a query SQL para verificar se o CPF já está cadastrado
            $query = "SELECT 1 FROM users WHERE cpf = :cpf LIMIT 1";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':cpf' => $cpf]); // Passa o valor de :cpf corretamente
            return $stmt->fetchColumn(); // Retorna true se o CPF existir, caso contrário, false
        } catch (PDOException $e) {
            // Exibe um alerta com a mensagem do erro
            alert_message("Erro ao verificar o CPF: " . $e->getMessage());
        }
    }
}
?>
