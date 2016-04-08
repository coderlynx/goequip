<?php

require_once 'autoload.php';
//session_start();
class Usuario implements JsonSerializable
{
	private $id;
	private $nombre;
	private $perfil;
	private $mail;
	private $fechaCreacion;

 
    public function __construct($id, $nombre, $perfil,$mail, $fechaCreacion=null)
    {
		$this->id = $id;
		$this->nombre = $nombre;
		$this->perfil = $perfil;
		$this->mail = $mail;
		$this->fechaCreacion = $fechaCreacion;
		

    }
	
	public function jsonSerialize() 
	{
		$json = array();
		
		//foreach($this as $key=>)
		return [
			'id' => $this->id,
			'nombre' => $this->nombre,
			'perfil' => $this->perfil,
			'mail' => $this->mail,
			'fechaCreacion' => $this->fechaCreacion
		];
		
	}
	 
	public function getAll()
	{
		
		try {
			$query = "SELECT us.id idUsuario, us.nombre nombreUsuario, us.mail,us.foto,us.baneado,
							 perfil.id idPerfil, perfil.descripcion descripcionPerfil
					  FROM usuarios us 
					  INNER JOIN tipo_usuario perfil
					  ON perfil.id = us.tipo";
			$stmt = DBConnection::getStatement($query);

			 if(!$stmt->execute()) {
				throw new Exception('Error al traer los usuarios');
			}
			
			$usuarios = [];
 
			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
				$perfil = new PerfilDeUsuario($row['idPerfil'],$row['descripcionPerfil']);
				$usuario = new Usuario($row['idUsuario'],$row['nombreUsuario'], $perfil, $row['mail'],null, $row['foto'],$row['baneado']);
				
				$usuarios[] = $usuario;
			}	
			
		return $usuarios;
			
		}catch(PDOException $e){
			
			print "Error!: " . $e->getMessage();
			
		}		
		
	}
	

	public function getById($idUsuario)
	{
		
		try {
			
			//$query = "SELECT * from usuarios WHERE nombre = :nombre AND password = :password";
			$query = "SELECT us.id idUsuario, us.nombre nombreUsuario, us.mail, us.foto,
							 perfil.id idPerfil, perfil.descripcion descripcionPerfil
					  FROM usuarios us 
					  INNER JOIN tipo_usuario perfil
					  ON perfil.id = us.tipo
					  WHERE us.id = :idUsuario";
			
			$stmt = DBConnection::getStatement($query);

			$stmt->bindParam(':idUsuario', $idUsuario,PDO::PARAM_INT);
			
			 if(!$stmt->execute()) {
				throw new Exception('Error al traer el usuario');
			}
			
			$usuarios = [];
 
			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
				$perfil = new PerfilDeUsuario($row['idPerfil'],$row['descripcionPerfil']);
				$usuario = new Usuario($row['idUsuario'],$row['nombreUsuario'], $perfil, $row['mail'], null, $row['foto']);
				
				$usuarios[] = $usuario;
			}	
			
		return $usuarios;
			
		}catch(PDOException $e){
			
			print "Error!: " . $e->getMessage();
			
		}		
		
	}
	
	public static function actualizar($usuario) {
		try{
			$db = DBConnection::getConnection();
			
			$db->beginTransaction();
						
			$query = "UPDATE usuarios SET mail = :mail, tipo = :tipo WHERE id = :id";
			
			$stmt = DBConnection::getStatement($query);
			
			$stmt->bindParam(':mail', $usuario->mail,PDO::PARAM_STR);
			$stmt->bindParam(':tipo', $usuario->perfil->id,PDO::PARAM_INT);
			$stmt->bindParam(':id', $usuario->id,PDO::PARAM_INT);
			
			if(!$stmt->execute()) {
				throw new Exception("Error al querer actualizar el usuario");
			}
			
		    $db->commit();
			echo "ok";
		} catch (PDOException $e)
			{
			  echo 'Error: ' . $e->getMessage();
			  $db->rollBack();
			}	
		
	}
	
	
	
	public static function actualizarPassword($pass_anterior, $pass_nueva, $idUsuario){
		
		try {
			
			$query = "SELECT * from usuarios WHERE id = :id";
			$stmt = DBConnection::getStatement($query);

			$stmt->bindParam(':id',$idUsuario,PDO::PARAM_INT);
			
			$stmt->execute();
 
			//si existe el usuario
			if($stmt->rowCount() == 1)
			{
				 $fila  = $stmt->fetch();
				 
				 $password_encriptado = password_hash($pass_nueva, PASSWORD_DEFAULT);

				if (password_verify($pass_anterior, $fila['password'])) {
					$db = DBConnection::getConnection();
			
					$db->beginTransaction();
								
					$query = "UPDATE usuarios SET password = :password WHERE id = :id";
					
					$stmt = DBConnection::getStatement($query);
					
					$stmt->bindParam(':password', $password_encriptado,PDO::PARAM_STR);
					$stmt->bindParam(':id', $idUsuario,PDO::PARAM_INT);
					
					if(!$stmt->execute()) {
						throw new Exception("Error al querer actualizar la clave");
					}
					
					$db->commit();
					
					echo "ok";
				} else {
					echo 'La clave anterior no coincide con la registrada.';
				}
				 
			}
		}catch(PDOException $e){
			
			print "Error!: " . $e->getMessage();
			
		}		
	}
	
		
	
	public static function eliminar($idUsuario) {
		try{
			$db = DBConnection::getConnection();
			
			$db->beginTransaction();
							
			//BORRO AL USUARIO Y/O OTRAS TABLAS EN LAS QUE APAREZCA
			$query = "DELETE FROM usuarios WHERE id = :id";
			
			$stmt = DBConnection::getStatement($query);
			
			$stmt->bindParam(':id', $idUsuario,PDO::PARAM_INT);
			
			if(!$stmt->execute()) {
				throw new Exception("Error al querer eliminar el usuario");
			}
			
		    $db->commit();
			echo "borrado exitosamente";
		} catch (PDOException $e)
			{
			  echo 'Error: ' . $e->getMessage();
			  $db->rollBack();
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
	
	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}
	
	public function setMail($mail) {
		$this->mail = $mail;
	}
	
	
}