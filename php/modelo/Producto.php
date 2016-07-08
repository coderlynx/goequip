<?php 
require_once 'autoload.php';
class Producto implements JsonSerializable 
{ 
	private $id;
	private $modelo;
	private $descripcion;
    private $categoria;
	private $talle; // array
	private $color; // array
	private $stock;
	private $precio;
    private $fotos; // array
    
	public function __construct($id = null, $modelo, $descripcion, $categoria, $talle = null, 
                                $color = null, $stock, $precio, $fotos = null) 
    {
        $this->id = $id;
        $this->modelo = $modelo;
        $this->descripcion = $descripcion;
        $this->categoria = $categoria;
        $this->talle = $talle;
        $this->color = $color;
        $this->stock = $stock;
        $this->precio = $precio;
        $this->fotos = $fotos;
    }
	public static $reglas = [
		'modelo' => ['required'],
		'descripcion' => ['required'],
		'categoria' => ['required'],
		//'color' => ['required'],
		'stock' => ['required'],
		'precio' => ['required']
	];
	/**
	 * Retorna el array con los datos de mi clase. Se implementó el método de JsonSerializable 
     * para poder acceder a los métodos privados de mi clase, ya que el json_encode al cual le 
     * paso mi lista de productos *respetaba el acceso y no lo pasaba al front.
	 * @return array de las propiedades mi producto.
	 */
	public  function jsonSerialize() 
	{
		$json = array();
		
		return [
			'id' => $this->id,
			'modelo' => $this->modelo,
			'descripcion' => $this->descripcion,
			'categoria' => $this->categoria,
			'talles' => $this->talle,
			'colores' => $this->color,
			'stock' => $this->stock,
			'precio' => $this->precio,
            'fotos' => $this->fotos
		];		
	}

