<?php
/*
 *
 */
class Clasificados extends ActiveRecord{
    
   const INACTIVO  =   0;
   const ACTIVO    =   1;
    
    public function before_save() { 
           
        
        $this->fecha_baja = date("Y-m-d H:i:s", strtotime($this->fecha_baja)); 
        $this->slug = Slug::slugurl($this->nombre);
    
        
        
        $condicion = "slug = '$this->slug'";
        $condicion.= (isset($this->id)) ? " AND id != $this->id" : ''; //Verifico si se modifica el clasificado
        if($this->find_first("conditions: $condicion")) {
            Flash::error('Lo sentimos, pero al parecer ya existe un título parecido.');
            return 'cancel';
        }        
    }
    
    
    public function initialize() {
        //por defecto el active record va a validar los campos que sean
        //NOT NULL en la tabla de la base de datos
        //En este caso la tabla menu tiene 2 campos not null:
        //los campos nombre,url (la clave primaria no se toma en cuenta)
        //sin embargo para efectos del ejemplo se hará uso de las validaciones
        //de active record para cambiar los mensajes a mostrar en caso de
        //error en la validacion de los campos.
        $this->validates_presence_of('ciudad_id', 'message: Por Favor seleccione una ciudad');
        $this->validates_presence_of('categoria_id', 'message: selecione una categoria');
        $this->validates_presence_of('email', 'message: Escriba su correo electronico');
        $this->validates_presence_of('nombre', 'message: Escriba su anuncio');
        
    }
    
   
    
    
    const DIR = "/public/img/upload/";
    const DIR_BD = "upload/";
 
        public $debug = false; 
        
  public function guardar2()
    { 
      
      
// var_dump($nombre) ;


      $file = Upload::factory('imagen','image');
        if($file->isUploaded()) 
            {      	                    
        
            $file->setMaxSize('2MB');// Tamaño máximo
            // Tipos de archivos permitidos
            //$file->setTypes(array('image/jpeg'));            
            // Extensiones permitidas
            //$file->setExtensions(array('jpg'));           
            $fileName = $_FILES["img"]["img"];
            
            Flash::notice($fileName);
            $fileName = md5(time()).".".getExtensionFile($fileName);
            Flash::notice($fileName);
            $this->ruta = Banners::DIR_BD.$fileName;          
            $file->setPath($ruta);
            
            $file->save($fileName);
              //Escribir esto en la bd si hay archivo 
            
              $this->img = $fileName;
              ////esto!         
                                 
             }      
                    
                    $this->nombre = $_POST['clasificados']['nombre'];
                    $this->categoria_id = $_POST['clasificados']['categoria_id'];
                    $this->estatus = $_POST['clasificados']['estatus']; 
                    $this->fecha_baja = $_POST['clasificados']['fecha_baja'];

                    if($this->save())
                    {                            
                            return TRUE;
                    }
            }
            

        
        
  public function guardar ($clasificado){
        
            
      $codigo = base_convert ( rand(0,9999999), 10, 32 );      
      $this->codigosecurity = $codigo;
      return $this->save($clasificado);
         
        
            
        
        
     }






            public function getClasificados($page, $ppage=20)
    {
        return $this->paginate("page: $page", "per_page: $ppage", 'order: id desc');
    }
  
    
    public function getListadoclasificado($estado='activos') 
    {
        if($estado=='activos') {
            $condicion = "estatus='1'";
        } else{
                $condicion = "estatus='0'";
            }
            
        return $this->find("conditions: $condicion", 'order: id ASC');
    }
     
    

//  sumar visitas
//    con la condicion id=id
//    y contamos vistas ++
   
    
    public function setVisitas($slug) 
    {
        $this->find_by_slug($slug);
        $this->visitas++;
        $this->update();
        return $this;                            
    }

// Test pruebas

public function ultimosavisos($limit=5) {
        $today = date('now');
        return $this->find('order: fecha_at desc',
                   "conditions: fecha_at <= $today",
                   "limit: $limit");
 
    }

public function ultimos($limit=30) {
        return $this->find("columns: clasificados.*, categorias.nombre, ciudades.nombre ",
                         "join: INNER JOIN categorias 
           on clasificados.categoria_id = categorias.id
             
           INNER JOIN ciudades
           on clasificados.ciudad_id = ciudades.id
           and clasificados.fecha_baja > CURRENT_DATE AND clasificados.site = 'AV'
           ","order: clasificados.id DESC",
                   "limit: $limit");
 
    } 
    
    // Fin Test pruebas
   

 //    Obtener clasiciados para el rss

