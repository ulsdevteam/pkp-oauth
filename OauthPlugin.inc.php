<?php

/**
 * @file plugins/generic/oauth/OauthPlugin.inc.php
 *
 * Copyright (c) 2015-2016 University of Pittsburgh
 * Copyright (c) 2014 Simon Fraser University Library
 * Copyright (c) 2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class OauthPlugin
 * @ingroup plugins_generic_oauth
 *
 * @brief This example plugin demonstrates basic plugin structures in PKP applications.
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class OauthPlugin extends GenericPlugin {
	/**
	 * Register the plugin, if enabled
	 * @param $category string
	 * @param $path string
	 * @return boolean
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {
			if ($this->getEnabled()) {
				// Register template callback
				HookRegistry::register('TemplateManager::display',array($this, 'templateCallback'));
				// Register load callback
				HookRegistry::register('LoadHandler', array($this, 'loadCallback'));
			}
			return true;
		}
		return false;
	}

	/**
	 * Hook callback function for LoadHander
	 * @param $hookName string
	 * @param $args array
	 * @return boolean
	 */
	function loadCallback($hookName, $args) {
		// Get the template manager from the hook parameters.
		$page =& $args[0];

		if ($this->getEnabled() && $page == 'oauth') {
			$this->import('pages/OauthHander');
			define('HANDLER_CLASS', 'OauthHander');
			return true;
		}

		// Permit additional plugins to use this hook; returning true
		// here would interrupt processing of this hook instead.
		return false;
	}

	/**
	 * Hook callback function for TemplateManager::display
	 * @param $hookName string
	 * @param $args array
	 * @return boolean
	 */
	function templateCallback($hookName, $args) {
		// Get the template manager from the hook parameters.
		$templateManager =& $args[0];
		$template =& $args[1];
				
		if ($this->getEnabled()) {
			switch ($template) {
				case 'frontend/pages/userRegister.tpl':
				case 'frontend/pages/userLogin.tpl':
					$templateManager->addHeader('exampleHeader', "<!-- The example generic plugin is inserting additional header information here. -->");
					break;
			}
		}

		// Permit additional plugins to use this hook; returning true
		// here would interrupt processing of this hook instead.
		return false;
	}

	/**
	 * Get the display name of this plugin
	 * @return string
	 */
	function getDisplayName() {
		return __('plugins.generic.oauth.name');
	}

	/**
	 * Get the description of this plugin
	 * @return string
	 */
	function getDescription() {
		return __('plugins.generic.oauth.description');
	}

	/**
	 * Get a list of available management verbs for this plugin
	 * @return array
	 */
	function getManagementVerbs() {
		return array_merge(
			parent::getManagementVerbs(),
			$this->getEnabled()?array(
				array('exampleVerb', __('plugins.generic.oauth.exampleVerb'))
			):array()
		);
	}

	/**
	 * @see Plugin::manage()
	 */
	function manage($verb, $args, &$message, &$messageParams, &$pluginModalContent = null) {
		if (!parent::manage($verb, $args, $message, $messageParams)) return false;
		$request = $this->getRequest();
		switch ($verb) {
			case 'exampleVerb':
				// Process the verb invocation
				return false;
			default:
				// Unknown management verb
				assert(false);
				return false;
		}
	}
}

?>
