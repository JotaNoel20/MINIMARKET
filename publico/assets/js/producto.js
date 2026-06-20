/**
 * LÓGICA DE PRODUCTOS
 */

// Buscar productos en tiempo real
function buscarProductos() {
    const input = document.getElementById('buscar-producto');
    const filtro = input.value.toLowerCase();
    const filas = document.querySelectorAll('#tabla-productos tbody tr');
    
    filas.forEach(fila => {
        const nombre = fila.querySelector('.nombre-producto')?.textContent?.toLowerCase() || '';
        const categoria = fila.querySelector('.categoria-producto')?.textContent?.toLowerCase() || '';
        const coincide = nombre.includes(filtro) || categoria.includes(filtro);
        fila.style.display = coincide ? '' : 'none';
    });
}

// Ordenar productos
function ordenarProductos(columna) {
    const tabla = document.getElementById('tabla-productos');
    const tbody = tabla.querySelector('tbody');
    const filas = Array.from(tbody.querySelectorAll('tr'));
    
    const orden = tabla.dataset.orden === 'asc' ? 'desc' : 'asc';
    tabla.dataset.orden = orden;
    
    filas.sort((a, b) => {
        const aVal = a.querySelector(`.${columna}`)?.textContent || '';
        const bVal = b.querySelector(`.${columna}`)?.textContent || '';
        return orden === 'asc' ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
    });
    
    filas.forEach(fila => tbody.appendChild(fila));
}

// Confirmar eliminación de producto
function confirmarEliminar(id, nombre) {
    if (confirm(`¿Estás seguro de eliminar el producto "${nombre}"?`)) {
        window.location.href = `/minimarket/productos/eliminar/${id}`;
    }
}