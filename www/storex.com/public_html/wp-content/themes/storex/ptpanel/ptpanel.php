<?php

if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);

    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

function callerName($functionName=null)
{
    $btArray = debug_backtrace();
    $btIndex = count($btArray) - 1;
    while($btIndex > -1)
    {
        if(!isset($btArray[$btIndex]['file']))
        {
            $btIndex--;
            if(isset($matches[1]))
            {
                if(class_exists($matches[1]))
                {
                    return $matches[1];
                }
                else
                {
                    continue;
                }
            }
            else
            {
                continue;
            }
        }
        else
        {
            $lines = file($btArray[$btIndex]['file']);
            $callerLine = $lines[$btArray[$btIndex]['line']-1];
            if(!isset($functionName))
            {
                preg_match('/([a-zA-Z\_]+)::/',
                $callerLine,
                $matches);
            }
            else
            {
                preg_match('/([a-zA-Z\_]+)::'.$functionName.'/',
                    $callerLine,
                    $matches);
            }
            $btIndex--;
            if(isset($matches[1]))
            {
                if(class_exists($matches[1]))
                {
                    return $matches[1];
                }
                else
                {
                    continue;
                }
            }
            else
            {
                continue;
            }
        }
    }
    return $matches[1];
}

abstract class Enum {

    private $_value;

    protected function __construct($value) {
        $this->_value = $value;
    }

    public function __toString() {
        return (string) $this->_value;
    }

    public static function enumerate() {
        
        if (PHP_VERSION_ID < 50300) { $class = callerName(); }
        else { $class = get_called_class(); }
        
        $ref = new ReflectionClass($class);
        
        $statics = $ref->getStaticProperties();
        foreach ($statics as $name => $value) {
            $ref->setStaticPropertyValue($name, new $class($value));
        }
    }
}

class FieldType extends Enum{
	
	public static $HIDDEN      = 0;
	public static $TEXT        = 1;
	public static $EMAIL       = 2;
	public static $NUMBER      = 3;
	public static $TEXTAREA    = 4;
	public static $SELECT      = 5;
	public static $CHECKBOX    = 6;
	public static $RADIOBUTTON = 7;
	public static $MEDIAUPLOAD = 8;
	public static $COLORPICKER = 9;
	public static $COLLECTION  = 10;
	public static $ONOFF       = 11;

}
	
FieldType::enumerate();

class FormField{

	protected $_value;
	protected $_name;
	protected $_id;
	protected $_section_id;
	protected $_option_label;
	protected $_additional_params;
	protected $_validatorFunc;
	protected $_inCustomizer;
	
	
	function __construct($option_id, $option_name, $section_id, $option_label = '', $additional_params = array(), $in_customizer = false){
		
		$this->_id = $option_id;
		$this->_name = $option_name;
		$this->_section_id = $section_id;
		$this->_option_label = $option_label;
		$this->_additional_params = $additional_params;
		$this->_inCustomizer = $in_customizer;
		$this->setView();
		
	}
	
	public function getSection(){
		return $this->_section_id;
	}
	
	public function getId(){
		return $this->_id;
	}
	
	public function getName(){
		return $this->_name;
	}
	
	public function getLabel(){
		return $this->_option_label;
	}
	
	public function getParams(){
		return $this->_additional_params;
	}
	
	public function __toString() {
        return (string) $this->_value;
    }
    
    public function setCustomValidator($function_name){
	    $this->_validatorFunc = $function_name;
    }
    
    public function sysValidate($params){
	    return $params;
    }
    
    public function setView(){
    	extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
	    $this->_value = '';
    }
    
    public function isInCustomizer(){
	    return $this->_inCustomizer;
    }
    
    public function validate($params){
    	$params = $this->sysValidate($params);
	    if ($this->_validatorFunc != null) $params = call_user_func($this->_validatorFunc, $params);
	    return $params;
    }

}

class HiddenField extends FormField{
	
	public function setView(){
		
		extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
		
		$this->_value = '<!--'.$this->_option_label.'-->
		<input type="hidden" id="'.$this->_id.'" name="'.$this->_name.'" value="'.(get_option($this->_name) != '' ? get_option($this->_name) : '' ).'" />';
		
	}

}

class OnOffField extends FormField{
	
