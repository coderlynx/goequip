<?php 
//
require_once 'autoload.php';
//session_start();

 class Reporte { 

   
   	/**
	 * Retorna los datos de ventas mensuales entre fechas
	 *
	 * @return  Producto  buscado
	 */
    public static function getVentasMensuales($filtros){
		try{
            
            $query = "SELECT SUM(total) as Total, MONTH(fecha) as Mes 
                    FROM pedidos 
                    WHERE fecha BETWEEN :fechaDesde AND :fechaHasta
                    GROUP BY MONTH(fecha) 
                    ORDER BY MONTH(fecha)";
											
            $stmt = Reporte::ejecutarQuery($query, $filtros);
            
            $datos = array();
            
            //$bool = new Constantes;
              //  die(json_encode($bool::TALLE));

			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                
                $un_registro = new StdClass;
                
                $un_registro->mes = Constantes::MES[$row['Mes']];
                $un_registro->total = $row['Total'];
                
                $datos[] = $un_registro;
			}
            
            return $datos;


		   } catch(PDOException $e)
			{
			  echo 'Error: ' . $e->getMessage();
			}
    }
     
    /**
	 * Retorna los datos de ventas diarios entre fechas
	 *
	 * @return  Producto  buscado
	 */
    public static function getVentasDiarias($filtros){
		try{
            
            $query = "SELECT SUM(total) as Total, DATE(fecha) as Dia 
                    FROM pedidos 
                    WHERE fecha BETWEEN :fechaDesde AND :fechaHasta
                    GROUP BY DATE(fecha) 
                    ORDER BY DATE(fecha)";
											
		    
            $stmt = Reporte::ejecutarQuery($query, $filtros);
            
            $datos = array();

			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                
                $un_registro = new StdClass;
                $un_registro->mes = $row['Dia'];
                $un_registro->total = $row['Total'];
                
                $datos[] = $un_registro;
			}
            
            return $datos;

		   } catch(PDOException $e)
			{
			  echo 'Error: ' . $e->getMessage();
			}
    }
     
     /**
	 * Retorna los datos de ventas semanales entre fechas
	 *
	 * @return  datos
	 */
    public static function getVentasSemanales($filtros){
		try{
            
            $query = "SELECT SUM(total) as Total, WEEK(fecha) as Semana 
                    FROM pedidos 
                    WHERE fecha BETWEEN :fechaDesde AND :fechaHasta
                    GROUP BY WEEK(fecha) 
                    ORDER BY WEEK(fecha)";
											
		    
            $stmt = Reporte::ejecutarQuery($query, $filtros);
            
            $datos = array();

			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                
                $un_registro = new StdClass;
                $un_registro->mes = $row['Semana'];
                $un_registro->total = $row['Total'];
                
                $datos[] = $un_registro;
			}
            
            return $datos;

		   } catch(PDOException $e)
			{
			  echo 'Error: ' . $e->getMessage();
			}
    }
     
    /**
	 * Retorna los datos de ventas anuales entre fechas
	 *
	 * @return  datos  buscado
	 */
    public static function getVentasAnuales($filtros){
		try{
            
            $query = "SELECT SUM(total) as Total, YEAR(fecha) as Año 
                    FROM pedidos 
                    WHERE fecha BETWEEN :fechaDesde AND :fechaHasta
                    GROUP BY YEAR(fecha) 
                    ORDER BY YEAR(fecha)";
											
		    
            $stmt = Reporte::ejecutarQuery($query, $filtros);
            
            $datos = array();

			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                
                $un_registro = new StdClass;
                $un_registro->mes = $row['Año'];
                $un_registro->total = $row['Total'];
                
                $datos[] = $un_registro;
			}
            
            return $datos;

		   } catch(PDOException $e)
			{
			  echo 'Error: ' . $e->getMessage();
			}
    }
     
     private static function ejecutarQuery($query, $filtros) {
         
            $stmt = DBConnection::getStatement($query);
		   
            $stmt->bindParam(':fechaDesde',  $filtros->fechaDesde);
            $stmt->bindParam(':fechaHasta',  $filtros->fechaHasta);

		   if(!$stmt->execute()) {
				throw new Exception('Error al traer datos');
			}
		   	   
         return $stmt;
         
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
	
	
	
 }
	
?>