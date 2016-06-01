<?php
require_once 'autoload.php';

class Funciones
{
    /**
     * Valida formato, mueve imágenes y retorna un array con las rutas
     * @param array $imagenes
     * @return array strings de rutas
     */
    public static function moverImagenes($imagenes) 
    {
        // Creo un array para almacenar las rutas
        $rutasImagenes = array();
        // Creo un array para almacenar errores
        $error = array();
        // Creo un array con los formatos permitidos
        $extensions_allowed = array("jpeg", "jpg", "png", "gif");
        // Obtengo nombre variable form
        $name = key($imagenes);
        // Defino Max Width & Height
        $maxWidth = 450;
        $maxHeight = 350;

        foreach($imagenes[$name]["tmp_name"] as $key => $tmp_name) {
            // ["name"] --> nombre archivo + extensión
            $file_name = $imagenes[$name]["name"][$key];
            // ["type"] --> MIME
            $type = $imagenes[$name]["type"][$key];
            // ["tmp_name"] --> ubicación temporal donde se almacena
            $file_tmp = $imagenes[$name]["tmp_name"][$key];
            // Extraigo la extensión del archivo
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            // Carpeta destino
            $destination = "..\\img\\productos\\";

            // Si el formato de la extensión del archivo está permitido...
            if (in_array($file_ext, $extensions_allowed)) {
                // Si el archivo no existe en la carpeta entonces lo muevo del temporal
                if (!file_exists("$destination".$file_name)) {
                    move_uploaded_file($file_tmp, "$destination".$file_name);
                    array_push($rutasImagenes, "$destination".$file_name);
                    $image = $file_name;
                } else {
                    // Obtengo el componente de la ruta, sin la extensión
                    $filename = basename($file_name, $file_ext);
                    // Creo una copia del archivo con la fecha unix actual
                    $newFileName = $filename.time().".".$file_ext;
                    // Muevo el archivo a la carpeta destino
                    move_uploaded_file($file_tmp, "$destination".$newFileName);
                    array_push($rutasImagenes, "$destination".$newFileName);
                    $image = $newFileName;
                }
                // Resize de la imágen
                // Tratamiento archivo
                self::resizeImage($destination.$image, $maxWidth, $maxHeight, $type);
            } else {
                // Si el archivo no tiene un formato valido, almaceno el archivo en el array 'error'
                // En principio no lo retorno...
                array_push($error, "$file_name, ");
                echo json_encode(var_dump($error));
            }
        }
        return $rutasImagenes;
    }
    /*
     * @param string $filename, example 'test.jpg'
     * @param int $width maximum width
     * @param int $height maximun height
     * @param string $type MIME
     * @return void
     */
    private static function resizeImage($filename, $width, $height, $type) 
    {
        // Content type
        //header('Content-Type: '.$type);

        // Get new dimensions
        list($width_orig, $height_orig) = getimagesize($filename);

        $ratio_orig = $width_orig / $height_orig;

        if ($width / $height > $ratio_orig) {
           $width = $height * $ratio_orig;
        } else {
           $height = $width / $ratio_orig;
        }

        // Resample
        $image_p = imagecreatetruecolor($width, $height);

        if ($type == 'image/png') {
            $image = imagecreatefrompng($filename);
            imagesavealpha($image, true);
            imagesavealpha($image_p, true);
            $trans_colour = imagecolorallocatealpha($image_p, 0, 0, 0, 127);
            imagefill($image_p, 0, 0, $trans_colour);
        }
        elseif ($type == 'image/jpg') {$image = imagecreatefromjpeg($filename); }
        elseif ($type == 'image/jpeg') {$image = imagecreatefromjpeg($filename); }
        elseif ($type == 'image/pjpeg') {$image = imagecreatefromjpeg($filename); }

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        // Output
        if ($type == 'image/png') {$result = imagepng($image_p, $filename); }
        elseif ($type == 'image/jpg') {$result = imagejpeg($image_p, $filename); }
        elseif ($type == 'image/jpeg') {$result = imagejpeg($image_p, $filename); }
        elseif ($type == 'image/pjpeg') {$result = imagejpeg($image_p, $filename); }
    }
}
?>