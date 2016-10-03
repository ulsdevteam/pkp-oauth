{**
 * plugins/generic/oauth/settingsForm.tpl
 *
 * Copyright (c) 2015-2016 University of Pittsburgh
 * Copyright (c) 2014-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * ORCID Profile plugin settings
 *
 *}
<div id="oauthSettings">
<div id="description">{translate key="plugins.generic.oauth.manager.settings.description"}</div>

<h3>{translate key="plugins.generic.webfeed.settings"}</h3>

<script>
	$(function() {ldelim}
		// Attach the form handler.
		$('#oauthSettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>

<form class="pkp_form" id="oauthSettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="settings" save=true}">
	{csrf}
	{include file="controllers/notification/inPlaceNotification.tpl" notificationId="oauthSettingsFormNotification"}

	{fbvFormArea id="oauthSettingsFormArea"}
<table width="100%" class="data">
	<tr valign="top">
		<td width="20%" class="label">{fieldLabel name="oauthAppName" required="true" key="plugins.generic.oauth.manager.settings.oauthAppName"}</td>
		<td width="80%" class="value"><input type="text" name="oauthAppName" id="oauthAppName" value="{$oauthAppName|escape}" size="40" class="textField" /></td>
	</tr>
	<tr valign="top">
		<td width="20%" class="label">{fieldLabel name="oauthAPIAuth" required="true" key="plugins.generic.oauth.manager.settings.oauthAPIAuth"}</td>
		<td width="80%" class="value"><input type="text" name="oauthAPIAuth" id="oauthAPIAuth" value="{$oauthAPIAuth|escape}" size="40" class="textField" /></td>
	</tr>
	<tr valign="top">
		<td width="20%" class="label">{fieldLabel name="oauthAPIVerify" required="true" key="plugins.generic.oauth.manager.settings.oauthAPIVerify"}</td>
		<td width="80%" class="value"><input type="text" name="oauthAPIVerify" id="oauthAPIVerify" value="{$oauthAPIVerify|escape}" size="40" class="textField" /></td>
	</tr>
	<tr valign="top">
		<td class="label">{fieldLabel name="oauthClientId" required="true" key="plugins.generic.oauth.manager.settings.oauthClientId"}</td>
		<td class="label"><input type="text" name="oauthClientId" id="oauthClientId" value="{$oauthClientId|escape}" size="40" class="textField" /></td>
	</tr>
	<tr valign="top">
		<td class="label">{fieldLabel name="oauthClientSecret" required="true" key="plugins.generic.oauth.manager.settings.oauthClientSecret"}</td>
		<td class="label"><input type="text" name="oauthClientSecret" id="oauthClientSecret" value="{$oauthClientSecret|escape}" size="40" class="textField" /></td>
	</tr>
	<tr valign="top">
		<td class="label">{fieldLabel name="oauthUniqueId" required="true" key="plugins.generic.oauth.manager.settings.oauthUniqueId"}</td>
		<td class="label"><input type="text" name="oauthUniqueId" id="oauthUniqueId" value="{$oauthUniqueId|escape}" size="40" class="textField" /></td>
	</tr>
	<tr valign="top">
		<td class="label">{fieldLabel name="oauthScope" required="true" key="plugins.generic.oauth.manager.settings.oauthScope"}</td>
		<td class="label"><input type="text" name="oauthScope" id="oauthScope" value="{$oauthScope|escape}" size="40" class="textField" /></td>
	</tr>
</table>

	{/fbvFormArea}

	{fbvFormButtons}
</form>

<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</div>
