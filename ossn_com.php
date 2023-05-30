<?php
/**
 * Open Source Social Network
 *
 * @package   (softlab24.com).ossn
 * @author    OSSN Core Team <info@softlab24.com>
 * @copyright (C) SOFTLAB24 LIMITED
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */

define('doublecaptcha', ossn_route()->com . 'doublecaptcha/');

/**
 * doublecaptcha initialize
 * 
 * @return void
 */
function doublecaptcha_init() {
		ossn_register_page('doublecaptcha', 'doublecaptcha_page_handler');
		ossn_extend_view('forms/signup/before/submit', 'doublecaptcha/view');
		ossn_extend_view('forms/resetlogin/before/submit', 'doublecaptcha/view');
		ossn_register_callback('action', 'load', 'doublecaptcha_check');
}
/**
 * doublecaptcha the actions which you wanted to validate
 *
 * @return array
 */
function doublecaptcha_actions_validate() {
		return ossn_call_hook('doublecaptcha', 'actions', false, array(
				'user/register',
				'resetlogin',
		));
}
/**
 * Validate the doublecaptcha actions
 *
 * @param string $callback  The callback type
 * @param string $type      The callback type
 * @param array  $params    The option values
 * 
 * @return string
 */
function doublecaptcha_check($callback, $type, $params) {
		$doublecaptcha = input('doublecaptcha_text');
		$token   = input('doublecaptcha');
		if(isset($params['action']) && in_array($params['action'], doublecaptcha_actions_validate()) && !doublecaptcha_verify($doublecaptcha, $token)) {
				if($params['action'] == 'user/register') {
						header('Content-Type: application/json');
						echo json_encode(array(
								'dataerr' =>  ossn_print('doublecaptcha:error'),
						));
						exit;
				} else {
						ossn_trigger_message(ossn_print('doublecaptcha:error'), 'error');
						redirect(REF);
				}
		}
}
/**
 * doublecaptcha image generate
 *
 * @return mixed
 */
function doublecaptcha_page_handler($args) {
		$token = $args[0];
		if(empty($token)) {
				ossn_error_page();
		}
		header("Content-type: image/jpeg");
		$doublecaptcha = doublecaptcha_generate($token);
		$n       = rand(1, 5);
		$image   = imagecreatefromjpeg(doublecaptcha . "images/bg$n.jpg");
		$colour  = imagecolorallocate($image, 0, 0, 0);
		imagettftext($image, 30, 0, 10, 30, $colour, doublecaptcha . "fonts/1.ttf", $doublecaptcha);
		imagejpeg($image);
		imagedestroy($image);
}
/**
 * Generate the doublecaptcha token
 *
 * @return string
 */
function doublecaptcha_generate_token() {
		return md5(ossn_generate_action_token('c') . rand());
}
/**
 * Generate a doublecaptcha based on the given seed value and length.
 *
 * @param string $seed_token
 * @return string
 */
function doublecaptcha_generate($seed_token) {
		return strtolower(substr(md5(ossn_generate_action_token('c') . $seed_token), 0, 5));
}

/**
 * Verify a doublecaptcha based on the input value entered by the user and the seed token passed.
 *
 * @param string $input_value
 * @param string $seed_token
 * @return bool
 */
function doublecaptcha_verify($input_value, $seed_token) {
		if(strcasecmp($input_value, doublecaptcha_generate($seed_token)) == 0) {
				return true;
		}
		
		return false;
}
ossn_register_callback('ossn', 'init', 'doublecaptcha_init');