<?php

class estatus{

	public static function select($Modelo =null){
		echo Form::select("$Modelo.estatus", array("0"=>"Activo","1"=>"Inactivo"));
 
	}

}