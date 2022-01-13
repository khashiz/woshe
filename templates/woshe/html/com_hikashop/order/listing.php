<?php
/**
 * @package	HikaShop for Joomla!
 * @version	4.4.4
 * @author	hikashop.com
 * @copyright	(C) 2010-2021 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>
<div id="hikashop_order_listing">
<?php
	echo $this->toolbarHelper->process($this->toolbar, $this->title);
?>
<form action="<?php echo hikashop_completeLink('order'); ?>" method="post" name="adminForm" id="adminForm">

<div class="uk-overflow-hidden uk-margin-bottom">
	<div>
        <?php /* ?>
		<div class="uk-hidden">
			<input type="text" name="search" id="hikashop_search" value="<?php echo $this->escape($this->pageInfo->search);?>" placeholder="<?php echo JText::_('HIKA_SEARCH'); ?>" class="inputbox" onchange="this.form.submit();" />
			<button class="hikabtn hikabtn-primary" onclick="this.form.submit();"><?php echo JText::_('GO'); ?></button>
<?php
	foreach($this->leftFilters as $name => $filterObj) {
		if(is_string($filterObj))
			echo $filterObj;
		else
			echo $filterObj->displayFilter($name, $this->pageInfo->filter);
	}
?>		</div>
        <?php */ ?>
		<div>
            <?php
            foreach($this->rightFilters as $name => $filterObj) {
                if ($name === 'order_status') {
                    if(is_string($filterObj))
                        echo $filterObj;
                    else
                        echo $filterObj->displayFilter($name, $this->pageInfo->filter, 'class="uk-width-1-1 uk-width-small@m font uk-select uk-select"');
                }
            }
            /*
            foreach($this->rightFilters as $name => $filterObj) {
                if(is_string($filterObj))
                    echo $filterObj;
                else
                    echo $filterObj->displayFilter($name, $this->pageInfo->filter);
            }
            */
            ?>
		</div>
	</div>
</div>

<div class="hikashop_order_listing">
	<div class="hikashop_orders_content">