          public function rss()
    {   return $this->find_all_by_sql("SELECT clasificados.id, clasificados.slug, clasificados.nombre, clasificados.estatus, clasificados.visitas, categorias.ruta, clasificados.fecha_at, clasificados.fecha_baja, categorias.nombre as categoria, ciudades.nombre as ciudad 
        FROM clasificados
            INNER JOIN categorias 
            on clasificados.categoria_id = categorias.id
            
            INNER JOIN ciudades
            on clasificados.ciudad_id = ciudades.id
            
            Where clasificados.fecha_baja > CURRENT_DATE AND clasificados.site = 'AV'
            ORDER BY clasificados.id DESC");
        }     

    

    
 
 //    Obtener clasiciados con fecha_baja mayor que
    
            
        public function getInnerJoinClasificados($page=1, $ppage=20)
    {   $sql = "SELECT clasificados.id, clasificados.slug, clasificados.nombre, clasificados.estatus, clasificados.visitas, categorias.ruta, clasificados.fecha_at, clasificados.fecha_baja, categorias.nombre as categoria, ciudades.nombre as ciudad 
        FROM clasificados
            INNER JOIN categorias 
            on clasificados.categoria_id = categorias.id
            
            INNER JOIN ciudades
            on clasificados.ciudad_id = ciudades.id
            
            Where clasificados.fecha_baja > CURRENT_DATE AND clasificados.site = 'AV'
            ORDER BY clasificados.id DESC";
    
    return $this->paginate_by_sql($sql, "per_page: $ppage", "page: $page");        
    }     
    
    
  //    Obtener clasiciados con SLUG   y  fecha_baja mayor que
            
        public function getInnerJoinClasificadosslug($slug)
     {   $sql = "SELECT clasificados.id, clasificados.slug, clasificados.nombre as minombre, clasificados.estatus, clasificados.visitas, clasificados.fecha_baja,  categorias.ruta as imgruta, categorias.nombre as categoria, clasificados.ciudad_id, ciudades.nombre as ciudad
            FROM clasificados
            INNER JOIN categorias 
            on clasificados.categoria_id = categorias.id
            
            INNER JOIN ciudades
            on clasificados.ciudad_id = ciudades.id
            WHERE clasificados.fecha_baja > CURRENT_DATE AND clasificados.slug = '$slug' AND clasificados.site = 'AV' ";
    
    return $this->find_by_sql($sql);     
    }
      
    
    
//    Obtener Top visitas
    
    public function getInnerJoinTopvisitas($page=1, $ppage=20)
    {   $sql = "SELECT clasificados.id, clasificados.slug, clasificados.nombre, clasificados.fecha_baja, clasificados.estatus, clasificados.visitas, categorias.ruta, categorias.nombre as categoria, ciudades.nombre as ciudad
            FROM clasificados
            INNER JOIN categorias 
            on clasificados.categoria_id = categorias.id
            
            INNER JOIN ciudades
            on clasificados.ciudad_id = ciudades.id
            WHERE clasificados.fecha_baja > CURRENT_DATE AND clasificados.site = 'AV'
            ORDER BY clasificados.visitas DESC ";
    
    return $this->paginate_by_sql($sql, "per_page: $ppage", "page: $page");        
    }  
    
// Obtener Ciudad
    
    public function getInnerJoinCiudad($ciudad,$page=1, $ppage=20)
    {   $sql = "SELECT clasificados.id, clasificados.slug, clasificados.nombre, clasificados.estatus, clasificados.visitas, clasificados.fecha_baja,  categorias.ruta, categorias.nombre as categoria, ciudades.nombre as ciudad 
            FROM clasificados
            INNER JOIN categorias 
            on clasificados.categoria_id = categorias.id
            
            INNER JOIN ciudades
            on clasificados.ciudad_id = ciudades.id
        
            WHERE ciudades.nombre='$ciudad' and clasificados.fecha_baja > CURRENT_DATE AND clasificados.site = 'AV'
            ORDER BY clasificados.id DESC ";
    
    return $this->paginate_by_sql($sql, "per_page: $ppage", "page: $page");        
    }
    
    

   
//Obtener Categorias
    
 public function getInnerJoinCategoria($categoria,$page=1, $ppage=20)
    {   $sql = "SELECT clasificados.id, clasificados.slug, clasificados.nombre, clasificados.estatus, clasificados.visitas,  categorias.ruta, clasificados.fecha_baja, categorias.nombre as categoria, ciudades.nombre as ciudad
            FROM clasificados
            INNER JOIN categorias 
            on clasificados.categoria_id = categorias.id
            
            INNER JOIN ciudades
            on clasificados.ciudad_id = ciudades.id
            
            WHERE categorias.nombre = '$categoria'  and clasificados.fecha_baja > CURRENT_DATE AND clasificados.site = 'AV' ";
    
    return $this->paginate_by_sql($sql, "per_page: $ppage", "page: $page");        
    }  
    
    
 
    
}


    
