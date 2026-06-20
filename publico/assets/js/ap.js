/**
 * FUNCIONES GLOBALES
 */

// Formatear número a moneda
function formatearMoneda(valor) {
    return 'Bs. ' + Number(valor).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// Mostrar alerta
function mostrarAlerta(mensaje, tipo = 'exito') {
    const colores = {
        exito: { bg: '#e8f5e9', color: '#2e7d32', borde: '#a5d6a7' },
        error: { bg: '#ffebee', color: '#c62828', borde: '#ef9a9a' },
        advertencia: { bg: '#fff3e0', color: '#e65100', borde: '#ffcc80' }
    };
    const estilo = colores[tipo] || colores.exito;
    const alerta = document.createElement('div');
    alerta.style.cssText = `
        padding: 12px 18px;
        border-radius: 6px;
        margin-bottom: 15px;
        background: ${estilo.bg};
        color: ${estilo.color};
        border: 1px solid ${estilo.borde};
        font-weight: bold;
    `;
    alerta.textContent = mensaje;
    return alerta;
}

// Confirmar acción
function confirmarAccion(mensaje) {
    return confirm(mensaje || '¿Estás seguro de realizar esta acción?');
}

// Redirigir
function redirigir(url) {
    window.location.href = url;
}

// Obtener parámetros de URL
function obtenerParametro(nombre) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(nombre);
}