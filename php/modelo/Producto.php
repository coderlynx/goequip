<?php 
//session_start();
require_once 'autoload.php';

 class Producto  implements JsonSerializable { 
	private $id;
	private $modelo;
	private $descripcion;
	private $talle;
	private $color;
	private $stock;
	private $precio;
	
	
	public function __construct($id=null, $modelo, $descripcion,$talle=null, $color, $stock, $precio) {
       $this->id = $id;
	   $this->modelo = $modelo;
	   $this->descripcion = $descripcion;
	   $this->talle = $talle;
	   $this->color = $color;
	   $this->stock = $stock;
	   $this->precio = $precio;
	  
    }
	
	public static $reglas = [
		'modelo' => ['required'],
		'descripcion' => ['required'],
		'color' => ['required'],
		'stock' => ['required'],
		'precio' => ['required']
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
	public static function insertProducto($producto){
		$db = DBConnection::getConnection();
		
		if($producto->id) /*Modifica*/ {
	 
			try	{
				$db->beginTransaction();
				
				$query = "UPDATE productos SET modelo = :modelo, descripcion = :descripcion, talle = :talle, color = :color, stock = :stock, precio = :precio WHERE id = :id";
				 
				$stmt = DBConnection::getStatement($query);
				
				$stmt = Producto::bindearDatos($stmt, $producto);
				$stmt->bindParam(':id', $producto->id,PDO::PARAM_INT);
				

				 if(!$stmt->execute()) {
					throw new Exception("Error en el editado del producto.");
				}
				
				//$_SESSION['idProducto'] = $producto->id;

				$db->commit();
				 
				 return $producto;
			 } catch(PDOException $e)
				{
				  echo 'Error: ' . $e->getMessage();
				  $db->rollBack();
				}
		}else /*Inserta*/ {
			try	{
				$db->beginTransaction();
				
				$query = "INSERT INTO productos (modelo, descripcion, talle, color, stock, precio)
				VALUES(:modelo, :descripcion, :talle, :color, :stock, :precio)";

				$stmt = DBConnection::getStatement($query);
															
				$stmt = Producto::bindearDatos($stmt, $producto);
                			 
				if(!$stmt->execute()) {
					throw new Exception("Error en el insertado del producto.");
				}

				$id = $db->lastInsertId();

				$producto->setId($id);
				
                //$_SESSION['idProducto'] = $producto->id;
			
				$db->commit();
				
				return $producto;
				 
			} catch(PDOException $e)
				{
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
	private static function bindearDatos($stmt, $producto) {
		
		 $stmt->bindParam(':modelo', $producto->modelo,PDO::PARAM_STR);
		 $stmt->bindParam(':descripcion', $producto->descripcion,PDO::PARAM_STR);
		 $stmt->bindParam(':talle', $producto->talle,PDO::PARAM_STR);
		 $stmt->bindParam(':color', $producto->color,PDO::PARAM_STR);
		 $stmt->bindParam(':stock', $producto->stock,PDO::PARAM_INT);
		 $stmt->bindParam(':precio', $producto->precio);
		
		 return $stmt;
	}
	
	
	/**
	 * Retorna un mensaje si fue eliminado correctamente o una excepcion
	 *
	 * @return  String el mensaje
	 */
   	public static function eliminar($idProducto){
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
			
			$listaProductos = Producto::getTodos();
			
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
	public static function getTodos(){
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