<?php
 
class ArchivosController extends AppController {
   
  public function index() {
        View::select('index');  //para mostrar siempre la vista con los formularios
        if (Input::hasPost('oculto')) {  //para saber si se envió el form
 
            //llamamos a la libreria y le pasamos el nombre del campo file del formulario
            //el segundo parametro de Upload::factory indica que estamos subiendo una imagen
            //por defecto la libreria Upload trabaja con archivos...
            $archivo = Upload::factory('archivo', 'image'); 
            $archivo->setExtensions(array('jpg', 'png', 'gif'));//le asignamos las extensiones a permitir
            if ($archivo->isUploaded()) {
                
                echo $_FILES['archivo']['name'];
                if ($archivo->save()) {

                    Flash::valid('Imagen subida correctamente...!!!');
                }
            }else{
                    Flash::warning('No se ha Podido Subir la imagen...!!!');
            }
        }
    }
 
}