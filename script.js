/* ======== AGENDA ======== */

// 1. Base de Dados: Atividades para a semana
const atividadesSemanais = {
    'segunda': [
       
    ],
    'terca': [
        'Em andamento'
        
    ],
    'quarta': [
       'Em andamento'
    ],
    'quinta': [
        'Em andamento'
    ],
    'sexta': [
       'Em andamento'
    ],
    'sabado': [
        'Em andamento'
    ],
    'domingo': [
       'Em andamento'
    ]
};

// 2. Elementos do DOM
const botoesContainer = document.getElementById('btnDias');
const botoes = document.querySelectorAll('.botao-dia');
const tituloDiaEl = document.getElementById('tituloDia');
const listaAulasEl = document.getElementById('listaAulas');
const nomesDias = {
    'segunda': 'Segunda-feira', 'terca': 'Terça-feira', 'quarta': 'Quarta-feira',
    'quinta': 'Quinta-feira', 'sexta': 'Sexta-feira', 'sabado': 'Sábado', 'domingo': 'Domingo'
};


// 3. Função para exibir as atividades do dia
function exibirAtividades(diaSelecionado) {
    // 3.1. Limpa a lista atual
    listaAulasEl.innerHTML = ''; 

    // 3.2. Atualiza o título
    tituloDiaEl.textContent = `Treino de ${nomesDias[diaSelecionado]}`;

    // 3.3. Obtém a lista de atividades para o dia
    const atividades = atividadesSemanais[diaSelecionado];

    if (atividades && atividades.length > 0) {
        atividades.forEach(atividade => {
            const li = document.createElement('li');
            li.textContent = atividade;
            listaAulasEl.appendChild(li);
        });
    } else {
        const li = document.createElement('li');
        li.textContent = 'Nenhuma atividade agendada.';
        listaAulasEl.appendChild(li);
    }
}

// 4. Função para gerenciar a seleção do botão
function selecionarDia(evento) {
    const botaoClicado = evento.target;
    const dia = botaoClicado.dataset.dia; // Pega o valor 'segunda', 'terca', etc.

    // 4.1. Remove a classe 'ativo' de todos os botões
    botoes.forEach(btn => btn.classList.remove('ativo'));
    
    // 4.2. Adiciona a classe 'ativo' ao botão clicado
    botaoClicado.classList.add('ativo');

    // 4.3. Chama a função de exibição
    exibirAtividades(dia);
}


// 5. Adiciona listeners de clique a todos os botões
botoes.forEach(botao => {
    botao.addEventListener('click', selecionarDia);
});


// 6. Define o dia atual como inicial (opcional)
function inicializar() {
    // Pega o dia da semana atual (0=Dom, 1=Seg, ..., 6=Sáb)
    const hoje = new Date().getDay(); 
    // Mapeia o índice JS para o data-dia do HTML (Segunda é 1, mas o array de botões é 0)
    
    // Ajusta o índice: Domingo (0) -> índice 6; Segunda (1) -> índice 0, etc.
    const indiceBotao = hoje === 0 ? 6 : hoje - 1; 

    if (botoes[indiceBotao]) {
        botoes[indiceBotao].click(); // Simula o clique no botão do dia atual
    } else {
        // Se for domingo (índice 6), mas não estiver na lista de botões ou erro, clique em Segunda
        botoes[0].click();
    }
}

// Inicializa a aplicação
//inicializar();

/* ======== FIM AGENDA ======== */



/* ======== TELA DE PAGAMENTO ======== */