	public function setView(){
		
		extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
		
		if (!isset($default)) $default = 'off';
		
		$this->_value = '<div class="control-group"><div class="form-item">'.(($this->_option_label != '') ? '<label class="control-label">'.$this->_option_label.'</label>' : '').'<input type="hidden" id="'.$this->_id.'" name="'.$this->_name.'" value="'.(get_option($this->_name) != '' ? get_option($this->_name) : $default ).'" >';
		$this->_value .= '
		<div class="onoff '.((get_option($this->_name) != '') ? esc_attr(get_option($this->_name)) : $default ).' " id="'.esc_attr($this->_id).'_toggler">
			<div class="inner">
				<div class="icon-toggler"></div>
			</div>
		</div>
		</div>'.(isset($description) ? '<p class="desc-block">'.$description.'</p>' : '').'</div>';
		$this->_value .= '<script>
			jQuery("#'.esc_js($this->_id).'_toggler").onoffswitcher("#'.esc_js($this->_id).'");
		</script>';
		
		
	}
	
}


class CollectionField extends FormField{
	
	public function setView(){
		
		extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
	
		$first = true;
		
		if(!isset($default)) $default = '';
		
		if (get_option($this->_name) == '') $cur_value = $default; else $cur_value = get_option($this->_name);
		
		if(!isset($required)) $requiered = false;
				
		$this->_value = '<div class="control-group '.$this->_name.'">
			<div class="form-item">'.(($this->_option_label != '') ? '<label class="control-label">'.$this->_option_label.'</label>' : '').'';
		
		if (count($options) > 0)
			
		foreach($options as $option) {
		
			$this->_value .= '<div id="id-'.strtolower(preg_replace('/\s+/', '_', $option['value'])).'" class="collection"><input class="collection_field '.strtolower(preg_replace('/\s+/', '_', $option['value'])).'" type="radio" name="'.$this->_name.'" value="'.$option['value'].'" '.($cur_value == $option['value'] ? ' checked ' : (($cur_value === '') && $first && $required ) ? 'checked' : '' ).' /><span class="pattern_img"><img  src="'.$option['icon'].'" alt="'.$option['label'].'" /></span></div>';
				$first = false;		}
			
		$this->_value .= '</div>'.(isset($description) ? '<p class="desc-block">'.$description.'</p>' : '').'</div>';
	
	}
	
}

	class ColorPickerField extends FormField{
	
	public function setView(){
		
		extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
		
		if (!isset($default)) $default = '#fff';
		
		if (!isset($required)) $required = false;
		
		$this->_value = '<div class="control-group color-picker-container '.$this->_name.'">
				<label class="control-label">'.$this->_option_label.'</label>
			<div class="form_holder">
				<input class="picker_field"  data-validation-regex-regex="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" data-validation-regex-message="'. esc_html__('Must be HEX color value', 'storex').'" type="text" id="'.$this->_id.'" name="'.$this->_name.'"  '.(($required) ? ' required ' :  '' ).' value="'.((get_option($this->_name) !== '') ? get_option($this->_name) : $default ).'" />
				<a href="#" id="'.$this->_id.'_button" class="button color-picker">&nbsp;</a>
			</div>
			<div class="picker-container" id="container_'.$this->_name.'"><div class="close_picker"></div><div id="picker_'.$this->_name.'"></div></div>
		<p class="help-block"></p>'.(isset($description) ? '<p class="desc-block">'.$description.'</p>' : '').'</div>
		<script>
			
			jQuery(document).ready(function($) {
					
				$(\'#picker_'.$this->_name.'\').farbtastic(\'#'.$this->_id.'\');
				$(\'#'.$this->_id.'_button\').click(function(e){
					var offset = $(this).offset();
					e.preventDefault();
					var offset = $(this).offset();
					$(\'#container_'.$this->_name.'\').css(\'top\', offset.top - 40 + "px");
					$(\'#container_'.$this->_name.'\').css(\'left\', offset.left - 100 + "px");
					$(\'#container_'.$this->_name.'\').fadeIn(\'fast\');
				});
				
			});
		</script>
		';
	
	}
		
}

class MediauploadField extends FormField{
	
	public function setView() {
		
		extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
		
		if (!isset($required)) $required = false;
		
		if (!isset($button_label)) $button_label = esc_html__('Upload', 'storex');
		
		if (!isset($default)) $default = '';
		
		$this->_value = '<div class="control-group">'.(($this->_option_label != '') ? '<label class="control-label">'.$this->_option_label.'</label>' : '').'<div class="controls">';
		
		$this->_value .= '<div class="form_holder"><input class="upload_media" type="text" id="'.$this->_id.'" name="'.$this->_name.'" '.(($required) ? ' required ' :  '' ).'  value="'.get_option($this->_name).'" />';
		
		$this->_value .= '<a href="#" id="'.$this->_id.'_button" class="button '.$this->_id.'_button">'.$button_label.'</a></div>';
		
		$this->_value .= '<script>
					jQuery(document).ready(function($){
						var _custom_media = true, _orig_send_attachment = wp.media.editor.send.attachment;
						
						$("#'.$this->_id.'_button").click(function(e){
							e.preventDefault();
							var send_attachment_bkp = wp.media.editor.send.attachment;
							var button = $(this);
							var id = button.attr("id").replace("_button", "");
							
							_custom_media = true;
							
							wp.media.editor.send.attachment = function(props, attachment){
								if (_custom_media) {
									$("#"+id).val(attachment.url);
								} else {
									return _orig_send_attachment.apply(this, [props, attachment]);
								}
							}
							
							wp.media.editor.open(button);
							return false;	

						});
						
						$(".add_media").on("click", function(){
							_custom_media = false;
						});
					
					});
				</script>';
		
		$this->_value .= '<p class="help-block"></p>'.(isset($description) ? '<p class="desc-block">'.$description.'</p>' : '').'</div></div>';
		
	}
			
}


/*class CheckboxFieldMulty extends FormField{
	
	public function setView(){
		
		extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
		
		if (!isset($max)) $max = false;
		
		if (!isset($min)) $min = false;
		
		if (isset($required) && ($min <= 0)) $min = 1;
		
		if ($max > 1) $multy = true; else $multy = false;
		
		
		if (isset($options) && count($options)) {
			
			$first = true;
			
			$this->_value = '<div class="control-group">'.(($this->_option_label != '') ? '<label class="control-label">'.$this->_option_label.'</label>' : '').'<div class="controls">';
			
			foreach($options as $value => $title) {
			
				$this->_value .= '<input type="checkbox" class="'.strtolower(preg_replace('/\s+/', '_', $value)).'" name="'.$this->_name.''.($multy ? '[]' : '').'" '.(($max && $first) ? ' maxchecked="'.$max.'" ' : '').'  '.((isset($max_message) && $first )? ' data-validation-maxchecked-message="'.$max_message.'"' : '' ).'  '.((isset($min_message) && $first) ? ' data-validation-minchecked-message="'.$min_message.'"' : '' ).'  '.(($min && $first) ? ' minchecked="'.$min.'" ' : '').'   value="'.$value.'" '. (((is_array($opt = get_option($this->_name)) && (in_array($value, $opt)))) ? ' checked ' : ((!is_array($opt) && ($opt == $value)) ? ' checked ' : '' )).' ><span class="checkbox_value">'.$title.'</span></option>';
				$first = false;
			}
			
			$this->_value .= '<p class="help-block"></p>'.(isset($description) ? '<p class="desc-block">'.$description.'</p>' : '').'</div></div>';
			
		}
		
	}
	
	function sysValidate($params){
		return $params;
	}
	
	
}*/


class CheckboxField extends FormField{
	
	public function setView(){
		
		extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
		
			if (!isset($default)) $default = '';
		
			$this->_value = '<div class="control-group">'.(($this->_option_label != '') ? '<label class="control-label">'.$this->_option_label.'</label>' : '').'<div class="controls">';
			
				$this->_value .= '<input type="checkbox" class="'.strtolower(preg_replace('/\s+/', '_', get_option($this->_name) )).'" name="'.$this->_name.'" value="1" '.(get_option($this->_name) == '1' ? ' checked' : '').' ><span class="checkbox_value">'.$this->getLabel().'</span></option>';
			
			$this->_value .= '<p class="help-block"></p>'.(isset($description) ? '<p class="desc-block">'.$description.'</p>' : '').'</div></div>';
		
	}
	
}

class RadiobuttonField extends FormField{
	
	public function setView(){
		
		extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
		
		if (!isset($required)) $required = false;
		
		if ((get_option($this->_name) == '') && isset($default)) $cur_val = $default; else $cur_val = get_option($this->_name);
		
		if (isset($options) && count($options)) {
				
			$first = true;
			
			$this->_value = '<div class="control-group">'.(($this->getLabel() != '') ? '<label class="control-label">'.$this->getLabel().'</label>' : '').'<div class="controls">';
			
			foreach($options as $value => $label){
				$this->_value .= '<label class="radio_wrapper"><input type="radio" class="'.strtolower(preg_replace('/\s+/', '_', $value)).'" name="'.$this->_name.'" value="'.$value.'" '.($cur_val == $value ? ' checked ' : (($cur_val === '') && $first && $required ) ? 'checked' : '' ).' /><span class="radio_value">'.$label.'</span></label>';
				$first = false;
			}
			
			$this->_value .= '<p class="help-block"></p>'.(isset($description) ? '<p class="desc-block">'.$description.'</p>' : '').'</div></div>';
			
		}
		
	}
	
}

class SelectField extends FormField{
	
	public function setView(){
		
		extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
		
		if (!isset($required)) $required = false;
		
		if (isset($options) && count($options)) {
			if (!isset($class)) $class = '';
			$this->_value = '<div class="control-group">'.(($this->getLabel() != '') ? '<label class="control-label">'.$this->getLabel().'</label>' : '').'<div class="controls">
			<select id="'.$this->_id.'" class="'.$class.'" name="'.$this->_name.'" '.(($required) ? ' required ' :  '' ).' >';
				
			foreach($options as $value => $title) {
				$this->_value .= '<option class="'.strtolower(preg_replace('/\s+/', '_', $value)).'" value="'.$value.'" '.(get_option($this->_name) == $value ? ' selected ' : '').' >'.$title.'</option>';
			}
			
			$this->_value .= '</select><p class="help-block"></p>'.(isset($description) ? '<p class="desc-block">'.$description.'</p>' : '').'</div></div>';
			
		}
		
	}

}

class TextareaField extends FormField{
	
	public function setView(){
		
		extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
				
		if (isset($size)) { $cols = $size[0]; $rows = $size[1]; } else { $cols = 20; $rows = 5; /* Default TextArea Size */ }
		if (!isset($required)) $required = false;
		
		$this->_value = '<div class="control-group">
			'.(($this->_option_label != '') ? '<label class="control-label">'.$this->_option_label.'</label>' : '').'
			<div class="controls">
			<textarea id="'.$this->_id.'" name="'.$this->_name.'" '.(($required) ? ' required ' :  '' ).' rows="'.$rows.'" cols="'.$cols.'" >'.((get_option($this->_name) != '') ? get_option($this->_name) : '').'</textarea><p class="help-block"></p>
			'.(isset($description) ? '<p class="desc-block">'.$description.'</p>' : '').'</div>
		</div>';
		
	}
		
}


class TextField extends FormField{
	
	public function setView(){
		
		extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
		
		if (!isset($required)) $required = false;
		
		$this->_value = '<div class="control-group">
			'.(($this->_option_label != '') ? '<label class="control-label">'.$this->_option_label.'</label>' : '').'
			<div class="controls">
			<input type="text" id="'.$this->_id.'" name="'.$this->_name.'" value="'.((get_option($this->_name) != '') ? get_option($this->_name) : '').'" '.(($required) ? ' required ' :  '' ).' /><p class="help-block"></p>
			'.(isset($description) ? '<p class="desc-block">'.$description.'</p>' : '').'</div>
		</div>';
		
	}
	
}


class EmailField extends FormField{
	
	public function setView(){
		
		extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
		
		if (!isset($required)) $required = false;
		
		$this->_value = '<div class="control-group">
			'.(($this->_option_label != '') ? '<label class="control-label">'.$this->_option_label.'</label>' : '').'
			<div class="controls">
			<input type="email" id="'.$this->_id.'" name="'.$this->_name.'" value="'.((get_option($this->_name) != '') ? get_option($this->_name) : '').'" '.(($required) ? ' required ' :  '' ).' /><p class="help-block"></p>
			'.(isset($description) ? '<p class="desc-block">'.$description.'</p>' : '').'</div>
		</div>';
		
	}
			
}

class NumberField extends FormField{
	
	public function setView(){
		
		extract($this->_additional_params, EXTR_PREFIX_SAME, "extra");
		
		if (!isset($required)) $required = false;
		
		$this->_value = '<div class="control-group">
			'.(($this->_option_label != '') ? '<label class="control-label">'.$this->_option_label.'</label>' : '').'
			 <div class="controls">
			<input type="number" id="'.$this->_id.'" name="'.$this->_name.'" '.(isset($min) ? 'min="'.$min.'"' : '').' '.(isset($max) ? 'max="'.$max.'"' : '').' value="'.((get_option($this->_name) != '') ? get_option($this->_name) : '').'" '.(($required) ? ' required ' :  '' ).' /><p class="help-block"></p>
			'.(isset($description) ? '<p class="desc-block">'.$description.'</p>' : '').'</div>
		</div>';
		
	}
	
}

class OptionFactory{
	
	public static $optionCollection = array();
	
	public static function create($option_id, $option_name, FieldType $type, $section_id, $option_label = '', $additional_params = array(), $in_customizer = false){
		
		foreach(self::$optionCollection as $field) {
			if ($option_name == $field->getName()) throw new Exception(sprintf(esc_html__('Option with name: %s already exists', 'storex'), $option_name));
		}
		
		switch($type){
			
			case FieldType::$HIDDEN : { 
					$obj = new HiddenField($option_id, $option_name, $section_id, $option_label, $additional_params, $in_customizer);
					self::addOptionToRegistger($obj);
					return $obj;
				
				} break;
			
			case FieldType::$TEXT : { 
					$obj = new TextField($option_id, $option_name, $section_id, $option_label, $additional_params, $in_customizer);
					self::addOptionToRegistger($obj);
					return $obj;
				
				} break;
				
			case FieldType::$TEXTAREA : { 
					$obj = new TextareaField($option_id, $option_name, $section_id, $option_label, $additional_params, $in_customizer);
					self::addOptionToRegistger($obj);
					return $obj;
				
				} break;
				
			case FieldType::$NUMBER : { 
					$obj = new NumberField($option_id, $option_name, $section_id, $option_label, $additional_params, $in_customizer);
					self::addOptionToRegistger($obj);
					return $obj;
				
				} break;
				
			case FieldType::$EMAIL : { 
					$obj = new EmailField($option_id, $option_name, $section_id, $option_label, $additional_params, $in_customizer);
					self::addOptionToRegistger($obj);
					return $obj;
				
				} break;
				
			 default: break;
			 
			 case FieldType::$SELECT : { 
					$obj = new SelectField($option_id, $option_name, $section_id, $option_label, $additional_params, $in_customizer);
					self::addOptionToRegistger($obj);
					return $obj;
				
				} break;
				
			case FieldType::$CHECKBOX : { 
					$obj = new CheckboxField($option_id, $option_name, $section_id, $option_label, $additional_params, $in_customizer);
					self::addOptionToRegistger($obj);
					return $obj;
				
				} break;
			
			case FieldType::$RADIOBUTTON : { 
					$obj = new RadiobuttonField($option_id, $option_name, $section_id, $option_label, $additional_params, $in_customizer);
					self::addOptionToRegistger($obj);
					return $obj;
				
				} break;
				
			case FieldType::$MEDIAUPLOAD : { 
					$obj = new MediauploadField($option_id, $option_name, $section_id, $option_label, $additional_params, $in_customizer);
					self::addOptionToRegistger($obj);
					return $obj;
				
				} break;
				
			case FieldType::$COLORPICKER : { 
					$obj = new ColorPickerField($option_id, $option_name, $section_id, $option_label, $additional_params, $in_customizer);
					self::addOptionToRegistger($obj);
					return $obj;
				
				} break;
				
			case FieldType::$COLLECTION : { 
					$obj = new CollectionField($option_id, $option_name, $section_id, $option_label, $additional_params, $in_customizer);
					self::addOptionToRegistger($obj);
					return $obj;
				
				} break;
			
			case FieldType::$ONOFF : { 
					$obj = new OnOffField($option_id, $option_name, $section_id, $option_label, $additional_params, $in_customizer);
					self::addOptionToRegistger($obj);
					return $obj;
				
				} break;
			
			
			 //default: break;
		}
		
	}
	
	
	public static function addOptionToRegistger($obj){
		self::$optionCollection[] = $obj;
	}
	
}




class PanelSectionFactory{
	
	protected $sectionTitle = '';
	protected $sectionId = '';
	protected $sectionIcon = array(1,1);
	protected $sectionDescription = '';
	protected $sectionContent = '';
	
	public static $availableSections = array();
	
	function __construct($sectionId, $sectionTitle, $sectionIcon, $sectionDescription = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'){
		self::registerSection($sectionId);
		$this->sectionTitle = $sectionTitle;
		$this->sectionId = $sectionId;
		$this->sectionIcon = $sectionIcon;
		$this->sectionDescription = $sectionDescription;					
	} 
	
	function setContent($content){
		$this->sectionContent = $content;	
	}
	
	public static function registerSection($sectionId){
		
		if (in_array($sectionId, self::$availableSections)) throw new Exception(sprintf(esc_html__('Sections with ID: %s already exists', 'storex'), $sectionId));
		self::$availableSections[] = $sectionId;
	
	}
	
	public function getTitle(){
		return $this->sectionTitle;
	}
	
	public function getId(){
		return $this->sectionId;
	}
	
	public function getIcon(){
		return $this->sectionIcon;
	}
	
	public function getDescription(){
		return $this->sectionDescription;
	}
	
	public function getContent(){
		return $this->sectionContent;
	}
}
	
class PTPanel{

	var $panelName = 'PlumTree';
	var $panelPrefix = 'pt_';
	var $customScripts = array();
	var $customStyles = array();
	
	protected $panelSections; 
	
	function __construct(){
		
		$this->init();
		
	}
	
	function addSection($sectionObj){
		$this->panelSections[] = $sectionObj;
	}
	
	function addPanelScripts($user_script_id, $user_script){
		$this->customScripts = array_merge($this->customScripts, array($user_script_id => $user_script));
	}
	
	function addPanelStyles($user_style_id, $user_style){
		$this->customStyles = array_merge($this->customStyles, array($user_style_id => $user_style));
	}
	
	function init(){
		add_action('admin_menu', array(&$this, 'createPanel'));
		add_action('admin_init', array(&$this, 'registerSettings'));
		add_action('customize_register' , array( &$this, 'registerCustomizer' ));
		add_action('customize_preview_init' , array( &$this, 'livePreview' ));
		add_action('customize_controls_print_scripts', array(&$this, 'customizerScripts'));
	}
	
	public function livePreview(){
		
		wp_enqueue_script( 
           'ptpanel-themecustomizer', //Give the script an ID
           get_template_directory_uri().'/js/theme-customizer.js', //Define it's JS file
           array( 'jquery','customize-preview' ), //Define dependencies
           '', //Define a version (optional) 
           true //Specify whether to put in footer (leave this true)
      );
		
	}
	
	public function customizerScripts(){
		
		wp_enqueue_script('ptpanel-formstyler-js',get_template_directory_uri() . '/ptpanel/js/formstyler.min.js', array('jquery'));
		wp_enqueue_style('ptpanel-formstyler-css', get_template_directory_uri() . '/ptpanel/css/formstyler.css');
		wp_enqueue_style('ptpanel-css', get_template_directory_uri() . '/ptpanel/css/styles.css');
		//wp_enqueue_script('ptpanel-bootstrap-validator-js',get_template_directory_uri() . '/ptpanel/js/jquery.validator.js');
		wp_enqueue_script('ptpanel-customize-js',get_template_directory_uri() . '/ptpanel/js/customize.js', array('jquery'));
		
		if (count($this->customScripts))
			    foreach($this->customScripts as $script_id => $script){
					wp_enqueue_script($script_id, $script);
				}
			    
		if (count($this->customStyles))
			    foreach($this->customStyles as $style_id => $style){
				    wp_enqueue_style($style, $style);
			    }
		
	}
	
	public function registerCustomizer($wp_customize){
	
		require_once( trailingslashit( get_template_directory() ). '/ptpanel/inc/CustomizerControls.php');
		
		foreach ($this->panelSections as $section) {
			$wp_customize->add_section( $section->getId(), 
		         array(
		            'title' => $section->getTitle(),
		            'priority' => 100, 
		            'capability' => 'edit_theme_options', 
		            'description' => $section->getDescription(), 
		         )
		    );
		}
		
		
		
		/*foreach(OptionFactory::$optionCollection as $field){
			
			if ($field->isInCustomizer()) {
				
				$wp_customize->add_setting( $field->getName(), 
				
			         array(
			            'default' => '', 
			            'type' => 'option', 
			            'capability' => 'edit_theme_options', 
			            'transport' => 'postMessage', 
			         ) 
			    ); 
			
			}
		}*/
		
		/*foreach(OptionFactory::$optionCollection as $field){
			
			if ($field->isInCustomizer()) {
				
				$control = null;
				
				switch(get_class($field)) {
					
					case 'TextField' : $control = new PT_Customize_Text_Control($wp_customize, 
			         $field->getId(), 
				         array(
				            'label' => $field->getLabel(), 
				            'section' => $field->getSection(), 
				            'settings' => $field->getName(), 
				            'priority' => 10, 
				         ) 
				      ); break;
				     
				    case 'TextareaField' : $control = new PT_Customize_Textarea_Control($wp_customize, 
			         $field->getId(), 
				         array(
				            'label' => $field->getLabel(), 
				            'section' => $field->getSection(), 
				            'settings' => $field->getName(),
				            'type' => 'textarea', 
				            'priority' => 10, 
				         ) 
				      ); break;
				      
				    case 'RadiobuttonField' : 
				     
				     $params = $field->getParams();
				      
				     $control = new PT_Customize_Radio_Control($wp_customize, 
			         $field->getId(), 
				         array(
				            'label' => $field->getLabel(), 
				            'section' => $field->getSection(), 
				            'settings' => $field->getName(),
				            'type' => 'radio',
				            'choices' => $params['options'],
				            'priority' => 10, 
				         ) 
				      ); break;
				      
				    case 'NumberField' : $control = new PT_Customize_Number_Control($wp_customize, 
			         $field->getId(), 
				         array(
				            'label' => $field->getLabel(), 
				            'section' => $field->getSection(), 
				            'settings' => $field->getName(), 
				            'priority' => 10, 
				         ) 
				      ); break;
				      
				    case 'EmailField' : $control = new PT_Customize_Email_Control($wp_customize, 
			         $field->getId(), 
				         array(
				            'label' => $field->getLabel(), 
				            'section' => $field->getSection(), 
				            'settings' => $field->getName(), 
				            'priority' => 10,
				         ) 
				      ); break;
				      
				    case 'SelectField' : 
				      
				     $params = $field->getParams();
				     $options = $params['options'];
				     	     
				     $control = new PT_Customize_Select_Control($wp_customize, 
			         $field->getId(), 
				         array(
				            'label' => $field->getLabel(), 
				            'section' => $field->getSection(), 
				            'settings' => $field->getName(),
				            'type' => 'select',
				            'choices' => $options,
				            'priority' => 10, 
				         ) 
				      ); break;
				      
				    case 'CheckboxField' : $control = new PT_Customize_Checkbox_Control($wp_customize, 
				      	$field->getId(), 
				         array(
				            'label' => $field->getLabel(), 
				            'section' => $field->getSection(), 
				            'settings' => $field->getName(),
				            'type' => 'checkbox',
				            'priority' => 10
				         ) 
				      ); break;
				      
				    case 'MediauploadField' : $control = new WP_Customize_Image_Control($wp_customize, 
				      	$field->getId(),
				      	array(
				            'label' => $field->getLabel(), 
				            'section' => $field->getSection(), 
				            'settings' => $field->getName(), 
				            'priority' => 10, 
				         )	
				      ); break;
				      
				    case 'ColorPickerField' : $control = new WP_Customize_Color_Control($wp_customize, 
				      	$field->getId(),
				      	array(
				            'label' => $field->getLabel(), 
				            'section' => $field->getSection(), 
				            'settings' => $field->getName(), 
				            'priority' => 10, 
				         )	
				      ); break;
				      
				    case 'CollectionField' :
				      
				      $params = $field->getParams();
				      
				       $control = new PT_Customize_Collection_Control($wp_customize, 
				      	$field->getId(),
				      	array(
				            'label' => $field->getLabel(), 
				            'section' => $field->getSection(), 
				            'settings' => $field->getName(),
				            'choices' => $params['options'],
				            'priority' => 10, 
				         )	
				      ); break;
					
				}
				
				foreach(OptionFactory::$optionCollection as $field){
			
					if ($field->isInCustomizer()) {
						$wp_customize->get_setting($field->getName())->transport = 'postMessage';
					}
				
				}

				$wp_customize->add_control($control);

			}
		
		}*/
		
	}

	function createPanel(){
		$page = add_theme_page($this->panelName, $this->panelName, 'administrator', 'pt-panel', array(&$this, 'loadPanel'));
		add_action( 'admin_print_scripts-'.$page, array(&$this, 'loadAssets'));
	}

	function loadAssets(){
		if ( is_admin() ) { 
		
				wp_enqueue_style('ptpanel-bootstrap-css', get_template_directory_uri() . '/ptpanel/css/bootstrap.min.css');
				wp_enqueue_style('ptpanel-formstyler-css', get_template_directory_uri() . '/ptpanel/css/formstyler.css');
				wp_enqueue_style('ptpanel-css', get_template_directory_uri() . '/ptpanel/css/styles.css');
				wp_enqueue_style('ptpanel-font', 'http://fonts.googleapis.com/css?family=Open+Sans');
				wp_enqueue_style('ptpanel-farbtastic-css', get_template_directory_uri() . '/ptpanel/css/farbtastic.css');
				
				wp_enqueue_script('ptpanel-bootstrap-js',get_template_directory_uri() . '/ptpanel/js/bootstrap.min.js');
				wp_enqueue_script('ptpanel-jquery-cookie-js',get_template_directory_uri() . '/ptpanel/js/cookie.jquery.js');
				wp_enqueue_script('ptpanel-bootstrap-validator-js',get_template_directory_uri() . '/ptpanel/js/jquery.validator.js');
			   	wp_enqueue_script('ptpanel-formstyler-js',get_template_directory_uri() . '/ptpanel/js/formstyler.min.js', array('jquery'));
			   	wp_enqueue_script('ptpanel-farbtastic-js',get_template_directory_uri() . '/ptpanel/js/farbtastic.js', array('jquery'));
			   	wp_enqueue_script('ptpanel-js',get_template_directory_uri() . '/ptpanel/js/helper.js', array('jquery'));
			    wp_enqueue_media();
			    
			    if (count($this->customScripts))
			    foreach($this->customScripts as $script_id => $script){
				    wp_enqueue_script($script_id, $script);
			    }
			    
			    if (count($this->customStyles))
			    foreach($this->customStyles as $style_id => $style){
				    wp_enqueue_style($style, $style);
			    }
			    
			    
			   	
		}
	}
	
	function registerSettings(){
		foreach(OptionFactory::$optionCollection as $option){
			register_setting($this->panelPrefix, $option->getName(), array($option, 'validate'));
		}
	}
	
	function collectSettings(){
		
		$collection = array();
		
		foreach(OptionFactory::$optionCollection as $option){
			$collection[] = array($option->getName() => get_option($option->getName()));
		}
		
		//print(base64_encode(serialize($collection)));
		
		die();
		
	}
	
	function setSettings($sets){
		
		/*$options = unserialize(base64_decode($sets));
		
		foreach($options as $opt){
			foreach($opt as $name => $value) {
				
				update_option($name, $value);
			
			}
		} */
	}
	
	function loadPanel(){
		if (!current_user_can('manage_options'))  {
			wp_die( esc_html__('You do not have sufficient permissions to access this page.', 'storex') );
		}
		$this->displayPanel();
	}
	
	function displayPanel(){
				
		echo '<style>';
		
		foreach ($this->panelSections as $section) {
			
			$icon = $section->getIcon();
			
			echo 'li #icon-'.$section->getId().'{ 
				background-position: '.($icon[0] * (-25)).'px '.($icon[1] * (-25)).'px !important;
			 }' ;
			 
			 echo 'li.active #icon-'.$section->getId().'{ 
				background-position: '.($icon[0] * (-25)).'px '.(($icon[1] + 4 ) * (-25)).'px !important;
			 }' ;
		
		}
		
		echo '</style>';
		
		echo '<div class="ptpanel"><div class="ptpanel-head wrap"><div class="icon32" id="icon-themes"><br/></div><h2>'.$this->panelName.'</h2></div>';
		echo settings_errors();
		echo '<form method="post" action="options.php">';
		settings_fields($this->panelPrefix);
		echo '<ul class="nav nav-tabs" id="ptpanel-tabs">';
		
		foreach ($this->panelSections as $section) {
			
			echo '<li><a href="#'.$section->getId().'"><span id="icon-'.$section->getId().'" class="icon"></span>'.$section->getTitle().'</a></li>';
		
		}
	    
	    echo '</ul><div class="tab-content">';
	    
	    foreach ($this->panelSections as $section) {
	    
		    echo '<div class="tab-pane active fade in" id="'.$section->getId().'">'.($section->getDescription() != '' ? '<p>'.$section->getDescription().'</p>':'').$section->getContent().'</div>';
		    
		}
		
		echo '<p class="submit"><button type="submit" class="btn btn-primary" id="submit" name="submit">'.esc_html__('Save Changes', 'storex').'</button></p>';
		echo '</div></form></div>';
			

	}

}
