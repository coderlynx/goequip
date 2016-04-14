<?php 
//session_start();
require_once 'autoload.php';

// Como no hay struct en PHP se utiliza una clase con todas sus variables públicas
class Domicilio { 
  public $id;
  public $calle;
  public $numero; 
  public $piso;
  public $depto;
  public $localidad; 
  public $provincia; 
  public $pais; 
  public $cp;
  
  public function __construct($calle, $numero, $piso, $depto, $localidad, $provincia, $pais, $cp) {
     $this->calle = $calle;
     $this->numero = $numero; 
     $this->piso = $piso;
     $this->depto = $depto; 
     $this->localidad = $localidad;
     $this->provincia = $provincia;
     $this->pais = $pais; 
     $this->cp = $cp;
  }
  
  public static $reglas = [
    'calle' => ['required'],
    'numero' => ['required'], 
    'localidad' => ['required'],
    'provincia' => ['required'],
    'pais' => ['required'],
    'cp' => ['required']
  ];
}
?>