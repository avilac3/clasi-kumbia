<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: 
 *
 * @category    
 * @package     Controllers 
 * @author      Carlos Avila
 * @copyright   Copyright (c) 2012 Servidigital Team (http://www.servidigital.co)
 * @version     1.0
 */



Load::model('clasificados');

class TwitterController extends AppController {
    

    
    public function index(){
        $this->clasificado = Load::model('clasificados')->getclasificadorandom();
        View::template(null);
 
    }  
    

}
