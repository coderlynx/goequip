<?php 
require_once 'autoload.php';

class Imagen implements JsonSerializable
{ 
    public $ruta = array();
    public $idProducto;
    public $orden;

    public function __construct($ruta, $idProducto, $orden = 0) 
    {
        $this->ruta = $ruta;
        $this->idProducto = $idProducto;
        $this->orden = $orden;
    }
    public function jsonSerialize() 
	{
		$json = array();
		
		return [
			'ruta' => $this->ruta,
			'idProducto' => $this->idProducto,
			'orden' => $this->orden
		];		
	}
    public static function insert($imagen)
    {
        $db = DBConnection::getConnection();
        
        try {
            $db->beginTransaction();
            
            for ($i = 0; $i < count($imagen->ruta); $i++) {
                $ruta = str_replace("..\\","", $imagen->ruta[$i]);
                
                $query = "INSERT INTO imagenes (ruta, idProducto, orden) 
                VALUES(:ruta, :idProducto, :orden)";

                $stmt = DBConnection::getStatement($query);									

                $stmt->bindParam(':ruta', $ruta, PDO::PARAM_STR);
                $stmt->bindParam(':idProducto', $imagen->idProducto, PDO::PARAM_INT);
                $stmt->bindParam(':orden', $imagen->orden, PDO::PARAM_INT);

                if (!$stmt->execute()) {
                    throw new Exception("Error en el insertado de la imagen.");
                }
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
            
			$query = "SELECT *
				      FROM imagenes
                      WHERE idProducto = :valor
                      ORDER BY orden";
											
            $stmt = DBConnection::getStatement($query);
            
            $stmt->bindParam(':valor', $idProducto, PDO::PARAM_INT);
		   
            if(!$stmt->execute()) {
				throw new Exception('Error al traer las fotos');
			}
            $db->commit();
            
			$rutasFotos = [];
			
			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $rutasFotos[] = $row['ruta'];
			}
            return $rutasFotos;
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