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
$tmpl = hikaInput::get()->getCmd('tmpl', '');
if(isset($this->params->address_id) || $tmpl == 'component') {
	echo $this->loadTemplate('legacy');
	return;
}

$show_url = 'address&task=listing';
$save_url = 'address&task=save&cid='.(int)@$this->address->address_id;
$update_url = 'address&task=edit&cid='.(int)@$this->address->address_id.'&address_type='.$this->address->address_type;
$delete_url = 'address&task=delete&cid='.(int)@$this->address->address_id;
$dest = 'hikashop_user_addresses_show';

if(!isset($this->edit) || $this->edit !== true ) {
?>
		<div class="uk-position-bottom-left uk-text-zero">
            <div class="uk-grid-small uk-grid-divider" data-uk-grid>
                <div><a class="uk-text-tiny font f500 uk-text-secondary" href="<?php echo hikashop_completeLink($update_url, 'ajax');?>" onclick="return window.addressMgr.get(this,'<?php echo $dest; ?>');"><?php echo JText::_('HIKA_EDIT'); ?></a></div>
                <div><a class="uk-text-tiny font f500 uk-text-danger" href="<?php echo hikashop_completeLink($delete_url, 'ajax');?>" onclick="return window.addressMgr.delete(this,<?php echo (int)@$this->address->address_id; ?>);"><?php echo JText::_('HIKA_DELETE'); ?></a></div>
            </div>
		</div>
<?php
} else {
	if(!empty($this->ajax)) {
?>
	<div class="hikashop_checkout_loading_elem"></div>
	<div class="hikashop_checkout_loading_spinner"></div>
<?php }
}

if(isset($this->edit) && $this->edit === true) {
	if(empty($this->address->address_id)) {
		$title = $this->type == 'billing' ? 'HIKASHOP_NEW_BILLING_ADDRESS': 'HIKASHOP_NEW_SHIPPING_ADDRESS';
	} else {
		$title = in_array($this->address->address_type, array('billing', 'shipping')) ? 'HIKASHOP_EDIT_'.strtoupper($this->address->address_type).'_ADDRESS' : 'HIKASHOP_EDIT_ADDRESS';
	}
?>
<div class="hikashop_address_edition">
	<h5 class="uk-display-block font f600 uk-text-dark"><?php echo JText::_($title); ?></h5>
    <div class="formContainer uk-form-stacked uk-margin-medium-bottom">
        <div class="uk-child-width-1-1 uk-child-width-1-3@m" data-uk-grid>
            <?php
            $error_messages = hikaRegistry::get('address.error');
            if(!empty($error_messages)) {
                foreach($error_messages as $msg) {
                    hikashop_display($msg[0], $msg[1]);
                }
            }

            if(!empty($this->extraData->address_top)) { echo implode("\r\n", $this->extraData->address_top); }

            foreach($this->fields as $fieldname => $field) {
                ?>
                <div id="hikashop_address_<?php echo $fieldname; ?>" class="<?php if ($fieldname == 'address_street') { echo 'uk-width-1-1 uk-width-2-3@m'; } elseif ($fieldname == 'address_pelak' || $fieldname == 'address_unit') { echo 'uk-width-1-2 uk-width-1-6@m'; } ?>">
                    <label class="uk-form-label hikashop_user_address_<?php echo $fieldname;?>"><?php
                        echo $this->fieldsClass->trans($field->field_realname);
                        if($field->field_required)
                            echo '<strong class="formRequired"><span class="uk-text-danger">&ensp;*&ensp;</span></strong>'; ?>
                    </label>
                    <div class="hikashop_user_address_<?php echo $fieldname;?>"><?php
                        $onWhat = 'onchange';
                        if($field->field_type == 'radio')
                            $onWhat = 'onclick';

                        $field->field_required = false;
                        echo $this->fieldsClass->display(
                            $field,
                            @$this->address->$fieldname,
                            'data[address]['.$fieldname.']',
                            false,
                            ' ' . $onWhat . '="window.hikashop.toggleField(this.value,\''.$fieldname.'\',\'address\',0);"',
                            false,
                            $this->fields,
                            $this->address
                        );
                        ?></div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php
	if(!empty($this->extraData) && !empty($this->extraData->address_bottom)) { echo implode("\r\n", $this->extraData->address_bottom); }

	if(empty($this->address->address_id)) {
?>
	<input type="hidden" name="data[address][address_type]" value="<?php echo @$this->address->address_type; ?>"/>
<?php
	}
?>
	<input type="hidden" name="data[address][address_id]" value="<?php echo @$this->address->address_id; ?>"/>
	<input type="hidden" name="data[address][address_user_id]" value="<?php echo @$this->address->address_user_id; ?>"/>
	<?php echo JHTML::_('form.token'); ?>

    <div class="uk-child-width-1-1 uk-child-width-1-3@m" data-uk-grid>
        <div>
            <div>
                <div class="uk-grid-small uk-child-width-1-2" data-uk-grid>
                    <div>
                        <a href="<?php echo hikashop_completeLink($save_url, 'ajax');?>" onclick="return window.addressMgr.form(this,'<?php echo $dest; ?>');" class="uk-button uk-button-primary uk-width-1-1 font hikashop_checkout_address_ok_button"><?php echo JText::_('HIKA_OK'); ;?></a>
                    </div>
                    <div>
                        <a href="<?php echo hikashop_completeLink($show_url, 'ajax');?>" onclick="return window.addressMgr.get(this,'<?php echo $dest; ?>');" class="uk-button uk-button-default uk-width-1-1 font hikashop_checkout_address_cancel_button"><?php echo JText::_('HIKA_CANCEL'); ;?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
} else {
	if($this->config->get('address_show_details', 0)) {
		foreach($this->fields as $fieldname => $field) {
?>
	<dl class="hika_options">
		<dt class="hikashop_user_address_<?php echo $fieldname;?>"><label><?php echo $this->fieldsClass->trans($field->field_realname);?></label></dt>
		<dd class="hikashop_user_address_<?php echo $fieldname;?>"><span><?php echo $this->fieldsClass->show($field, @$this->address->$fieldname);?></span></dd>
	</dl>
<?php
		}
	} else {
		echo $this->addressClass->maxiFormat($this->address, $this->fields, true);
	}

	if(!empty($this->display_badge)) {
?>
		<div class="" style="float:right"><?php
			if(in_array($this->address->address_type, array('billing', '', 'both')))
				echo '<span class="hk-label hk-label-blue">'.JText::_('HIKASHOP_BILLING_ADDRESS').'</span>';
			if(in_array($this->address->address_type, array('shipping', '', 'both')))
				echo '<span class="hk-label hk-label-orange">'.JText::_('HIKASHOP_SHIPPING_ADDRESS').'</span>';
		?></div>
<?php
	}
}

if(!empty($this->init_js)) {
?>
<script type="text/javascript">
<?php echo $this->init_js; ?>
</script>
<?php
}
