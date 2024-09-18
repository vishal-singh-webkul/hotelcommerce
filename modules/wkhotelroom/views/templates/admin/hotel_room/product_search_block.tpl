{**
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
*}

<div class="input-group col-lg-5">
    <input type="text" value="{$productName|escape:'htmlall':'UTF-8'}" name="productName" id="productName" class="form-control" autocomplete="off">
    <input type="hidden" value="{$idProduct|escape:'htmlall':'UTF-8'}" name="id_product" id="id_product" class="form-control">
    <span class="input-group-addon"><i class="icon-search"></i></span>
    <ul class="list-unstyled prod_suggest_ul"></ul>
</div>