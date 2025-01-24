<?php
/**
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License version 3.0
* that is bundled with this package in the file LICENSE.md
* It is also available through the world-wide-web at this URL:
* https://opensource.org/license/osl-3-0-php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to support@qloapps.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to a newer
* versions in the future. If you wish to customize this module for your needs
* please refer to https://store.webkul.com/customisation-guidelines for more information.
*
* @author Webkul IN
* @copyright Since 2010 Webkul
* @license https://opensource.org/license/osl-3-0-php Open Software License version 3.0
*/

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_1($module)
{
    $objUpgrade = new UpgradeDashOccupancy101($module);
    return $objUpgrade->initUpgrade();
}

class UpgradeDashOccupancy101
{
    public function __construct($module)
    {
        $this->module = $module;
    }

    public function initUpgrade()
    {
        if (!$this->module->registerHook('dashboardZoneOne')
            || !$this->module->unregisterHook('dashboardZoneTwo')
        ) {
            return false;
        }
        return true;
    }
}