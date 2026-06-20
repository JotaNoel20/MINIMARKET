<?php
namespace App\Nucleo;

/**
 * Sistema de Vistas
 * Renderiza archivos de vista con datos
 */
class Vista
{
    private $layout = 'principal';
    private $datosGlobales = [];

    /**
     * Establece el layout a usar
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Agrega datos globales para todas las vistas
     */
    public function setDatosGlobales($datos)
    {
        $this->datosGlobales = array_merge($this->datosGlobales, $datos);
    }

    /**
     * Renderiza una vista
     */
    public function renderizar($vista, $datos = [])
    {
        // Combinar datos globales con locales
        $datos = array_merge($this->datosGlobales, $datos);
        
        // Extraer datos para que estén disponibles como variables
        extract($datos);

        // Ruta de la vista
        $archivoVista = RUTA_VIEWS . '/' . $vista . '.php';

        if (!file_exists($archivoVista)) {
            throw new \Exception("Vista '$vista' no encontrada en: $archivoVista");
        }

        // Iniciar buffer de salida
        ob_start();
        
        // Incluir la vista
        include $archivoVista;
        
        // Obtener contenido
        $contenido = ob_get_clean();

        // ✅ Si la vista es 'autenticacion/login', usar plantilla login.php (sin sidebar)
        if ($vista === 'autenticacion/login') {
            $archivoLayout = RUTA_VIEWS . '/plantillas/login.php';
            if (file_exists($archivoLayout)) {
                ob_start();
                include $archivoLayout;
                return ob_get_clean();
            }
        }

        // Si el contenido ya incluye el layout (plantillas), devolverlo directamente
        if (strpos($vista, 'plantillas/') === 0) {
            return $contenido;
        }

        // Buscar si la vista ya incluye su propio layout
        if (strpos($contenido, 'plantillas/') !== false) {
            return $contenido;
        }

        // Usar layout por defecto
        $archivoLayout = RUTA_VIEWS . '/plantillas/' . $this->layout . '.php';
        
        if (file_exists($archivoLayout)) {
            ob_start();
            include $archivoLayout;
            return ob_get_clean();
        }

        // Si no hay layout, devolver solo el contenido
        return $contenido;
    }

    /**
     * Renderiza una vista parcial (sin layout)
     */
    public function renderizarParcial($vista, $datos = [])
    {
        $archivoVista = RUTA_VIEWS . '/' . $vista . '.php';
        
        if (!file_exists($archivoVista)) {
            throw new \Exception("Vista parcial '$vista' no encontrada");
        }

        extract($datos);
        ob_start();
        include $archivoVista;
        return ob_get_clean();
    }

    /**
     * Verifica si una vista existe
     */
    public function existe($vista)
    {
        $archivoVista = RUTA_VIEWS . '/' . $vista . '.php';
        return file_exists($archivoVista);
    }
}