<?php

/**
 * @file plugins/generic/exampleGenericPlugin/ExampleGenericPlugin.inc.php
 *
 * Copyright (c) 2014 Simon Fraser University Library
 * Copyright (c) 2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class ExampleGenericPlugin
 * @ingroup plugins_generic_exampleGenericPlugin
 *
 * @brief This example plugin demonstrates basic plugin structures in PKP applications.
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class ExampleGenericPlugin extends GenericPlugin {
	/**
	 * Register the plugin, if enabled
	 * @param $category string
	 * @param $path string
	 * @return boolean
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {
			if ($this->getEnabled()) {
				// You could register for hooks here if needed
				HookRegistry::register('TemplateManager::display',array($this, 'callback'));
			}
			return true;
		}
		return false;
	}

	/**
	 * Hook callback function for TemplateManager::display
	 * @param $hookName string
	 * @param $args array
	 * @return boolean
	 */
	function callback($hookName, $args) {
		// Get the template manager from the hook parameters.
		$templateManager =& $args[0];

		// Add some additional content to the headers as a demo.
		$additionalHeadData = $templateManager->get_template_vars('additionalHeadData');
		$templateManager->assign('additionalHeadData', $additionalHeadData."\n<!-- The example generic plugin is inserting additional header information here. -->");

		// Permit additional plugins to use this hook; returning true
		// here would interrupt processing of this hook instead.
		return false;
	}

	/**
	 * Get the display name of this plugin
	 * @return string
	 */
	function getDisplayName() {
		return __('plugins.generic.exampleGenericPlugin.name');
	}

	/**
	 * Get the description of this plugin
	 * @return string
	 */
	function getDescription() {
		return __('plugins.generic.exampleGenericPlugin.description');
	}

	/**
	 * Get a list of available management verbs for this plugin
	 * @return array
	 */
	function getManagementVerbs() {
		return array_merge(
			parent::getManagementVerbs(),
			$this->getEnabled()?array(
				array('exampleVerb', __('plugins.generic.exampleGenericPlugin.exampleVerb'))
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
