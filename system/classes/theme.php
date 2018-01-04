<?php

class theme extends system {

  private $includes = array('header','footer');
  private $variables = array();
  
  // may includes be named by creator (class?)
  function set_include($position,$include) {
      $this->include_positions[$position][] = $include;
  }
  
  function get_includes($position) {
      foreach($this->include_positions[$position] as $include) {
          echo $include . "\n";
      }
  }
  
  function html($before,$content,$after) {
      return $before . $content . $after;
  }
  
  final function display_page() {
  
      echo "<html>\n";
      echo "<head>\n";
      $this->get_includes('header');
      echo "</head>\n";
      echo "<body>\n";
      $this->object_path();
      echo "\n</body>\n";
      $this->get_includes('footer');
      echo "</html>\n";
  
  }

}

?>
