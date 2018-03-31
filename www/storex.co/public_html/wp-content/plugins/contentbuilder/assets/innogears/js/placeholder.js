/**
 * Manage placeholders on Javascript files
 *
 * Set & Get placeholder on Javascript files
 *
 * @author		InnoGears Team <support@www.innogears.com>
 * @package		IGPGBLDR
 * @version		$Id$
 */

// define array of placeholders javascript
var $placeholders = new Array();
$placeholders['widget_title']   = '_IG_WIDGET_TIGLE_';
$placeholders['extra_class']    = '_IG_EXTRA_CLASS_';
$placeholders['index']          = '_IG_INDEX_';
$placeholders['custom_style']   = '_IG_STYLE_';
$placeholders['standard_value'] = '_IG_STD_';
$placeholders['wrapper_append'] = '_IG_WRAPPER_TAG_';

// custom sprintf function for javascript: %s
function sprintf(format, etc) {
    var arg = arguments;
    var i = 1;
    return format.replace(/%((%)|s)/g, function (m) { return m[2] || arg[i++] })
}

// custom sprintf function for javascript: {0}, {1}
String.prototype.custom_sprintf = function() {
    var formatted = this;
    for( var arg in arguments ) {
        formatted = formatted.replace("{" + arg + "}", arguments[arg]);
    }
    return formatted;
};

/**
 * Add placeholder to string
 * Ex:	data.replace(/&lt;/g, '&_IG_WRAPPER_TAG_lt;') => ig_pb_add_placeholder( data, '&lt;', 'index', '&l{0}t;')
*/
function ig_pb_add_placeholder( $string, $replace, $placeholder, $expression ){
	if ( !( $placeholders[$placeholder] ) )
		return NULL;
	$replace = $replace.replace('/', '\\/')
    var regexp = new RegExp($replace, "g");
	if ( !( $expression ) )
		return $string.replace( regexp, $placeholders[$placeholder] );
	else
		return $string.replace( regexp, $expression.custom_sprintf($placeholders[$placeholder]) );
        //return $string.replace( regexp, sprintf( $expression, $placeholders[$placeholder] ) );
}

/**
 * Replace placeholder with real value
 * Ex:	html.replace(/_IG_INDEX_/g, value) => ig_pb_remove_placeholder(html, 'index', value)
*/
function ig_pb_remove_placeholder( $string, $placeholder, $value ){
    if ( ! $string ) {
        return '';
    }
    
	if ( !( $placeholders[$placeholder] ) )
		return $string;
    var regexp = new RegExp($placeholders[$placeholder], "g");
	return $string.replace( regexp, $value );
}

// get placeholder value
function ig_pb_get_placeholder( $placeholder ){
    if ( !( $placeholders[$placeholder] ) )
		return NULL;
    return $placeholders[$placeholder];
}