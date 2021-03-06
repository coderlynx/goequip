<?php 
//
require_once 'autoload.php';
//session_start();

 class Pedido  implements JsonSerializable { 
	private $id;
	private $nroPedido;
    private $idCliente;
	private $total;
    private $cantidad;
	private $formaDePago;
	private $formaDeEnvio;
	private $estadoDePago;
	private $productos;
    private $fecha;
    
	
	public function __construct($id=null, $nroPedido, $idCliente,$total,$cantidad, $formaDePago, $formaDeEnvio, $estadoDePago, $productos=null, $fecha=null) {
       $this->id = $id;
	   $this->nroPedido = $nroPedido;
	   $this->idCliente = $idCliente;
	   $this->total = $total;
	   $this->cantidad = $cantidad;
	   $this->formaDePago = $formaDePago;
	   $this->formaDeEnvio = $formaDeEnvio;
	   $this->estadoDePago = $estadoDePago;
	   $this->productos = $productos;
       $this->fecha = $fecha;
	  
    }
	
	public static $reglas = [
		'nroPedido' => ['required'],
		'idCliente' => ['required'],
		'total' => ['required'],
		'cantidad' => ['required'],
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
			'nroPedido' => $this->nroPedido,
			'idCliente' => $this->idCliente,
			'total' => $this->total,
			'cantidad' => $this->cantidad,
			'formaDePago' => $this->formaDePago,
			'formaDeEnvio' => $this->formaDeEnvio,
			'estadoDePago' => $this->estadoDePago,
			'productos' => $this->productos,
			'fecha' => $this->fecha,
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
            
                //valido que no exista un pedido con el mismo numero
                $query = "SELECT count(*) as pedido from pedidos where nroPedido = :nroPedido";

                $stmt = DBConnection::getStatement($query);

                $stmt->bindParam(':nroPedido', $pedido->nroPedido,PDO::PARAM_INT);

                if(!$stmt->execute()) {
                    throw new Exception("Error en buscar el pedido.");
                }
            
                 while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                    if ($row["pedido"] == 1) die("Ya existe ese pedido");
                 }
				
                //INSERTO EL PEDIDO
				$query = "INSERT INTO pedidos (idCliente, nroPedido, total, cantidad, formaDePago, formaDeEnvio, estadoDePago, fecha)
				VALUES(:idCliente, :nroPedido, :total, :cantidad, :formaDePago, :formaDeEnvio, :estadoDePago, :fecha)";

				$stmt = DBConnection::getStatement($query);
															
				$stmt = Pedido::bindearDatos($stmt, $pedido);
                			 
				if(!$stmt->execute()) {
					throw new Exception("Error en el insertado del pedido.");
				}

				$id = $db->lastInsertId();

				$pedido->setId($id);
            
                //INSERTO EL DETALLE DEL PEDIDO
                foreach($pedido->productos as $prod) {
                    $query = "INSERT INTO detallepedido (idPedido, idProducto,cantidad,precio,talle,color) values (:idPedido, :idProducto, :cantidad, :precio,:talle,:color)";

                    $stmt = DBConnection::getStatement($query);
                    
                    //por ahora lo dejo asi porque viene del carrito que muestra el nombre del talle y color
                    $idColor = array_search($prod->color, Constantes::$COLOR);
                    $idTalle = array_search($prod->talle, Constantes::$TALLE);

                    $stmt->bindParam(':idPedido', $id,PDO::PARAM_INT );
                    $stmt->bindParam(':idProducto', $prod->id,PDO::PARAM_INT );
                    $stmt->bindParam(':cantidad', $prod->stock,PDO::PARAM_INT );
                    $stmt->bindParam(':precio', $prod->precio,PDO::PARAM_STR );
                    $stmt->bindParam(':talle', $idTalle,PDO::PARAM_INT );
                    $stmt->bindParam(':color', $idColor,PDO::PARAM_INT );

                    if(!$stmt->execute()) {
                        throw new Exception("Error en el insertado del detalle del pedido");
                    }
                    
                    Producto::updateStock($prod);
                    
                }
            
            
                //Inserto la entrega
                $entrega = new Entrega(null, $pedido->formaDeEnvio->costo, $pedido->formaDeEnvio->idZona, $pedido->nroPedido, null);
            
                Entrega::insert($entrega);

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
		 $stmt->bindParam(':cantidad', $pedido->cantidad,PDO::PARAM_INT);
		 $stmt->bindParam(':formaDePago', $pedido->formaDePago,PDO::PARAM_INT);
		 $stmt->bindParam(':formaDeEnvio', $pedido->formaDeEnvio->idZona,PDO::PARAM_INT);
         $stmt->bindParam(':estadoDePago', $pedido->estadoDePago,PDO::PARAM_STR);
		 $stmt->bindParam(':fecha',  $fecha);
		
		 return $stmt;
	}
	
	
	/**
	 * Retorna un mensaje si fue eliminado correctamente o una excepcion
	 *
	 * @return  String el mensaje
	 */
   	public static function delete($idPedido){
		try{
			$db = DBConnection::getConnection();
			
			$db->beginTransaction();
			
			//BORRO EL PRODUCTO
			$query = "DELETE FROM pedidos WHERE id = :id";
		
			$stmt = DBConnection::getStatement($query);
			
			$stmt->bindParam(':id', $idPedido,PDO::PARAM_INT);
			
			if(!$stmt->execute()) {
				throw new Exception("Error al eliminar el pedido");
			}
		   
		    $db->commit();
			echo "Pedido eliminado correctamente";
		} catch (PDOException $e)
			{
			  echo 'Error: ' . $e->getMessage();
			  $db->rollBack();
			}	
    }
   
   	/**
	 * Retorna un pedido especifico, buscado por id. De no encontrar retorna una excepcion
	 *
	 * @return  Producto  buscado
	 */
    public static function getById($id){
		try{
            
            $query = "SELECT ped.id, ped.nroPedido, ped.total, ped.cantidad, ped.formaDePago, ped.formaDeEnvio, ped.estadoDePago,  ped.fecha, 
                     cli.nombre nombre, cli.apellido apellido,
                     detPed.cantidad, detPed.precio precioUnitario, detPed.talle, detPed.color,
                     prod.modelo
					 FROM pedidos ped 
                     INNER JOIN clientes cli ON cli.id = ped.idCliente
                     INNER JOIN detallepedido detPed ON detPed.idPedido = ped.id
                     INNER JOIN productos prod ON prod.id = detPed.idProducto
                     WHERE ped.id = :id";
											
		    $stmt = DBConnection::getStatement($query);
		   
            $stmt->bindParam(':id', $id,PDO::PARAM_INT);

		   if(!$stmt->execute()) {
				throw new Exception('Error al traer el pedido');
			}
		   	   
			$productos = array();
            
            $idPedido = 0;
			
			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                
                if($idPedido != $row['id'] ) {
                    
                    $cliente = $row['apellido'] . ", " . $row['nombre'];
                    $pedido = new Pedido($row['id'], $row['nroPedido'], $cliente, $row['total'], $row['cantidad'], $row['formaDePago'], $row['formaDeEnvio'], $row['estadoDePago'], null, $row['fecha']);               
                }
                                        
                $producto = new Producto(null, $row['modelo'], '', '', Constantes::$TALLE[$row['talle']], Constantes::$COLOR[$row['color']], $row['cantidad'], $row['precioUnitario']); 
                
                $productos[] = $producto;
                
                $idPedido = $row['id'];
			}
            
            $pedido->productos = $productos;
            
			 return $pedido;


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
			 
			$query = "SELECT ped.id, ped.nroPedido, ped.total, ped.cantidad, ped.formaDePago, ped.formaDeEnvio, ped.estadoDePago,  ped.fecha,
                     cli.nombre nombre, cli.apellido apellido
					 FROM pedidos ped INNER JOIN clientes cli ON cli.id = ped.idCliente";
											
		   $stmt = DBConnection::getStatement($query);
		   

		   if(!$stmt->execute()) {
				throw new Exception('Error al traer los pedidos');
			}
		   	   
			$pedidos = array();
            //$formaDePago = "T.C";
            //$formaDeEnvio = "Sucursal";
			
			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {

                //if ($row['formaDePago'] != Constantes::$PAGO_TARJETA) $formaDePago = "Factura";
                //if ($row['formaDeEnvio'] != Constantes::ENTREGA_SUCURSAL) $formaDeEnvio = "Domicilio";
                
               
                
                //$cliente = $row['apellido'] . ", " . $row['nombre'];
                //$pedido = new Pedido($row['id'], $row['nroPedido'], $cliente, $row['total'], $formaDePago, $formaDeEnvio, $row['estadoDePago'], null, $row['fecha']);

                $pedidos[] =  Pedido::armarPedido($row);// $pedido;

			}
		
			 return $pedidos;


	   } catch(PDOException $e)
			{
			  echo 'Error: ' . $e->getMessage();
			}
    }
     
    private static function armarPedido($row) {
        
        if ($row['formaDePago'] == Constantes::PAGO_TARJETA) {
            $formaDePago = Constantes::PAGO_TARJETA_DESCRIPCION;
        } else {
             $formaDePago = Constantes::PAGO_FACTURA_DESCRIPCION;
        }
        
        if ($row['formaDeEnvio'] == Constantes::ENTREGA_SUCURSAL) {
            $formaDeEnvio = Constantes::ENTREGA_SUCURSAL_DESCRIPCION;
        } else {
            $formaDeEnvio = Constantes::ENTREGA_DOMICILIO_DESCRIPCION;
        }
        
        $cliente = $row['apellido'] . ", " . $row['nombre'];        
        $pedido = new Pedido($row['id'], $row['nroPedido'], $cliente, $row['total'], $row['cantidad'], $formaDePago, $formaDeEnvio, $row['estadoDePago'], null, $row['fecha']);
        
        return $pedido;
        
    }
     
    public static function validateStockDelPedido($productos) {
         //INSERTO EL DETALLE DEL PEDIDO
        foreach($productos as $prod) {
            Producto::validateStock($prod);
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
     
     public function setProductos($valor) {
		$this->productos = $valor;
    }
	
	
 }
	
?>