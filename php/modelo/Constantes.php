<?php
require_once 'autoload.php';

class Constantes {
    public static $ENTREGA_DOMICILIO = 2;
    public static $ENTREGA_SUCURSAL = 1;
    public static $ENTREGA_SUCURSAL_DESCRIPCION = "Sucursal";
    public static $ENTREGA_DOMICILIO_DESCRIPCION = "Domicilio";
    
    public static $PAGO_TARJETA = 1;
    public static $PAGO_TARJETA_DESCRIPCION = "Tarjeta";
    public static $PAGO_FACTURA = 2;
    public static $PAGO_FACTURA_DESCRIPCION = "Factura";
    
    public static $MES = [
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
    
}

?>