<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class BuscarController extends AppController    {
    
    public function index($busqueda){
        
        //    Obtener lista de clasficados con la busqueda
$this->listClasificados = Load::model('clasificados')->getInnerJoinClasificadosBusqueda($busqueda,$page,10);  
 
        
    }

    
}




?>


