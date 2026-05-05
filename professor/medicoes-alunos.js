document.addEventListener('DOMContentLoaded', function() {
    // Handle clear search
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            const form = document.createElement('form');
            form.method = 'POST';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'limpar_pesquisa';
            input.value = '1';
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        });
    }
    const form = document.querySelector('.dados-form');
    if (form) {
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
        const camposNumericos = [bracosInput, abdomenInput, pesoInput, alturaInput, pernasInput];
        
        function formatarNumero(valor) {
            if (valor === '' || valor === '.' || valor === ',') return '0.00';
            
            valor = valor.replace(',', '.');
            
            if (valor.endsWith('.')) {
                return valor + '00';
            }
            
            if (valor.startsWith('.')) {
                valor = '0' + valor;
            }
            
            const partes = valor.split('.');
            let parteInteira = partes[0] || '0';
            let parteDecimal = partes[1] || '00';
            
            if (parteDecimal.length === 1) {
                parteDecimal = parteDecimal + '0';
            } else if (parteDecimal.length > 2) {
                parteDecimal = parteDecimal.substring(0, 2);
            }
            
            return parteInteira + '.' + parteDecimal;
        }
        
        camposNumericos.forEach(input => {
            if (input) {
                input.addEventListener('keypress', function(e) {
                    const teclasPermitidas = [
                        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
                        '.', ',',
                        'Backspace', 'Tab', 'Enter', 'Delete',
                        'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'
                    ];
                    
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
                
                input.addEventListener('input', function(e) {
                    let valor = e.target.value;
                    valor = valor.replace(/[^\d,.]/g, '');
                    
                    if ((valor.match(/[,.]/g) || []).length > 1) {
                        const primeiroSeparador = valor.search(/[,.]/);
                        valor = valor.substring(0, primeiroSeparador + 1) + 
                               valor.substring(primeiroSeparador + 1).replace(/[,.]/g, '');
                    }
                    
                    e.target.value = valor;
                });
                
                input.addEventListener('blur', function(e) {
                    const valor = e.target.value;
                    
                    if (valor === '') {
                        e.target.value = '0.00';
                        e.target.classList.remove('campo-invalido');
                        return;
                    }
                    
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
        
        if (form) {
            form.addEventListener('submit', function(e) {
                let formularioValido = true;
                const erros = [];
                
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
            });
        }
        
        function mostrarErroRapido(input, mensagem) {
            // Remove erro anterior
            const erroAnterior = input.parentNode.querySelector('.erro-rapido');
            if (erroAnterior) {
                erroAnterior.remove();
            }
            
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
            

            setTimeout(function() {
                if (erroDiv.parentNode) {
                    erroDiv.remove();
                }
            }, 3000);
        }

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

        
        function resetForm() {
            formTitle.textContent = 'Inserir Novos Dados Físicos';
            dadoIdInput.value = '';
            bracosInput.value = '';
            abdomenInput.value = '';
            pesoInput.value = '';
            alturaInput.value = '';
            pernasInput.value = '';
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
            
            camposNumericos.forEach(input => {
                input.classList.remove('campo-invalido');
            });
        }

        const editButtons = document.querySelectorAll('.btn-editar-js');
        editButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                // The form will be submitted via POST, no need for preventDefault
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

                let cancelButton = form.querySelector('.btn-cancelar');
                if (!cancelButton) {
                    cancelButton = document.createElement('button');
                    cancelButton.type = 'button';
                    cancelButton.classList.add('btn-cancelar');
                    cancelButton.textContent = 'Cancelar';
                    cancelButton.addEventListener('click', resetForm);
                    formActions.appendChild(cancelButton);
                }

                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

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
            if (!dadoIdInput.value) {
                resetForm();
            }
        }
    }
});