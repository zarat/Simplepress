<?php

/**
 * Simplepress HTML
 *
 * HTML Bearbeitung
 * 
 * Usage: html::trim('<p>Lorem ipsum dolor sit amet</p>', 10);
 *
 * @author Manuel Zarat
 * @version 0.2.0
 * @link https://github.com/zarat/simplepress   
 * @since 06/2018 
 */

class html {

    protected $limit_reached = false, $length_total = 0, $length_max = 25, $removements = array();

    public static function trim($html, $length_max = 25) {
        $dom = new DomDocument();        
        if (version_compare(PHP_VERSION, '5.4.0') < 0) {
            $dom->loadHTML($html);
        } else {
            $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        }
        $instance = new static();
        $removements = $instance->walk($dom, $length_max);
        foreach ($removements as $child) {
            $child->parentNode->removeChild($child);
        }
        /**
         * Entfernen von doctype, html..
         */
        if (version_compare(PHP_VERSION, '5.4.0') < 0) {
            $dom->removeChild($dom->firstChild);
            $dom->replaceChild($dom->firstChild->firstChild->firstChild, $dom->firstChild);
            return $dom->saveHTML();
        }
        return $dom->saveHTML();
    }
    protected function walk(DomNode $node, $length_max) {
        if ($this->limit_reached) {
            $this->removements[] = $node;
        } else {
            /** 
             * Nur text nodes sollten text enthalten
             */
            if ($node instanceof DomText) {
                $this->length_total += $nodeLen = strlen($node->nodeValue);
                /**
                 * UTF-8 support
                 */
                if ($this->length_total > $length_max) {
                    $node->nodeValue = mb_substr($node->nodeValue, 0, $nodeLen - ($this->length_total - $length_max)) . '...';
                    $this->limit_reached = true;
                }
            }
            // unternodes
            if (isset($node->childNodes)) {
                foreach ($node->childNodes as $child) {
                    $this->walk($child, $length_max);
                }
            }
        }
        return $this->removements;
    }
}

?>
