<?php
// Inicia a sessão para armazenar temporariamente as informações fornecidas pelo usuário durante o cadastro
session_start();

// Definição da classe Cadastro, responsável por gerenciar o cadastro do usuário
class Cadastro {
    // Método que salva o nome e o sobrenome na sessão e redireciona para a próxima etapa
    public function save_step1($nome, $sobrenome) {
        // Armazena o nome e o sobrenome na sessão
        $_SESSION['nome'] = $nome;
        $_SESSION['sobrenome'] = $sobrenome;
        // Redireciona para a página da próxima etapa do cadastro
        header('Location: ../../frontend/pages/cadastro2.html');
        exit(); // Interrompe a execução do código após o redirecionamento
    }

    // Método que salva a data de nascimento e o sexo na sessão e redireciona para a próxima etapa
    public function save_step2($data_nascimento, $sexo) {
        // Armazena a data de nascimento e o sexo na sessão
        $_SESSION['data_nascimento'] = $data_nascimento;
        $_SESSION['sexo'] = $sexo;
        // Redireciona para a próxima página do cadastro
        header('Location: ../../frontend/pages/cadastro3.html');
        exit(); // Interrompe a execução do código após o redirecionamento
    }

    // Método que salva o CPF e o telefone na sessão e redireciona para a próxima etapa
    public function save_step3($cpf, $telefone) {
        // Armazena o CPF e o telefone na sessão
        $_SESSION['cpf'] = $cpf;
        $_SESSION['telefone'] = $telefone;
        // Redireciona para a página seguinte do cadastro
        header('Location: ../../frontend/pages/cadastro4.html');
        exit(); // Interrompe a execução do código após o redirecionamento
    }

    // Método que salva o email e a senha na sessão e redireciona para o backend processar o registro
    public function save_step4($email, $senha) {
        // Salva o email e a senha na sessão
        $_SESSION['email'] = $email;
        $_SESSION['senha'] = $senha; // Aqui seria interessante aplicar criptografia na senha
        // Redireciona para o script backend que irá processar o registro do usuário
        header('Location: ../../backend/controllers/user_controller.php');
        exit(); // Interrompe a execução do código após o redirecionamento
    }
}

// Verifica se a requisição é do tipo POST. Se não for, exibe uma mensagem de erro e interrompe o processo
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Erro: Acesso inválido!"; // Informa que a requisição não é válida
    exit(); // Interrompe a execução do código
}

// Instancia um objeto da classe Cadastro
$cadastro = new Cadastro();

// Recupera os dados enviados via POST pelo formulário
$nome = $_POST['nome'];
$sobrenome = $_POST['sobrenome'];
$data_nascimento = $_POST['data_nascimento'];
$sexo = $_POST['sexo'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$senha = $_POST['senha'];

// Verifica se o nome e sobrenome foram preenchidos e chama o método save_step1 para armazená-los na sessão
if ($nome && $sobrenome) {
    $cadastro->save_step1($nome, $sobrenome);
}

// Verifica se a data de nascimento e o sexo foram preenchidos e chama o método save_step2 para armazená-los na sessão
if ($data_nascimento && $sexo) {
    $cadastro->save_step2($data_nascimento, $sexo);
}

// Verifica se o CPF e o telefone foram preenchidos e chama o método save_step3 para armazená-los na sessão
if ($cpf && $telefone) {
    $cadastro->save_step3($cpf, $telefone);
}

// Verifica se o email e a senha foram preenchidos e chama o método save_step4 para armazená-los na sessão
if ($email && $senha) {
    $cadastro->save_step4($email, $senha);
}

// Se algum dado obrigatório não foi preenchido, exibe uma mensagem de erro e interrompe o processo
if (!isset($nome, $sobrenome, $data_nascimento, $sexo, $cpf, $telefone, $email, $senha)) {
    echo "Erro: Dados incompletos!"; // Mensagem indicando que os dados não estão completos
    exit(); // Interrompe a execução do código
} 
?>
