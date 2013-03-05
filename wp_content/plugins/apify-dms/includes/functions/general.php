<?php
/**
 * 通用函数集
 *
 * @package    includes
 * @subpackage functions
 *
 * @copyright Copyright (c) 2013, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */

/**
 * 根据参数生成过滤器参数
 */
function apify_generate_filters_from_params($filter_keys, $request_params) {
	$filters = array();

	if( !empty($filter_keys) ) {
		foreach( $filter_keys as $k ) {
			if( isset($request_params[$k]) && ($request_params[$k] != '-1') ) {
				$filters[$k] = $request_params[$k];
			}
		}
	}

	return $filters;
}

/**
 *  Output a selection field - alias function for zen_draw_checkbox_field() and zen_draw_radio_field()
 */
function apify_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    $selection = '<input type="' . apify_output_string($type) . '" name="' . zen_output_string($name) . '"';

    if (!empty($value))
        $selection .= ' value="' . apify_output_string($value) . '"';

    if (($checked == true) || (isset ($GLOBALS[$name]) && is_string($GLOBALS[$name]) && (($GLOBALS[$name] == 'on') || (isset ($value) && (stripslashes($GLOBALS[$name]) == $value))))) {
        $selection .= ' checked="checked"';
    }

    if (!empty($parameters))
        $selection .= ' ' . $parameters;

    $selection .= ' />';

    return $selection;
}

/**
 *  Output a form checkbox field
 */
function apify_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return apify_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
}

/**
 * Output a form radio field
 */
function apify_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return apify_draw_selection_field($name, 'radio', $value, $checked, $parameters);
}

/**
 *  Output a form pull down menu
 *  Pulls values from a passed array, with the indicated option pre-selected
 */
function apify_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $styleclass = '') {

    if($styleclass == ''){
        $field = '<select name="' . apify_output_string($name) . '"';
    }
    else{
        $field = '<select class="'.$styleclass.'" name="' . apify_output_string($name) . '"';
    }

    if (!empty($parameters))
        $field .= ' ' . $parameters;

    $field .= '>';
    if (empty ($default) && isset ($GLOBALS[$name]))
        $default = stripslashes($GLOBALS[$name]);

    foreach ($values as $id=>$value) {
        if(preg_match('/^([\-]{2}).+?([\-]{2})/i',$value)){
			/*
            $field .= '  <optgroup label="' . apify_output_string($value, array (
            '"' => '&quot;',
            '\'' => '&#039;',
            '<' => '&lt;',
            '>' => '&gt;'
			)) .'"></optgroup>';*/
			$field .= '  <option value="-1">' . apify_output_string($value, array (
            '"' => '&quot;',
            '\'' => '&#039;',
            '<' => '&lt;',
            '>' => '&gt;'
			)) .'</option>';
        }else {
			$field .= '  <option value="' . apify_output_string($id) . '"';
			if ($default == $id) {
				$field .= ' selected="selected"';
			 }
			$field .= '>' . apify_output_string($value, array (
				'"' => '&quot;',
				'\'' => '&#039;',
				'<' => '&lt;',
				'>' => '&gt;'
			)) . '</option>';
        }
    }
    $field .= '</select>';

    return $field;
}

/**
 * Returns a string with conversions for security.
 *
 * @param string The string to be parsed
 * @param string contains a string to be translated, otherwise just quote is translated
 * @param boolean Do we run htmlspecialchars over the string
 * @return string
 */
function apify_output_string($string = "", $translate = false, $protected = false) {
    if (is_array($string)) {
        return;
    }
    if ($protected == true) {
        return htmlspecialchars($string);
    } else {
        if ($translate == false) {
            return  strtr(trim($string) , array(
                '"' => '&quot;'
            ));
        } else {
            return strtr(trim($string), $translate);
        }
    }
}


/**
 * 非空判断
 * 
 * @param string $value
 */
function apify_not_null($value) {
	if (is_array($value)) {
		if (sizeof($value) > 0) {
			return true;
		} else {
			return false;
		}
	} else {
		if ( (is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Return a random value
 * 
 * @param integer $min
 * @param integer $max
 */
function apify_rand($min = null, $max = null) {
	static $seeded;

	if (!$seeded) {
		mt_srand((double)microtime()*1000000);
		$seeded = true;
	}

	if (isset($min) && isset($max)) {
		if ($min >= $max) {
			return $min;
		} else {
			return mt_rand($min, $max);
		}
	} else {
		return mt_rand();
	}
}

/**
 * 数据入库前处理
 */
function apify_db_prepare_input($string) {
    if (is_string($string)) {
      	return trim(stripslashes($string));
    } elseif (is_array($string)) {
		reset($string);
      	while (list($key, $value) = each($string)) {
        	$string[$key] = apify_db_prepare_input($value);
      	}
      	return $string;
    } else {
      	return $string;
    }
}

