<?php 
//session_start();
require_once 'autoload.php';

 class Talle implements JsonSerializable { 
	private $id;
    private $descripcion;
   
	public function __construct($id,$descripcion) 
    {
        $this->id = $id;
        $this->descripcion = $descripcion;

    }

	
	/**
	 * Retorna el array con los datos de mi clase. Se implementó el método de JsonSerializable para poder acceder a los métodos privados de mi clase, ya que el json_encode al cual le paso mi lista de clientes respetaba el acceso y no lo pasaba al front.
	 *
	 * @return array de las propiedades mi cliente.
	 */
	public  function jsonSerialize() 
	{
		//$json = array();
		
		return [
			'id' => $this->id,
			'descripcion' => $this->descripcion,
			
		];
		
	}
	
	
/*GETTER Y SETTER */
	public function __get($propiedad)
	{
		return $this->$propiedad;
	}
	
	
 }
	
?>