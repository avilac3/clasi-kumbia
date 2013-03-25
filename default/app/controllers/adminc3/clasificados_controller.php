<?php
/*
 * @author Carlos 
 */
    
//cargamos modelo
Load::models('clasificados');
    
    class ClasificadosController extends AppController{
    
//        obtiene una lista para paginar
        public function index($page=1) 
    {        
        $clasificado = new Clasificados();
        $this->listClasificados = $clasificado->getClasificados($page);
    }  
    
    
    
    
    public function ver($id) 
    {        
        $this->clasificado = Load::model("clasificados")->find($id);
    }
    
    
    
   /**
     * Edita un Registro
     *
     * @param int $id (requerido)
     */
    public function editar($id)
    {
        $clasificado = new Clasificados();
 
        //se verifica si se ha enviado el formulario (submit)
        if(Input::hasPost('clasificados')){
 
            if($clasificado->update(Input::post('clasificados'))){
                 Flash::valid('Operación exitosa');
                //enrutando por defecto al index del controller
                return Router::redirect();
            } else {
                Flash::error('Falló Operación');
            }
        } else {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->clasificados = $clasificado->find((int)$id);
        }
    }
    
    
     
    
     /**
     * Eliminar un menu
     * 
     * @param int $id (requerido)
     */
    public function borrar($id)
    {
        $clasificado = new Clasificados();
        if ($clasificado->delete((int)$id)) {
                Flash::valid('Operación exitosa');
        }else{
                Flash::error('Falló Operación'); 
        }
 
        //enrutando por defecto al index del controller
        return Router::redirect();
    }
    
    
    
    
    
    
    } 
