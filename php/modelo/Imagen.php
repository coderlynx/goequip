<?php 
//session_start();
require_once 'autoload.php';

// Como no hay struct en PHP se utiliza una clase con todas sus variables públicas
class Imagen { 
  public $id;
  public $ruta;
  public $orden; 
  public $idProducto;
  
  public function __construct($ruta, $orden, $idProducto) {
     $this->ruta = ruta;
     $this->orden = orden; 
     $this->idProducto = idProducto;
  }
}
?>