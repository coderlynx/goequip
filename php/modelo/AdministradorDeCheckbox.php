<?php
require_once 'autoload.php';

class AdministradorDeCheckbox
{
		
	public static function getProvincias() {
		try {
			 
			/*$stmt = AdministradorDeCombos::ejecutarConsulta('provincias');
			
			$provincias = array();
						
			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
								
				$provincia = new Provincia($row['id'],$row['descripcion']);

				$provincias[] = $provincia;
			
			}
		
			return $provincias;*/


	   } catch(PDOException $e)
			{
			  echo 'Error: ' . $e->getMessage();
			}
		
	}
	
    
    public static function getTalles() {
			try {
				 
				$stmt = self::ejecutarConsulta('talles');
				
				$talles = array();
							
				while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
									
					$unTalle = new Talle($row['id'],$row['descripcion']);

					$talles[] = $unTalle;
				
				}
			
				return $talles;


		   } catch(PDOException $e)
				{
				  echo 'Error: ' . $e->getMessage();
				}
			
	}
    
	public static function getColores() {
			try {
				 
				$stmt = self::ejecutarConsulta('colores');
				
				$colores = array();
							
				while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
									
					$unColor = new Color($row['id'],$row['descripcion']);

					$colores[] = $unColor;
				
				}
			
				return $colores;


		   } catch(PDOException $e)
				{
				  echo 'Error: ' . $e->getMessage();
				}
			
	}
	

	private static function ejecutarConsulta ($nombre) {
		$query = "SELECT * FROM " . $nombre;

			$stmt = DBConnection::getStatement($query);
		   
			if(!$stmt->execute()) {
				throw new Exception("Error en traer los " .$nombre);
			}
		   	   
		return $stmt;
	}
	

}