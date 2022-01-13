<?php
/**
 * @package	HikaShop for Joomla!
 * @version	4.4.4
 * @author	hikashop.com
 * @copyright	(C) 2010-2021 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php if(empty($this->ajax)) { ?>
<div id="hikashop_checkout_coupon_<?php echo $this->step; ?>_<?php echo $this->module_position; ?>" data-checkout-step="<?php echo $this->step; ?>" data-checkout-pos="<?php echo $this->module_position; ?>" class="uk-width-1-1 hikashop_checkout_coupon">
<?php } ?>
	<div class="hikashop_checkout_loading_elem"></div>
	<div class="hikashop_checkout_loading_spinner"></div>

<?php

	$cart = $this->checkoutHelper->getCart();
	if(empty($cart->coupon)) {
?>
        <div class="uk-margin-bottom">
            <div class="uk-grid-small" data-uk-grid>
                <div class="uk-width-expand">
                    <h5 class="uk-display-block font f600 uk-text-dark uk-margin-remove-top uk-margin-remove-bottom" for="hikashop_checkout_coupon_input_<?php echo $this->step; ?>_<?php echo $this->module_position; ?>"><?php echo JText::_('HIKASHOP_ENTER_COUPON'); ?></h5>
                </div>
                <div class="uk-width-auto"><?php $this->checkoutHelper->displayMessages('coupon'); ?></div>
            </div>
        </div>
	<div class="uk-grid-small" data-uk-grid>
        <div class="uk-width-expand">
            <input class="uk-input hikashop_checkout_coupon_field" id="hikashop_checkout_coupon_input_<?php echo $this->step; ?>_<?php echo $this->module_position; ?>" type="text" name="checkout[coupon]" value=""/>
        </div>
        <div class="uk-width-1-4">
            <button type="submit" onclick="return window.checkout.submitCoupon(<?php echo $this->step.','.$this->module_position; ?>);" class="uk-button uk-button-primary uk-width-1-1 hikabtn_checkout_coupon_add"><?php echo JText::_('HIKA_OK'); ?></button>
        </div>
    </div>
<?php
	} else {
	    ?>
        <h5 class="uk-display-block font f600 uk-text-dark uk-margin-remove-top"><?php echo JText::_('HIKASHOP_COUPON'); ?></h5>
    <?php
		echo '<div class="uk-child-width-auto uk-grid-small uk-text-zero" data-uk-grid><div><p class="font f500 uk-text-small uk-text-dark">'.JText::sprintf('HIKASHOP_COUPON_LABEL', @$cart->coupon->discount_code).'</p></div>';
		if(empty($cart->cart_params->coupon_autoloaded)) {
			global $Itemid;
			$url_itemid = '';
			if(!empty($Itemid))
				$url_itemid = '&Itemid=' . $Itemid;
?>
            <div>
                <a class="uk-text-small uk-text-danger font f500" href="#removeCoupon" onclick="return window.checkout.removeCoupon(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>);" title="<?php echo JText::_('REMOVE_COUPON'); ?>"><?php echo JText::_('REMOVE_COUPON'); ?></a>
            </div>
<?php
		}
		echo '</div>';
	}

	if(empty($this->ajax)) { ?>
</div>
<script type="text/javascript">
if(!window.checkout) window.checkout = {};
window.Oby.registerAjax(['checkout.coupon.updated','cart.updated'], function(params){
	if(params && (params.cart_empty || (params.resp && params.resp.empty))) return;
	window.checkout.refreshCoupon(<?php echo (int)$this->step; ?>, <?php echo (int)$this->module_position; ?>);
});
window.checkout.refreshCoupon = function(step, id) { return window.checkout.refreshBlock('coupon', step, id); };
window.checkout.submitCoupon = function(step, id) {
	var el = document.getElementById('hikashop_checkout_coupon_input_' + step + '_' + id);
	if(!el)
		return false;
	if(el.value == '') {
		window.Oby.addClass(el, 'hikashop_red_border');
		return false;
	}
	return window.checkout.submitBlock('coupon', step, id);
};
window.checkout.removeCoupon = function(step, id) {
	window.checkout.submitBlock('coupon', step, id, {'checkout[removecoupon]':1});
	return false;
};
</script>
<?php }
