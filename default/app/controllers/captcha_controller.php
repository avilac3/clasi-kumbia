<?php
Load::Lib("recaptcha");

class CaptchaController extends ApplicationController
{
        public function index()
        {    
//            Declaro la variable null al principio   
                $this->previousError = NULL;
		// Si vienen datos de formulario, los enviamos a la vista de nuevo
		if ($this->has_post('Datos')) $this->Datos = $this->post('Datos');
 
		// Comprobamos que se haya rellenado el reCAPTCHA
                if ($this->has_post("recaptcha_response_field"))
                {
			// Realizamos la comprobacion
                        $ret = reCaptcha::validate();
                        if ($ret->is_valid) {
				$this->Datos = $this->post('Datos');
				$this->render("validado");
			}
			// Enviamos el error a la vista
			$this->previousError = $ret->error;
                }
                
        }
}