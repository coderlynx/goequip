<?php /** 
* Esta clase permite manejar funciones basicas de un carrito de 
* compras, esta desarrollada usando el patron de diseño iterator 
* para orfecer mayor encapsulamiento y orden en los datos 
* para utilizarla deben tener una clase producto que contenga 
* el atributo "id" representando la identificacion del producto 
* 
*/  

require_once 'autoload.php';

  
class Carrito  {  
/** 
* Viene representando la lista de productos del carrito 
* @var Collection 
*/  
var $productos;
var $productosArray;
var $valorTotal;
var $cantidadTotal;
  
function Carrito()  
{  
    $this->productos = new Collection('Producto');
    $this->productosArray = array();
    $this->valorTotal = 0.00;
    $this->cantidadTotal = 0;
}  
  
/** 
* Esta funcion devuelve un producto a partir del codigo 
* @param $codigo 
* @return Producto 
*/  
public function getProducto($id)  
{  
$iterator = $this->productos->getIterator();  
while($iterator->valid())  
{  
 $prod = $iterator->current();  
  
 if($prod->id == $id)  
 {  
  return $prod;  
 }  
  
 $iterator->next();  
}  
  
return null;  
}  
  
/** 
* Permite agregar un producto al carrito de compras 
* @param $producto 
* @return null 
*/  
public function addProducto($producto)  
{  
$prod = $this->getProducto($producto->id);  
if($prod) $prod->stock++;  
else  
 $this->productos->add($producto);  
}  
  
/** 
* Elimina un producto del carrito, a partir de un codigo 
* @param $codigo 
* @return null 
*/  
public function removeProducto($id)  
{  
    $existe = false;  
    $iterator = $this->productos->getIterator();  
    while($iterator->valid())  
    {  
     $prod = $iterator->current();  
     if($prod->id == $id)  
     {  
      if($prod->stock > 1)  
       $prod->stock--;  
      else  
      {  
       $this->productos->remove($iterator->key());  
      }  
     }  
     $iterator->next();  
    }  
}
    
public function showProductos() {
   
    $this->productosArray = [];
    $iterator = $this->productos->getIterator();  
    while($iterator->valid())  
    {  
        $this->productosArray[] = $iterator->current();  

        $iterator->next();  
    }  
    
    return $this->productosArray;
}
    
public function calculateMontoTotal() {
   
    $this->valorTotal = 0;
    $iterator = $this->productos->getIterator();  
    
    while($iterator->valid())  
    {  
        $prod = $iterator->current();  
        //$this->productosArray[] = $iterator->current();  
        $this->valorTotal += ($prod->stock * $prod->precio);
        $iterator->next();  
    }  
       return $this->valorTotal;
}
    
public function calculateCantidadTotal() {

    $this->cantidadTotal = 0;
    $iterator = $this->productos->getIterator();  

    while($iterator->valid())  
    {  
        $prod = $iterator->current();  
        //$this->productosArray[] = $iterator->current();  
        $this->cantidadTotal += $prod->stock;
        $iterator->next();  
    }  
   return $this->cantidadTotal;
}
  
/** 
* Se utiliza solo con fines de desarrollo, imprime la lista 
* de productos del carrito, se puede modificar para imprimir 
* toda la informacion de cada producto 
* @return String 
*/  
public function trace()  
{  
$out = "";  
$iterator = $this->productos->getIterator();  
while($iterator->valid())  
{  
 $prod = $iterator->current();  
  
 $out .= "Producto ".$prod->modelo." -> ".$prod->stock."  
";  
  
 $iterator->next();  
}  
  
return $out;  
}  
  
}  

?>