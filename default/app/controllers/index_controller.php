<?php
/**
 * Controller por defecto si no se usa el routes
 * 
 */

ini_set('display_errors', 'Off');

Load::Lib("recaptcha");
Load::Lib("slug");
Load::lib("twitteroauth");

Load::model('clasificados');

class IndexController extends AppController    {
	protected $consumerKey;
	protected $consumerSecret;
	protected $callBack;
 
	public function before_filter() {
                /* Esto es mio, ya que tengo los valores en la base de datos, lo dejo para servir de ejemplo
		$rows = $this->Configuration->find("name LIKE '%oauth%' ORDER BY name ASC");
		$this->callBack 	= $rows[0]->value;
		$this->consumerKey 	= $rows[1]->value;
		$this->consumerSecret	= $rows[2]->value; before_filter
                */
        $this->callBack 	= "http://localhost/clasi-kumbia/oauth/_callback";
		$this->consumerKey 	= "KE1VKY3vtKgtVX4ABjzgXw";
		$this->consumerSecret	= "9dSrnqnLqiuiFF82utgKZ9fqhixGJqCzqlWqxnFU4";
	}    

    
//        obtiene una lista para paginar
        public function index($page=1) {
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
    
    
    
// PUBLICAR CLASIFICADO     
    

public function publicar($page=1) {
//    etiqueta title y description  
    $this->pageTitle = 'publica tu clasificado gratis';
    $this->pageDescription = 'publica gratis tu casa, automovil, empleo y mucho mas clasificados gratis faciles y sencillos  clasificados neiva';


session_start();
		if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) 
		{
			View::select(NULL, NULL);
			return Router::redirect("oauth/_register");
		}
 
		/* Get user access tokens out of the session. */
		$access_token = $_SESSION['access_token'];
 
		/* If access tokens are not available redirect to connect page. */
		if (empty($access_token['oauth_token']) || empty($access_token['oauth_token_secret'])) {
			header('Location: http://localhost/clasi-kumbia/oauth/_register/');
		}
 
		/* Create a TwitterOauth object with consumer/user tokens. */
		$connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
 
		/* Get credentials to test API access */
		/* $credentials = $connection->get('account/verify_credentials'); */
		$this->credentials = $connection->get('account/verify_credentials');

 
		if ($credentials->error) {
			$this->msg = $credentials->error."<br><br><a href='http://localhost/clasi-kumbia/oauth/_register'>Register now</a>";
		}
		else {
			$this->msg = "Acceso confirmado, OAuth correcto. Bienvenido ".$credentials->screen_name.".<br>";
		}    

    
 
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


//                Flash::success('Operación exitosa');
//                Input::delete();
//$clasificado = Load::model('clasificados')->find_by_id($clasificado->id);
//$clasificado->slug =$clasificado->slug.'-'.$clasificado->id;
//$clasificado->update();
        


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
   
    public function ver2(){
        

        
$clasificado = Load::model('clasificados')->find_by_id(1);
$clasificado->titulo ="Televisor test slug";
$clasificado->update();

echo $clasificado->titulo;
        
    }
    

    


    public function random(){
Load::models('clasificados');
    
 $clasificado = new Clasificados();
 $clasificado->getclasificadorandom();   //Codigo random

 return Router::redirect("clasificado/$clasificado->slug/");

    
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
    
    
       
    
    
}
