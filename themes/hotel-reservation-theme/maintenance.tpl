{*
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!DOCTYPE html>
<html lang="{$language_code|escape:'html':'UTF-8'}">

<head>
	<meta charset="utf-8">
	<title>{$meta_title|escape:'html':'UTF-8'}</title>
	{if isset($meta_description)}
		<meta name="description" content="{$meta_description|escape:'html':'UTF-8'}">
	{/if}
	{if isset($meta_keywords)}
		<meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}">
	{/if}
	<meta name="robots" content="{if isset($nobots)}no{/if}index,follow">
	<link rel="shortcut icon" href="{$favicon_url}">
	<link href="{$css_dir}maintenance.css" rel="stylesheet">
	<script src="{$base_dir}js/jquery/jquery-1.11.0.min.js"></script>
	<script src="{$js_dir}maintenance.js"></script>
	<link href='//fonts.googleapis.com/css?family=Open+Sans:600' rel='stylesheet'>
</head>

<body>
	<div id="maintenance">
		<div class="header-container{if count($languages) > 1} multilang{/if}">
			<div class="logo-container">
				<img src="{$logo_url}" alt="logo" />
			</div>
			{if count($languages) > 1}
				<div class="language-selector-wrap">
					<div class="dropdown">
						{foreach from=$languages item=language}
							{if $language.iso_code == $lang_iso}
								<button class="dropdown-toggle" type="button" data-toggle="dropdown">
									{$language.name|regex_replace:'/\s\(.*\)$/':''}
									<span class="caret"></span>
								</button>
							{/if}
						{/foreach}

						<ul class="dropdown-menu">
							{foreach from=$languages item=language}
								<li {if $language.iso_code == $lang_iso}class="disabled"{/if}>
									<a href="{$link->getLanguageLink($language.id_lang)|escape:'html':'UTF-8'}" title="{$language.name}">
										<span>{$language.name|regex_replace:'/\s\(.*\)$/':''}</span>
									</a>
								</li>
							{/foreach}
						</ul>
					</div>
				</div>
			{/if}
		</div>
		<div class="margin-l-r">
			{if isset($errors) && $errors}
				<div class="alert alert-danger">
					<strong>{l s='Error!'}</strong>
					<ol>
						{foreach from=$errors key=k item=error}
							<li>{$error}</li>
						{/foreach}
					</ol>
				</div>
			{/if}
		</div>
		<div class="containter">
			<div class="left">
				<div class="">
					<img class="" src="{$img_ps_dir}maintenance_banner.png"></img>
				</div>
			</div>
			<div class="right">
				<h2>{l s='We\'ll be back soon.'}</h2>
				<p>{l s='We are currently updating our site and will be back really soon.'}</p>
				<p>{l s='Thanks for your patience!'}</p>
				{if isset($allowEmployee) && $allowEmployee}
					<div>
						<p class="clicker blue" tabindex="1">{l s='Are you a member?'}</p>
						<div class="login-form-wrap" {if !isset($smarty.post.SubmitLogin)}style="display:none;"{/if}>
							<form action="" method="post">
								<div class="form_content clearfix">
									<div class="form-group form-ok">
										<label class="" for="email">{l s='Email address'}</label>
										<br>
										<input class="form-control" placeholder="{l s='Email'}" type="email" id="email" name="email" {if isset($smarty.post.SubmitLogin)}value="{$smarty.post.email|escape:'html':'UTF-8'}"{/if}>
									</div>
									<div class="form-group form-ok">
										<label class="" for="passwd">{l s='Password'}</label>
										<br>
										<input class="form-control" type="password" placeholder="{l s='Password'}" id="passwd" name="passwd" value="">
									</div>
									<button type="submit" id="SubmitLogin" name="SubmitLogin" class="btn btn-primary">
										<span>
											{l s='Log in'}
										</span>
									</button>
									<button type="button" id="cancelLogin" name="cancelLogin"
										class="btn btn-primary cancel-login">
										<span>
											{l s='Cancel'}
										</span>
									</button>
								</div>
							</form>
						</div>
					</div>
				{/if}
			</div>
		</div>
	</div>
</body>

</html>