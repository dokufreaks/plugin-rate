<?php
/**
 * Rate Plugin
 * 
 * Provides a syntax for displaying "n out of m" ratings.
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Gina Haeussge <osd@foosel.net>
 */

if(!defined('DOKU_INC'))
  define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_rate extends DokuWiki_Syntax_Plugin {

    /**
     * return some info
     */
    function getInfo() {
        return array(
            'author' => 'Gina Haeussge',
            'email'  => 'osd@foosel.net',
            'date'   => '2008-12-05',
            'name'   => 'Rate Plugin',
            'desc'   => 'Provides a syntax for displaying "n out of m" ratings.',
            'url'    => 'http://foosel.org/snippets/dokuwiki/rate',
        );
    }

    function getType() { return 'substition'; }
	function getSort(){ return 308; }

    function connectTo($mode) {
         $this->Lexer->addSpecialPattern('\{\{rate>\d+/\d+\}\}', $mode, 'plugin_rate');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler) {
    	$match = trim(substr($match, 7, -2));
    	list($numerator, $denominator) = split('/', $match);
    	return array($numerator, $denominator);
    }

    /**
     * Create output
     */
    function render($mode, &$renderer, $data) {
        if ($mode == 'xhtml') {
            list($numerator, $denominator) = $data;
            if ($numerator > $denominator)
            	return false;
            
            $renderer->doc .= '<span class="rating">';
            for ($i = 0; $i < $numerator; $i++)
            	$renderer->doc .= '<img src="'.DOKU_URL.'lib/plugins/rate/img/star_filled.png" width="16" height="16" alt="filled star" />';
            for ($i = $numerator; $i < $denominator; $i++) 
            	$renderer->doc .= '<img src="'.DOKU_URL.'lib/plugins/rate/img/star_empty.png" width="16" height="16" alt="empty star" />';
            $renderer->doc .= '</span>';
            return true;
        }
        return false;
    }
}
