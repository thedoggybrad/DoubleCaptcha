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
$token = doublecaptcha_generate_token();
?>
<div class="margin-top-10">
	<img src="<?php echo ossn_site_url("doublecaptcha/{$token}");?>" />
	<input type="text" name="doublecaptcha_text" class="margin-top-10" placeholder="<?php echo ossn_print('doublecaptcha:text');?>" />
</div>
<input type="hidden" name="doublecaptcha" value="<?php echo $token;?>" />

