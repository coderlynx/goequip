<?php 
//session_start();
require_once 'autoload.php';

 class Entrega implements JsonSerializable { 
	private $id;
    private $costo;
    private $idZona;
    private $nombreZona;
    private $numero;
   
	public function __construct($id=null,$costo,$idZona,$nombreZona, $numero) 
    {
        $this->id = $id;
        $this->costo = $costo;
        $this->idZona = $idZona;
        $this->nombreZona = $nombreZona;
        $this->numero = $numero;

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
			'costo' => $this->costo,
			'idZona' => $this->idZona,
			'nombreZona' => $this->nombreZona,
			'numero' => $this->numero
			
		];
		
	}
	
	
/*GETTER Y SETTER */
	public function &__get($propiedad)
	{
		return $this->$propiedad;
	}
     
     public function __set($propiedad, $valor){
		
		if(!property_exists($this, $propiedad)) {
			throw new Exception('La propiedad <b>' . $propiedad . "</b> no existe.");
		}
			
		$metodo = "set" . ucfirst($propiedad);
		
		if(method_exists($this, $metodo)) {
			$this->$metodo($valor);
		}
	}
     
     public function setCosto($valor) {
		$this->costo = $valor;
    }
     
     public function setIdZona($valor) {
		$this->idZona = $valor;
    }
     
    public function setNombreZona($valor) {
		$this->nombreZona = $valor;
    }
	
	
 }
	
?>