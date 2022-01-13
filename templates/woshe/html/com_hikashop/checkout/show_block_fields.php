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
$cart = $this->checkoutHelper->getCart();
if(!hikashop_level(2))
	return;
$fields = null;
if(!empty($cart->order_fields)) {
	$fields = hikashop_copy($cart->order_fields);
	if(!empty($this->options['fields'])){
		$ids = is_string($this->options['fields']) ? explode(',', $this->options['fields']) :  $this->options['fields'];
		$unset = array();
		foreach($cart->order_fields as $k => $field){
			if(!in_array($field->field_id, $ids)){
				$unset[] = $k;
			}
		}
		if(count($unset)){
			foreach($unset as $u){
				unset($fields[$u]);
			}
		}
	}
}

$labelcolumnclass = '';
$inputcolumnclass = '';

if(empty($this->ajax)) {
?>
<div id="hikashop_checkout_fields_<?php echo $this->step; ?>_<?php echo $this->module_position; ?>" data-checkout-step="<?php echo $this->step; ?>" data-checkout-pos="<?php echo $this->module_position; ?>" class="hikashop_checkout_fields uk-width-1-1">
<?php } ?>
	<div class="hikashop_checkout_loading_elem"></div>
	<div class="hikashop_checkout_loading_spinner"></div>

<?php
	$this->checkoutHelper->displayMessages('fields');

?>
<div class="uk-form-stacked">

<?php
	if(!empty($fields)) {
		if(!empty($this->options['show_title'])) {
?>
	<h5 class="uk-display-block font f600 uk-text-dark uk-margin-remove-top"><?php echo JText::_('PREFERRED_SHIPPING_DATE_HOUR'); ?></h5>
<?php
		} ?>
    <div class="uk-child-width-1-1 uk-child-width-1-2@m" data-uk-grid>
		<?php
		foreach($fields as $fieldName => $oneExtraField) {
			$oneExtraField->registration_page = @$this->registration_page;
			$value = (isset($_SESSION['hikashop_order_data']) && is_object($_SESSION['hikashop_order_data']) && isset($_SESSION['hikashop_order_data']->$fieldName) && !is_null($_SESSION['hikashop_order_data']->$fieldName)) ? $_SESSION['hikashop_order_data']->$fieldName : @$cart->cart_fields->$fieldName;

			if(empty($value) && !empty($this->options['read_only']))
				continue;
?>
	<div class="hikashop_checkout_<?php echo $fieldName;?>_line" id="hikashop_order_<?php echo $this->step . '_' . $this->module_position . '_' . $oneExtraField->field_namekey; ?>">
<?php
			$requiredDisplay = true;
			if(!empty($this->options['read_only'])) {
				$requiredDisplay = false;
			}
			$label =  $this->fieldClass->getFieldName($oneExtraField, $requiredDisplay, $labelcolumnclass.' uk-form-label formControlLabel');
			if(!empty($this->options['read_only'])) {
				$label = str_replace('</label>',' :</label>',$label);
			}
			echo $label;
?>
		<div class="<?php echo $inputcolumnclass;?>">
<?php

			if(empty($this->options['read_only'])) {
				$onWhat = ($oneExtraField->field_type == 'radio') ? 'onclick' : 'onchange';
				echo $this->fieldClass->display(
					$oneExtraField,
					$value,
					'data[order_' . $this->step . '_' . $this->module_position.']['.$fieldName.']',
					false,
					' class="hkform-control" '.$onWhat.'="window.hikashop.toggleField(this.value,\''.$fieldName.'\',\'order_' . $this->step . '_' . $this->module_position.'\',0,\'hikashop_\');"',
					false,
					$fields,
					$cart->cart_fields,
					false
				);
			}else{
				echo $this->fieldClass->show($oneExtraField, $value);
			}
?>
		</div>
	</div>
<?php
		}
		if(!empty($this->options['show_submit'])) {
?>
	<div class="hkform-group control-group hikashop_fields_button_line">
		<div class="<?php echo $labelcolumnclass;?> hkcontrol-label"></div>
		<div class=" <?php echo $inputcolumnclass;?>">
			<button type="submit" onclick="return window.checkout.submitFields(<?php echo $this->step.','.$this->module_position; ?>);" class="<?php echo $this->config->get('css_button','hikabtn'); ?> hikabtn_checkout_fields_submit">
				<?php echo JText::_('HIKA_SUBMIT_FIELDS'); ?>
			</button>
		</div>
	</div>
<?php
		}
	}
?>
</div>
</div>
<?php
	if(!empty($this->options['js'])) {
?>
<script type="text/javascript">
<?php echo $this->options['js']; ?>
</script>
<?php
	}
	if(empty($this->ajax)) { ?>
</div>
<script type="text/javascript">
if(!window.checkout) window.checkout = {};
window.Oby.registerAjax(['checkout.fields.updated','cart.updated','checkout.shipping.changed','checkout.payment.changed'], function(params){
	window.checkout.refreshFields(<?php echo (int)$this->step; ?>, <?php echo (int)$this->module_position; ?>);
});
window.checkout.refreshFields = function(step, id) { return window.checkout.refreshBlock('fields', step, id); };
window.checkout.submitFields = function(step, id) {
	return window.checkout.submitBlock('fields', step, id);
};
</script>
<?php }
