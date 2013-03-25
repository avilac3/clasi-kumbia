<?php

class FotoController extends ApplicationController
{
    /**
     * Accion para subir foto
     *
     */
    
   public function subir()
    {
        if(Input::hasPost('clasificados'))
       {	       
	        
            $foto = Load::model('foto');	        
            if($foto->guardar())
            {
                Flash::success('Operación exitosa');				
            }else Flash::error($foto->error);			        	        		        
    						
        }        
    }
    
    
//    public function subir()
//    {
//        $foto = Load::model('foto');
//        $foto->guardar();
//    }
//    
//    
    
}