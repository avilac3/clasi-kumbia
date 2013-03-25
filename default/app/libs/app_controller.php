<?php
/**
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 **/

// @see Controller nuevo controller
require_once CORE_PATH . 'kumbia/controller.php';

class AppController extends Controller {

	final protected function initialize()
	{
          if ($this->module_name == 'adminc3') {
                View::template("adminc3"); 
            }
	}

	final protected function finalize()
	{
            
	}
}