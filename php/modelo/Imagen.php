<?php 
require_once 'autoload.php';

class Imagen implements JsonSerializable
{ 
    public $nombre;
    public $tipo;
    public $size;
    public $ruta;
    public $rutaThumbnail;
    public $idProducto;
    public $orden;

    public function __construct($nombre, $tipo, $size, $ruta, $rutaThumbnail,
                                $idProducto, $orden = 0) 
    {
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->size = $size;
        $this->ruta = $ruta;
        $this->rutaThumbnail = $rutaThumbnail;
        $this->idProducto = $idProducto;
        $this->orden = $orden;
    }
    public function jsonSerialize() 
	{
		$json = array();
		
		return [
            'nombre' => $this->nombre,
            'tipo' => $this->tipo,
            'size' => $this->size,
			'ruta' => $this->ruta,
            'rutaThumbnail' => $this->rutaThumbnail,
			'idProducto' => $this->idProducto,
			'orden' => $this->orden
		];		
	}
    public static function insert($imagen)
    {
        $db = DBConnection::getConnection();
        
        try {
            $db->beginTransaction();
            
            $ruta = str_replace("..\\","", $imagen->ruta);
            $rutaThumbnail = str_replace("..\\","", $imagen->rutaThumbnail);

            $query = "INSERT INTO imagenes(nombre, tipo, size, ruta, 
                                            rutaThumbnail, idProducto, orden) 
                      VALUES(:nombre, :tipo, :size, :ruta, :rutaThumbnail, 
                            :idProducto, :orden)";

            $stmt = DBConnection::getStatement($query);									

            $stmt->bindParam(':nombre', $imagen->nombre, PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $imagen->tipo, PDO::PARAM_STR);
            $stmt->bindParam(':size', $imagen->size, PDO::PARAM_INT);
            $stmt->bindParam(':ruta', $ruta, PDO::PARAM_STR);
            $stmt->bindParam(':rutaThumbnail', $rutaThumbnail, PDO::PARAM_STR);
            $stmt->bindParam(':idProducto', $imagen->idProducto, PDO::PARAM_INT);
            $stmt->bindParam(':orden', $imagen->orden, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                throw new Exception("Error en el insertado de la imagen.");
            }
            
            $db->commit();
				 
            return $imagen;
        } catch (PDOException $e) {
            echo 'Error: '.$e->getMessage();
            $db->rollBack();
        }
    }
    public static function borrarImagen($ruta, $idProducto) 
    {
		try	{
			$query = "DELETE FROM imagenes WHERE idProducto = :idProducto AND ruta = :ruta";
					 
			$stmt = DBConnection::getStatement($query);
			
			$stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
			$stmt->bindParam(':ruta', $ruta, PDO::PARAM_STR);
			
			if (!$stmt->execute()) {
					throw new Exception("Error en el borrado de la foto.");
            }
            echo 'Imagen borrada con exito.';
				
		} catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
		}
	}
    public static function getFotos($idProducto)
    {
        $db = DBConnection::getConnection();
        
		try {
            $db->beginTransaction();
            
			$query = "SELECT nombre, tipo, size, ruta, rutaThumbnail, 
                             idProducto, orden
                      FROM imagenes
                      WHERE idProducto = :valor
                      ORDER BY orden";
											
            $stmt = DBConnection::getStatement($query);
            
            $stmt->bindParam(':valor', $idProducto, PDO::PARAM_INT);
		   
            if(!$stmt->execute()) {
				throw new Exception('Error al traer las imagenes');
			}
            
            $db->commit();
            
			$imagenes = [];
			
			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                
                $imagen = new Imagen($row['nombre'], $row['tipo'], $row['size'],
                                     $row['ruta'], $row['rutaThumbnail'], 
                                     $row['idProducto'], $row['orden']);
                
                $imagenes[] = $imagen;
			}
            
            return $imagenes;
            
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            $db->rollBack();
        }
    }
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
}
?>