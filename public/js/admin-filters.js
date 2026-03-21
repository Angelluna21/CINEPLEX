document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. FILTRO DE FUNCIONES (CARTELERA)
    // ==========================================
    const funcionesSearch = document.getElementById('searchInput');
    const funcionesSucursalFilter = document.getElementById('sucursalFilter');
    
    // We try to identify if we are on the funciones page by checking table structure
    const isFuncionesPage = document.querySelector('.pelicula-titulo') !== null;
    
    if (isFuncionesPage && funcionesSearch) {
        const rows = document.querySelectorAll('#tableBody tr:not(#noResultsRow)');
        const noResultsRow = document.getElementById('noResultsRow');

        function filterFunciones() {
            const searchText = funcionesSearch.value.toLowerCase().trim();
            const selectedSucursal = funcionesSucursalFilter ? funcionesSucursalFilter.value.toLowerCase().trim() : '';
            let visibleCount = 0;

            rows.forEach(row => {
                const pelicula = (row.querySelector('.pelicula-titulo')?.textContent || '').toLowerCase().trim();
                const sala = (row.querySelector('.sala-nombre')?.textContent || '').toLowerCase().trim();
                const sucursal = (row.querySelector('.sucursal-nombre')?.textContent || '').toLowerCase().trim();

                const matchesSearch = pelicula.includes(searchText) || sala.includes(searchText);
                const matchesSucursal = selectedSucursal === "" || sucursal.includes(selectedSucursal);

                if (matchesSearch && matchesSucursal) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            });

            if (noResultsRow) {
                noResultsRow.style.display = (visibleCount === 0 && rows.length > 0) ? "" : "none";
            }
        }

        funcionesSearch.addEventListener('input', filterFunciones);
        if (funcionesSucursalFilter) funcionesSucursalFilter.addEventListener('change', filterFunciones);
    }

    // ==========================================
    // 2. FILTRO DE SALAS
    // ==========================================
    const salaSearch = document.getElementById('salaSearch');
    const salaSucursalFilter = document.getElementById('sucursalFilter');
    const isSalasPage = document.querySelector('.sala-row') !== null;

    if (isSalasPage && salaSearch) {
        const rows = document.querySelectorAll('.sala-row');

        function filterSalas() {
            const searchText = salaSearch.value.toLowerCase().trim();
            const selectedSucursal = salaSucursalFilter ? salaSucursalFilter.value.toLowerCase().trim() : '';

            rows.forEach(row => {
                const name = (row.querySelector('.sala-name')?.textContent || '').toLowerCase().trim();
                const sucursal = (row.querySelector('.sala-sucursal')?.textContent || '').toLowerCase().trim();
                
                const matchesSearch = name.includes(searchText);
                const matchesSucursal = selectedSucursal === "" || sucursal.includes(selectedSucursal);

                row.style.display = (matchesSearch && matchesSucursal) ? "" : "none";
            });
        }

        salaSearch.addEventListener('input', filterSalas);
        if (salaSucursalFilter) salaSucursalFilter.addEventListener('change', filterSalas);
    }

    // ==========================================
    // 3. FILTRO DE SUCURSALES
    // ==========================================
    const isSucursalesPage = document.querySelector('h1')?.textContent.includes('Sucursales') && document.getElementById('searchInput');
    if (isSucursalesPage && !isFuncionesPage) {
        const sucursalesSearch = document.getElementById('searchInput');
        const rows = document.querySelectorAll('#tableBody tr');

        if (sucursalesSearch) {
            sucursalesSearch.addEventListener('input', function() {
                let filter = this.value.toLowerCase().trim();
                rows.forEach(row => {
                    let nameElement = row.querySelector('td:nth-child(2)');
                    if (nameElement) {
                        let text = nameElement.textContent.toLowerCase().trim();
                        row.style.display = text.includes(filter) ? "" : "none";
                    }
                });
            });
        }
    }

    // ==========================================
    // 4. FILTRO DE PELÍCULAS
    // ==========================================
    const buscadorPeliculas = document.getElementById('buscadorPeliculas');
    if (buscadorPeliculas) {
        const tarjetas = document.querySelectorAll('.tarjeta-pelicula');
        const mensajeVacio = document.getElementById('mensajeSinResultados');

        buscadorPeliculas.addEventListener('input', function(e) {
            const textoBusqueda = e.target.value.toLowerCase().trim();
            let tarjetasVisibles = 0;

            tarjetas.forEach(tarjeta => {
                const titulo = tarjeta.getAttribute('data-titulo') || '';
                if (titulo.includes(textoBusqueda)) {
                    tarjeta.style.display = 'flex';
                    tarjetasVisibles++;
                } else {
                    tarjeta.style.display = 'none';
                }
            });

            if (mensajeVacio) {
                if (tarjetasVisibles === 0 && tarjetas.length > 0) {
                    mensajeVacio.classList.remove('hidden');
                } else {
                    mensajeVacio.classList.add('hidden');
                }
            }
        });
    }

    // ==========================================
    // 5. FILTRO DE USUARIOS
    // ==========================================
    const userSearch = document.getElementById('searchInput');
    const isUsuariosPage = document.querySelector('.user-row') !== null;

    if (isUsuariosPage && userSearch) {
        const rows = document.querySelectorAll('.user-row');
        const noResultsRow = document.getElementById('noResults');

        userSearch.addEventListener('input', function() {
            const filter = this.value.toLowerCase().trim();
            let visibleCount = 0;

            rows.forEach(row => {
                const name = (row.querySelector('.user-name')?.textContent || '').toLowerCase().trim();
                const email = (row.querySelector('.user-email')?.textContent || '').toLowerCase().trim();
                
                if (name.includes(filter) || email.includes(filter)) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            });

            if (noResultsRow) {
                noResultsRow.style.display = (visibleCount === 0 && rows.length > 0) ? "" : "none";
            }
        });
    }

});
