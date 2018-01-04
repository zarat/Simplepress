<?php

class system extends core {

private $view = false;
private $type = false;
private $id = false;

    // parse the querystring
    final function the_querystring($key=false) {
        if($_SERVER['QUERY_STRING']) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
            if(false !== $key) {
                return $parameters[$key];
            } else {
                return $parameters;
            }
        }
        return false;
    }
    
    function object_path() { 
        
        /*
        those variables for building the query really MUST NOT be static like that !!
        
        take care of href linking (clean, santitized,..) before its too late
        they must not be 'final'
        
        @type
        kind of object (post,page,archive)
        
        @id
        id of the @type
        
        @task
        what to do with the selected id of type?
        
        @pagination
        for archives ---> but what about comments? they can have multiple pages if tey are too much but belongs to a single id. 
        do it with javascript@frontend or treat them as individual objects?
        
        TYPES OF LINKS
               
            Default
                .com/?type={TYPE}&id={ID}&task={TASK}&page={PAGINATION}
            Clean
                .com/{TYPE}/{ID}/{TASK}/{PAGINATION}
        
        
        */        
        $this->type = !empty($this->the_querystring('type')) ? $this->the_querystring('type') : "default"; // default
        $this->id = !empty($this->the_querystring('id')) ? $this->the_querystring('id') : false; // 0
        
        /*
        $type should come out of the database..
        */
        switch($this->type) {
            case "post":
            case "page":
                $this->view = "single"; 
                break;
            case "category":
            case "tag":
            case "search":
                $this->view = "archive"; 
                break;
            default:
                $this->view = "default"; 
                break;
        }
        /*
        in combination with $view
        
        pagination and stuff should happen here?!
        */
        $system = new system();
        
        switch($this->view) {        
            case "single":  
                include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single.php";
            break;
            case "archive":
                include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive.php";
            break;            
            case "default": 
                include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "index.php";
            break;        
        }    
        return false;      
    }

}

?>
