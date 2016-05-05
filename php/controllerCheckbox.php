<?php
require_once 'autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	if (isset($_GET['provincias'])) {
		echo json_encode(AdministradorDeCheckbox::getProvincias(json_decode($_POST['provincias'])));
	}
	
	if ($_GET['check'] == "talles") {
        
		echo json_encode(AdministradorDeCheckbox::getTalles(json_decode($_GET['check'])));
	}
    
    if ($_GET['check'] == "colores") {
        
		echo json_encode(AdministradorDeCheckbox::getColores(json_decode($_GET['check'])));
	}


} 