	/**
	 * Retorna el aviso con id si fue guardado o editado con exito, si no un mensaje con la excepcion
	 * @return Aviso el aviso insertado o editado
	 */
	public static function insert($producto)
    {
		$db = DBConnection::getConnection();
		/* Modificar */ /* Insertar */
		if ($producto->id) {
			try	{
				$db->beginTransaction();
				
				$query = "UPDATE productos SET modelo = :modelo, descripcion = :descripcion, 
                idCategoria = :idCategoria, stock = :stock, precio = :precio WHERE id = :id";
				 
				$stmt = DBConnection::getStatement($query);
				
				$stmt = Producto::bindearDatos($stmt, $producto);
				$stmt->bindParam(':id', $producto->id, PDO::PARAM_INT);
				
				if (!$stmt->execute()) {
					throw new Exception("Error en el editado del producto.");
				}
                self::insertarTalles($producto);
				self::insertarColores($producto);
                
				$db->commit();
				 
				return $producto;
            } catch (PDOException $e) {
				echo 'Error: '.$e->getMessage();
				$db->rollBack();
            }
		} else {
			try	{
				$db->beginTransaction();
				
				$query = "INSERT INTO productos (modelo, descripcion, idCategoria, stock, precio)
				VALUES(:modelo, :descripcion, :idCategoria, :stock, :precio)";

				$stmt = DBConnection::getStatement($query);										
				$stmt = Producto::bindearDatos($stmt, $producto);
                			 
				if (!$stmt->execute()) {
					throw new Exception("Error en el insertado del producto.");
				}

				$id = $db->lastInsertId();
				$producto->setId($id);
                
                self::insertarTalles($producto);
				self::insertarColores($producto);

				$db->commit();
				
				return $producto;
				 
			} catch (PDOException $e) {
				echo 'Error: ' . $e->getMessage();
				$db->rollBack();
            }
		}
	}
	/**
	 * Retorna el statement con todos los datos bindeados
	 * @return  Statement el statement de la conexion
	 */
	private static function bindearDatos($stmt, $producto) 
    {
        $stmt->bindParam(':modelo', $producto->modelo, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $producto->descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':idCategoria', $producto->categoria, PDO::PARAM_INT);
        //$stmt->bindParam(':talle', $producto->talle,PDO::PARAM_STR);
        //$stmt->bindParam(':color', $producto->color,PDO::PARAM_STR);
        $stmt->bindParam(':stock', $producto->stock, PDO::PARAM_INT);
        $stmt->bindParam(':precio', $producto->precio, PDO::PARAM_STR);

        return $stmt;
	}
	/**
	 * Retorna un mensaje si fue eliminado correctamente o una excepcion
	 * @return  String el mensaje
	 */
    public static function delete($idProducto)
    {
        try {
			$db = DBConnection::getConnection();
			$db->beginTransaction();

            $query = "UPDATE productos SET baja = 1 WHERE id = :id";
            
			$stmt = DBConnection::getStatement($query);
			
			$stmt->bindParam(':id', $idProducto, PDO::PARAM_INT);
			
			if(!$stmt->execute()) {
				throw new Exception("Error al dar de baja al producto.");
			}
		   
		    $db->commit();
            
			echo "Producto dado de baja correctamente.";
		} catch (PDOException $e) {
		    echo 'Error: ' . $e->getMessage();
		    $db->rollBack();
		}		
    }
    /**
	 * Retorna un producto especifico, buscado por id. De no encontrar retorna una excepcion
	 * @return  Producto  buscado
	 */
    public static function getById($id)
    {
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
				die(json_encode("error"));
			}
			return $prod;

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    /**
	 * Retorna productos de una categoria especifica, buscado por idCategoria. De no encontrar retorna una excepcion
	 *
	 * @return  Producto  buscado
	 */
    public static function getByCategoria($id, $orden)
    {
		try {
            $listaProductos = Producto::getAll();
			
			$productosPorCategoria = [];
            
			foreach ($listaProductos as $producto) {
				if ($id == $producto->categoria->id) {
					$productosPorCategoria[] = $producto;
				}
			}
            usort($productosPorCategoria, array( 'Producto', $orden));
			
			return $productosPorCategoria;
            
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
	/**
	 * Retorna un array de todos los productos. De tirar un error arroja una excepcion
	 * @return  Array el array de productos
	 */
	public static function getAll()
    {
		try {
			$query = "SELECT prod.id, prod.modelo, prod.descripcion, prod.stock, prod.precio, 
                      cat.id idCategoria, cat.descripcion descripcionCategoria,
                      t.id idTalle, t.descripcion descripcionTalle,
                      c.id idColor, c.descripcion descripcionColor,
                      img.id idImagen, img.nombre, img.tipo, img.size, img.ruta, 
                      img.rutaThumbnail, img.idProducto, img.orden
                      FROM productos prod 
                      INNER JOIN categorias cat ON prod.idCategoria = cat.id
                      LEFT JOIN talles_productos tp ON tp.idProducto = prod.id
                      LEFT JOIN talles t ON tp.idTalle = t.id
                      LEFT JOIN colores_productos cp ON cp.idProducto = prod.id
                      LEFT JOIN colores c ON cp.idColor = c.id
                      LEFT JOIN imagenes img ON prod.id = img.idProducto
                      WHERE prod.baja = 0";
											
            $stmt = DBConnection::getStatement($query);
		   
            if (!$stmt->execute()) {
				throw new Exception('Error al traer los productos');
			}
            $productos = array();
            $idAnterior = 0;
            $idTalleAnterior = 0;
            $idColorAnterior = 0;
            $idImagenAnterior = 0;
			
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                //hacer un corte de control para los talles y colores
                $producto;
                
                if ($idAnterior != $row['id']) {
                    
                    $talle = new Talle($row['idTalle'],$row['descripcionTalle']);
                    $color = new Color($row['idColor'],$row['descripcionColor']);
                    $categoria = new Categoria($row['idCategoria'], $row['descripcionCategoria']);
                    $producto = new Producto($row['id'], $row['modelo'], $row['descripcion'], 
                                             $categoria, null, null, $row['stock'], $row['precio']);
                    
                    $imagen = new Imagen($row['idImagen'], $row['nombre'], $row['tipo'], 
                                         $row['size'], $row['ruta'],
                                         $row['rutaThumbnail'], $row['idProducto']);
                    
                    $producto->addImagen($imagen);
                    $producto->addTalle($talle);
                    $producto->addColor($color);
                    
                    $idImagenAnterior = $row['idImagen'];
                    $idTalleAnterior = $row['idTalle'];
                    $idColorAnterior = $row['idColor'];

                    $productos[] = $producto;
                } else {
                    self::CorteControlImagenes($producto, $row, $idImagenAnterior);
                    self::CorteControlTalles($producto, $row, $idTalleAnterior);
                    self::CorteControlColores($producto, $row, $idColorAnterior);
                }
                $idAnterior = $row['id'];
			}
            return $productos;
        } catch(PDOException $e) {
			  echo 'Error: ' . $e->getMessage();
        }
    }
    /**
	 * Retorna un array de todos los productos. De tirar un error arroja una excepcion
	 * @return  Array el array de productos
	 */
	public static function getByTexto($valor)
    {
		try {
			$query = "SELECT *
				      FROM productos
                      WHERE baja = 0 AND modelo LIKE :valor or descripcion LIKE :valor";
											
            $stmt = DBConnection::getStatement($query);
            
            $texto = '%' . $valor . '%';
            $stmt->bindParam(':valor', $texto, PDO::PARAM_STR);
		   
		   if (!$stmt->execute()) {
				throw new Exception('Error al traer los productos');
			} 
			$productos = array();
			
			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $fotos = Imagen::getFotos($row['id']);
                $producto = new Producto($row['id'], $row['modelo'], $row['descripcion'], '', 
                                         null, null, $row['stock'], $row['precio']);
                $producto->fotos = $fotos;
                $productos[] = $producto;
			}
			 return $productos;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    /**
     * Retorna un array de todos los productos. De tirar un error arroja una excepcion
     * @return  Array el array de productos
     */
    public static function updateStock($prod) 
    {
        //TRAIGO EL PRODUCTO PARA CONOCER SU STOCK
        $query = "SELECT stock 
                  FROM productos
                  WHERE id = :id";
        $stmt = DBConnection::getStatement($query);

        $stmt->bindParam(':id', $prod->id,PDO::PARAM_INT );

        if(!$stmt->execute()) {
            throw new Exception("Error en buscar el producto del detalle del pedido");
        }

        while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            $stockViejo = $row["stock"];
            $nuevoStock = $stockViejo - $prod->stock;

            if ($nuevoStock < 0) die("No se pudo crear el pedido por falta de stock");

            //UPDATE DEL STOCK DEL PRODUCTO 
            $query = "UPDATE productos 
                      SET stock = :stock
                      WHERE id = :id";
            
            $stmt = DBConnection::getStatement($query);
            $stmt->bindParam(':id', $prod->id,PDO::PARAM_INT );
            $stmt->bindParam(':stock', $nuevoStock,PDO::PARAM_INT );
            
            if (!$stmt->execute()) {
                throw new Exception("Error en actualizar el stock del producto");
            }
        }
    }
   /**
	 * Retorna un array de todos los productos. De tirar un error arroja una excepcion
	 * @return  Array el array de productos
	 */
	public static function validateStock($prod)
    {
		try {
			//TRAIGO EL PRODUCTO PARA CONOCER SU STOCK
            $query = "SELECT stock 
                      FROM productos
                      WHERE id = :id";
            
            $stmt = DBConnection::getStatement($query);
            $stmt->bindParam(':id', $prod->id,PDO::PARAM_INT );
            
            if (!$stmt->execute()) {
                throw new Exception("Error en buscar el stock del producto");
            }
            
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $stockViejo = $row["stock"];
                $nuevoStock = $stockViejo - $prod->stock;

                if ($nuevoStock < 0) die("En el transcurso de la operacion nos hemos quedado sin stock del producto '" . 
                                         $prod->modelo . "'. Si desea puede proseguir sin ese producto en el carrito o puede contactarnos via mail o telefono con nosotros para solucionarlo. Gracias.");
            }
        } catch (PDOException $e) {
			  echo 'Error: ' . $e->getMessage();
        }
    }
    /**
	 * Inserta los talles en la tabla
	 */
	private static function insertarTalles($producto) 
    {
		$id = $producto->id;
		
		$query1 = "DELETE FROM talles_productos WHERE idProducto = :idProducto";
		$stmt = DBConnection::getStatement($query1);
		$stmt->bindParam(':idProducto', $id,PDO::PARAM_INT );
		
		if(!$stmt->execute()) {
			throw new Exception("Error en el borrado de la relacion con los talles.");
		}
		
		foreach($producto->talle as $idTalle) {
			$query2 = "INSERT INTO talles_productos (idProducto,idTalle) values (:idProducto, :idTalle)";
		 
			$stmt = DBConnection::getStatement($query2);
			
			$stmt->bindParam(':idProducto', $id,PDO::PARAM_INT );
			$stmt->bindParam(':idTalle', $idTalle,PDO::PARAM_INT );
			
			if (!$stmt->execute()) {
				throw new Exception("Error en el insertado de la relacion con los talles.");
			}
		}
	}
	/**
	 * Inserta los colores en la tabla
	 * @return  
	 */
	private static function insertarColores($producto) 
    {
		$id = $producto->id;
		
		$query1 = "DELETE FROM colores_productos WHERE idProducto = :idProducto";
		$stmt = DBConnection::getStatement($query1);
		$stmt->bindParam(':idProducto', $id,PDO::PARAM_INT );
		
		if(!$stmt->execute()) {
			throw new Exception("Error en el borrado de la relacion con los colores.");
		}
		
		foreach($producto->color as $idColor) {
			$query = "INSERT INTO colores_productos (idProducto,idColor) values (:idProducto, :idColor)";
		 
			$stmt = DBConnection::getStatement($query);
			
			$stmt->bindParam(':idProducto', $id,PDO::PARAM_INT );
			$stmt->bindParam(':idColor', $idColor,PDO::PARAM_INT );
			
			if (!$stmt->execute()) {
				throw new Exception("Error en el insertado de la relacion con los colores.");
			}
		}
	}
    public static function ordenarPorPrecioMayor($a, $b)
	{
		if ($a->precio == $b->precio) {
				return 0;
        }
        return ($a->precio < $b->precio) ? +1 : -1;
	}
    public static function ordenarPorPrecioMenor($a, $b)
	{
		if ($a->precio == $b->precio) {
				return 0;
        }
        return ($a->precio > $b->precio) ? +1 : -1;
	}
    private static function CorteControlImagenes(&$producto, $row, &$idImagenAnterior) 
    {
		if ($idImagenAnterior != $row['idImagen'] && !self::contieneElemento($producto->fotos, $row['idImagen'])) {
			
            $img = new Imagen($row['idImagen'], $row['nombre'], $row['tipo'], 
                              $row['size'], $row['ruta'],
                              $row['rutaThumbnail'], $row['idProducto']);
            
			$producto->addImagen($img);
			
			$idImagenAnterior = $row['idImagen'];
		}		
	}
    /* INICIO TALLE Y COLOR */
    private static function CorteControlTalles(&$producto, $row, &$idTalleAnterior) 
    {
		if ($idTalleAnterior != $row['idTalle'] && !self::contieneElemento($producto->talle,$row['idTalle'])) {
			$tal = new Talle($row['idTalle'],$row['descripcionTalle']);
			$producto->addTalle($tal);
			
			$idTalleAnterior = $row['idTalle'];
		}		
	}
	private static function CorteControlColores(&$producto, $row, &$idColorAnterior) 
    {
		if ($idColorAnterior != $row['idColor'] && !self::contieneElemento($producto->color,$row['idColor'])) {
			$col = new Color($row['idColor'],$row['descripcionColor']);
			$producto->addColor($col);
			
			$idColorAnterior = $row['idColor'];
		}		
	}
    private static function contieneElemento($elementos, $id) 
    {
		foreach($elementos as $unElemento) {
			if ($id == $unElemento->id) {
				return true;
			}
		}
		return false;
	}
    public function addImagen($imagen) 
	{
		$this->fotos[] = $imagen;
	}
    public function addTalle($talle) 
	{
		$this->talle[] = $talle;
	}
	public function addColor($color) 
	{
		$this->color[] = $color;
	}
    /* FIN TALLE Y COLOR */
	
    /* GETTER Y SETTER */
	public function &__get($propiedad)
    {
		return $this->$propiedad;
	}
	public function __set($propiedad, $valor)
    {
		if (!property_exists($this, $propiedad)) {
			throw new Exception('La propiedad <b>' . $propiedad . "</b> no existe.");
		}	
		$metodo = "set" . ucfirst($propiedad);
		
		if(method_exists($this, $metodo)) {
			$this->$metodo($valor);
		}
	}
	 /**
	 * Retorna si esta seteada la propiedad. Tuve que recurrir a este metodo porque 
     * al estar seteada en private las propiedades, el empty del Validator consideraba que no
	 * existia, pero cuando se llama a dicho método pasa por el __isset y verifica si esta seteada o no
	 * @return boolean 
	 */
	public function __isset($propiedad)
    {
		return isset($this->$propiedad);
	}
	public function setId($valor) 
    {
		$this->id = $valor;
    }
    public function setStock($valor) 
    {
		$this->stock = $valor;
    }
    public function setFotos($valor) 
    {
		$this->fotos = $valor;
    }
 }
?>