<?php
/**
 * @package	HikaShop for Joomla!
 * @version	4.4.4
 * @author	hikashop.com
 * @copyright	(C) 2010-2021 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php
$labelcolumnclass = '';
$inputcolumnclass = '';

if(empty($this->ajax)) {
?>
<div id="hikashop_checkout_address_<?php echo $this->step; ?>_<?php echo $this->module_position; ?>" data-checkout-step="<?php echo $this->step; ?>" data-checkout-pos="<?php echo $this->module_position; ?>" class="uk-width-1-1 hikashop_checkout_address">
<?php
}
?>
	<div class="hikashop_checkout_loading_elem"></div>
	<div class="hikashop_checkout_loading_spinner"></div>
<?php

if(!empty($this->options['display'])) {

	if(empty($this->addressClass))
		$this->addressClass = hikashop_get('class.address');
	$cart = $this->checkoutHelper->getCart();
	$this->cart_addresses = $this->checkoutHelper->getAddresses();

	$this->checkoutHelper->displayMessages('address');

	$shippingAddress_override = $this->checkoutHelper->getShippingAddressOverride();
	if(!empty($shippingAddress_override) && @$this->options['edit_address'] === true && !empty($this->options['show_shipping']) && @$this->options['new_address_type'] == 'shipping') {
		$this->options['edit_address'] = false;
	}
	if(empty($this->options['edit_address']) && !empty($this->options['show_billing']) && !empty($this->options['show_shipping'])) {
?>
	<div>
		<div>
<?php
	}

	if(!empty($this->options['edit_address'])) {
		if(empty($this->edit_address))
			$this->edit_address = new stdClass();
?>
			<fieldset class="formContainer hika_address_field hikashop_checkout_checkout_address_block">
<?php
		$fields_type = '';
		if($this->options['edit_address'] === true) {
			$label = 'HIKASHOP_NEW_ADDRESS';
			if(!empty($this->options['new_address_type']) && in_array($this->options['new_address_type'], array('billing','shipping'))) {
				$label = 'HIKASHOP_NEW_'.strtoupper($this->options['new_address_type']).'_ADDRESS';
				$fields_type = $this->options['new_address_type'];
			}
?>
				<h5 class="uk-display-block font f600 uk-text-dark uk-margin-remove-top"><?php echo JText::_($label); ?></h5>
<?php
		} else {
			$fields_type = @$this->edit_address->address_type;
?>
				<input type="hidden" name="data[address_<?php echo $this->step . '_' . $this->module_position; ?>][address_id]" value="<?php echo (int)$this->options['edit_address']; ?>"/>
				<h5 class="uk-display-block font f600 uk-text-dark uk-margin-remove-top"><?php echo JText::_('HIKASHOP_EDIT_ADDRESS'); ?></h5>
<?php
		}

		$this->checkoutHelper->displayMessages('address');
?>
<fieldset class="formContainer uk-form-stacked uk-margin-medium-bottom">
    <div class="uk-child-width-1-1 uk-child-width-1-3@m" data-uk-grid>
<?php
		if(!empty($this->extraData[$this->module_position]) && !empty($this->extraData[$this->module_position]->address_top)) { echo implode("\r\n", $this->extraData[$this->module_position]->address_top); }
		if(!empty($fields_type))
			$fields_type .= '_';
		foreach($this->cart_addresses[$fields_type.'fields'] as $field) {
			if(empty($field->field_frontcomp))
				continue;

			$fieldname = $field->field_namekey;
?>

	<div class="hikashop_checkout_address_<?php echo $fieldname;?> <?php if ($fieldname == 'address_street') { echo 'uk-width-1-1 uk-width-2-3@m'; } elseif ($fieldname == 'address_pelak' || $fieldname == 'address_unit') { echo 'uk-width-1-2 uk-width-1-6@m'; } ?>" id="hikashop_checkout_address_<?php echo $this->step . '_' . $this->module_position .'_'.$fieldname; ?>">
<?php
		$classname = $labelcolumnclass.' uk-form-label';
		echo $this->fieldClass->getFieldName($field, true, $classname);
?>
		<div class="<?php echo $inputcolumnclass;?>">
<?php
			$onWhat = ($field->field_type == 'radio') ? 'onclick' : 'onchange';
			$field->table_name = 'order';
			echo $this->fieldClass->display(
				$field,
				@$this->edit_address->$fieldname,
				'data[address_'.$this->step . '_' . $this->module_position.']['.$fieldname.']',
				false,
				' class="hkform-control" ' . $onWhat . '="window.hikashop.toggleField(this.value,\''.$fieldname.'\',\'address_'.$this->step . '_'.$this->module_position.'\',0,\'hikashop_checkout_\');"',
				false,
				$this->cart_addresses['fields'],
				$this->edit_address,
				false
			);
?>
		</div>
	</div>
	<input type="hidden" name="data[address_selecttype_<?php echo $this->step . '_' . $this->module_position; ?>]" value="1" />
<?php
		}
		if(!empty($this->options['same_address']) && !empty($this->options['new_address_type']) && ($this->options['new_address_type'] == 'shipping' || $this->options['show_shipping'])) {
			$checked = '';
			if(!empty($this->options['same_address_pre_checked'])) {
				$checked = ' checked';
			}
?>
	<div class="uk-hidden hkform-group control-group hikashop_checkout_address_same" id="hikashop_checkout_address_<?php echo $this->step . '_' . $this->module_position .'_same'; ?>">
		<div class="<?php echo $labelcolumnclass; ?>"></div>
		<div class="<?php echo $inputcolumnclass;?>">
			<label><input type="checkbox"<?php echo $checked; ?> name="data[address_bothtypes_<?php echo $this->step . '_' . $this->module_position; ?>]" value="1"> <?php
				$other = ($this->options['new_address_type'] == 'billing') ? 'shipping' : 'billing';
				echo JText::_('HIKASHOP_ALSO_'.strtoupper($other).'_ADDRESS');
			?></label>
		</div>
	</div>
<?php
		}
?>
    </div>
</fieldset>
<?php
		if(!empty($this->options['new_address_type'])) {
?>
				<input type="hidden" name="data[address_type_<?php echo $this->step . '_' . $this->module_position; ?>]" value="<?php echo $this->options['new_address_type']; ?>" />
<?php
		}

		if(!empty($this->extraData[$this->module_position]) && !empty($this->extraData[$this->module_position]->address_bottom)) { echo implode("\r\n", $this->extraData[$this->module_position]->address_bottom); }
?>
			</fieldset>
			<div class="hikashop_address_required_info_line uk-hidden">
				<div class="controls"><?php echo JText::_('HIKA_REGISTER_REQUIRED'); ?></div>
			</div>
        <div data-uk-grid>
            <div class="uk-width-1-1 uk-width-1-3@m">
                <div>
                    <div class="uk-grid-small" data-uk-grid>
                        <div class="uk-width-1-1 uk-width-1-2@m">
                            <button onclick="return window.checkout.submitAddress(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>);" class="uk-button uk-button-primary uk-width-1-1 hikashop_checkout_address_ok_button"><?php echo JText::_('HIKA_OK'); ;?></button>
                        </div>
                        <?php if(!empty($this->options['display_cancel'])) { ?>
                            <div class="uk-width-1-1 uk-width-1-2@m">
                                <button onclick="return window.checkout.refreshAddress(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>);" class="uk-button uk-button-default uk-width-1-1 hikashop_checkout_address_cancel_button"><?php echo JText::_('HIKA_CANCEL'); ;?></button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

<?php
	}

	if(empty($this->options['edit_address']) && !empty($this->options['show_billing'])) {
?>

			<fieldset class="hika_address_field hikashop_checkout_billing_address_block">
				<legend><?php echo JText::_('HIKASHOP_BILLING_ADDRESS'); ?></legend>
<?php
		if(!empty($this->options['read_only'])) {
			echo $this->addressClass->displayAddress($this->cart_addresses['billing_fields'], $this->cart_addresses['data'][ $cart->cart_billing_address_id ], 'address');
		} elseif($this->options['address_selector'] == 2) {
			if(!empty($this->options['multi_address'])) {
				$values = array();
				foreach($this->cart_addresses['data'] as $k => $address) {
					if(empty($address))
						continue;
					if(!empty($address->address_type) && !in_array($address->address_type, array('both', 'billing')))
						continue;
					$addr = $this->addressClass->miniFormat($address);
					$values[] = JHTML::_('select.option', $k, $addr);
				}
				$values[] = JHTML::_('select.option', 0, JText::_('HIKASHOP_NEW_ADDRESS_ITEM'));
				echo JHTML::_('select.genericlist', $values, 'checkout[address][billing]', 'class="uk-select font uk-width-1-1 hikashop_field_dropdown" onchange="window.checkout.submitAddress('.$this->step.','.$this->module_position.');"', 'value', 'text', $cart->cart_billing_address_id, 'hikashop_address_billing_selector_'.$this->step.'_'.$this->module_position);
			} else {
?>
				<input type="hidden" name="checkout[address][billing]" value="<?php echo $cart->cart_billing_address_id; ?>" />
<?php
			}
			$update_url = 'address&task=edit&cid='.(int)$cart->cart_billing_address_id;
			$delete_url = 'address&task=delete&cid='.(int)$cart->cart_billing_address_id;
?>
				<div class="hika_address_element">
					<div class="hika_edit">
						<a href="<?php echo hikashop_completeLink($update_url);?>" onclick="return window.checkout.editAddress(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>,<?php echo (int)$cart->cart_billing_address_id; ?>);" title="<?php echo JText::_('HIKA_EDIT'); ?>"><i class="fas fa-pen"></i><span><?php echo JText::_('HIKA_EDIT'); ?></span></a>
<?php
			if(!empty($this->options['multi_address'])) {
?>
						<a href="<?php echo hikashop_completeLink($delete_url);?>" onclick="return window.checkout.deleteAddress(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>,<?php echo (int)$cart->cart_billing_address_id; ?>);" title="<?php echo JText::_('HIKA_DELETE'); ?>" ><i class="fas fa-trash"></i><span><?php echo JText::_('HIKA_DELETE'); ?></span></a>
<?php
			}
?>
					</div>
					<div class="hika_address_display">
<?php
			echo $this->addressClass->displayAddress($this->cart_addresses['billing_fields'], $this->cart_addresses['data'][ $cart->cart_billing_address_id ], 'address');
?>
					</div>
				</div>
<?php
		} else { // address_selector : 0 or 1
			foreach($this->cart_addresses['data'] as $k => $address) {
				if(!empty($address->address_type) && !in_array($address->address_type, array('both', 'billing')))
					continue;

				$checked = '';
				if($cart->cart_billing_address_id == (int)$address->address_id)
					$checked = ' checked="checked"';
				elseif(empty($this->options['multi_address']))
					continue;

				$update_url = 'address&task=edit&cid='.(int)$address->address_id;
				$delete_url = 'address&task=delete&cid='.(int)$address->address_id;
				$input_type = 'radio';
				if(empty($this->options['multi_address']))
					$input_type = 'hidden';
?>
				<div class="hika_address_element">
					<div class="hika_edit">
						<input type="<?php echo $input_type; ?>" name="checkout[address][billing]" value="<?php echo (int)$address->address_id; ?>" onchange="window.checkout.submitAddress(<?php echo (int)$this->step; ?>,<?php echo (int)$this->module_position; ?>);" <?php echo $checked; ?>/>
						<a href="<?php echo hikashop_completeLink($update_url);?>" onclick="return window.checkout.editAddress(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>,<?php echo (int)$address->address_id; ?>);" title="<?php echo JText::_('HIKA_EDIT'); ?>"><i class="fas fa-pen"></i> <span><?php echo JText::_('HIKA_EDIT'); ?></span></a>
<?php
				if(!empty($this->options['multi_address'])) {
?>
						<a href="<?php echo hikashop_completeLink($delete_url);?>" onclick="return window.checkout.deleteAddress(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>,<?php echo (int)$address->address_id; ?>);" title="<?php echo JText::_('HIKA_DELETE'); ?>"><i class="fas fa-trash"></i> <span><?php echo JText::_('HIKA_DELETE'); ?></span></a>
<?php
			}
?>
					</div>
					<div class="hika_address_display">
<?php
				echo $this->addressClass->displayAddress($this->cart_addresses['billing_fields'], $address, 'address');
?>
					</div>
				</div>
<?php
			}
			if(!empty($this->options['multi_address'])) {
?>
				<button onclick="return window.checkout.newAddress(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>, 'billing');" class="<?php echo $this->config->get('css_button','hikabtn'); ?> hikabtn-success hikashop_checkout_address_new_button"><i class="fa fa-plus"></i> <?php echo JText::_('HIKA_NEW'); ?></button>
<?php
			}
		}
?>
			</fieldset>
<?php
	}

	if(empty($this->options['edit_address']) && !empty($this->options['show_billing']) && !empty($this->options['show_shipping'])) {
?>
		</div>
		<div class="hkc-sm-6">
<?php
	}

	if(empty($this->options['edit_address']) && !empty($this->options['show_shipping'])) {
		if($shippingAddress_override !== '') {
?>

			<fieldset class="formContainer uk-form-stacked hika_address_field hikashop_checkout_shipping_address_block">
                <div class="uk-margin-bottom uk-text-zero">
                    <div class="uk-grid-small" data-uk-grid>
                        <div class="uk-width-expand"><h5 class="uk-display-block font f600 uk-text-dark uk-margin-remove"><?php echo JText::_('HIKASHOP_SHIPPING_ADDRESS'); ?></h5></div>
                        <?php if(!empty($this->options['multi_address'])) { ?>
                            <div class="uk-flex uk-flex-middle uk-width-auto">
                                <a href="#" onclick="return window.checkout.newAddress(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>, 'shipping');" class="uk-text-small font f500 uk-text-success uk-display-inline-block hikashop_checkout_address_new_button"><?php echo JText::_('HIKA_NEW_ADDRESS'); ?></a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
<?php
			$shipping_address_id = (int)$cart->cart_shipping_address_ids;
			if(!empty($shippingAddress_override)) {
?>
				<span class="hikashop_checkout_shipping_address_info"><?php
					echo $shippingAddress_override;
				?></span>
<?php
			} elseif(!empty($this->options['read_only'])) {
				echo $this->addressClass->displayAddress($this->cart_addresses['shipping_fields'], $this->cart_addresses['data'][ $shipping_address_id ], 'address');
			} elseif($this->options['address_selector'] == 2) {
				if(!empty($this->options['multi_address'])) {
					$values = array();
					foreach($this->cart_addresses['data'] as $k => $address) {
						if(empty($address))
							continue;
						if(!empty($address->address_type) && !in_array($address->address_type, array('both', 'shipping')))
							continue;
						$addr = $this->addressClass->miniFormat($address);
						$values[] = JHTML::_('select.option', $k, $addr);
					}
					$values[] = JHTML::_('select.option', 0, JText::_('HIKASHOP_NEW_ADDRESS_ITEM'));
					echo JHTML::_('select.genericlist', $values, 'checkout[address][shipping]', 'class="uk-select font uk-width-1-1 hikashop_field_dropdown" onchange="window.checkout.submitAddress('.$this->step.','.$this->module_position.');"', 'value', 'text', $shipping_address_id, 'hikashop_address_shipping_selector_'.$this->step.'_'.$this->module_position);
				} else {
?>
				<input type="hidden" name="checkout[address][shipping]" value="<?php echo $shipping_address_id; ?>" />
<?php
				}
				$update_url = 'address&task=edit&cid='.(int)$shipping_address_id;
				$delete_url = 'address&task=delete&cid='.(int)$shipping_address_id;
?>
				<div class="hika_address_element">
					<div class="hika_edit">
						<a href="<?php echo hikashop_completeLink($update_url);?>" onclick="return window.checkout.editAddress(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>,<?php echo (int)$shipping_address_id; ?>);" title="<?php echo JText::_('HIKA_EDIT'); ?>"><i class="fas fa-pen"></i> <span><?php echo JText::_('HIKA_EDIT'); ?></span></a>
<?php
				if(!empty($this->options['multi_address'])) {
?>
						<a href="<?php echo hikashop_completeLink($delete_url);?>" onclick="return window.checkout.deleteAddress(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>,<?php echo (int)$shipping_address_id; ?>);" title="<?php echo JText::_('HIKA_DELETE'); ?>"><i class="fas fa-trash"></i> <span><?php echo JText::_('HIKA_DELETE'); ?></span></a>
<?php
				}
?>
					</div>
					<div class="hika_address_display">
<?php
				echo $this->addressClass->displayAddress($this->cart_addresses['shipping_fields'], $this->cart_addresses['data'][ $shipping_address_id ], 'address');
?>
					</div>
				</div>
<?php
			} else {
			    echo '<div class="uk-child-width-1-1" data-uk-grid>';
				foreach($this->cart_addresses['data'] as $k => $address) {
					if(!empty($address->address_type) && !in_array($address->address_type, array('both', 'shipping')))
						continue;

					$checked = '';
					if($shipping_address_id == (int)$address->address_id)
						$checked = ' checked="checked"';
					elseif(empty($this->options['multi_address']))
						continue;

					$update_url = 'address&task=edit&cid='.(int)$address->address_id;
					$delete_url = 'address&task=delete&cid='.(int)$address->address_id;
					$input_type = 'radio';
					if(empty($this->options['multi_address']))
						$input_type = 'hidden';
?>
				<div class="hika_address_element">
                    <div class="uk-grid-small" data-uk-grid>
                        <div class="uk-width-auto uk-flex uk-flex-middle">
                            <input id="addressRadio-<?php echo (int)$address->address_id; ?>" class="uk-radio uk-margin-remove" type="<?php echo $input_type; ?>" name="checkout[address][shipping]" value="<?php echo (int)$address->address_id; ?>" onchange="window.checkout.submitAddress(<?php echo (int)$this->step; ?>,<?php echo (int)$this->module_position; ?>);" <?php echo $checked; ?>/>
                        </div>
                        <div class="uk-width-expand">
                            <div class="uk-position-relative">
                                <label for="addressRadio-<?php echo (int)$address->address_id; ?>">
                                    <div class="hika_address_display">
                                        <?php echo $this->addressClass->displayAddress($this->cart_addresses['shipping_fields'], $address, 'address'); ?>
                                    </div>
                                </label>
                                <div class="uk-position-bottom-left uk-text-zero">
                                    <div class="uk-grid-small uk-grid-divider" data-uk-grid>
                                        <div><a class="uk-text-tiny font f500 uk-text-secondary" href="<?php echo hikashop_completeLink($update_url);?>" onclick="return window.checkout.editAddress(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>,<?php echo (int)$address->address_id; ?>);" title="<?php echo JText::_('HIKA_EDIT'); ?>"><?php echo JText::_('HIKA_EDIT'); ?></a></div>
                                        <?php if(!empty($this->options['multi_address'])) { ?>
                                            <div><a class="uk-text-tiny font f500 uk-text-danger" href="<?php echo hikashop_completeLink($delete_url);?>" onclick="return window.checkout.deleteAddress(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>,<?php echo (int)$address->address_id; ?>);" title="<?php echo JText::_('HIKA_DELETE'); ?>"><?php echo JText::_('HIKA_DELETE'); ?></a></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
                <?php } ?>
                <?php echo '</div>'; ?>


                <?php } ?>
			</fieldset>
<?php
		}
	}

	if(empty($this->options['edit_address']) && !empty($this->options['show_billing']) && !empty($this->options['show_shipping'])) {
?>
		</div>
	</div>
<?php
	}

	if(!empty($this->options['js'])) {
?>
<script type="text/javascript">
<?php echo $this->options['js']; ?>
</script>

<?php
	}

} // Options:Display

if(empty($this->ajax)) {
?>
</div>
<script type="text/javascript">
if(!window.checkout) window.checkout = {};
window.Oby.registerAjax(['checkout.user.updated','checkout.address.updated'], function(params){
	window.checkout.refreshAddress(<?php echo (int)$this->step; ?>, <?php echo (int)$this->module_position; ?>);
});
window.checkout.refreshAddress = function(step, id) { return window.checkout.refreshBlock('address', step, id); };
window.checkout.submitAddress = function(step, id) { return window.checkout.submitBlock('address', step, id); };
window.checkout.editAddress = function(step, id, addr) {
	window.checkout.submitBlock('address', step, id, {'checkout[address][edit]':addr});
	return false;
};
window.checkout.deleteAddress = function(step, id, addr) {
	window.checkout.submitBlock('address', step, id, {'checkout[address][delete]':addr});
	return false;
};
window.checkout.newAddress = function(step, id, type) {
	window.checkout.submitBlock('address', step, id, {'checkout[address][new]':type});
	return false;
};
</script>
<?php
}elseif(!empty($this->options['edit_address'])) {
?>
<script type="text/javascript">
document.getElementById('hikashop_checkout_address_<?php echo $this->step; ?>_<?php echo $this->module_position; ?>').scrollIntoView();
</script>
<?php
}
