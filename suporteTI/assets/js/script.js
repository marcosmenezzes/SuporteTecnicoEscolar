document.addEventListener('DOMContentLoaded', function () {
    // Filtro de busca por texto nos chamados
    const busca = document.getElementById('buscaChamado');
    if (busca) {
        busca.addEventListener('keyup', function () {
            let termo = this.value.toLowerCase();
            document.querySelectorAll('.chamado-item').forEach(function (item) {
                item.style.display = item.textContent.toLowerCase().includes(termo) ? '' : 'none';
            });
        });
    }

    // Faz com que alerts desapareçam automaticamente após 3s
    document.querySelectorAll('.alert').forEach(function (alerta) {
        setTimeout(function () {
            alerta.classList.add('d-none');
        }, 3000);
    });
});
