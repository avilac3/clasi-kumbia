<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Clase para el manejo de texto y otras cosas
 * Uso: $slug = DwUtils::slug('ESTO ES UN TEXTO CUALQUIERA', '-', 50);
 * Resultado: esto-es-un-texto-cualquiera
 *
 * @package     Libs
 * @author      Iván D. Meléndez
 * @copyright   Copyright (c) 2010 Dailyscript Team (http://www.dailyscript.co)
 * @version     1.0
 */

class Slug {
    /**
     * Metodo para crear el slug de los titulos, categorias y etiquetas
     */
    public static function slugurl ($string, $string2, $separator = '-', $length = 110) {
        $search = explode(',', 'ç,Ç,ñ,Ñ,æ,Æ,œ,á,Á,é,É,í,Í,ó,Ó,ú,Ú,à,À,è,È,ì,Ì,ò,Ò,ù,Ù,ä,ë,ï,Ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,Š,Œ,Ž,š,¥');
        $replace = explode(',', 'c,C,n,N,ae,AE,oe,a,A,e,E,i,I,o,O,u,U,a,A,e,E,i,I,o,O,u,U,ae,e,i,I,oe,ue,y,a,e,i,o,u,a,e,i,o,u,s,o,z,s,Y');
        $string = str_replace($search, $replace, $string);
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9_]/i', $separator, $string);
        $string = preg_replace('/' . $separator . '[' . $separator . ']*/', $separator, $string);
        $string3 = $string.'-'.$string2;
        if (strlen($string3) > $length) {
            $string3 = substr($string3, 0, $length);
        }
        $string3 = preg_replace('/' . $separator . '$/', '', $string3);
        $string3 = preg_replace('/^' . $separator . '/', '', $string3);
        return $string3;
    }
}