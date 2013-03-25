<?php
/**
 * Modelo para subir fotos
 *
 */
 
// Carga la libreria Upload
Load::lib('upload');
 
class Foto extends ActiveRecord
{
    /**
     * Guarda el documento
     *
     * @return boolean
     */
   
    
    public function subir()
    {        
        $file = Upload::factory('archivo','image');   
        if($file->isUploaded()) 
        {	                    
            $file->setMaxSize('2MB');// Tamaño máximo
            // Tipos de archivos permitidos
            //$file->setTypes(array('image/jpeg'));            
            // Extensiones permitidas
            //$file->setExtensions(array('jpg'));           
            $fileName = $_FILES["archivo"]["name"];
            $ruta = dirname(APP_PATH)."/public/img/upload/";           
            $file->setPath($ruta);

            if($file->save($fileName))
            {

                if(!is_bool($fileName))
                {                 
                    $this->tamanio = $_FILES["archivo"]["size"];    	 	
                    $this->nombre = $_FILES["archivo"]["name"];
                    $this->ruta = "upload/$fileName";     	 	
                    $this->titulo = $_POST['banners']['titulo'];
                    $this->descripcion = $_POST['banners']['descripcion'];
                    $this->estatus = 0;

                    if($this->save())
                    {                            
                            return TRUE;
                    }
                }
            }                
        }    		
    }
    
    
//    public function guardar()
//    {
//        // Instancia con factory un objeto ImageUpload
//        $img = Upload::factory('foto', 'image');
// 
//        // Verifica si se subió la imagen
//        if(!$img->isUploaded()) {
//            return FALSE;
//        }
// 
//        // Tamaño máximo
//        $img->setMaxSize('2MB');
// 
//        // Tipos de imagenes permitidas
//        $img->setTypes(array('jpg', 'png'));
// 
//        // Extensiones permitidas
//        $img->setExtensions(array('jpg', 'png'));
// 
//        // Guarda la imagen
//        $carga = $upload->saveRandom();
//        if($carga){
//            return $carga; //para que retorne el nombre que se va a guardar en la bd
//        }
//        
//        
//        
//        if($img->saveRandom()) {
//             
//            Flash::valid('Operación Exitosa');
//            return TRUE;
//        }
// 
//        return FALSE;
//    }
//
//    
//    /* Ajuste al método saveRandom */
// 
//        /**
//     * Guarda el archivo con un nombre aleatorio
//     * 
//     * @return string | boolean nombre de archivo generado o FALSE si falla
//     */
//    public function saveRandom() {
//                //Se verifica la extension del archivo cargado
//                $ext = substr(strrchr($_FILES[$this->_name]['name'], "."),1);
//        // Genera el nombre de archivo
//        $name = md5(time()).".$ext";
//         
//        // Guarda el archivo
//        if($this->save($name)) {
//            return $name;
//        }
//         
//        return FALSE;
//    }
    
    
    
    
}