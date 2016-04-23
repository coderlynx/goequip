<?php
require_once 'autoload.php';

class Constantes {
    const ENTREGA_DOMICILIO = 2;
    const ENTREGA_SUCURSAL = 1;
    const ENTREGA_SUCURSAL_DESCRIPCION = "Sucursal";
    const ENTREGA_DOMICILIO_DESCRIPCION = "Domicilio";
    
    const PAGO_TARJETA = 1;
    const PAGO_TARJETA_DESCRIPCION = "Tarjeta";
    const PAGO_FACTURA = 2;
    const PAGO_FACTURA_DESCRIPCION = "Factura";
    
    const MES = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    ];
    
    const TALLE = [
        1 => 'L',
        2 => 'M',
        3 => 'S',
        4 => 'XS'
    ];
        
    const COLOR = [
        1 => 'Blanco',
        2 => 'Azul',
        3 => 'Negro'
    ];
    
    
}

?>