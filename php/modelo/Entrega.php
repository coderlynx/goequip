<?php 
//session_start();
require_once 'autoload.php';

 class Entrega implements JsonSerializable { 
	private $id;
    private $costo;
    private $idZona;
    private $nombreZona;
    private $numeroSeguimiento;
    private $numero;
    
   
	public function __construct($id=null,$costo,$idZona, $numero,$seguimiento=null) 
    {
        $this->id = $id;
        $this->costo = $costo;
        $this->idZona = $idZona;
        //$this->nombreZona = $nombreZona;
        $this->numero = $numero;
        $this->numeroSeguimiento = $seguimiento;

    }
     
    public static $reglas = [
		'costo' => ['required'],
		'idZona' => ['required'],
		//'nombreZona' => ['required'],
		'numero' => ['required']
	];

	
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
			//'nombreZona' => $this->nombreZona,
			'numero' => $this->numero,
			'numeroSeguimiento' => $this->numeroSeguimiento
			
		];
		
	}
     
    public static function insert($entrega){
		$db = DBConnection::getConnection();
		
		try	{
				//$db->beginTransaction();
            
                //valido que no exista una entrega con el mismo numero
                $query = "SELECT count(*) as entrega from entregas where numeroPedido = :nroPedido";

                $stmt = DBConnection::getStatement($query);

                $stmt->bindParam(':nroPedido', $entrega->numero,PDO::PARAM_INT);

                if(!$stmt->execute()) {
                    throw new Exception("Error en buscar la entrega.");
                }
            
                 while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                    if ($row["entrega"] == 1) die("Ya existe ese numero de entrega");
                 }
				
                //INSERTO EL PEDIDO
				$query = "INSERT INTO entregas (costo, idZona, numeroPedido, fecha)
				VALUES(:costo, :idZona, :numeroPedido, :fecha)";

				$stmt = DBConnection::getStatement($query);
															
				$fecha = date('Y/m/d H:i:s');
		
                 $stmt->bindParam(':costo', $entrega->costo,PDO::PARAM_STR);
                 $stmt->bindParam(':idZona', $entrega->idZona,PDO::PARAM_INT);
                 $stmt->bindParam(':numeroPedido', $entrega->numero,PDO::PARAM_INT);
                 $stmt->bindParam(':fecha',  $fecha);
                			 
				if(!$stmt->execute()) {
					throw new Exception("Error en el insertado de la entrega.");
				}

			
				//$db->commit();
				
				return 'ok';
				 
			} catch(PDOException $e)
				{
				  echo 'Error: ' . $e->getMessage();
				//  $db->rollBack();
				}
	}
     
     public static function getAll(){
		try {
			 
			$query = "SELECT *
					 FROM entregas";
											
		   $stmt = DBConnection::getStatement($query);
		   

		   if(!$stmt->execute()) {
				throw new Exception('Error al traer las entregas');
			}
		   	   
			$entregas = array();
            //$formaDePago = "T.C";
            //$formaDeEnvio = "Sucursal";
			
			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {

                $entrega = new Entrega($row['id'], $row['costo'], $row['idZona'], $row['numeroPedido'], $row['numeroSeguimiento']);
               

                $entregas[] =  $entrega;

			}
		
			 return $entregas;


	   } catch(PDOException $e)
			{
			  echo 'Error: ' . $e->getMessage();
			}
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