<?php
	$url_itemid = (!empty($this->Itemid) ? '&Itemid=' . $this->Itemid : '');
	$cancel_orders = false;
	$print_invoice = false;
	$cancel_url = '&cancel_url='.base64_encode(hikashop_currentURL());

	$i = 0;
	$k = 0;
	if (count($this->rows)) {
        echo '<div class="uk-child-width-1-1" data-uk-grid>';
        foreach($this->rows as &$row) {
            $order_link = hikashop_completeLink('order&task=show&cid='.$row->order_id.$url_itemid.$cancel_url);
            ?>
            <div>
            <div class="uk-card uk-card-default uk-box-shadow-small">
                <div class="uk-card-header uk-padding-small">
                    <div class="uk-grid-small uk-grid-divider" data-uk-grid>
                        <div class="uk-width-expand">
                            <div>
                                <a class="uk-display-block uk-text-dark uk-link-reset" href="<?php echo $order_link; ?>">
                                    <div>
                                        <div class="uk-grid-small uk-child-width-1-2 uk-child-width-1-4@m uk-text-center uk-text-right@m uk-text-right" data-uk-grid>
                                            <div class="hika_cpanel_date">
                                                <span class="uk-display-block uk-text-muted font uk-text-tiny f500 uk-margin-small-bottom"><?php echo JText::_('ORDER_TIME'); ?></span>
                                                <span class="uk-display-block uk-text-dark font uk-text-small f600"><?php echo JHtml::date((int)$row->order_created, 'D ، d M Y'); ?></span>
                                            </div>
                                            <div class="hika_cpanel_price">
                                                <span class="uk-display-block uk-text-muted font uk-text-tiny f500 uk-margin-small-bottom"><?php echo JText::_('ORDER_AMOUNT'); ?></span>
                                                <span class="uk-display-block uk-text-dark font uk-text-small f600"><?php echo $this->currencyClass->format($row->order_full_price, $row->order_currency_id); ?></span>
                                            </div>
                                            <div class="hika_cpanel_order_number">

                                                <?php if(!empty($row->extraData->topLeft)) { echo implode("\r\n", $row->extraData->topLeft); } ?>
                                                <span class="uk-display-block uk-text-muted font uk-text-tiny f500 uk-margin-small-bottom"><?php echo  JText::_('ORDER_NUMBER'); ?></span>
                                                <span class="uk-display-block uk-text-dark font uk-text-small f600"><?php echo $row->order_number; ?></span>
                                                <?php /* if(!empty($row->order_invoice_number)) { ?>
                                                    <span class="hika_cpanel_title"><?php echo JText::_('INVOICE_NUMBER'); ?> : </span>
                                                    <span class="hika_cpanel_value"><?php echo $row->order_invoice_number; ?></span>
                                                <?php } */ ?>
                                                <?php if(!empty($row->extraData->bottomLeft)) { echo implode("\r\n", $row->extraData->bottomLeft); } ?>
                                            </div>
                                            <div class="hika_cpanel_order_status">
                                                <span class="uk-display-block uk-text-muted font uk-text-tiny f500 uk-margin-small-bottom"><?php echo JText::_('ORDER_STATUS'); ?></span>
                                                <?php if(!empty($row->extraData->topMiddle)) { echo implode("\r\n", $row->extraData->topMiddle); } ?>
                                                <span class="uk-display-block uk-text-<?php echo preg_replace('#[^a-z_0-9]#i', '_', str_replace(' ','_', $row->order_status)); ?> font uk-text-small f600"><?php echo hikashop_orderStatus($row->order_status); ?></span>
                                                <?php if(!empty($row->extraData->bottomMiddle)) { echo implode("\r\n", $row->extraData->bottomMiddle); } ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="uk-width-auto">
                            <div class="uk-height-1-1 uk-flex uk-flex-middle">
                                <!-- PRODUCTS LISTING BUTTON -->
                                <?php if($row->order_id == $this->row->order_id) { ?>
                                    <a class="uk-button uk-button-default uk-padding-small uk-flex uk-flex-middle uk-flex-center uk-height-1-1" data-uk-tooltip="offset: 10; cls: uk-active font;" data-title="<?php echo $this->escape(JText::_('DISPLAY_PRODUCTS')); ?>" href="#" onclick="return window.localPage.handleDetails(this, <?php echo (int)$row->order_id; ?>);"><i class="fas fa-angle-up"></i></a>
                                <?php } else { ?>
                                    <a class="uk-button uk-button-default uk-padding-small uk-flex uk-flex-middle uk-flex-center uk-height-1-1" data-uk-tooltip="offset: 10; cls: uk-active font;" data-title="<?php echo $this->escape(JText::_('DISPLAY_PRODUCTS')); ?>" href="#" onclick="return window.localPage.handleDetails(this, <?php echo (int)$row->order_id; ?>);"><i class="fas fa-angle-down"></i></a>
                                <?php } ?>
                                <!-- EO PRODUCTS LISTING BUTTON -->
                            </div>
                        </div>
                    </div>
                </div>
                <div data-order-container="<?php echo (int)$row->order_id; ?>">
                    <?php
                    if($row->order_id == $this->row->order_id) {
                        $this->setLayout('order_products');
                        echo $this->loadTemplate();
                    }
                    ?>
                </div>
                <div class="uk-card-footer uk-padding-small">
                    <!-- TOP RIGHT EXTRA DATA -->
                    <?php if(!empty($row->extraData->topRight)) { echo implode("\r\n", $row->extraData->topRight); } ?>
                    <!-- EO TOP RIGHT EXTRA DATA -->
                    <!-- ACTIONS BUTTON -->
                    <?php
                    $dropData = array();
                    $dropData[] = array(
                        'name' => JText::_('HIKA_ORDER_DETAILS'),
                        'link' => $order_link
                    );
                    if(!empty($row->show_print_button)) {
                        $print_invoice = true;
                        $dropData[] = array(
                            'name' => JText::_('PRINT_INVOICE'),
                            'link' => '#print_invoice',
                            'click' => 'return window.localPage.printInvoice('.(int)$row->order_id.');',
                        );
                    }
                    if(!empty($row->show_cancel_button)) {
                        $cancel_orders = true;
                        $dropData[] = array(
                            'name' => '<i class="fas fa-ban"></i> '. JText::_('CANCEL_ORDER'),
                            'link' => '#cancel_order',
                            'click' => 'return window.localPage.cancelOrder('.(int)$row->order_id.',\''.$row->order_number.'\');',
                        );
                    }
                    if(!empty($row->show_payment_button) && bccomp($row->order_full_price, 0, 5) > 0) {
                        $url_param = ($this->payment_change) ? '&select_payment=1' : '';
                        $url = hikashop_completeLink('order&task=pay&order_id='.$row->order_id.$url_param.$url_itemid);
                        if($this->config->get('force_ssl',0) && strpos('https://',$url) === false)
                            $url = str_replace('http://','https://', $url);
                        $dropData[] = array(
                            'name' => '<i class="fas fa-money-bill-alt"></i> '. JText::_('PAY_NOW'),
                            'link' => $url
                        );
                    }
                    if($this->config->get('allow_reorder', 0)) {
                        $url = hikashop_completeLink('order&task=reorder&order_id='.$row->order_id.$url_itemid);
                        if($this->config->get('force_ssl',0) && strpos('https://',$url) === false)
                            $url = str_replace('http://','https://', $url);
                        $dropData[] = array(
                            'name' => '<i class="fas fa-redo-alt"></i> '. JText::_('REORDER'),
                            'link' => $url
                        );
                    }
                    if(!empty($row->show_contact_button)) {
                        $url = hikashop_completeLink('order&task=contact&order_id='.$row->order_id.$url_itemid);
                        $dropData[] = array(
                            'name' => '<i class="far fa-envelope"></i> '. JText::_('CONTACT_US_ABOUT_YOUR_ORDER'),
                            'link' => $url
                        );
                    }
                    if(!empty($row->actions)) {
                        $dropData = array_merge($dropData, $row->actions);
                    }

                    echo '<div class="uk-grid-small uk-child-width-1-1 uk-child-width-auto@m" data-uk-grid>';
                    for ($k=0;$k<count($dropData);$k++){
                        echo '<div><a href="'.$dropData[$k]['link'].'" class="uk-button uk-button-default uk-width-1-1"'.($dropData[$k]['click'] ? "onclick='".$dropData[$k]['click']."'" : "").'>'.$dropData[$k]['name'].'</a></div>';
                    }
                    echo '</div>';

                    /*
                    if(!empty($dropData)) {
                        echo $this->dropdownHelper->display(
                            JText::_('HIKASHOP_ACTIONS'),
                            $dropData,
                            array('type' => 'btn',  'right' => true, 'up' => false)
                        );
                    }
                    */
                    ?>
                    <!-- EO ACTIONS BUTTON -->
                    <!-- BOTTOM RIGHT EXTRA DATA -->
                    <?php if(!empty($row->extraData->bottomRight)) { echo implode("\r\n", $row->extraData->bottomRight); } ?>
                    <!-- EO BOTTOM RIGHT EXTRA DATA -->
                </div>
            </div>
            </div>
            <?php
            $i++;
            $k = 1 - $k;
        }
        echo '</div>';
    } else {
	    echo '<div><div class="uk-placeholder uk-placeholder-large uk-height-medium uk-flex uk-flex-middle uk-flex-center"><p class="font uk-h6 f500 uk-text-muted">'.JText::_('HIKA_CPANEL_NO_ORDERS').'</p></div></div>';
    }
	unset($row);
