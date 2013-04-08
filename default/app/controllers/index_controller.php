<?php
/**
 * Controller por defecto si no se usa el routes
 * 
 */

ini_set('display_errors', 'Off');

Load::Lib("recaptcha");

class IndexController extends AppController    {
    

    
//        obtiene una lista para paginar
        public function index($page=1) 
    {
//    etiqueta title y description  
    $this->pageTitle = 'clasificados gratis  avisoya  Clasificados colombia';
    $this->pageDescription = 'encuentra casas, automoviles, empleos y mucho mas clasificados gratis faciles y sencillos  clasificados neiva';




//    Obtener lista de clasficados con el join
$this->listClasificados = Load::model('clasificados')->getInnerJoinClasificados($page,10);  
    
 
//Hago save a mi aviso con llave de seguridad 
$this->previousError = "";
if(Input::hasPost('clasificados')){   
    if(SecurityKey::isValid()) {
       if (Input::hasPost('recaptcha_response_field')){
            // Realizamos la comprobacion
            $ret = reCaptcha::validate();
            if ($ret->is_valid) {
//                $this->Clasificados = Input::post('clasificados');                      
             $clasificado = new Clasificados();              
            if($clasificado->guardar(Input::post('clasificados'))){


                Flash::success('Operación exitosa');
                Input::delete();




$post = Input::post('clasificados');

$email = $post['email'];


//var_dump($email); 


//envio email phpmailer avilac3
/*
Load::lib('PHPMailer/class.phpmailer');
$obj = new PHPMailer();
$obj->IsSMTP();          // Habilitamos el uso de SMTP
$obj->SMTPAuth   = true;          // Habilitamos la autenticación SMTP
$obj->Host       = "mail.avisoya.com";          // Nombre del servidor SMTP
$obj->Port       = 25;          // Puerto del SMTP
$obj->Username   = "info@avisoya.com";          // Cuenta de usuario del SMTP
$obj->Password   = "carlos";            // Clave del usuario SMTP
$obj->AddAddress( "".$clasificado->email."","Titulo del destinatario");

$obj->SetFrom("info@avisoya.com", 'avisoya');
$obj->Subject = "Felicidades por tu nuevo aviso";
$body = '<img src="http://www.avisoya.com/img/logo.png"><br></br>
        <a href="http://www.avisoya.com/clasificado/'.$clasificado->slug.'/">Click Aqui Clasificado</a>  '.$clasificado->email.' ';
$obj->MsgHTML($body);
$obj->Send();


// finenvio email phpmailer avilac3
*/
                return Router::redirect("clasificado/$clasificado->slug/");

              }else{ 
                  Flash::error($clasificado->error);                                                                             
              }
            }
            // Enviamos el error a la vista
            $this->previousError = $ret->error;
            Flash::error('Codigo AntiSpam proporcionado no es el correcto. por favor, inténtelo de nuevo.');
        }else{
                        //si no se ha enviando el captcha declaramas la variable a NULL
                        $this->previousError = NULL;
        }
    }  
         
}




     
   
    
    
    }

         
        
        
    
    
    
   public function ver($slug)
        {
      
Load::models('clasificados');
$clasificado = new Clasificados();
 $clasificado->getInnerJoinClasificadosslug($slug);   //Codigo lo recibes el slug


 // mostrar registro por el slug       
     $this->clasificado = Load::model('Clasificados')->setVisitas($slug);
     $fechab = $clasificado->fecha_baja;
    
    $this->imgruta = $clasificado->imgruta;   //ruta imagen
    $this->obtcate = $clasificado->categoria; //categoria actual
    $this->ciudad = $clasificado->ciudad;     //ciudad
    $this->estatus = $clasificado->estatus;     //ciudad
  
//    etiqueta title y description SEO 
    $this->pageTitle = substr(strip_tags($clasificado->minombre),0,90);
    $this->pageDescription = $clasificado->minombre;
   
     
//     condicion para no mostrar los registros caducados por fecha o estatus

          $this->mostrar = $estatus;

    if(!$this->mostrar = 1)         
     {
         
         Flash ::error("Aviso no Disponible");
     
     //    etiqueta title y description SEO ´

    $this->pageTitle = "Aviso No Disponible";
    $this->pageDescription = "Aviso no Disponible";
         
        }


    
         
    }

// FEED RSS 


    public function rss(){
    View::Template(NULL);
    $this->listClasificadosrss = Load::model('clasificados')->rss();


    }
    
    
    
    /*
     * Método para agregar
     */
    
    public function publicar()
    {
        

//    etiqueta title y description  
    $this->pageTitle = 'publica tu clasificado gratis';
    $this->pageDescription = 'publica gratis tu casa, automovil, empleo y mucho mas clasificados gratis faciles y sencillos  clasificados neiva';

        
        if(Input::hasPost('clasificados')){
            

//Hago save a mi aviso con llave de seguridad 
$this->previousError = "";
if(Input::hasPost('clasificados')){   
    if(SecurityKey::isValid()) {
       if (Input::hasPost('recaptcha_response_field')){
            // Realizamos la comprobacion
            $ret = reCaptcha::validate();
            if ($ret->is_valid) {
//                $this->Clasificados = Input::post('clasificados');                      
             $clasificado = new Clasificados();              
            if($clasificado->guardar(Input::post('clasificados'))){


                Flash::success('Operación exitosa');
                Input::delete();




$post = Input::post('clasificados');

$email = $post['email'];


//var_dump($email); 


//envio email phpmailer avilac3
/*
Load::lib('PHPMailer/class.phpmailer');
$obj = new PHPMailer();
$obj->IsSMTP();          // Habilitamos el uso de SMTP
$obj->SMTPAuth   = true;          // Habilitamos la autenticación SMTP
$obj->Host       = "mail.avisoya.com";          // Nombre del servidor SMTP
$obj->Port       = 25;          // Puerto del SMTP
$obj->Username   = "info@avisoya.com";          // Cuenta de usuario del SMTP
$obj->Password   = "carlos";            // Clave del usuario SMTP
$obj->AddAddress( "".$clasificado->email."","Titulo del destinatario");

$obj->SetFrom("info@avisoya.com", 'avisoya');
$obj->Subject = "Felicidades por tu nuevo aviso";
$body = '<img src="http://www.avisoya.com/img/logo.png"><br></br>
        <a href="http://www.avisoya.com/clasificado/'.$clasificado->slug.'/">Click Aqui Clasificado</a>  '.$clasificado->email.' ';
$obj->MsgHTML($body);
$obj->Send();


// finenvio email phpmailer avilac3
*/
                return Router::redirect("clasificado/$clasificado->slug/");

              }else{ 
                  Flash::error($clasificado->error);                                                                             
              }
            }
            // Enviamos el error a la vista
            $this->previousError = $ret->error;
            Flash::error('Codigo AntiSpam proporcionado no es el correcto. por favor, inténtelo de nuevo.');
        }else{
                        //si no se ha enviando el captcha declaramas la variable a NULL
                        $this->previousError = NULL;
        }
    }  
         
}




     
   
            
            
            
            
            
        }
        
        
    }
        
    
    
    
    
}
