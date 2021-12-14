<?php
/**
 * @package	HikaShop for Joomla!
 * @version	4.4.4
 * @author	hikashop.com
 * @copyright	(C) 2010-2021 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php if(empty($this->print_cart)) {
	/* echo $this->toolbarHelper->process($this->toolbar, $this->title); */ ?>
<form method="POST" id="hikashop_show_cart_form" name="hikashop_show_cart_form" action="<?php echo hikashop_completeLink('cart&task=show&cid='.(int)@$this->cart->cart_id); ?>">
<!-- CART NAME -->
<?php
	if(!empty($this->manage) && $this->cart->cart_type != 'wishlist' && $this->config->get('enable_multicart') && !empty($this->user_carts)) {
?>
<dl class="hika_options">
	<dt><label for="cart_name"><?php echo JText::_('HIKASHOP_CART_NAME'); ?></label></dt>
	<dd>
		<input type="text" id="cart_name" name="data[cart_name]" class="inputbox" value="<?php echo $this->escape($this->cart->cart_name); ?>"/>
	</dd>
</dl>
<?php
	}
?>
<!-- EO CART NAME -->
<!-- WISHLIST NAME -->
<?php
	if(!empty($this->cart) && $this->cart->cart_type == 'wishlist' && !empty($this->multi_wishlist)) {
		if(!empty($this->manage)) {
?>
<dl class="hika_options">
	<dt><label for="cart_name"><?php echo JText::_('HIKASHOP_WISHLIST_NAME'); ?></label></dt>
	<dd>
		<input type="text" id="cart_name" name="data[cart_name]" class="inputbox" value="<?php echo $this->escape($this->cart->cart_name); ?>"/>
	</dd>
	<dt><label for="cart_share"><?php echo JText::_('SHARE'); ?></label></dt>
	<dd><?php
		echo $this->cartShareType->display('data[cart_share]', $this->cart->cart_share);
	?></dd>
</dl>
<?php
		} else {
?>
<dl class="hika_options">
	<dt><label><?php echo JText::_('HIKASHOP_WISHLIST_NAME'); ?></label></dt>
	<dd><?php
		if(!empty($this->cart->cart_name))
			echo $this->escape($this->cart->cart_name);
		else
			echo '<em>'.JText::_('HIKA_NO_NAME').'</em>';
	?></dd>
</dl>
<?php
		}
	}
