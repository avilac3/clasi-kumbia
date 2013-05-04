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
        $this->callBack 	= "http://avisoya.com/oauth/_callback";
		$this->consumerKey 	= "KE1VKY3vtKgtVX4ABjzgXw";
		$this->consumerSecret	= "9dSrnqnLqiuiFF82utgKZ9fqhixGJqCzqlWqxnFU4";
	} 
        
        
        
        
        
        

/////////////////////////////////////////////////////////////////////////////////        
//        obtiene una lista para paginar
/////////////////////////////////////////////////////////////////////////////////            
        
 public function index($page=1) {
//    etiqueta title y description  
    $this->pageTitle = 'clasificados gratis  avisoya  Clasificados colombia';
    $this->pageDescription = 'encuentra casas, automoviles, empleos y mucho mas clasificados gratis faciles y sencillos  clasificados neiva';


//    Obtener lista de clasficados con el join
$this->listClasificados = Load::model('clasificados')->getInnerJoinClasificados($page,10);   

 }
   
    
    
    
    
/////////////////////////////////////////////////////////////////////////////////    
// PUBLICAR CLASIFICADO     
/////////////////////////////////////////////////////////////////////////////////    

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
			header('Location: http://avisoya.com/oauth/_register/');
		}
 
		/* Create a TwitterOauth object with consumer/user tokens. */
		$connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
 
		/* Get credentials to test API access */
		/* $credentials = $connection->get('account/verify_credentials'); */
		$this->credentials = $connection->get('account/verify_credentials');

 
		if ($credentials->error) {
			$this->msg = $credentials->error."<br><br><a href='http://avisoya.com/oauth/_register'>Register now</a>";
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


               Flash::success('Operación exitosa');
                Input::delete();

                
$clasificadonew = Load::model('clasificados')->getInnerJoinClasificadosslug($clasificado->slug);                
// para publicar tweet    
// Mensaje        
   $url = "http://avisoya.com/$clasificadonew->slug";
   $ciudad = (empty($clasificadonew->ciudad)) ? '' : "#{$clasificadonew->ciudad}";
   $categoria = (empty($clasificadonew->categoria)) ? '' : "#{$clasificadonew->categoria}";
       
    // Enviar Tweet
$connection->post('statuses/update', array('status' => $clasificadonew->titulo.' '.$url.' '.$ciudad.' '.$categoria.' '));

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
   

    


/////////////////////////////////////////////////////////////////////////////////    
// Editar CLASIFICADO   
/////////////////////////////////////////////////////////////////////////////////    

public function editar($page=1) {
//    etiqueta title y description  
    $this->pageTitle = 'Edita tu clasificado gratis';
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
			header('Location: http://avisoya.com/oauth/_register/');
		}
 
		/* Create a TwitterOauth object with consumer/user tokens. */
		$connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
 
		/* Get credentials to test API access */
		$credentials = $connection->get('account/verify_credentials'); 
		$this->credentials = $connection->get('account/verify_credentials');

 
		if ($credentials->error) {
			$this->msg = $credentials->error."<br><br><a href='http://avisoya.com/oauth/_register'>Register now</a>";
		}
		else {
			$this->msg = "Acceso confirmado, OAuth correcto. Bienvenido ".$credentials->screen_name.".<br>";
		
//    Obtener lista de clasficados con el join
$this->listClasificados = Load::model('clasificados')->getMisClasificados( $twitter_id="$credentials->id",$page,10);  
                        
                        
                        
                        
                        
                        
                }    

    
   
    
    }
       
/////////////////////////////////////////////////////////////////////////////////
// Editar CLASIFICADO     
/////////////////////////////////////////////////////////////////////////////////    

