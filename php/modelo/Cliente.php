<?php 
//session_start();
require_once 'autoload.php';

 class Cliente implements JsonSerializable { 
	private $id;
	private $dni;
	private $cuil;
	private $apellido;
	private $nombre;
	private $telefono;
	private $email;
    private $idDomicilio;
    private $domicilio;
     
    private $idUsuario;
    private $baja;

   
	public function __construct($id=null, $dni, $cuil,$apellido, $nombre, $telefono, $email, $domicilio = null, 
                                $idUsuario, $baja = 0) 
    {
        $this->id = $id;
        $this->dni = $dni;
        $this->cuil = $cuil;
        $this->apellido = $apellido;
        $this->nombre = $nombre;
        $this->telefono = $telefono;
        $this->email = $email;
        // Domicilio
        $this->domicilio = $domicilio;
        
        $this->idUsuario = $idUsuario;
        $this->baja = $baja;
    }
	
	public static $reglas = [
		'dni' => ['required'],
        //'cuil' => ['required'],
		'apellido' => ['required'],
		'nombre' => ['required'],
		'telefono' => ['required'],
		'email' => ['required'],
        'idUsuario' => ['required']
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
			'dni' => $this->dni,
			'cuil' => $this->cuil,
			'apellido' => $this->apellido,
			'nombre' => $this->nombre,
			'telefono' => $this->telefono,
			'email' => $this->email,
            'domicilio' => $this->domicilio,
            'idUsuario' => $this->idUsuario,
            'baja' => $this->baja
		];
		
	}
	
	/**
	 * Retorna el aviso con id si fue guardado o editado con exito, si no un mensaje con la excepcion
	 *
	 * @return Aviso el aviso insertado o editado
	 */
    
	public static function insertCliente($cliente){
		$db = DBConnection::getConnection();
		
		if($cliente->id) /*Modifica*/ {
	 
			try	{
				$db->beginTransaction();
				
				$query = "UPDATE clientes SET dni = :dni, cuil = :cuil, apellido = :apellido, nombre = :nombre, telefono = :telefono, email = :email WHERE id = :id";
				 
				$stmt = DBConnection::getStatement($query);
				
				$stmt = Cliente::bindearDatos($stmt, $cliente);
              
				$stmt->bindParam(':id', $cliente->id,PDO::PARAM_INT);
				
				if(!$stmt->execute()) {
				  throw new Exception("Error en el editado del cliente.");
				}

				$db->commit();
				 
				return $cliente;
		    } catch(PDOException $e) {
				echo 'Error: ' . $e->getMessage();
				$db->rollBack();
		    }
		}else /*Inserta*/ {
			try	{
				$db->beginTransaction();
				
                $query = "INSERT INTO clientes (dni, cuil, apellido, nombre, telefono, email, idUsuario)
				VALUES(:dni, :cuil, :apellido, :nombre, :telefono, :email, :idUsuario)";
                
				$stmt = DBConnection::getStatement($query);
															
				$stmt = Cliente::bindearDatos($stmt, $cliente);
                			 
				if(!$stmt->execute()) {
					throw new Exception("Error en el insertado del cliente.");
				}

				$id = $db->lastInsertId();

				$cliente->setId($id);
              
                $query = "INSERT INTO domicilios (calle, numero, piso, depto, localidad, provincia, pais, cp, idCliente) 
                VALUES(:calle, :numero, :piso, :depto, :localidad, :provincia, :pais, :cp, $cliente->id)";
                
                $stmt = DBConnection::getStatement($query);
                
                $stmt = Cliente::bindearDatosDom($stmt, $cliente);
                
                if(!$stmt->execute()) {
					throw new Exception("Error en el insertado del domicilio del cliente.");
				}
                
				$db->commit();
				
				return $cliente;
				 
			} catch(PDOException $e){
				echo 'Error: ' . $e->getMessage();
				$db->rollBack();
		    }
		}
	}
	/**
	 * Retorna el statement con todos los datos bindeados
	 *
	 * @return  Statement el statement de la conexion
	 */
    private static function bindearDatosDom($stmt, $cliente) {
		
        $stmt->bindParam(':calle', $cliente->domicilio->calle,PDO::PARAM_STR);
        $stmt->bindParam(':numero', $cliente->domicilio->numero,PDO::PARAM_STR);
        $stmt->bindParam(':piso', $cliente->domicilio->piso,PDO::PARAM_STR);
        $stmt->bindParam(':depto', $cliente->domicilio->depto,PDO::PARAM_STR);
        $stmt->bindParam(':localidad', $cliente->domicilio->localidad,PDO::PARAM_STR);
        $stmt->bindParam(':provincia', $cliente->domicilio->provincia,PDO::PARAM_STR);
        $stmt->bindParam(':pais', $cliente->domicilio->pais,PDO::PARAM_STR);
        $stmt->bindParam(':cp', $cliente->domicilio->cp,PDO::PARAM_STR);

        return $stmt;
	}
	private static function bindearDatos($stmt, $cliente) {
		
        $stmt->bindParam(':dni', $cliente->dni,PDO::PARAM_STR);
        $stmt->bindParam(':cuil', $cliente->cuil,PDO::PARAM_STR);
        $stmt->bindParam(':apellido', $cliente->apellido,PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $cliente->nombre,PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $cliente->telefono,PDO::PARAM_STR);
        $stmt->bindParam(':email', $cliente->email,PDO::PARAM_STR);
        $stmt->bindParam(':idUsuario', $cliente->idUsuario, PDO::PARAM_INT);

        return $stmt;
	}
	/**
	 * Retorna un mensaje si fue eliminado correctamente o una excepcion
	 *
	 * @return  String el mensaje
	 */
   	public static function eliminar($idCliente){
		try{
			$db = DBConnection::getConnection();
			$db->beginTransaction();
			
			// Doy de baja un cliente
			// $query = "DELETE FROM clientes WHERE id = :id";
            $query = "UPDATE clientes SET baja = 1 WHERE id = :id";
            
			$stmt = DBConnection::getStatement($query);
			
			$stmt->bindParam(':id', $idCliente,PDO::PARAM_INT);
			
			if(!$stmt->execute()) {
				throw new Exception("Error al dar de baja al cliente.");
			}
		   
		    $db->commit();
			echo "Cliente dado de baja correctamente.";
		} catch (PDOException $e){
		    echo 'Error: ' . $e->getMessage();
		    $db->rollBack();
		}	
    }
   	/**
	 * Retorna un cliente especifico, buscado por id. De no encontrar retorna una excepcion
	 *
	 * @return  Cliente  buscado
	 */
    public static function getById($id){
		try{
			$listaClientes = Cliente::getAll();
			
			$cli = null;
			foreach($listaClientes as $cliente) {
				if ($id == $cliente->id) {
					$cli = $cliente;
					break;
				}
			}
			
			if (!isset($cli)) {
				throw new Exception("No se encontro el cliente.");
			}
			
			return $cli;

		} catch(PDOException $e){
		    echo 'Error: ' . $e->getMessage();
		}
    }
     
    /**
	 * Retorna un cliente especifico, buscado por id de usuario. De no encontrar retorna una excepcion
	 *
	 * @return  Cliente  buscado
	 */
    public static function getByIdUsuario($idUsuario){
		try{
			$listaClientes = Cliente::getAll();
			
			$cli = null;
			foreach($listaClientes as $cliente) {
				if ($idUsuario == $cliente->idUsuario) {
					$cli = $cliente;
					break;
				}
			}
			
			if (!isset($cli)) {
				die("No se encontro el cliente.");// throw new Exception("No se encontro el cliente.");
			}
			
			return $cli;

		} catch(PDOException $e){
		    echo 'Error: ' . $e->getMessage();
		}
    }
	/**
	 * Retorna un array de todos los clientes. De tirar un error arroja una excepcion
	 *
	 * @return  Array el array de clientes
	 */
	public static function getAll(){
		try {
		    $query = "SELECT cli.id, cli.dni, cli.cuil, cli.apellido, cli.nombre, cli.telefono, cli.email, 
                      cli.baja, cli.idUsuario,    
	                  dom.calle, dom.numero, dom.numero, dom.piso, dom.depto, dom.localidad, dom.provincia, 
                      dom.pais, dom.cp
                      FROM clientes cli 
                      INNER JOIN domicilios dom ON cli.id = dom.idCliente 
                      ORDER BY apellido";
		    /*
            $query = "SELECT cli.id, cli.dni, cli.cuil, cli.apellido, cli.nombre, cli.telefono, cli.email
					 FROM clientes cli ";  
            */
		    $stmt = DBConnection::getStatement($query);
		   
		    if(!$stmt->execute()) {
		      throw new Exception('Error al traer los clientes.');
			}
		  
			$clientes = array();
			
			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
              $domicilio = new Domicilio($row['calle'], $row['numero'], $row['piso'], $row['depto'], $row['localidad'], $row['provincia'], $row['pais'], $row['cp']);
              
              $cliente = new Cliente($row['id'], $row['dni'], $row['cuil'], $row['apellido'], $row['nombre'], $row['telefono'], $row['email'], $domicilio, $row['idUsuario'], $row['baja']);
              
              $clientes[] = $cliente;
			}
		
		    return $clientes;
        } catch(PDOException $e){
		    echo 'Error: ' . $e->getMessage();
		}
    }
/*GETTER Y SETTER */
	public function __get($propiedad)
	{
		return $this->$propiedad;
	}
	
	public function __set($propiedad, $valor)
	{
		if(!property_exists($this, $propiedad)) {
			throw new Exception('La propiedad <b>' . $propiedad . "</b> no existe.");
		}
			
		$metodo = "set" . ucfirst($propiedad);
		
		if(method_exists($this, $metodo)) {
			$this->$metodo($valor);
		}
	}
	 /**
	 * Retorna si esta seteada la propiedad. Tuve que recurrir a este metodo porque al estar seteada en private las propiedades, el empty del Validator consideraba que no
	 * existia, pero cuando se llama a dicho método pasa por el __isset y verifica si esta seteada o no
	 *
	 * @return boolean 
	 */
	public function __isset($propiedad)
	{
		return isset($this->$propiedad);
		
	}
	
	public function setId($valor) {
		$this->id = $valor;
    }
 }
	
?>