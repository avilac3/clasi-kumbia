<?php
class Banners extends ActiveRecord
{
   
    public $debug = FALSE;
    
    public function guardar()
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

                    if($this->save())
                    {                            
                            return TRUE;
                    }
                }
            }                
        }    		
    }
    
}