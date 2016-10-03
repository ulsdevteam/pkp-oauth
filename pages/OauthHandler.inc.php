<?php
/**
* @file plugins/generic/oauth/pages/OauthHandler.inc.php
*
* Copyright (c) 2015-2016 University of Pittsburgh
* Copyright (c) 2014-2016 Simon Fraser University Library
* Copyright (c) 2003-2016 John Willinsky
* Distributed under the GNU GPL v2 or later. For full terms see the file docs/COPYING.
*
* @class OauthHandler
* @ingroup plugins_generic_oauth
*
* @brief Handle return call from OAuth
*/

import('classes.handler.Handler');

class OauthHandler extends Handler {
	function oauthAuthorize($args, $request) {
		$context = $request->getContext();
		$plugin = PluginRegistry::getPlugin('generic', 'oauthplugin');
		$contextId = ($context == null) ? 0 : $context->getId();

		// fetch the access token
		$curl = curl_init();
		curl_setopt_array($curl, array(
				CURLOPT_URL => $plugin->getSetting($contextId, 'orcidProfileAPIPath').OAUTH_OAUTH_TOKEN_URL,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => array('Accept: application/json'),
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => http_build_query(array(
						'code' => $request->getUserVar('code'),
						'grant_type' => 'authorization_code',
						'client_id' => $plugin->getSetting($contextId, 'orcidClientId'),
						'client_secret' => $plugin->getSetting($contextId, 'orcidClientSecret')
				))
		));
		$result = curl_exec($curl);
		$response = json_decode($result, true);

		// we could need the further ORCID information,
		// e.g. for the whole ORCID ID URI, we will search the user after, or
		// user given name and surname, e.g. to compare ORCID and OJS user names, or
		// to prefill the registration form, if there is no such OJS user:
		curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL =>	$url = $plugin->getSetting($contextId, 'orcidProfileAPIPath') . OAUTH_ORCID_API_VERSION_URL . urlencode($response['orcid']) . '/' . OAUTH_ORCID_PROFILE_URL,
				CURLOPT_POST => false,
				CURLOPT_HTTPHEADER => array('Accept: application/json'),
		));
		$result = curl_exec($curl);
		$info = curl_getinfo($curl);
		if ($info['http_code'] == 200) {
			$json = json_decode($result, true);
		}

		$userDao = DAORegistry::getDAO('UserDAO');
		$userByAuthId = $userDao->getBySetting('orcid', $json['orcid-profile']['orcid-identifier']['uri']);
		if ($userByAuthId) {
			// register user session -- log in user.
			$reason = null;
			Validation::registerUserSession($userByAuthId, $reason);

			echo '<html><body><script type="text/javascript">
						opener.location.reload();
						window.close();
					</script></body></html>';
		} else {
			// redirect user to the prefilled registration page
			$redirectUrl = $request->url(null, 'user', 'register');
			// TO-DO: check if the ORCID data (email) exists, else there is no index 0 notice
			// TO-DO: check why the fields are not filled
			echo '<html><body><script type="text/javascript">
						opener.location.href = \'' .$redirectUrl .'\';
						opener.document.getElementById("firstName").value = ' . json_encode($json['orcid-profile']['orcid-bio']['personal-details']['given-names']['value']) . ';
						opener.document.getElementById("lastName").value = ' . json_encode($json['orcid-profile']['orcid-bio']['personal-details']['family-name']['value']) . ';
						opener.document.getElementById("email").value = ' . json_encode($json['orcid-profile']['orcid-bio']['contact-details']['email'][0]['value']) . ';
						opener.document.getElementById("orcid").value = ' . json_encode($json['orcid-profile']['orcid-identifier']['uri']). ';
						opener.document.getElementById("connect-orcid-button").style.display = "none";
						window.close();
					</script></body></html>';

		}
	}

}

?>
