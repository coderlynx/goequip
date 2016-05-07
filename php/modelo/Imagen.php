<?php 
//session_start();
require_once 'autoload.php';

// Como no hay struct en PHP se utiliza una clase con todas sus variables públicas
class Imagen { 
  public $id;
  public $ruta;
  public $idProducto;
  public $orden; 

  public function __construct($ruta, $idProducto, $orden) {
    $this->ruta = ruta;
    $this->idProducto = idProducto;
    $this->orden = orden;
  }
}
?>