document.addEventListener('DOMContentLoaded', () => {
    
    const tabs = document.querySelectorAll('.tab-pagamento');
    const conteudos = document.querySelectorAll('.conteudo-metodo');

    const chavePixInput = document.getElementById('chavePix');
    const numeroCartaoInput = document.getElementById('numero');

     tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const metodo = tab.dataset.metodo;

            // Remove a classe 'ativo' de todas as abas e conteúdos
            tabs.forEach(t => t.classList.remove('ativo'));
            conteudos.forEach(c => c.classList.remove('ativo'));

            // Adiciona a classe 'ativo' na aba e no conteúdo selecionado
            tab.classList.add('ativo');
            document.getElementById(`conteudo-${metodo}`).classList.add('ativo');
        });
    });


 // DO PIX (Copia e Cola)

    const btnCopiar = document.getElementById('btnCopiar');

    btnCopiar.addEventListener('click', () => {
        chavePixInput.select();
        chavePixInput.setSelectionRange(0, 99999);

        // Usando a API de cópia moderna
        navigator.clipboard.writeText(chavePixInput.value)
            .then(() => {
                const textoOriginal = btnCopiar.textContent;
                btnCopiar.textContent = 'Chave Copiada!';

                setTimeout(() => {
                    btnCopiar.textContent = textoOriginal;
                }, 1500);
            })
            .catch(err => {
                alert('Erro ao copiar a chave. Tente copiar manualmente.');
            });
    });

  
    // COMPORTAMENTO DO CARTÃO (Validação e Formatação)


    numeroCartaoInput.addEventListener('input', (e) => {
        let valor = e.target.value.replace(/\D/g, '');
        valor = valor.replace(/(\d{4})(?=\d)/g, '$1 ');
        e.target.value = valor;
        validarNumeroCartao(e.target);
    });

    function validarNumeroCartao(input) {
        const valor = input.value.replace(/\s/g, '');
        const grupoCampo = input.closest('.campo-grupo');

        // * Implementação simplificada de validação *
   
        grupoCampo.classList.remove('campo-valido', 'campo-invalido');

        if (valor.length === 16) {
            grupoCampo.classList.add('campo-valido');
            return true;
        } else if (valor.length > 0 && valor.length < 16) {
            grupoCampo.classList.add('campo-invalido');
            return false;
        }
        return false;
    }

    // ==========Mudança de abas

    document.getElementById('formPagamento').addEventListener('submit', (e) => {
        e.preventDefault();

        // Verifica qual aba está ativa
        const metodoAtivo = document.querySelector('.tab-pagamento.ativo').dataset.metodo;
        const botaoPagar = e.submitter; // Pega o botão que acionou o submit

        botaoPagar.disabled = true;

        if (metodoAtivo === 'cartao') {

            if (validarNumeroCartao(numeroCartaoInput)) {
                botaoPagar.textContent = 'Processando Cartão...';

                setTimeout(() => {
                    alert("Sucesso! Pagamento via Cartão Aprovado.");
                    botaoPagar.textContent = 'Compra Finalizada!';
                }, 2000);
            } else {
                alert('Por favor, corrija o número do cartão.');
                botaoPagar.disabled = false;
            }

        } else if (metodoAtivo === 'pix') {
            botaoPagar.textContent = 'Aguardando Confirmação...';

            setTimeout(() => {
                // EM ANDAMENTO = checar se o Pix foi pago
                alert("Notificação enviada! Verifique o status em 5 minutos.");
                botaoPagar.textContent = 'Notificação Enviada.';
                botaoPagar.disabled = true;
            }, 2000);
        }
    });

    // Inicializa a validação do cartão no carregamento
    //validarNumeroCartao(numeroCartaoInput);
});


//VALIDAÇÃO DOS DADOS DOS CAMPOS DA PAGINA CADASTRO_ALUNO

document.addEventListener('DOMContentLoaded', function() {
    // 1. MÁSCARA DE CPF EM TEMPO REAL
    const cpfInput = document.getElementById('cpf');

    if (cpfInput) {
        // Adiciona o ouvinte para aplicar a máscara a cada tecla digitada/colada
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value;
            
            // 1. Remove tudo que não for dígito. Isso garante que o usuário só insira números.
            value = value.replace(/\D/g, ""); 
    
            // 2. Aplica a formatação do CPF (999.999.999-99)
            // Note: A ordem é importante aqui. A máscara é aplicada passo a passo.
            
            // Insere o primeiro ponto (após o 3º dígito)
            value = value.replace(/(\d{3})(\d)/, "$1.$2"); 
            
            // Insere o segundo ponto (após o 6º dígito)
            value = value.replace(/(\d{3})(\d)/, "$1.$2"); 
            
            // Insere o traço (após o 9º dígito)
            // O $ é crucial para garantir que o traço vá para a posição correta
            value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2"); 

            // Limita o tamanho do campo em 14 caracteres (11 dígitos + 3 separadores)
            if (value.length > 14) {
                 value = value.substring(0, 14);
            }
    
            // Atualiza o valor do campo com a string formatada
            e.target.value = value;
        });
    }
    // VALIDAÇÃO AO SUBMETER O FORMULÁRIO
    // (Ajustada para usar a máscara)

    
    const form = document.getElementById('formCadastro');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            let isValid = true;

            // --- Validação do CPF ---
            if (cpfInput) {
                // Remove a máscara (pontos e traços) para verificar o total de dígitos
                const cpfValue = cpfInput.value.replace(/\D/g, ''); 
                
                if (cpfValue.length !== 11) {
                    alert('Por favor, insira um CPF válido com 11 dígitos.');
                    cpfInput.focus();
                    isValid = false;
                }
            }
          
        });
    }
});