public function edita($slugc) {
//    etiqueta title y description  
    $this->pageTitle = 'Edita tu clasificado gratis';
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
			header('Location: http://avisoya.com/oauth/_register/');
		}
 
		/* Create a TwitterOauth object with consumer/user tokens. */
		$connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
 
		/* Get credentials to test API access */
		$credentials = $connection->get('account/verify_credentials'); 
		$this->credentials = $connection->get('account/verify_credentials');

 
		if ($credentials->error) {
			$this->msg = $credentials->error."<br><br><a href='http://avisoya.com/oauth/_register'>Register now</a>";
		}
		else {
			$this->msg = "Acceso confirmado, OAuth correcto. Bienvenido ".$credentials->screen_name.".<br>";
		
             $clasificado = new Clasificados();              


 
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
            if($clasificado->update(Input::post('clasificados'))){

               Flash::success('Operación exitosa');
//                

                return Router::redirect("editar/");

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


else {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->clasificados = $clasificado->find_by_slug($slug);
        }
                        
                        
                        
                        
                }    

    
   
    
    }    
    
/////////////////////////////////////////////////////////////////////////////////
// CAMBIAR A VENDIDO CLASIFICADO     
/////////////////////////////////////////////////////////////////////////////////    

public function estado($id) {

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
			header('Location: http://avisoya.com/oauth/_register/');
		}
 
		/* Create a TwitterOauth object with consumer/user tokens. */
		$connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
 
		/* Get credentials to test API access */
		$credentials = $connection->get('account/verify_credentials'); 
		$this->credentials = $connection->get('account/verify_credentials');

 
		if ($credentials->error) {
			$this->msg = $credentials->error."<br><br><a href='http://avisoya.com/oauth/_register'>Register now</a>";
		}
		else {
			$this->msg = "Acceso confirmado, OAuth correcto. Bienvenido ".$credentials->screen_name.".<br>";
		

                        

$this->clasificado = Load::model('clasificados')->find_by_id($id);
$clasificado = Load::model('clasificados')->find_by_id($id);

if ($clasificado->vendido = (int)(!$clasificado->vendido )) {
    
    $clasificado->vendido = '1';
    $clasificado->update();
    Flash::success('Operación exitosa  ');
                    return Router::redirect("editar/");

}else {

    $clasificado->vendido = '0';
    $clasificado->update();
    Flash::success('Operación exitosa');
                    return Router::redirect("editar/");


}
   
    
    }
}
    

/////////////////////////////////////////////////////////////////////////////////    
// Anuncio Random
/////////////////////////////////////////////////////////////////////////////////
    
    
    public function random(){
Load::models('clasificados');
    
 $clasificado = new Clasificados();
 $clasificado->getclasificadorandom();   //Codigo random

 return Router::redirect("clasificado/$clasificado->slug/");

    
}



/////////////////////////////////////////////////////////////////////////////////
// Ver Anuncio usando el slug
/////////////////////////////////////////////////////////////////////////////////
    
    
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
    $this->pageTitle = substr(strip_tags($clasificado->titulo),0,90);
    $this->pageDescription = substr(strip_tags($clasificado->anuncio),0,90);
   
     
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
/////////////////////////////////////////////////////////////////////////////////
// FEED RSS 
/////////////////////////////////////////////////////////////////////////////////

    public function rss(){
    View::Template(NULL);
    $this->listClasificadosrss = Load::model('clasificados')->rss();


    }
    
 
/////////////////////////////////////////////////////////////////////////////////    
// buscar 
/////////////////////////////////////////////////////////////////////////////////    
        public function buscar($busqueda= NULL, $page=1){

            $this->busqueda = $busqueda;
        if ($busqueda){ //si viene lleno el parametro
    $this->listClasificados = Load::model('clasificados')->getInnerJoinClasificadosBusqueda($busqueda,$page,10);  
        }else if (Input::get("b")){ //si se manda el form
            Router::toAction("../buscar/" . Input::get("b"));
        }  else {
          flash::error('por favor inserte un parametro para buscar');  
            Router::redirect('./');
        }


 }
    
    
       
    
    
}
