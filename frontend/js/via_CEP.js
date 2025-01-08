// Função que remove qualquer caractere não numérico
function apenasNumeros(campo) {
    return campo.value.replace(/\D/g, '');
}

// Desabilita o botão de envio enquanto a busca do CEP está em andamento
function toggleButton(state) {
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = !state;
}

// Função para buscar o CEP por meio da API - viaCEP
function buscarEnderecoViaCEP() {
    // Obtém o elemento do campo 'cep'
    const cep = document.getElementById('cep');
    // Remove os caracteres não númericos
    const cepValue = apenasNumeros(cep);

    // Verifica se o CEP somente com números é diferente de 8
    if (cepValue.length !== 8) {
        // Lança um erro, caso seja verdadeiro
        alert("Por favor, insira um CEP válido.");
        return;
    }

    toggleButton(false); // Desabilita o botão

    // Faz a requisição da API - viaCEP
    fetch(`https://viacep.com.br/ws/${cepValue}/json/`)
        .then(response => response.json()) // Converte a resposta para um JSON
        .then(data => {
            // Verifica se o CEP não foi encontrado
            if (data.erro) {
                alert("CEP não encontrado."); // Informa ao usuário
                return;
            }
            // Preenche os campos do formulário com os dados retornados pela API
            document.getElementById('rua').value = data.logradouro || '';
            document.getElementById('bairro').value = data.bairro || '';
            document.getElementById('cidade').value = data.localidade || '';
            document.getElementById('estado').value = data.uf || '';
        })
        .catch(error => {
            // Alerta o usuário em caso de falha na requisição
            alert("Erro ao buscar o endereço. Tente novamente.");
        })
        .finally(() => {
            toggleButton(true); // Reabilita o botão
        });
}

// Chama a função quando o CEP perder o foco(user der sair do campo CEP)
document.getElementById('cep').addEventListener('blur', buscarEnderecoViaCEP);

document.getElementById("estado").addEventListener("input", function () {
    const estadoInput = this.value.toUpperCase();
    this.value = estadoInput.replace(/[^A-Z]/g, "").slice(0, 2); // Remove caracteres não permitidos e limita a 2 caracteres
});
