<?php
class Categorias extends ActiveRecord{
    
    public function getCategorias($page, $ppage=20)
    {
        return $this->paginate("page: $page", "per_page: $ppage", 'order: id desc');
    }
 

   
       
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
            $ruta = dirname(APP_PATH)."/public/img/upload/categorias";           
            $file->setPath($ruta);

            if($file->save($fileName))
            {

                if(!is_bool($fileName))
                {                 
                    $this->nombre = $_POST['categorias']['nombre'];
                    $this->descripcion = $_POST['categorias']['descripcion'];
                    $this->ruta = "upload/categorias/$fileName";

                    if($this->save())
                    {                            
                            return TRUE;
                    }
                }
            }                
        }    		
    }        
    
    
    
    
    
    
}



