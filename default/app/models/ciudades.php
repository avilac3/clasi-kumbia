<?php
class Ciudades extends ActiveRecord{
 
 public function getCiudades($page, $ppage=20)
    {
        return $this->paginate("page: $page", "per_page: $ppage", 'order: ciudad_id desc');
    }   
    
}

