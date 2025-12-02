document.addEventListener('DOMContentLoaded', function() {
    // Handle click on student rows
    const rows = document.querySelectorAll('.aluno-row');
    rows.forEach(row => {
        row.addEventListener('click', () => {
            const alunoId = row.getAttribute('data-aluno-id');
            if (alunoId) {
                window.location.href = `?aluno_id=${alunoId}`;
            }
        });
    });

    // Get form elements
    const form = document.querySelector('.dados-form');
    const formTitle = document.querySelector('.form-section h3');
    const dadoIdInput = form.querySelector('input[name="dado_id"]');
    const bracosInput = form.querySelector('#bracos');
    const abdomenInput = form.querySelector('#abdomen');
    const pesoInput = form.querySelector('#peso');
    const alturaInput = form.querySelector('#altura');
    const pernasInput = form.querySelector('#pernas');
    const dataMedidaInput = form.querySelector('#data_medida');
    const submitButton = form.querySelector('.btn-salvar');
    const formActions = form.querySelector('.form-actions');

    // *** VALIDAÇÃO: PERMITE VÍRGULA E PONTO, COMPLETA COM ZEROS ***
    const camposNumericos = [bracosInput, abdomenInput, pesoInput, alturaInput, pernasInput];
    
    // Função para formatar número completando com zeros
    function formatarNumero(valor) {
        if (valor === '' || valor === '.' || valor === ',') return '0.00';
        
        // Substitui vírgula por ponto
        valor = valor.replace(',', '.');
        
        // Se terminar com ponto, adiciona "00"
        if (valor.endsWith('.')) {
            return valor + '00';
        }
        
        // Se começar com ponto, adiciona "0" antes
        if (valor.startsWith('.')) {
            valor = '0' + valor;
        }
        
        // Divide em partes inteira e decimal
        const partes = valor.split('.');
        let parteInteira = partes[0] || '0';
        let parteDecimal = partes[1] || '00';
        
        // Completa a parte decimal com zeros se necessário
        if (parteDecimal.length === 1) {
            parteDecimal = parteDecimal + '0';
        } else if (parteDecimal.length > 2) {
            parteDecimal = parteDecimal.substring(0, 2);
        }
        
        return parteInteira + '.' + parteDecimal;
    }
    
    camposNumericos.forEach(input => {
        if (input) {
            // Permite digitação de números, ponto e vírgula
            input.addEventListener('keypress', function(e) {
                // Permite: números (0-9), ponto (.), vírgula (,), backspace, tab, enter, delete, setas
                const teclasPermitidas = [
                    '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
                    '.', ',',
                    'Backspace', 'Tab', 'Enter', 'Delete',
                    'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'
                ];
                
                // Se a tecla não está na lista de permitidas, bloqueia
                if (!teclasPermitidas.includes(e.key)) {
                    e.preventDefault();
                    return false;
                }
                
                // Impede múltiplos pontos ou vírgulas
                if ((e.key === '.' || e.key === ',') && 
                    (input.value.includes('.') || input.value.includes(','))) {
                    e.preventDefault();
                    return false;
                }
            });
            
            // Validação no input (para colar valores)
            input.addEventListener('input', function(e) {
                let valor = e.target.value;
                
                // Remove qualquer caractere que não seja número, ponto ou vírgula
                valor = valor.replace(/[^\d,.]/g, '');
                
                // Remove pontos ou vírgulas extras, mantendo apenas o primeiro
                if ((valor.match(/[,.]/g) || []).length > 1) {
                    const primeiroSeparador = valor.search(/[,.]/);
                    valor = valor.substring(0, primeiroSeparador + 1) + 
                           valor.substring(primeiroSeparador + 1).replace(/[,.]/g, '');
                }
                
                e.target.value = valor;
            });
            
            // Formata automaticamente ao sair do campo
            input.addEventListener('blur', function(e) {
                const valor = e.target.value;
                
                if (valor === '') {
                    e.target.value = '0.00';
                    e.target.classList.remove('campo-invalido');
                    return;
                }
                
                // Verifica se é um número válido
                const valorFormatado = formatarNumero(valor);
                const numero = parseFloat(valorFormatado);
                
                if (isNaN(numero)) {
                    e.target.classList.add('campo-invalido');
                    mostrarErroRapido(e.target, 'Digite um número válido');
                } else {
                    e.target.classList.remove('campo-invalido');
                    e.target.value = valorFormatado;
                }
            });
        }
    });
    
    // Validação no envio do formulário
    if (form) {
        form.addEventListener('submit', function(e) {
            let formularioValido = true;
            const erros = [];
            
            // Formata todos os campos antes do envio
            camposNumericos.forEach(input => {
                const valor = input.value.trim();
                const nomeCampo = input.previousElementSibling.textContent;
                
                if (valor === '') {
                    input.value = '0.00';
                    input.classList.remove('campo-invalido');
                    return;
                }
                
                const valorFormatado = formatarNumero(valor);
                const numero = parseFloat(valorFormatado);
                
                if (isNaN(numero)) {
                    input.classList.add('campo-invalido');
                    formularioValido = false;
                    erros.push(`${nomeCampo} deve conter um número válido`);
                } else {
                    input.classList.remove('campo-invalido');
                    input.value = valorFormatado; // Garante formatação correta
                }
            });
            
            if (!formularioValido) {
                e.preventDefault();
                alert('Por favor, corrija os seguintes erros:\n• ' + erros.join('\n• '));
            }
            // Se estiver válido, o formulário é enviado normalmente
        });
    }
    
    // Função para mostrar erro rápido
    function mostrarErroRapido(input, mensagem) {
        // Remove erro anterior
        const erroAnterior = input.parentNode.querySelector('.erro-rapido');
        if (erroAnterior) {
            erroAnterior.remove();
        }
        
        // Cria novo erro
        const erroDiv = document.createElement('div');
        erroDiv.className = 'erro-rapido';
        erroDiv.textContent = mensagem;
        erroDiv.style.cssText = `
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 5px;
            font-weight: 500;
        `;
        
        input.parentNode.appendChild(erroDiv);
        
        // Remove o erro após 3 segundos
        setTimeout(function() {
            if (erroDiv.parentNode) {
                erroDiv.remove();
            }
        }, 3000);
    }

    // Impede colar texto com letras
    document.addEventListener('paste', function(e) {
        if (camposNumericos.includes(e.target)) {
            // Pega o texto colado
            const textoColado = (e.clipboardData || window.clipboardData).getData('text');
            
            // Verifica se contém letras
            if (/[a-zA-Z]/.test(textoColado)) {
                e.preventDefault();
                alert('Não é permitido colar texto com letras nos campos de medição.');
                return false;
            }
        }
    });

    // *** FIM DA VALIDAÇÃO ***

    // Function to reset the form to "insert" mode
    function resetForm() {
        formTitle.textContent = 'Inserir Novos Dados Físicos';
        dadoIdInput.value = '';
        bracosInput.value = '';
        abdomenInput.value = '';
        pesoInput.value = '';
        alturaInput.value = '';
        pernasInput.value = '';
        // Set dataMedidaInput to today's date
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        dataMedidaInput.value = `${yyyy}-${mm}-${dd}`;

        submitButton.textContent = 'Salvar Dados';
        const cancelButton = form.querySelector('.btn-cancelar');
        if (cancelButton) {
            cancelButton.remove();
        }
        
        // Remove classes de erro ao resetar
        camposNumericos.forEach(input => {
            input.classList.remove('campo-invalido');
        });
    }

    // Handle click on edit buttons
    const editButtons = document.querySelectorAll('.btn-editar-js');
    editButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const row = button.closest('.data-row');
            
            const id = row.dataset.id;
            const braco = parseFloat(row.dataset.braco.replace(',', '.'));
            const abdomen = parseFloat(row.dataset.abdomen.replace(',', '.'));
            const peso = parseFloat(row.dataset.peso.replace(',', '.'));
            const altura = parseFloat(row.dataset.altura.replace(',', '.'));
            const perna = parseFloat(row.dataset.perna.replace(',', '.'));
            const data = row.dataset.data;

            formTitle.textContent = 'Editar Dados Físicos';
            dadoIdInput.value = id;
            bracosInput.value = braco.toFixed(2);
            abdomenInput.value = abdomen.toFixed(2);
            pesoInput.value = peso.toFixed(2);
            alturaInput.value = altura.toFixed(2);
            pernasInput.value = perna.toFixed(2);
            dataMedidaInput.value = data;

            submitButton.textContent = 'Atualizar Dados';

            // Add Cancel button if it doesn't exist
            let cancelButton = form.querySelector('.btn-cancelar');
            if (!cancelButton) {
                cancelButton = document.createElement('button');
                cancelButton.type = 'button';
                cancelButton.classList.add('btn-cancelar');
                cancelButton.textContent = 'Cancelar';
                cancelButton.addEventListener('click', resetForm);
                formActions.appendChild(cancelButton);
            }

            // Scroll to the form
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // Initialize form state
    if (dadoIdInput && dadoIdInput.value) {
        submitButton.textContent = 'Atualizar Dados';
        let cancelButton = form.querySelector('.btn-cancelar');
        if (!cancelButton) {
            cancelButton = document.createElement('button');
            cancelButton.type = 'button';
            cancelButton.classList.add('btn-cancelar');
            cancelButton.textContent = 'Cancelar';
            cancelButton.addEventListener('click', resetForm);
            formActions.appendChild(cancelButton);
        }
    } else {
        resetForm();
    }
});