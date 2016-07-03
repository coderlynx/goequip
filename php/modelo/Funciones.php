<?php
require_once 'autoload.php';

class Atributo
{
    public $nombre;
    public $tipo;
    public $size;
    public $ruta;
    public $rutaThumbnail;
}
class Funciones
{
    /**
     * Valida formato, mueve imágenes y retorna un array con las rutas
     * @param array $files
     * @return array strings de rutas
     */
    public static function tratarImagenes($files) 
    {
        // Creo un array para almacenar las imagenes
        $imagenes = array();
        
        // Creo un array para almacenar errores
        $error = array();
        
        // Creo un array con los formatos permitidos
        $extensions_allowed = array("jpeg", "jpg", "png", "gif");
        
        // Obtengo nombre variable form
        $name = key($files);
        
        // Max Width & Height para imagen grande
        $maxWidth = 450;
        $maxHeight = 350;
        
        // Max Width & Height para imagen miniatura
        $minWidth = 100;
        $minHeight = 100;
        
        // Carpetas destino
        $destination = "../img/productos/450_350/";
        $destination_thumb = "../img/productos/thumbs/";

        foreach($files[$name]["tmp_name"] as $key => $tmp_name) {
             
            // Verifica si no viene algún archivo vacío dentro de $_FILES
            if ($files[$name]["name"][$key] != "") {
                // ["name"] --> nombre archivo + extensión
                $file_name = $files[$name]["name"][$key];

                // ["type"] --> MIME
                $type = $files[$name]["type"][$key];

                // ["tmp_name"] --> ubicación temporal donde se almacena
                $file_tmp = $files[$name]["tmp_name"][$key];

                // Extraigo la extensión del archivo
                $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

                // Si el formato de la extensión del archivo está permitido...
                if (in_array($file_ext, $extensions_allowed)) {

                    // Encapsulo atributos de cada imagen
                    $atributosImagen = new Atributo();
                    $atributosImagen->nombre = $files[$name]["name"][$key];
                    $atributosImagen->tipo = $files[$name]["type"][$key];
                    $atributosImagen->size = $files[$name]["size"][$key];

                    // Si el archivo no existe en la carpeta entonces hago dos copias
                    // Una copia irá a la carpeta '450x350' y la otra a 'thumbs'
                    if (!file_exists("$destination".$file_name)) {
                        $image = self::copiarImagen($file_tmp, "$destination".$file_name, $atributosImagen->ruta);
                        $image_thumb = self::copiarImagen($file_tmp, "$destination_thumb"."thumb_".$file_name, 
                                                          $atributosImagen->rutaThumbnail);
                    } else {
                        // Obtengo el componente de la ruta, sin la extensión
                        $filename = basename($file_name, $file_ext);

                        // Creo una copia del archivo con la fecha unix actual
                        $newFileName = $filename.time().".".$file_ext;
                        $newFileNameThumb = $filename.time().".".$file_ext;

                        // Muevo el archivo a la carpeta destino
                        $image = self::copiarImagen($file_tmp, "$destination".$newFileName, $atributosImagen->ruta);
                        $image_thumb = self::copiarImagen($file_tmp, "$destination_thumb"."thumb_".$newFileNameThumb, $atributosImagen->rutaThumbnail);
                    }

                    /* Resize de la imágen */
                    // Tratamiento de ambos archivos
                    self::resizeImage($destination.$image, $maxWidth, $maxHeight, $type, $atributosImagen->size);
                    self::resizeImage($destination_thumb.$image_thumb, $minWidth, $minHeight, $type);
                } else {
                    // Si el archivo no tiene un formato valido, almaceno el archivo en el array 'error'
                    // En principio no lo retorno...
                    array_push($error, "$file_name, ");
                    echo json_encode(var_dump($error));
                }

                array_push($imagenes, $atributosImagen);
            }
        }
        return $imagenes;
    }
    /*
     * @param string $file - archivo temporal
     * @param string $destination - ruta de destino + nombre del archivo
     * @param array $ruta - ruta de la imagen
     * @return string - nombre del nuevo archivo
     */
    private static function copiarImagen($file, $destination, &$ruta)
    {
        $partes_ruta = pathinfo($destination);

        if (!file_exists($partes_ruta['dirname'])) mkdir($partes_ruta['dirname']);
        
        copy($file, $destination);
        $ruta = $destination;
        return basename($destination);
    }
    /*
     * @param string $path - ruta del archivo a eliminar
     *
     */
    public static function borrarImagenFisica($path, $pathThumb)
    {
        // Defino ruta relativa
        $root = "../";
        
        $path = $root.$path;
        $pathThumb = $root.$pathThumb;
        
        $resultThumb = unlink($pathThumb);
        $result = unlink($path);
        
        if ($result) {
            echo "Imagen física eliminada.";
        } else {
            echo "La imagen no se pudo eliminar físicamente";
        }
    }
    /*
     * @param string $filename, example 'test.jpg'
     * @param int $width maximum width
     * @param int $height maximun height
     * @param string $type MIME
     * @return void
     */
    private static function resizeImage($filename, $width, $height, $type, &$newSize = null) 
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
        
        $newSize = filesize($filename);
    }
}
?>