?>
<!-- PAGINATION -->
		<div class="uk-hidden">
			<div class="pagination">
				<?php $this->pagination->form = '_bottom'; echo $this->pagination->getListFooter(); ?>
				<?php echo '<span class="hikashop_results_counter">'.$this->pagination->getResultsCounter().'</span>'; ?>
			</div>
		</div>
<!-- EO PAGINATION -->
	</div>



	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>"/>
	<input type="hidden" name="option" value="<?php echo HIKASHOP_COMPONENT; ?>" />
	<input type="hidden" name="task" value="listing" />
	<input type="hidden" name="ctrl" value="<?php echo hikaInput::get()->getCmd('ctrl'); ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</div>
</form>
<script type="text/javascript">
if(!window.localPage) window.localPage = {};
window.localPage.handleDetails = function(btn, id) {
	var d = document, details = d.getElementById('hika_order_'+id+'_details');

	if(details) {
		details.style.display = (details.style.display == 'none' ? '' : 'none');
		if(details.style.display) {
			btn.innerHTML = '<i class="fas fa-angle-down"></i>';
			btn.setAttribute('data-original-title','<?php echo $this->escape(JText::_('DISPLAY_PRODUCTS')); ?>');
		} else{
			btn.innerHTML = '<i class="fas fa-angle-up"></i>';
			btn.setAttribute('data-original-title','<?php echo $this->escape(JText::_('HIDE_PRODUCTS')); ?>');
		}
		return false;
	}

	return window.localPage.loadOrderDetails(btn, id);
};
window.localPage.loadOrderDetails = function(btn, id) {
	var d = document, o = window.Oby, el = d.querySelector('[data-order-container="'+id+'"]');
	if(!el) return false;
	btn.classList.add('hikadisabled');
	btn.disabled = true;
	btn.blur();
	btn.innerHTML = '<i class="fas fa-spinner fa-pulse"></i>';
	var c = d.createElement('div');
	o.xRequest("<?php echo hikashop_completeLink('order&task=order_products', 'ajax', false, true); ?>", {mode:'POST',data:'cid='+id},function(xhr){
		if(!xhr.responseText || xhr.status != 200) {
			btn.innerHTML = '<i class="fas fa-angle-down"></i>';
			return;
		}
		btn.classList.remove('hikadisabled');
		btn.disabled = false;
		var resp = o.trim(xhr.responseText);
		c.innerHTML = resp;
		el.appendChild(c.querySelector('#hika_order_'+id+'_details'));
		btn.innerHTML = '<i class="fas fa-angle-up"></i>';
		btn.setAttribute('data-original-title','<?php echo $this->escape(JText::_('HIDE_PRODUCTS')); ?>');
	});
	return false;
};
</script>
<?php

