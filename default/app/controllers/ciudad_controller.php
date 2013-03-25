<?php
/**
 * Controller por defecto si no se usa el routes
 * 
 */

class CiudadController extends AppController    {
    
    
     public function index($ciudad,$page=1){

         
         $this->listClasificados = Load::model('clasificados')->getInnerJoinCiudad($ciudad,$page,10); 
         
//         $this->categorias = Load::model('categorias')->find();
//         $this->ciudades = Load::model('ciudades')->find();
         

       
//    etiqueta title y description SEO
    $obtciudad = Load::model('ciudades')->find_by_nombre($ciudad); // consulta sql filtrando por nombre ciudad
    $this->pageTitle = "Clasificados $obtciudad->nombre";
    $this->pageDescription = "$obtciudad->descripcion";
    $this->barrainfociudad = "$obtciudad->nombre";
    $this->ciudad = $ciudad;
    
    
  
                    
        
    }
}