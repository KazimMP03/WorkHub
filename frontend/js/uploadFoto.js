// Obtém os elementos necessários
const profilePhoto = document.getElementById('profile-photo');
const editIcon = document.querySelector('.edit-photo-icon');
const photoInput = document.getElementById('foto');

// Função para disparar o clique no input de upload de foto
function triggerFileInput() {
    photoInput.click(); // Dispara o clique no campo de input de arquivo
}

// Adiciona eventos de clique na foto e no ícone de lápis
profilePhoto.addEventListener('click', triggerFileInput);
editIcon.addEventListener('click', triggerFileInput);

// Se desejar, pode adicionar um evento para saber quando o usuário seleciona um arquivo
photoInput.addEventListener('change', function(event) {
    if (event.target.files.length > 0) {
        // Você pode fazer algo aqui quando o arquivo for selecionado, por exemplo, mostrar o nome do arquivo
        console.log("Foto selecionada: ", event.target.files[0].name);
    }
});

