<?php

/**
 * @file plugins/generic/oauth/OrcidProfileSettingsForm.inc.php
 *
 * Copyright (c) 2015-2016 University of Pittsburgh
 * Copyright (c) 2014-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class OauthSettingsForm
 * @ingroup plugins_generic_oauth
 *
 * @brief Form for site admins to modify ORCID Profile plugin settings
 */


import('lib.pkp.classes.form.Form');

class OauthSettingsForm extends Form {

	/** @var $contextId int */
	var $contextId;

	/** @var $plugin object */
	var $plugin;

	/**
	 * Constructor
	 * @param $plugin object
	 * @param $contextId int
	 */
	function OauthSettingsForm(&$plugin, $contextId) {
		$this->contextId = $contextId;
		$this->plugin =& $plugin;

		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');

		$this->addCheck(new FormValidator($this, 'oauthAPIPath', 'required', 'plugins.generic.oauth.manager.settings.oauthAPIPathRequired'));

		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$contextId = $this->contextId;
		$plugin =& $this->plugin;

		$oauthAppName = $plugin->getSetting($contextId, 'oauthAppName');
		$settings = json_decode($plugin->getSetting($contextId, 'oauthAppSettings', 'string'), TRUE);
		$this->_data = $settings[$oauthAppName];
		$this->_data['oauthAppName'] = $oauthAppName;
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('oauthAppName','oauthAPIPath','oauthClientId','oauthClientSecret','oauthUniqueId','oauthScope'));
	}

	/**
	 * Fetch the form.
	 * @copydoc Form::fetch()
	 */
	function fetch($request) {
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->plugin->getName());
		return parent::fetch($request);
	}

	/**
	 * Save settings.
	 */
	function execute() {
		$plugin =& $this->plugin;
		$contextId = $this->contextId;

		$plugin->updateSetting($contextId, 'oauthAppName', $this->getData('oauthAppName'), 'string');
		// TODO: implement multi-app settings here
		$plugin->updateSetting(
			$contextId,
			'oauthAppSettings',
			json_encode(
				array(
					$this->getData('oauthAppName') => array(
						'oauthAPIPath' => $this->getData('oauthAPIPath'),
						'oauthClientId' => $this->getData('oauthClientId'),
						'oauthClientSecret' => $this->getData('oauthClientSecret'),
						'oauthUniqueId' => $this->getData('oauthUniqueId'),
						'oauthScope' => $this->getData('oauthScope'),
					)
				)
			),
			'string'
		);
	}
}

?>
