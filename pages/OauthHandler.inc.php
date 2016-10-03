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
		$oauthApp = $plugin->getSettings($contextId, 'oauthAppName', 'string');

		$oauthSettings = json_decode($plugin->getSettings($contextId, 'oauthAppSettings', 'string'), TRUE);
		// fetch the access token
		$curl = curl_init();
		curl_setopt_array($curl, array(
				CURLOPT_URL => $oauthSettings[$oauthApp]['oauthAPIPath'],
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => array('Accept: application/json'),
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => http_build_query(array(
						'code' => $request->getUserVar('code'),
						'grant_type' => 'authorization_code',
						'client_id' => $oauthSettings[$oauthApp]['oauthClientId'],
						'client_secret' => $oauthSettings[$oauthApp]['oauthClientSecret']
				))
		));
		$result = curl_exec($curl);
		$response = json_decode($result, true);

		if ($response[$oauthSettings[$oauthApp]['oauthUniqueId']]) {

			$userSettingsDao = DAORegistry::getDAO('UserSettingsDAO');
			$userDao = DAORegistry::getDAO('UserDAO');
			// Should this be UserSettingsDAO::getUsersBySetting()?
			$userByAuthId = $userDao->getBySetting('oauth::'.$oauthApp, $response['orcid']);
			if ($userByAuthId) {
				// OAuth successful, with match -- log in user.
				$reason = null;
				Validation::registerUserSession($userByAuthId, $reason);
			} else {
				// OAuth successful, but not linked to a user account (yet)
				$sessionManager = SessionManager::getManager();
				$userSession = $sessionManager->getUserSession();
				$user = $userSession->getUser();

				if (isset($user)) {
					// If the user is authenticated, link this user account
					$userSettingsDao->updateSetting($user->getId(), 'oauth::'.$oauthApp, $response[$oauthSettings[$oauthApp]['oauthUniqueId']], 'string');
				} else {
					// Otherwise, send the user to the login screen (keep track of the oauthUniqueId to link upon login!)
					$userSession->setSessionVar('oauth', json_encode(array('oauth::'.$oauthApp => $response[$oauthSettings[$oauthApp]['oauthUniqueId']])));
					Validation::redirectLogin();
				}
			}
		} else {
			// OAuth login was tried, but failed
			// Show a message?
			Validation::redirectLogin('plugins.generic.oauth.message.oauthLoginError');
		}
	}

}

?>
