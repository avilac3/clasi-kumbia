<?php

Load::models('banners');
class BannerController extends AppController {

    
    public function crear()
    {
        if(Input::hasPost('banners'))
       {	       
	        
            $banner = new Banners(); 	        
            if($banner->guardar())
            {
                Flash::success('Operación exitosa');				
            }else Flash::error($banner->error);			        	        		        
    						
        }        
    }
    
    
}