?>
<!-- EO WISHLIST NAME -->
<?php
}
?>
    <div class="uk-grid-divider" data-uk-grid>
        <div class="uk-width-1-1 uk-width-expand@m">
            <table id="hikashop_cart_product_listing" class="uk-table uk-table-middle uk-table-divider uk-text-justify uk-table-justify">
                <thead>
                <tr>
                    <!-- SELECT ALL HEADER -->
                    <?php /* if($this->checkbox_column) { ?>
                        <th data-title="<?php echo JText::_('SELECT_ALL'); ?>" ><input type="checkbox" onchange="window.hikashop.checkAll(this);" /></th>
                    <?php } */ ?>
                    <!-- EO SELECT ALL HEADER -->
                    <!-- PRODUCT NAME HEADER -->
                    <th class="font f500 hikashop_cart_name_title title"><?php
                        echo JText::_('CART_PRODUCT_NAME');
                        ?></th>
                    <!-- EO PRODUCT NAME HEADER -->
                    <!-- CUSTOM PRODUCT FIELDS HEADER -->
                    <?php
                    if(hikashop_level(1) && !empty($this->productFields)) {
                        foreach($this->productFields as $fieldname => $field) {
                            echo '<th class="uk-text-center font f500 uk-width-small hikashop_cart_product_'.$fieldname.' title">'.$this->fieldsClass->trans($field->field_realname).'</th>';
                        }
                    }
                    ?>
                    <!-- EO CUSTOM PRODUCT FIELDS HEADER -->
                    <!-- STATUS HEADER -->
                    <th class="uk-text-center font f500 uk-table-shrink hikashop_cart_status_title title"><?php
                        echo JText::_('HIKASHOP_CHECKOUT_STATUS');
                        ?></th>
                    <!-- EO STATUS HEADER -->
                    <!-- UNIT PRICE HEADER -->
                    <th class="uk-text-center font f500 hikashop_cart_price_title title"><?php
                        echo JText::_('CART_PRODUCT_UNIT_PRICE');
                        ?></th>
                    <!-- EO UNIT PRICE HEADER -->
                    <!-- QUANTITY HEADER -->
                    <th class="uk-text-center font f500 uk-width-small hikashop_cart_quantity_title title"><?php
                        echo JText::_('PRODUCT_QUANTITY');
                        ?></th>
                    <!-- EO QUANTITY HEADER -->
                    <!-- TOTAL PRICE HEADER -->
                    <th class="uk-text-center font f500 hikashop_cart_price_title title"><?php
                        echo JText::_('CART_PRODUCT_TOTAL_PRICE');
                        ?></th>
                    <!-- EO TOTAL PRICE HEADER -->
                </tr>
                </thead>

                <tbody>
                <?php
                $group = $this->config->get('group_options', 0);
                $width = (int)$this->config->get('cart_thumbnail_x', 62);
                $height = (int)$this->config->get('cart_thumbnail_y', 80);
                $image_options = array(
                    'default' => true,
                    'forcesize' => $this->config->get('image_force_size', true),
                    'scale' => $this->config->get('image_scale_mode','inside')
                );

                $i = 1;
                $k = 1;
                if(!empty($this->cart->products)) {
                    foreach($this->cart->products as $k => $product) {
                        if($group && !empty($product->cart_product_option_parent_id))
                            continue;
                        if(empty($product->cart_product_quantity) || substr($k,0,1) === 'p')
                            continue;

                        if(empty($this->cart->cart_products[$k]))
                            continue;

                        if (isset($product->bundle_quantity)) {
                            if($product->product_quantity == -1 || $product->product_quantity > $product->bundle_quantity)
                                $product->product_quantity = $product->bundle_quantity;
                        }

                        $cart_product = $this->cart->cart_products[$k];
                        $status = 'err';
                        $text = '';
                        if (empty($product) || (!empty($product->product_sale_end) && $product->product_sale_end < time())) {
                            $text = JText::_('HIKA_NOT_SALE_ANYMORE');
                        } elseif ($product->product_quantity == -1) {
                            $text = JText::sprintf('X_ITEMS_IN_STOCK', JText::_('HIKA_UNLIMITED'));
                            $status = 'ok';
                        } elseif (($product->product_quantity - $product->cart_product_quantity) >= 0) {
                            $text = JText::sprintf('X_ITEMS_IN_STOCK', $product->product_quantity);
                            $status = 'ok';
                        } else {
                            $text = JText::_('NOT_ENOUGH_STOCK');
                        }

                        ?>
                        <tr class="row<?php echo $k; ?>">
                            <!-- PRODUCT CHECKBOX -->
                            <?php
                                    /*
                            if($this->checkbox_column) {
                                ?>
                                <td class="hikashop_show_cart_form_checkbox">
                                    <?php
                                    if ($status == 'ok') {
                                        ?>
                                        <input type="checkbox" name="products[]" value="<?php echo (int)$k; ?>" id="cb<?php echo $k; ?>"/>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <?php
                            } */
                            ?>
                            <!-- EO PRODUCT CHECKBOX -->
                            <!-- PRODUCT NAME -->
                            <td class="uk-table-shrink" data-title="<?php echo JText::_('CART_PRODUCT_NAME'); ?>" >
                                <div class="uk-flex uk-flex-middle">
                                <?php
                                $image_path = (!empty($product->images) ? @$product->images[0]->file_path : '');
                                $img = $this->imageHelper->getThumbnail($image_path, array('width' => $width, 'height' => $height), $image_options);
                                if($img->success) {
                                    $attributes = '';
                                    if($img->external)
                                        $attributes = ' width="'.$img->req_width.'" height="'.$img->req_height.'"';
                                    echo '<div><img class="uk-margin-small-left uk-preserve-width" title="'.$this->escape(@$product->images[0]->file_description).'" alt="'.$this->escape(@$product->images[0]->file_name).'" src="'.$img->url.'" '.$attributes.' /></div>';
                                }

                                ?>
                                <div class="hikashop_cart_product_name uk-display-block uk-text-nowrap">
<?php
if(empty($this->print_cart)) {
?>
					<a class="uk-text-small font f500 uk-text-dark hoverPrimary uk-display-block" href="<?php echo hikashop_contentLink('product&task=show&cid='.$product->product_id.'&name='.$product->alias, $product); ?>">
<?php
}
echo $product->product_name;
if(empty($this->print_cart)) {
?>
					</a>
<?php
}
?>
				</div>
                                <?php


                                $html = '';
                                $edit = !empty($product->has_options) && $group;
                                if(!empty($product->product_parent_id))
                                    $edit = true;

                                if(hikashop_level(2) && !empty($this->itemFields)) {
                                    $html .= '<p class="hikashop_order_product_custom_item_fields">';
                                    foreach($this->itemFields as $field) {
                                        $namekey = $field->field_namekey;
                                        if(!empty($cart_product->$namekey) && strlen($cart_product->$namekey)) {
                                            $edit = true;
                                            $html .= '<p class="hikashop_order_item_'.$namekey.'">' .
                                                $this->fieldsClass->getFieldName($field) . ': ' .
                                                $this->fieldsClass->show($field, $cart_product->$namekey) .
                                                '</p>';
                                        }
                                    }
                                    $html .= '</p>';

                                }

                                if($group) {
                                    foreach($this->cart->products as $opt_k => $opt_product) {
                                        if($opt_product->cart_product_option_parent_id != $product->cart_product_id)
                                            continue;

                                        $html .= '<p class="hikashop_cart_option_name">' . $opt_product->product_name . '</p>';
                                        if(!empty($opt_product->prices[0])) {
                                            if(!isset($product->prices[0])) {
                                                $product->prices[0] = new stdClass();
                                                $product->prices[0]->price_value = 0;
                                                $product->prices[0]->price_value_with_tax = 0;
                                                $product->prices[0]->price_currency_id = !empty($this->cart->cart_currency_id) ? (int)$this->cart->cart_currency_id : hikashop_getCurrency();
                                                $product->prices[0]->unit_price = new stdClass();
                                                $product->prices[0]->unit_price->price_value = 0;
                                                $product->prices[0]->unit_price->price_value_with_tax = 0.0;
                                                $product->prices[0]->unit_price->price_currency_id = $product->prices[0]->price_currency_id;
                                            }

                                            foreach(get_object_vars($product->prices[0]) as $key => $value) {
                                                if(is_object($value)) {
                                                    foreach(get_object_vars($value) as $key2 => $var2) {
                                                        if(strpos($key2,'price_value') !== false)
                                                            $product->prices[0]->$key->$key2 += @$opt_product->prices[0]->$key->$key2;
                                                    }
                                                } else {
                                                    if(strpos($key,'price_value') !== false)
                                                        $product->prices[0]->$key += @$opt_product->prices[0]->$key;
                                                }
                                            }
                                        }
                                    }
                                }

                                if($edit) {
                                    $popupHelper = hikashop_get('helper.popup');
                                    echo $popupHelper->display(
                                            '<i class="fas fa-pen"></i>',
                                            'HIKASHOP_EDIT_CART_PRODUCT',
                                            hikashop_completeLink('cart&task=product_edit&cart_id='.$this->cart->cart_id.'&cart_product_id='.$cart_product->cart_product_id.'&tmpl=component&'.hikashop_getFormToken().'=1'),
                                            'edit_cart_product',
                                            576, 480, 'data-uk-tooltip="cls: uk-active font" class="uk-text-small uk-margin-small-right uk-text-gray hoverPrimary" title="'.JText::_('EDIT_THE_OPTIONS_OF_THE_PRODUCT').'"', '', 'link'
                                        );
                                }

                                if($this->config->get('show_code')) {
                                    echo '<br/>' . '<span class="hikashop_cart_product_code">'.$product->product_code.'</span>';
                                }

                                echo $html;

                                if(!empty($product->extraData) && !empty($product->extraData->cart))
                                    echo '<div class="hikashop_cart_product_extradata"><p>' . implode('</p><p>', $product->extraData->cart) . '</p></div>';

                                ?></div></td>
                            <!-- EO PRODUCT NAME -->
                            <!-- CUSTOM PRODUCT FIELDS -->
                            <?php
                            if(hikashop_level(1) && !empty($this->productFields)) {
                                foreach($this->productFields as $field) {
                                    $namekey = $field->field_namekey;
                                    ?>			<td data-title="<?php echo $this->fieldsClass->trans($field->field_realname); ?>" >
                                        <?php
                                        if(!empty($product->$namekey)) {
                                            echo '<p class="hikashop_order_product_'.$namekey.'">' . $this->fieldsClass->show($field, $product->$namekey) . '</p>';
                                        }
                                        ?>
                                    </td>
                                    <?php
                                }
                            }
                            ?>
                            <!-- EO CUSTOM PRODUCT FIELDS -->
                            <!-- STATUS -->
                            <td class="uk-text-center" data-title="<?php echo JText::_('HIKASHOP_CHECKOUT_STATUS'); ?>"><?php
                                $tooltip_images = array(
                                    'ok' => '<i class="uk-text-success fa fa-check-circle"></i>',
                                    'err' => '<i class="uk-text-danger fa fa-times-circle"></i>'
                                );
                                echo hikashop_hktooltip($text, '', $tooltip_images[$status]);
                                ?></td>
                            <!-- EO STATUS -->
                            <!-- UNIT PRICE -->
                            <td class="uk-text-center uk-text-secondary uk-text-small font f500 uk-text-nowrap" data-title="<?php echo JText::_('CART_PRODUCT_UNIT_PRICE'); ?>"><?php
                                $this->setLayout('listing_price');
                                $this->row =& $product;
                                $this->unit = true;
                                echo $this->loadTemplate();
                                ?></td>
                            <!-- EO UNIT PRICE -->
                            <!-- QUANTITY -->
                            <td class="uk-text-zero" data-title="<?php echo JText::_('PRODUCT_QUANTITY'); ?>">
                                <div class="uk-grid-small uk-grid-divider uk-child-width-auto uk-flex-center" data-uk-grid>
                                <?php
                                if(!empty($this->manage)) {
                                    if($this->cart->cart_type == 'wishlist') {
                                        $this->row->product_min_per_order = 1;
                                        $this->row->product_max_per_order = 0;
                                    }
                                    echo $this->loadHkLayout('quantity', array(
                                        'quantity_fieldname' => 'data[products]['.$product->cart_product_id.'][quantity]',
                                        'onchange_script' => 'return window.hikashop.submitform(\'apply\',\'hikashop_show_cart_form\');',
                                        'force_input' => true,
                                        'extra_data' => 'data-hk-product-name="'.$this->escape(strip_tags($product->product_name)).'" onkeypress="if(event.keyCode==13 && window.cartMgr.checkQuantity(this)){ window.hikashop.submitform(\'apply\',\'hikashop_show_cart_form\'); }"',
                                    ));
                                } else {
                                    ?>
                                    <div class="uk-text-center hikashop_product_quantity_div hikashop_product_quantity_input_div_none">
                                        <span><?php echo $product->cart_product_quantity; ?></span>
                                    </div>
                                    <?php
                                }
                                ?>
                                <?php
                                if(!empty($this->manage)) {
                                    ?>
                                    <div>
                                        <div class="uk-flex uk-flex-middle uk-height-1-1">
                                            <a data-uk-tooltip="cls: uk-active font;" title="<?php echo JText::_('HIKA_DELETE'); ?>" class="uk-text-small uk-text-danger hikashop_no_print" href="#delete" onclick="var qtyField = document.getElementById('<?php echo $this->last_quantity_field_id; ?>'); if(!qtyField) return false; qtyField.value = 0; return window.hikashop.submitform('apply','hikashop_show_cart_form');" title="<?php echo JText::_('HIKA_DELETE'); ?>">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                }

                                if(!empty($product->bought)) {
                                    ?>
                                    <div class="hikashop_wishlist_product_bought">
					<span><?php
                        $desc = '';
                        if($this->manage) {
                            $buyers = array();
                            foreach($product->related_orders as $related_order) {
                                if(empty($buyers[(int)$related_order->order_user_id]))
                                    $buyers[(int)$related_order->order_user_id] = array($related_order->user_email, 0);
                                $buyers[(int)$related_order->order_user_id][1] += (int)$related_order->order_product_quantity;
                            }
                            foreach($buyers as $buyer) {
                                $desc .= $buyer[0] . ' ('.$buyer[1].')';
                            }
                        }

                        if(!empty($desc)) {
                            echo hikashop_hktooltip($desc, '', JText::sprintf('HIKA_BOUGHT_X_TIMES', (int)$product->bought));
                        } else {
                            echo JText::sprintf('HIKA_BOUGHT_X_TIMES', (int)$product->bought);
                        }
                        ?></span>
                                    </div>
                                    <?php
                                }
                                ?>
                                </div>
                            </td>
                            <!-- EO QUANTITY -->
                            <!-- TOTAL PRICE -->
                            <td class="uk-text-center uk-text-secondary uk-text-small font f500 uk-text-nowrap" data-title="<?php echo JText::_('CART_PRODUCT_TOTAL_PRICE'); ?>"> <?php
                                $this->setLayout('listing_price');
                                $this->row =& $product;
                                $this->unit = false;
                                echo $this->loadTemplate();
                                ?></td>
                            <!-- EO TOTAL PRICE -->
                        </tr>
                        <?php
                        $k = 1 - $k;
                        $i++;
                    }
                }
                ?>
                <?php /* ?>
			<!-- For responsive display
		<tr class="hika_show_cart_total_text_2">
			<td data-title="<?php echo JText::_('PRODUCT_QUANTITY'); ?>" class="hika_show_cart_total_quantity"><?php
				echo (int)@$this->cart->package['total_items'];
			?></td>
			<td data-title="<?php echo JText::_('HIKASHOP_FINAL_TOTAL'); ?>" class="hika_show_cart_total_price"><?php
	if(!empty($this->cart->total->prices)) {
		if($this->config->get('price_with_tax')) {
			echo $this->currencyClass->format($this->cart->total->prices[0]->price_value_with_tax, $this->cart->total->prices[0]->price_currency_id);
		}
		if($this->config->get('price_with_tax') == 2) {
			echo JText::_('PRICE_BEFORE_TAX');
		}
		if($this->config->get('price_with_tax') == 2 || !$this->config->get('price_with_tax')) {
			echo $this->currencyClass->format($this->cart->total->prices[0]->price_value, $this->cart->total->prices[0]->price_currency_id);
		}
		if($this->config->get('price_with_tax') == 2) {
			echo JText::_('PRICE_AFTER_TAX');
		}
	}
			?></td>
		</tr> -->
    <?php */ ?>
                </tbody>
            </table>
        </div>
        <div class="uk-width-1-1 uk-width-1-3@m">
            <div>
                <div class="uk-child-width-1-1 uk-grid-small" data-uk-grid>
                    <div>
                        <div class="uk-background-muted">
                            <div>
                                <?php
                                $cols = 5 + ($this->checkbox_column ? 1 : 0) + (hikashop_level(2) && !empty($this->productFields) ? count($this->productFields) : 0);
                                ?>
                                <!-- CART TOTAL AMOUNT -->
                                <div class="uk-padding">
                                    <h5 class="uk-display-block font f600 uk-text-dark"><?php echo JText::_('HIKASHOP_ORDER_SUMMERY'); ?></h5>
                                    <div class="uk-grid-small" data-uk-grid>
                                        <div class="uk-width-expand uk-text-small font uk-text-gray f500" data-uk-leader><?php echo JText::_('HIKASHOP_TOTAL_PRODUCTS'); ?></div>
                                        <div class="uk-text-small font uk-text-secondary f500"><?php echo (int)@$this->cart->package['total_items']; ?></div>
                                    </div>
                                    <div class="uk-grid-small" data-uk-grid>
                                        <div class="uk-width-expand uk-text-small font uk-text-gray f500" data-uk-leader><?php echo JText::_('HIKASHOP_TOTAL'); ?></div>
                                        <div class="uk-text-small font uk-text-secondary f500">
                                            <?php
                                            if(!empty($this->cart->total->prices)) {
                                                if($this->config->get('price_with_tax')) {
                                                    echo $this->currencyClass->format($this->cart->total->prices[0]->price_value_with_tax, $this->cart->total->prices[0]->price_currency_id);
                                                }
                                                if($this->config->get('price_with_tax') == 2) {
                                                    echo JText::_('PRICE_BEFORE_TAX');
                                                }
                                                if($this->config->get('price_with_tax') == 2 || !$this->config->get('price_with_tax')) {
                                                    echo $this->currencyClass->format($this->cart->total->prices[0]->price_value, $this->cart->total->prices[0]->price_currency_id);
                                                }
                                                if($this->config->get('price_with_tax') == 2) {
                                                    echo JText::_('PRICE_AFTER_TAX');
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- EO CART TOTAL AMOUNT -->
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="<?php echo JUri::base(),'checkout'; ?>" class="uk-button uk-button-primary uk-button-large uk-width-1-1 font"><?php echo JText::_('CHECKOUT'); ?></a>
                    </div>
                    <div>
                        <p class="uk-text-center uk-margin-remove uk-text-tiny uk-text-muted font f500"><?php echo JText::_('SHIPPING_MAY_APPLY'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php if(empty($this->print_cart)) { ?>
	<input type="hidden" name="option" value="<?php echo HIKASHOP_COMPONENT; ?>" />
	<input type="hidden" name="ctrl" value="cart"/>
	<input type="hidden" name="task" value="show"/>
	<input type="hidden" name="cid" value="<?php echo (int)@$this->cart->cart_id; ?>"/>
	<input type="hidden" name="addto_type" value=""/>
	<input type="hidden" name="addto_id" value=""/>
	<?php echo JHTML::_('form.token'); ?>
</form>
<script type="text/javascript">
if(!window.checkout) window.checkout = {};
window.Oby.registerAjax(['checkout.cart.updated','cart.updated'], function(params){
	window.location.reload();
});

window.hikashop.ready(function(){
	setTimeout(function(){window.hikashop.dlTitle('hikashop_show_cart_form')},1000);
});
if(!window.cartMgr) window.cartMgr = {};
window.cartMgr.moveProductsTo = function(id, type) {
	var d = document, form = d.getElementById('hikashop_show_cart_form');
	if(!form)
		form = d.forms['hikashop_show_cart_form'];
	if(!form)
		return false;
	form.task.value = 'addtocart';
	form.addto_type.value = type;
	form.addto_id.value = parseInt(id);
	if(typeof form.onsubmit == 'function')
		form.onsubmit();
	form.submit();
	return false;
};
window.cartMgr.checkQuantity = function(el) {
	var value = parseInt(el.value), old = el.getAttribute('data-hk-qty-old'),
		min = parseInt(el.getAttribute('data-hk-qty-min')),
		max = parseInt(el.getAttribute('data-hk-qty-max'));
	if(old)
		old = parseInt(old);
	if(isNaN(value)) {
		el.value = old || (isNaN(min) ? 1 : min);
		return false;
	}
	if(isNaN(min) || isNaN(max))
		return false;
	if((value <= max || max == 0) && value >= min)
		return true;

	if(max > 0 && value > max) {
		msg = '<?php echo JText::_('TOO_MUCH_QTY_FOR_PRODUCT', true); ?>';
		el.value = max;
	} else if(value < min) {
		msg = '<?php echo JText::_('NOT_ENOUGH_QTY_FOR_PRODUCT', true); ?>';
		el.value = min;
	}
	name = el.getAttribute('data-hk-product-name');
	if(msg && name)
		alert(msg.replace('%s', name));
	return true;
};
window.cartMgr.moveProductsToCart = function(id) { return window.cartMgr.moveProductsTo(id, 'cart'); };
window.cartMgr.moveProductsToWishlist = function(id) { return window.cartMgr.moveProductsTo(id, 'wishlist'); };
</script>
<?php }else{ ?>
<script type="text/javascript">
window.hikashop.ready( function() {window.focus();if(document.all){document.execCommand('print', false, null);}else{window.print();}setTimeout(function(){window.top.hikashop.closeBox();}, 2000);});
</script>
<?php } ?>
