// Função que remove qualquer caractere não numérico
function apenasNumeros(campo) {
    return campo.value.replace(/\D/g, '');
}

// Função para aplicar máscara de CPF
function mascaraCPF() {
    // Obtém o elemento do campo 'cpf'
    var cpf = document.getElementById('cpf');

    // Remove todos os caracteres não numéricos
    cpf.value = apenasNumeros(cpf);

    // Aplica a máscara conforme a quantidade de caracteres
    // Após terceiro caractere, insere '.'
    if (cpf.value.length > 2 && cpf.value.length <= 5) {
        cpf.value = cpf.value.substring(0, 3) + '.' + cpf.value.substring(3);
    }
    // Após sexto caractere, insere '.'
    else if (cpf.value.length > 5 && cpf.value.length <= 8) {
        cpf.value = cpf.value.substring(0, 3) + '.' + cpf.value.substring(3, 6) + '.' + cpf.value.substring(6);
    }
    // Após o oitavo caractere, insere '-'
    else if (cpf.value.length > 8) {
        cpf.value = cpf.value.substring(0, 3) + '.' + cpf.value.substring(3, 6) + '.' + cpf.value.substring(6, 9) + '-' + cpf.value.substring(9, 11);
    }
}

// Função para aplicar máscara de Telefone
function  mascaraTelefone() {
    // Obtém o elemento do campo 'telefone'
    var telefone = document.getElementById('telefone');

    // Remove todos os caracteres não numéricos
    telefone.value = apenasNumeros(telefone);

    // Aplica a máscara conforme a quantidade de caracteres
    // Após o segundo caractere, insere '()'
    if (telefone.value.length > 2 && telefone.value.length <= 6) {
        telefone.value = '(' + telefone.value.substring(0, 2) + ') ' + telefone.value.substring(2);
    }
    // Após digitar o quinto caractere do número, insere o '-' 
    else if (telefone.value.length > 6) {
        telefone.value = '(' + telefone.value.substring(0, 2) + ') ' + telefone.value.substring(2, 7) + '-' + telefone.value.substring(7, 11);
    }
}

function mascaraCEP() {
    var cep = document.getElementById('cep');

    cep.value = apenasNumeros(cep);

    if (cep.value.length > 5) {
        cep.value = cep.value.substring(0, 5) + '-' + cep.value.substring(5, 9)
    }
}

// Função para limpar os dados de CPF e Telefone para enviar para o backend
function limparDados() {
    // Obtém o elemento do campo 'cpf'
    var cpf = document.getElementById('cpf');

    // Obtém o elemento do campo 'telefone'
    var telefone = document.getElementById('telefone');

    var cep = document.getElementById('cep');

    // Limpa a máscara do CPF (removendo os pontos e hífen)
    cpf.value = apenasNumeros(cpf);

    // Limpa a máscara do telefone (removendo os parênteses, espaço e hífen)
    telefone.value = apenasNumeros(telefone)

    cep.value = apenasNumeros(cep);
}