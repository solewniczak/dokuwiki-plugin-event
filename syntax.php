<?php
/**
 * Plugin Now: Inserts a timestamp.
 * 
 * @license    GPL 3 (http://www.gnu.org/licenses/gpl.html)
 * @author     Szymon Olewniczak <szymon.olewniczak@rid.pl>
 */

// must be run within DokuWiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once DOKU_PLUGIN.'syntax.php';

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_event extends DokuWiki_Syntax_Plugin {

    function getPType(){
       return 'block';
    }

    function getType() { return 'substition'; }
    function getSort() { return 32; }


    function connectTo($mode) {
	$this->Lexer->addSpecialPattern('\[event]',$mode,'plugin_event');
    }
    function handle($match, $state, $pos, &$handler) {
	//there should be possibility to set event page's format in the futhure
	$format = 'polish_full';
	return $format;
    }

    function render($mode, &$renderer, $data) {
        if($mode == 'xhtml') {
	    $event =& plugin_load('helper', 'event');

	    switch($data)
	    {
		case 'polish_full':
		    $miesiace = array(
		    '01' => 'stycznia',
		    '02' => 'lutego',
		    '03' => 'marca',
		    '04' => 'kwietnia',
		    '05' => 'maja',
		    '06' => 'czerwca',
		    '07' => 'lipca',
		    '08' => 'sierpnia',
		    '09' => 'wrzesnia',
		    '10' => 'pazdziernika',
		    '11' => 'listopada',
		    '12' => 'grudnia',
		    );
		    $day = date('j');
		    $month = $miesiace[date('m')];
		    $file_name = $day.'_'.$month;
		break;
	    }

	    $file = wikiFN($file_name);
	    if(file_exists($file))
	    {
		$cont = file_get_contents($file);
		$renderer->doc .= $event->parse($cont);
	    }

	    return true;
        }
        return false;
    }
}
