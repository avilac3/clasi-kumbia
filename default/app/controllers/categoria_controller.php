<?php
/**
 * Controller por defecto si no se usa el routes
 * 
 */

ini_set('display_errors', 'Off');


class CategoriaController extends AppController    { 
    
    
    
     public function index($categoria,$page=1){

         
         $this->listClasificados = Load::model('clasificados')->getInnerJoinCategoria($categoria,$page,10); 
 

             
//    etiqueta title y description SEO
    $obtcategoria = Load::model('categorias')->find_by_nombre($categoria); // consulta sql filtrando por nombre ciudad
    $this->pageTitle = "Clasificados $obtcategoria->nombre";
    $this->pageDescription = "$obtcategoria->descripcion";
    $this->barrainfocate = "$obtcategoria->nombre";  // seo y obtener el nombre de categoria para paginar
    $this->categoria = $categoria;
 
        
    }
    
    
    
}