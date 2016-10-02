<?php
/**
* @file plugins/generic/oauth/pages/OauthHandler.inc.php
*
* Copyright (c) 2015-2016 University of Pittsburgh
* Copyright (c) 2014-2016 Simon Fraser University Library
* Copyright (c) 2003-2016 John Willinsky
* Distributed under the GNU GPL v2 or later. For full terms see the file docs/COPYING.
*
* @class OauthHander
* @ingroup plugins_generic_oauth
*
* @brief Handle return call from OAuth
*/
import('classes.handler.Handler');
class OauthHandler extends Handler {

	/**
	* Authorize handler
	* @param $args array
	* @param $request Request
	*/
	function oauthAuthorize($args, $request) {
		$context = Request::getContext();
		$op = Request::getRequestedOp();
		$plugin =& PluginRegistry::getPlugin('generic', 'oauthplugin');
		$contextId = ($context == null) ? 0 : $context->getId();
		// fetch the access token
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $plugin->getSetting($contextId, 'oauthUrl').OAUTH_TOKEN_URL,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array('Accept: application/json'),
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query(array(
				'code' => Request::getUserVar('code'),
				'grant_type' => 'authorization_code',
				'client_id' => $plugin->getSetting($contextId, 'orcidClientId'),
				'client_secret' => $plugin->getSetting($contextId, 'orcidClientSecret')
			))
		));
		$result = curl_exec($curl);
		$response = json_decode($result, true);
		# TODO: authorize user on success, message on failure
	}
}
?>
