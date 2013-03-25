<?php
class Archivador
{
    /**
     * Guardar archivo
     *
     * @return boolean
     */
    public static function guardar()
    {
// Instancia con factory un objeto FileUpload
        $file = Upload::factory('archivo');
        
// Verifica si se subió el documento
        if(!$file->isUploaded()) {
            return FALSE;
        }

//le asignamos las extensiones a permitir
        $file->setExtensions(array('jpg', 'png', 'gif'));
// Tamaño máximo
        $file->setMaxSize('2MB');

// Guarda el archivo en el directorio "public/files/upload"
        if($file->save()) {
             Flash::valid('Operación Exitosa');
             return TRUE;
        }
 
        return FALSE;
    }
}