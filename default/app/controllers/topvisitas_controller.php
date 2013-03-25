<?php
/**
 * Controller por defecto si no se usa el routes
 * 
 */



class TopvisitasController extends AppController    { 
    
    
    
     public function index($page=1){

         
         $this->listClasificados = Load::model('clasificados')->getInnerJoinTopvisitas($page,10); 
         
 //    etiqueta title y description  SEO
        $this->pageTitle = "Los Clasificados Mas Visitados";
        $this->pageDescription = "encuentra los avisos mas visitados del sitio";
   
                    
        
    }
    
    
    
}