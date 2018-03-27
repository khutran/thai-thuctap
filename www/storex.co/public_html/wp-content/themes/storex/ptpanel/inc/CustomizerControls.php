<?php 

require_once( ABSPATH . WPINC . '/class-wp-customize-setting.php' );
require_once( ABSPATH . WPINC . '/class-wp-customize-section.php' );
require_once( ABSPATH . WPINC . '/class-wp-customize-control.php' );

class PT_Customize_Text_Control extends WP_Customize_Control{
	
	public $type = 'text';
	
}

class PT_Customize_Number_Control extends WP_Customize_Control{
	
	public $type = 'number';
	
	public function render_content(){
		?>	<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input type="number" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
			</label>
		<?php
	}
	
}

class PT_Customize_Email_Control extends WP_Customize_Control{
	
	public $type = 'email';
	
	public function render_content(){
		?>	<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input type="email" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
			</label>
		<?php
	}
}

class PT_Customize_Radio_Control extends WP_Customize_Control{
	
	public $type = 'radio';
		
}

class PT_Customize_Checkbox_Control extends WP_Customize_Control{
	
	public $type = 'checkbox';
		
}

class PT_Customize_Textarea_Control extends WP_Customize_Control{
	
	public $type = 'textarea';
		
	public function render_content() {
        ?>
	        <label>
		        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		        <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
	        </label>
        <?php
    }
	
}

class PT_Customize_Select_Control extends WP_Customize_Control{
	
	public $type = 'select';
		
	public function render_content() {
		?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select <?php $this->link(); ?>>
					<?php foreach ( $this->choices as $value => $label )
					echo '<option class="'.strtolower(preg_replace('/\s+/', '_', $value)).'" value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
					?>
				</select>
			</label>
		<?php
    }
    
}

class PT_Customize_Collection_Control extends WP_Customize_Control{
	
	public $type = 'collection';
	
	public function render_content() {
		$name = '_customize_radio_' . $this->id;

		?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php foreach ( $this->choices as $option ) { ?>

					<label><div class="collection">
						<input class="collection_field" type="radio" value="<?php echo esc_attr( $option['value'] ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $option['value'] ); ?> />
						<span class="pattern_img"><img src="<?php echo $option['icon']?>" alt="<?php echo $option['label']?>" /></span>
					</div></label>
		<?php }
	}
}
