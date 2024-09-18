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

{if isset($product) && $product}
    <li class="row service-product-element">
        <div class="col-xs-4 col-sm-3 col-md-2">
            <a href="{$link->getImageLink($product.link_rewrite, $product.id_image, 'large_default')|escape:'html':'UTF-8'}" rel="htl-images{$product['id_product']}" class="fancybox" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}">
                <img class="img-responsive service-product-img" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}">
            </a>
            {foreach $product.images as $image}
                {if $image['cover'] == 0}
                    <a href="{$link->getImageLink($product.link_rewrite, $image.id_image, 'large_default')|escape:'html':'UTF-8'}" rel="htl-images{$product['id_product']}" class="fancybox hidden"  title="{if !empty($image.legend)}{$image.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}">
                    </a>
                {/if}
            {/foreach}
        </div>
        <div class="col-xs-8 col-sm-9 col-md-10">
            <div class="row">
                <div class="col-sm-12 clearfix service-product-block">
                    <span class="service-product-name">{$product['name']}</span>
                </div>
                {if $product['description_short']}
                    <div class="col-sm-12 clearfix service-product-short-desc service-product-block">
                        {$product['description_short']}
                    </div>
                {/if}

                <div class="col-sm-12 service_product_action_block">
                    {if !$PS_CATALOG_MODE && !$order_date_restrict && ($product.show_price && !isset($restricted_country_mode))}
                        <div class="service-product-price">
                            {if !$priceDisplay}{convertPrice price=$product.price_tax_incl}{else}{convertPrice price=$product.price_tax_exc}{/if}{if $product.price_calculation_method == Product::PRICE_CALCULATION_METHOD_PER_DAY}<span class="price-label">{l s='/Night'}</span>{/if}
                            {if $product.allow_multiple_quantity && $product.available_for_order && $product.max_quantity > 0}
                                <div class="service-max-quantity-info">
                                    {l s='Maximum'} {$product.max_quantity} {l s='quantity can be added'}.
                                </div>
                            {/if}
                        </div>
                    {/if}

                    {if ($product.show_price && !isset($restricted_country_mode))}
                        {if $product.available_for_order && !$PS_CATALOG_MODE && !$order_date_restrict && !((isset($restricted_country_mode) && $restricted_country_mode))}
                            <div class="service-product-actions">
                                {if $product.allow_multiple_quantity && $product.available_for_order}
                                    <div class="qty_container">
                                        <input type="hidden" class="service_product_qty" id="service_product_qty_{$product.id_product}" name="service_product_qty_{$product.id_product}" data-id-product="{$product.id_product}" data-max_quantity="{$product.max_quantity}" value="{if isset($product.quantity_added) && $product.quantity_added}{$product.quantity_added|escape:'html':'UTF-8'}{else}1{/if}">
                                        <div class="qty_count pull-left">
                                            <span>{if isset($product.quantity_added) && $product.quantity_added}{$product.quantity_added|escape:'html':'UTF-8'}{else}1{/if}</span>
                                        </div>
                                        <div class="qty_direction pull-left">
                                            <a href="#" class="btn btn-default quantity_up service_product_qty_up"><span><i class="icon-plus"></i></span></a>
                                            <a href="#" class="btn btn-default quantity_down service_product_qty_down"><span><i class="icon-minus"></i></span></a>
                                        </div>
                                    </div>
                                {/if}
                                <button class="btn btn-service-product {if isset($product.selected) && $product.selected} btn-danger remove_roomtype_product{else} btn-success add_roomtype_product{/if} select_room_service select_room_service_{$product.id_product} pull-right" data-id-product="{$product.id_product}">{if isset($product.selected) && $product.selected}{l s='Remove'}{else}{l s='Select'}{/if}</button>
                            </div>
                        {/if}
                    {/if}
                </div>
            </div>
        </div>
    </li>
{/if}