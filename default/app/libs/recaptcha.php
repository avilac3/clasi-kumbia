<?php 
// Incluimos el fichero con la librería
require_once APP_PATH. '/libs/recaptchalib.php';
/** 
 * Clase para consumir el servicio anti-spam reCaptcha
 * @author Soukron 
 */
class reCaptcha
{
    /**
     * Clave Publica reCaptcha
     * @var string
     */
    private static $_publicKey  = '6LemNswSAAAAAJ6MJgiyfcvxj8KPQtCZDX5oL7lf';
    /**
     * Clave Privada reCaptcha
     */
    private static $_privateKey = '6LemNswSAAAAAAmp0nAtLWKDd8ZBdL37jZFjVvY1';   
 
    /**
     * Genera el HTML con el código reCaptcha
     * 
     * @param $error
     * @return string 
     */
    public static function html($error = NULL)
    {
        return recaptcha_get_html(self::$_publicKey, $error);
    }
    /**
     * Valida que el código colocado sea el correcto
     * @return Response
     */
    public static function validate()
    {
        $ret = recaptcha_check_answer (self::$_privateKey,
                $_SERVER['REMOTE_ADDR'],
                $_POST['recaptcha_challenge_field'],
                $_POST['recaptcha_response_field']);
 
        return $ret;
    }
}
