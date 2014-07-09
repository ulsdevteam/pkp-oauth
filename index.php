<?php

/**
 * @defgroup plugins_generic_exampleGenericPlugin Example generic plugin
 */

/**
 * @file plugins/generic/exampleGenericPlugin/index.php
 *
 * Copyright (c) 2014 Simon Fraser University Library
 * Copyright (c) 2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_exampleGenericPlugin
 * @brief Wrapper for example generic plugin.
 *
 */

require_once('ExampleGenericPlugin.inc.php');

return new ExampleGenericPlugin();

?>
