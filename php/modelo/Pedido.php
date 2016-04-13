<?php 
//
require_once 'autoload.php';
//session_start();

 class Pedido  implements JsonSerializable { 
	private $id;
	private $nroPedido;
    private $idCliente;
	private $total;
	private $formaDePago;
	private $formaDeEnvio;
	private $productos;
    
	
	public function __construct($id=null, $nroPedido, $idCliente,$total, $formaDePago, $formaDeEnvio, $productos=null) {
       $this->id = $id;
	   $this->nroPedido = $nroPedido;
	   $this->idCliente = $idCliente;
	   $this->total = $total;
	   $this->formaDePago = $formaDePago;
	   $this->formaDeEnvio = $formaDeEnvio;
	   $this->productos = $productos;
	  
    }
	
	public static $reglas = [
		'nroPedido' => ['required'],
		'idCliente' => ['required'],
		'total' => ['required'],
		'formaDePago' => ['required'],
		'formaDeEnvio' => ['required']
	];
	
	
	/**
	 * Retorna el array con los datos de mi clase. Se implementó el método de JsonSerializable para poder acceder a los métodos privados de mi clase, ya que el json_encode al cual le paso mi lista de avisos respetaba el acceso y no lo pasaba al front.
	 *
	 * @return array de las propiedades mi aviso.
	 */
	public  function jsonSerialize() 
	{
		$json = array();
		
		return [
			'id' => $this->id,
			'modelo' => $this->modelo,
			'descripcion' => $this->descripcion,
			'talle' => $this->talle,
			'color' => $this->color,
			'stock' => $this->stock,
			'precio' => $this->precio
		];
		
	}
	

	/**
	 * Retorna el aviso con id si fue guardado o editado con exito, si no un mensaje con la excepcion
	 *
	 * @return Aviso el aviso insertado o editado
	 */
	public static function insert($pedido){
		$db = DBConnection::getConnection();
		
		try	{
				$db->beginTransaction();
				
				$query = "INSERT INTO pedidos (idCliente, nroPedido, total, formaDePago, formaDeEnvio, fecha)
				VALUES(:idCliente, :nroPedido, :total, :formaDePago, :formaDeEnvio, :fecha)";

				$stmt = DBConnection::getStatement($query);
															
				$stmt = Pedido::bindearDatos($stmt, $pedido);
                			 
				if(!$stmt->execute()) {
					throw new Exception("Error en el insertado del pedido.");
				}

				$id = $db->lastInsertId();

				$pedido->setId($id);
            
                foreach($pedido->productos as $prod) {
                    $query = "INSERT INTO detallepedido (idPedido, idProducto,cantidad,precio) values (:idPedido, :idProducto, :cantidad, :precio)";

                    $stmt = DBConnection::getStatement($query);

                    $stmt->bindParam(':idPedido', $id,PDO::PARAM_INT );
                    $stmt->bindParam(':idProducto', $prod->id,PDO::PARAM_INT );
                    $stmt->bindParam(':cantidad', $prod->stock,PDO::PARAM_INT );
                    $stmt->bindParam(':precio', $prod->precio,PDO::PARAM_STR );

                    if(!$stmt->execute()) {
                        throw new Exception("Error en el insertado del detalle del pedido");
                    }
                }
				
                //$_SESSION['idProducto'] = $producto->id;
			
				$db->commit();
				
				return $pedido;
				 
			} catch(PDOException $e)
				{
				  echo 'Error: ' . $e->getMessage();
				  $db->rollBack();
				}
		

	}
	

	/**
	 * Retorna el statement con todos los datos bindeados
	 *
	 * @return  Statement el statement de la conexion
	 */
	private static function bindearDatos($stmt, $pedido) {
        
         $fecha = date('Y/m/d H:i:s');
		
		 $stmt->bindParam(':idCliente', $pedido->idCliente,PDO::PARAM_INT);
		 $stmt->bindParam(':nroPedido', $pedido->nroPedido,PDO::PARAM_INT);
		 $stmt->bindParam(':total', $pedido->total,PDO::PARAM_STR);
		 $stmt->bindParam(':formaDePago', $pedido->formaDePago,PDO::PARAM_INT);
		 $stmt->bindParam(':formaDeEnvio', $pedido->formaDeEnvio,PDO::PARAM_INT);
		 $stmt->bindParam(':fecha',  $fecha);
		
		 return $stmt;
	}
	
	
	/**
	 * Retorna un mensaje si fue eliminado correctamente o una excepcion
	 *
	 * @return  String el mensaje
	 */
   	public static function delete($idProducto){
		try{
			$db = DBConnection::getConnection();
			
			$db->beginTransaction();
			
			//BORRO EL PRODUCTO
			$query = "DELETE FROM productos WHERE id = :id";
		
			$stmt = DBConnection::getStatement($query);
			
			$stmt->bindParam(':id', $idProducto,PDO::PARAM_INT);
			
			if(!$stmt->execute()) {
				throw new Exception("Error al eliminar el producto");
			}
		   
		    $db->commit();
			echo "Producto eliminado correctamente";
		} catch (PDOException $e)
			{
			  echo 'Error: ' . $e->getMessage();
			  $db->rollBack();
			}	
    }
   
   	/**
	 * Retorna un producto especifico, buscado por id. De no encontrar retorna una excepcion
	 *
	 * @return  Producto  buscado
	 */
    public static function getById($id){
		try{
			
			$listaProductos = Producto::getAll();
			
			$prod = null;
			foreach($listaProductos as $producto) {
				if ($id == $producto->id) {
					$prod = $producto;
					break;
				}
			}
			
			if (!isset($prod)) {
				throw new Exception("No se encontro el producto.");
			}
			
			return $prod;

		   } catch(PDOException $e)
			{
			  echo 'Error: ' . $e->getMessage();
			}
    }
	

	/**
	 * Retorna un array de todos los productos. De tirar un error arroja una excepcion
	 *
	 * @return  Array el array de productos
	 */
	public static function getAll(){
		try {
			 
			$query = "SELECT prod.id, prod.modelo, prod.descripcion, prod.talle, prod.color, prod.stock, prod.precio
					 FROM productos prod ";
											
		   $stmt = DBConnection::getStatement($query);
		   

		   if(!$stmt->execute()) {
				throw new Exception('Error al traer los productos');
			}
		   	   
			$productos = array();
			
			
			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {

                $producto = new Producto($row['id'], $row['modelo'], $row['descripcion'], $row['talle'], $row['color'], $row['stock'], $row['precio']);

                $productos[] = $producto;

			}
		
			 return $productos;


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
     
    public function setStock($valor) {
		$this->stock = $valor;
    }
	
	
 }
	
?>