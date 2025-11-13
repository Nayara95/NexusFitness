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
        const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months start at 0!
        const dd = String(today.getDate()).padStart(2, '0');
        dataMedidaInput.value = `${yyyy}-${mm}-${dd}`;

        submitButton.textContent = 'Salvar Dados';
        const cancelButton = form.querySelector('.btn-cancelar');
        if (cancelButton) {
            cancelButton.remove();
        }
    }

    // Handle click on edit buttons
    const editButtons = document.querySelectorAll('.btn-editar-js');
    editButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default link behavior (page reload)
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
            bracosInput.value = braco;
            abdomenInput.value = abdomen;
            pesoInput.value = peso;
            alturaInput.value = altura;
            pernasInput.value = perna;
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

    // Initialize form state (in case of page load with editar_id)
    if (dadoIdInput.value) {
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
        resetForm(); // Ensure form is in reset state on initial load if no dado_id
    }
});