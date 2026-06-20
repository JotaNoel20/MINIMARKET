<?php
namespace App\Nucleo;

/**
 * Validador de Datos
 * Valida formularios y datos de entrada
 */
class Validacion
{
    private $errores = [];
    private $datos = [];

    /**
     * Valida un conjunto de datos con reglas
     */
    public function validar($datos, $reglas)
    {
        $this->datos = $datos;
        $this->errores = [];

        foreach ($reglas as $campo => $regla) {
            $reglasLista = explode('|', $regla);
            $valor = $datos[$campo] ?? null;

            foreach ($reglasLista as $reglaIndividual) {
                $this->aplicarRegla($campo, $valor, $reglaIndividual);
            }
        }

        return empty($this->errores);
    }

    /**
     * Aplica una regla de validación a un campo
     */
    private function aplicarRegla($campo, $valor, $regla)
    {
        // Requerido
        if ($regla === 'required' && (empty($valor) && $valor !== '0')) {
            $this->errores[$campo] = "El campo $campo es obligatorio";
            return;
        }

        // Si está vacío y no es required, saltar
        if (empty($valor) && $valor !== '0') {
            return;
        }

        // Mínimo
        if (strpos($regla, 'min:') === 0) {
            $min = (int)substr($regla, 4);
            if (strlen($valor) < $min) {
                $this->errores[$campo] = "El campo $campo debe tener al menos $min caracteres";
            }
            return;
        }

        // Máximo
        if (strpos($regla, 'max:') === 0) {
            $max = (int)substr($regla, 4);
            if (strlen($valor) > $max) {
                $this->errores[$campo] = "El campo $campo no debe exceder $max caracteres";
            }
            return;
        }

        // Numérico
        if ($regla === 'numeric') {
            if (!is_numeric($valor)) {
                $this->errores[$campo] = "El campo $campo debe ser un número";
            }
            return;
        }

        // Mínimo numérico
        if (strpos($regla, 'min_num:') === 0) {
            $min = (float)substr($regla, 8);
            if ((float)$valor < $min) {
                $this->errores[$campo] = "El campo $campo debe ser mayor o igual a $min";
            }
            return;
        }

        // Máximo numérico
        if (strpos($regla, 'max_num:') === 0) {
            $max = (float)substr($regla, 8);
            if ((float)$valor > $max) {
                $this->errores[$campo] = "El campo $campo debe ser menor o igual a $max";
            }
            return;
        }

        // Email
        if ($regla === 'email') {
            if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                $this->errores[$campo] = "El campo $campo debe ser un email válido";
            }
            return;
        }

        // En lista (in:valor1,valor2)
        if (strpos($regla, 'in:') === 0) {
            $valoresPermitidos = explode(',', substr($regla, 3));
            if (!in_array($valor, $valoresPermitidos)) {
                $this->errores[$campo] = "El campo $campo tiene un valor no permitido";
            }
            return;
        }
    }

    /**
     * Verifica si la validación falló
     */
    public function falla()
    {
        return !empty($this->errores);
    }

    /**
     * Verifica si la validación pasó
     */
    public function pasa()
    {
        return empty($this->errores);
    }

    /**
     * Obtiene todos los errores
     */
    public function errores()
    {
        return $this->errores;
    }

    /**
     * Obtiene el primer error
     */
    public function primerError()
    {
        return reset($this->errores);
    }

    /**
     * Obtiene los datos validados
     */
    public function datos()
    {
        return $this->datos;
    }
}