<?php
Load::lib("twitteroauth");
class OAuthController extends AppController{    
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
 
	public function index() 
	{
		view::template(NULL);

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
            // para publicar tweet    $connection->post('statuses/update', array('status' => 'here the content of your tweet, you can add hashtags or links'));

		/* Get credentials to test API access */
		/* $credentials = $connection->get('account/verify_credentials'); */
		$this->credentials = $connection->get('account/verify_credentials');

 
		if ($credentials->error) {
			$this->msg = $credentials->error."<br><br><a href='http://avisoya.com/oauth/_register'>Register now</a>";
		}
		else {
			$this->msg = "Acceso confirmado, OAuth correcto. Bienvenido ".$credentials->screen_name.".<br><br><a href='http://avisoya.com/oauth/_logout'>Logout</a>";
		}
	}
 
	public function _redirect() 
	{
		session_start();
 
		/* Create TwitterOAuth object and get request token */
		$connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret);
 
		/* Get request token */
		$request_token = $connection->getRequestToken($this->callBack);
 
		/* Save request token to session */
		$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
		/* If last connection fails don't display authorization link */
		switch ($connection->http_code) {
			case 200:
				/* Build authorize URL */
				$url = $connection->getAuthorizeURL($token);
				header('Location: ' . $url);
				break;
			default:
				echo 'Could not connect to Twitter. Refresh the page or try again later.';
		}
		die();
	}
 
	public function _register() {
            
            //    etiqueta title y description  
    $this->pageTitle = 'publica tu clasificado gratis con twitter';
    $this->pageDescription = 'publica gratis tu casa, automovil, empleo y mucho mas clasificados gratis faciles y sencillos  clasificados neiva';



		session_start();
		session_destroy();
	}
 
	public function _logout() {
		view::template(NULL);

		session_start();
		session_destroy();
		View::select(NULL, NULL);
               Flash::success('Operación exitosa');
                return Router::redirect("/");
	}
 
	public function _callback() 
	{

		session_start();
 
		/* If the oauth_token is old redirect to the connect page. */
		if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
			$_SESSION['oauth_status'] = 'oldtoken';
			header('Location: http://avisoya.com/oauth/_register/');
		}
 
		/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
		$connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
 
		/* Request access tokens from twitter */
		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
 
		/* Save the access tokens. Normally these would be saved in a database for future use. */
		$_SESSION['access_token'] = $access_token;
 
		/* Remove no longer needed request tokens */
		unset($_SESSION['oauth_token']);
		unset($_SESSION['oauth_token_secret']);
 
		/* If HTTP response is 200 continue otherwise send to connect page to retry */
		if (200 == $connection->http_code) {
			/* The user has been verified and the access tokens can be saved for future use */
			$_SESSION['status'] = 'verified';
			header('Location: http://avisoya.com/publicar/');
		} else {
			/* Save HTTP status for error dialog on connnect page.*/
			header('Location: http://avisoya.com/oauth/_register/');
		}
		die();
	}
 
}
?>