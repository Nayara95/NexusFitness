function getFormattedDateTime() {
    const date = new Date();
    const options = {
        timeZone: 'America/Sao_Paulo',
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
    };
    return new Intl.DateTimeFormat('pt-BR', options).format(date);
}

// Função para a data de cadastro em cadastro-aluno.html
function setRegistrationDate() {
    const element = document.getElementById('data-cadastro');
    if (element) {
        element.value = getFormattedDateTime();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Para cadastro-aluno.html - define e atualiza a data de cadastro
    if (document.getElementById('data-cadastro')) {
        setRegistrationDate(); // Chamada inicial
        setInterval(setRegistrationDate, 1000);
    }
});