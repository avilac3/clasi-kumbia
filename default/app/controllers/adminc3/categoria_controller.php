<?php
/*
 */
	      Load::models('categorias');

    class CategoriaController extends AppController{
         
public function index($page=1) 
    {
		$categoria = new Categorias();
		$this->results = $categoria ->getCategorias($page);                
    }
       
    
  public function crear()
    {
        if(Input::hasPost('categorias'))
       {	       
  
            $categoria = new categorias(); 	        
            if($categoria->guardar())
            {
                Flash::success('Categoria Creada Exitosamente');				
            }else Flash::error($categoria->error);			        	        		        
   
   //enrutando por defecto al index del controller
        return Router::redirect();            
        }        
   
        
    }
    
    
    
    
    
     /**
     * Edita un Registro
     *
     * @param int $id (requerido)
     */
    public function editar($id)
    {
        $categoria = new Categorias();
 
        //se verifica si se ha enviado el formulario (submit)
        if(Input::hasPost('categorias')){
 
            if($categoria->update(Input::post('categorias'))){
                 Flash::valid('Registro Editado Exitosamente');
                //enrutando por defecto al index del controller
                return Router::redirect();
            } else {
                Flash::error('Falló Operación');
            }
        } else {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->categorias = $categoria->find((int)$id);
        }
    }
    
    
    
  
    /**
     * Eliminar un menu
     * 
     * @param int $id (requerido)
     */
    public function borrar($id)
    {
         
            $categoria = new categorias(); 	        
        if ($categoria->delete((int)$id)) {
                Flash::valid('Registro Borrado Exitosamente');
        }else{
                Flash::error('Falló Operación'); 
        }
 
        //enrutando por defecto al index del controller
        return Router::redirect();
    }
    
    
 }

