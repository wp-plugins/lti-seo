<?php namespace Lti\Seo\Plugin;

/**
 * Used to spot field types on forms and display values accordingly
 *
 * Class Fields
 * @package Lti\Seo\Plugin
 */
abstract class Fields {
	public $value;
	public $isTracked;

	public function __construct( $value, $default = "", $isTracked = false ) {
		$this->isTracked = $isTracked;
		if ( $value ) {
			$this->value = sanitize_text_field( stripslashes($value) );
		} else {
			$this->value = $default;
		}
	}
}

class Field_Checkbox extends Fields {
	public function __construct( $value, $default = false, $isTracked = false ) {
		$this->isTracked = $isTracked;
		if ( $value === true || (int) $value === 1 || $value === "true" || $value === 'on' ) {
			$this->value = true;
		} else if ( $value === false ) {
			$this->value = false;
		} else {
			$this->value = $default;
		}
	}
}

class Field_Radio extends Fields {

	public function __construct( $value, $default = "", $isTracked = false ) {
		$this->isTracked = $isTracked;
		if ( is_array( $default ) ) {
			$defaults = array_flip( $default['choice'] );
			if ( $value ) {
				if ( isset( $defaults[ $value ] ) ) {
					$this->value = $value;
				} else {
					$this->value = $default['default'];
				}
			} else {
				$this->value = $default['default'];
			}
		} else {
			$this->value = null;
		}
	}
}

class Field_Text extends Fields {
}

class Field_String extends Fields {

}

class Field_Url extends Fields {
	public function __construct( $value, $default = "", $isTracked = false ) {
		$this->isTracked = $isTracked;
		if ( $value && ! filter_var( $value, FILTER_VALIDATE_URL ) === false ) {
			$this->value = $value;
		} else {
			$this->value = $default;
		}
	}

}

class Field_Html extends Fields {
	public function __construct( $value, $default = "", $isTracked = false ) {
		$this->isTracked = $isTracked;
		if ( $value) {
			$this->value = $value;
		} else {
			$this->value = $default;
		}
	}

}
