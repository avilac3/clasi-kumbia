<?php
class Youtube {
    
    public static function getThumbails($id, $img='0.jpg') {
        return "<img src='http://img.youtube.com/vi/$id/$img' />";
    }
    
    
        public static function getYoutubeID($url) {                
                $tube = parse_url($url);
		if ($tube["path"] == "/watch") {
			parse_str($tube["query"], $query);
			$id = $query["v"];
		} else {
			$id = "";   
		}
		return $id;
	}
        
        
        

}