if(!empty($this->rows) && ($print_invoice || $cancel_orders)) {
	echo $this->popupHelper->display(
		'',
		'INVOICE',
		hikashop_completeLink('order&task=invoice'.$url_itemid.$url_token,true),
		'hikashop_print_popup',
		760, 480, '', '', 'link'
	);
?>
<script>
if(!window.localPage) window.localPage = {};
window.localPage.cancelOrder = function(id, number) {
	var d = document, form = d.getElementById('hikashop_cancel_order_form');
	if(!form || !form.elements['order_id']) {
		console.log('Error: Form not found, cannot cancel the order');
		return false;
	}
	if(!confirm('<?php echo JText::_('HIKA_CONFIRM_CANCEL_ORDER', true); ?>'.replace(/ORDER_NUMBER/, number)))
		return false;
	form.elements['order_id'].value = id;
	form.submit();
	return false;
};
window.localPage.printInvoice = function(id) {
	hikashop.openBox('hikashop_print_popup','<?php
		$u = hikashop_completeLink('order&task=invoice'.$url_itemid.$url_token,true);
		echo $u;
		echo (strpos($u, '?') === false) ? '?' : '&';
	?>order_id='+id);
	return false;
};
</script>
<form action="<?php echo hikashop_completeLink('order&task=cancel_order&email=1'); ?>" name="hikashop_cancel_order_form" id="hikashop_cancel_order_form" method="POST">
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>"/>
	<input type="hidden" name="option" value="<?php echo HIKASHOP_COMPONENT; ?>" />
	<input type="hidden" name="task" value="cancel_order" />
	<input type="hidden" name="email" value="1" />
	<input type="hidden" name="order_id" value="" />
	<input type="hidden" name="ctrl" value="order" />
	<input type="hidden" name="redirect_url" value="<?php echo hikashop_currentURL(); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<?php
}
?>
</div>
