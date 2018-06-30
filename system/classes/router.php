<?php

/**
 * Simplepress Router Klasse
 * 
 * @author Manuel Zarat
 */

class router {

private $routes = Array();

private $pathNotFound = null;

private $methodNotAllowed = null;
  
    public function add( $expression, $function, $method = 'get' ) {
    
        array_push(
                
            $this->routes, 
            Array(
                  'expression' => $expression,
                  'function' => $function,
                  'method' => $method
                )
        );
        
    }

    public function pathNotFound( $function ) {
    
        $this->pathNotFound = $function;
        
    }
  
    public function methodNotAllowed( $function ) {
    
        $this->methodNotAllowed = $function;
        
    }

    public function run( $basepath = '/' ) {

        $parsed_url = parse_url($_SERVER['REQUEST_URI']);//Parse Uri
    
        if( isset( $parsed_url['path'] ) ) {
        
              $path = $parsed_url['path'];
              
        } else {
        
              $path = '/';
              
        }
  
        $method = $_SERVER['REQUEST_METHOD'];
    
        $path_match_found = false;
    
        $route_match_found = false;
    
        foreach( $this->routes as $route ){
    
            /**
             * Basepath anfuegen
             */
            if( $basepath != '' && $basepath != '/' ) {
            
                  $route['expression'] = '(' . $basepath . ')' . $route['expression'];
            
            }
      
            /**
             * Delimiter anfuegen
             */
            $route['expression'] = '^' . $route['expression'];
            $route['expression'] = $route['expression'] . '$';

            if( preg_match( '#' . $route['expression'] . '#', $path, $matches ) ) {
      
                $path_match_found = true;
        
                if( strtolower( $method ) == strtolower( $route['method'] ) ) {
        
                    array_shift( $matches );
          
                    if( $basepath != '' && $basepath != '/' ) {
                        array_shift($matches);
                    }
          
                    call_user_func_array( $route['function'], $matches );
          
                    $route_match_found = true;
          
                    //break;
                  
                }
              
            }
          
        }
  
        /**
        * Keine Route
        */
        if( !$route_match_found ) {
        
            /**
            * Aber ein passender Pfad
            */
            if( $path_match_found ) {
            
                header("HTTP/1.1 405 Method Not Allowed");
                
                if( $this->methodNotAllowed ) {
                
                    call_user_func_array($this->methodNotAllowed, Array( $path, $method ) );
                
                }
            
            } else {
            
                header("HTTP/1.1 404 Not Found");
                
                if( $this->pathNotFound ) {
                
                    call_user_func_array( $this->pathNotFound, Array( $path ) );
                    
                }
            
            }
          
        }
  
    }

}

?>
