<?php

/**
 * @author Manuel Zarat
 * @date 05.01.2018
 * 
 */

class system extends core {

private $view = false;
private $type = false;
private $id = false;

    /**
     * Der Querystring, wegen URL rewriting (spaeter) wichtig
     *
     * @param string $key Fuer einen bestimmten Parameter
     * @return srtring $parameter Den Parameter $key
     * @return array $parameters Alle Parameter
     */
    final function the_querystring($key=false) {
        if($_SERVER['QUERY_STRING']) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
            if(false !== $key && !empty($parameters[$key])) {
                return $parameters[$key];
            } else {
                return $parameters;
            }
        }
        return false;
    }

}

?>
