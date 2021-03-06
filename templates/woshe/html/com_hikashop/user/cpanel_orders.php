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
$url_itemid = (isset($this->url_itemid)) ? $this->url_itemid : '';
$cancel_orders = false;
$print_invoice = false;

?>

<?php
if(empty($this->cpanel_data->cpanel_orders)) {
?>
	<div>
        <div class="uk-placeholder uk-placeholder-large uk-height-medium uk-flex uk-flex-middle uk-flex-center">
            <p class="font uk-h6 f500 uk-text-muted"><?php echo JText::_('HIKA_CPANEL_NO_ORDERS'); ?></p>
        </div>
	</div>
<?php
}
$cancel_url = '&cancel_url='.base64_encode(hikashop_currentURL());
if (count($this->cpanel_data->cpanel_orders)) {
    echo '<h5 class="uk-display-block font f600 uk-text-dark uk-margin-remove-top">'.$this->cpanel_data->cpanel_title.'</h5>';
}
echo '<div class="uk-child-width-1-1" data-uk-grid>';
foreach($this->cpanel_data->cpanel_orders as $order_id => $order) {
	$order_link = hikashop_completeLink('order&task=show&cid='.$order_id.$url_itemid.$cancel_url);
?>
    <div>
<div class="uk-card uk-card-default uk-box-shadow-small">
	<div class="uk-card-header uk-padding-small">
		<a class="uk-display-block uk-text-dark uk-link-reset" href="<?php echo $order_link; ?>">
            <div>
                <div class="uk-grid-small uk-child-width-1-2 uk-child-width-1-4@m uk-text-center uk-text-right@m uk-text-right" data-uk-grid>
                    <div class="hika_cpanel_date">
                        <span class="uk-display-block uk-text-muted font uk-text-tiny f500 uk-margin-small-bottom"><?php echo JText::_('ORDER_TIME'); ?></span>
                        <span class="uk-display-block uk-text-dark font uk-text-small f600"><?php echo JHtml::date($order->order_created, 'D ، d M Y'); ?></span>
                    </div>
                    <div class="hika_cpanel_price">
                        <span class="uk-display-block uk-text-muted font uk-text-tiny f500 uk-margin-small-bottom"><?php echo JText::_('ORDER_AMOUNT'); ?></span>
                        <span class="uk-display-block uk-text-dark font uk-text-small f600"><?php echo $this->currencyClass->format($order->order_full_price, $order->order_currency_id); ?></span>
                    </div>
                    <div class="hika_cpanel_order_number">
                        <?php if(!empty($order->extraData->topLeft)) { echo implode("\r\n", $order->extraData->topLeft); } ?>
                        <span class="uk-display-block uk-text-muted font uk-text-tiny f500 uk-margin-small-bottom"><?php echo  JText::_('ORDER_NUMBER'); ?></span>
                        <span class="uk-display-block uk-text-dark font uk-text-small f600"><?php echo $order->order_number; ?></span>
                        <?php if(!empty($order->order_invoice_number)) { ?>
                            <span class="hika_cpanel_title"><?php echo JText::_('INVOICE_NUMBER'); ?> : </span>
                            <span class="hika_cpanel_value"><?php echo $order->order_invoice_number; ?></span>
                        <?php } ?>
                        <?php if(!empty($order->extraData->bottomLeft)) { echo implode("\r\n", $order->extraData->bottomLeft); } ?>
                    </div>
                    <div class="hika_cpanel_order_status">
                        <span class="uk-display-block uk-text-muted font uk-text-tiny f500 uk-margin-small-bottom"><?php echo JText::_('ORDER_STATUS'); ?></span>
                        <?php if(!empty($order->extraData->topMiddle)) { echo implode("\r\n", $order->extraData->topMiddle); } ?>
                        <span class="uk-display-block uk-text-<?php echo $order->order_status; ?> font uk-text-small f600"><?php echo hikashop_orderStatus($order->order_status); ?></span>
                        <?php if(!empty($order->extraData->bottomMiddle)) { echo implode("\r\n", $order->extraData->bottomMiddle); } ?>
                    </div>
                </div>
            </div>
		</a>
	</div>

	<div class="uk-card-body uk-padding-small">
		<div class="uk-child-width-auto uk-grid-small uk-flex-center uk-flex-right@m" data-uk-grid>
<?php if(!empty($order->extraData->beforeProductsListing)) { echo implode("\r\n", $order->extraData->beforeProductsListing); } ?>
<?php
	$show_more = false;
	$max_products = (int)$this->config->get('max_products_cpanel', 4);
	if($max_products <= 0) $max_products = 4;
	if(count($order->products) > $max_products) {
		$order->products = array_slice($order->products, 0, $max_products);
		$show_more = true;
	}
	$group = $this->config->get('group_options',0);
	foreach($order->products as $product) {
		if($group && $product->order_product_option_parent_id)
			continue;
		$link = '#';
		if(!empty($product->product_id) && !empty($this->products[$product->product_id]) && !empty($this->products[$product->product_id]->product_published))
			$link = hikashop_contentLink('product&task=show&cid='.$product->product_id.'&name='.@$this->products[$product->product_id]->alias . $url_itemid, $this->products[$product->product_id]);

?>
			<div>
<?php
		if(!empty($this->cpanel_data->cpanel_order_image)) {
			$img = $this->imageHelper->getThumbnail(@$product->images[0]->file_path, array(62, 80), array('default' => true, 'forcesize' => true,  'scale' => 'outside'));
			if(!empty($img) && $img->success) {
?>
				<a class="uk-text-center uk-display-block" href="<?php echo $link; ?>"><img class="hika_cpanel_product_image" src="<?php echo $img->url; ?>" alt="" /></a>
<?php
			}
		}
?>
		<a class="uk-hidden uk-display-block font uk-text-tiny f500 uk-text-dark hoverPrimary uk-text-center uk-margin-small-bottom" href="<?php echo $link; ?>">
			<span class="hika_cpanel_product_name"><?php echo $product->order_product_name; ?></span>
<?php
		if($this->config->get('show_code')) {
?>
			<span class="hikashop_cpanel_product_code"><?php echo $product->order_product_code; ?></span>
<?php
		}
		if($group) {
			foreach($order->products as $j => $optionElement) {
				if($optionElement->order_product_option_parent_id != $product->order_product_id)
					continue;
				$product->order_product_price += $optionElement->order_product_price;
				$product->order_product_tax += $optionElement->order_product_tax;
				$product->order_product_total_price += $optionElement->order_product_total_price;
				$product->order_product_total_price_no_vat += $optionElement->order_product_total_price_no_vat;
			}
		}
?>
		</a>
				<p class="uk-margin-remove font f500 uk-text-tiny uk-text-center uk-hidden">
                    					<span class="hika_cpanel_product_price_quantity">
						<?php echo  $product->order_product_quantity; ?>
					</span>
					<span class="hika_cpanel_product_price_amount">
						<?php echo  $this->currencyClass->format( $product->order_product_price + $product->order_product_tax, $order->order_currency_id ); ?>
					</span>
				</p>
<?php
		if(!empty($product->extraData))
			echo '<p class="hikashop_order_product_extra">' . (is_string($product->extraData) ? $product->extraData : implode('<br/>', $product->extraData)) . '</p>';
?>
			</div>
<?php
	}
	if($show_more) {
?>
			<a href="<?php echo $order_link; ?>" class="hk-list-group-item hika_cpanel_product hika_cpanel_product_more"><span><?php
				echo JText::_('SHOW_MORE_PRODUCTS');
			?> <i class="fa fa-arrow-right"></i></span></a>
<?php
	}
?>
<?php if(!empty($order->extraData->afterProductsListing)) { echo implode("\r\n", $order->extraData->afterProductsListing); } ?>
		</div>
		<div class="uk-hidden">
<?php if(!empty($order->extraData->beforeInfo)) { echo implode("\r\n", $order->extraData->beforeInfo); } ?>
			<dl class="hika_cpanel_order_methods">
<?php if(!empty($order->payment)) { ?>
				<dt><?php echo JText::_('HIKASHOP_PAYMENT_METHOD'); ?></dt>
				<dd><?php echo $order->payment->payment_name; ?></dd>
<?php } ?>
<?php if(!empty($order->shippings)) { ?>
				<dt><?php echo JText::_('HIKASHOP_SHIPPING_METHOD'); ?></dt>
				<dd><?php
		$shippingClass = hikashop_get('class.shipping');
		$shippings_data = $shippingClass->getAllShippingNames($order);
		if(!empty($shippings_data)) {
			if(count($shippings_data) > 1) {
				echo '<ul><li>'.implode('</li><li>', $shippings_data).'</li></ul>';
			} else {
				echo reset($shippings_data);
			}
		}
?></dd>
<?php } ?>
			</dl>
<?php if(!empty($order->extraData->afterInfo)) { echo implode("\r\n", $order->extraData->afterInfo); } ?>
		</div>
	</div>
    <div class="uk-card-footer uk-padding-small">
        <?php if(!empty($order->extraData->topRight)) { echo implode("\r\n", $order->extraData->topRight); } ?>
        <?php
        $dropData = array(
            array(
                'name' => JText::_('HIKA_ORDER_DETAILS'),
                'link' => $order_link
            )
        );

        if(!empty($order->show_print_button)) {
            $print_invoice = true;
            $dropData[] = array(
                'name' => JText::_('PRINT_INVOICE'),
                'link' => '#print_invoice',
                'click' => 'return window.localPage.printInvoice('.(int)$order->order_id.');',
            );
        }
        if(!empty($order->show_contact_button)) {
            $url = hikashop_completeLink('order&task=contact&order_id='.$order->order_id.$url_itemid);
            $dropData[] = array(
                'name' => JText::_('CONTACT_US_ABOUT_YOUR_ORDER'),
                'link' => $url
            );
        }
        if(!empty($order->show_cancel_button)) {
            $cancel_orders = true;
            $dropData[] = array(
                'name' => JText::_('CANCEL_ORDER'),
                'link' => '#cancel_order',
                'click' => 'return window.localPage.cancelOrder('.(int)$order->order_id.',\''.$order->order_number.'\');',
            );
        }
        if(!empty($order->show_payment_button) && bccomp($order->order_full_price, 0, 5) > 0) {
            $url_param = ($this->payment_change) ? '&select_payment=1' : '';
            $url = hikashop_completeLink('order&task=pay&order_id='.$order->order_id.$url_param.$url_itemid);
            if($this->config->get('force_ssl',0) && strpos('https://',$url) === false)
                $url = str_replace('http://','https://', $url);
            $dropData[] = array(
                'name' => JText::_('PAY_NOW'),
                'link' => $url
            );
        }
        if($this->config->get('allow_reorder', 0)) {
            $url = hikashop_completeLink('order&task=reorder&order_id='.$order->order_id.$url_itemid);
            if($this->config->get('force_ssl',0) && strpos('https://',$url) === false)
                $url = str_replace('http://','https://', $url);
            $dropData[] = array(
                'name' => JText::_('REORDER'),
                'link' => $url
            );
        }

        if(!empty($order->actions)) {
            $dropData = array_merge($dropData, $order->actions);
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
                array('type' => 'btn', 'right' => true, 'up' => false)
            );
        }
        */
        ?>
        <?php if(!empty($order->extraData->bottomRight)) { echo implode("\r\n", $order->extraData->bottomRight); } ?>
    </div>
</div>
    </div>
<?php
}
echo '</div>';

if(!empty($this->cpanel_data->cpanel_orders) && ($print_invoice || $cancel_orders)) {
	echo $this->popupHelper->display(
		'',
		'INVOICE',
		hikashop_completeLink('order&task=invoice'.$url_itemid.'',true),
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
		$u = hikashop_completeLink('order&task=invoice'.$url_itemid,true);
		echo $u;
		echo (strpos($u, '?') === false) ? '?' : '&';
	?>order_id='+id);
	return false;
};
</script>
<form action="<?php echo hikashop_completeLink('order&task=cancel_order&email=1'); ?>" name="hikashop_cancel_order_form" id="hikashop_cancel_order_form" method="POST">
	<input type="hidden" name="Itemid" value="<?php global $Itemid; echo $Itemid; ?>"/>
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
