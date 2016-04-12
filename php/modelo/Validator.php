<?php
require_once ('php/autoload.php');

class Validator
{
	public $datos;
	public $reglas;
	public $exito;
	public $errores = [];
	
	public function validate($datos, $reglas)
	{
		$this->datos = $datos;
		// Recorremos las reglas
		foreach($reglas as $nombreCampo => $listaDeReglas) {
			// Recorremos las reglas de un campo
			foreach($listaDeReglas as $unaRegla) {
				// Vamos a chequear el tipo de validación
				if(strpos($unaRegla, ':') !== false) {
					// Ej: $unaRegla = "min:4"
					$datosRegla = explode(':', $unaRegla);
					// Ej: $nombreRegla = "_min"
					$nombreRegla = '_' . $datosRegla[0];
					$valorRegla = $datosRegla[1];
					
					// Ej: $this->_min();
					$this->$nombreRegla($nombreCampo, $valorRegla);
				} else {
					// Ej: $unaRegla = 'required'
					$nombreRegla = "_" . $unaRegla;
					
					$this->$nombreRegla($nombreCampo);
				}
			}
		}
	}
	
	/**
	 * Retorna todos los errores en un array. De no haber ninguno, retorna un array vacío.
	 *
	 * @return array El array de errores.
	 */
	public function getErrores()
	{
		return $this->errores;
	}
	
	/**
	 * Retorna el error del $campo pedido, si existe. De no existir, retorna false.
	 * 
	 * @return string|boolean
	 */
	public function getError($campo)
	{
		return isset($this->errores[$campo]) ? $this->errores[$campo] : false;
	}
	
	/**
	 * Retorna un boolean indicando si la validación tuvo éxito o no.
	 *
	 * @return boolean
	 */
	public function tuvoExito()
	{
		//return count($this->errores) == 0;
		return empty($this->errores);
	}
	
	protected function _required($campo)
	{
		if(empty($this->datos->$campo)) {
			$this->errores[$campo] = "El $campo no puede estar vacio.";
			return false;
		}
		
		return true;
	}
	
	protected function _min($campo, $longitud)
	{
		if(strlen($this->datos[$campo]) < $longitud) {
			$this->errores[$campo] = "El $campo debe tener al menos $longitud caracteres.";
			return false;
		}
		return true;
	}
	
	protected function _equals($campo, $campoVerificacion)
	{
		if($this->datos[$campo] !== $this->datos[$campoVerificacion]) {
			$this->errores[$campo] = "$campo no coincide con <b>$campoVerificacion</b>.";
			return false;
		}
		return true;
	}
	
	protected function _numeric($campo)
	{
		if(!is_numeric($this->datos[$campo])) {
			$this->errores[$campo] = "El $campo debe ser un numero.";
			return false;
		}
		return true;
	}
}