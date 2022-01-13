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
	$form = ',0';
	if(!$this->config->get('ajax_add_to_cart', 1)) {
		$form = ',\'hikashop_product_form\'';
	}
?>

<div class="uk-grid-column-large uk-grid-row-medium" data-uk-grid>
	<div id="hikashop_product_left_part" class="uk-width-1-1 uk-width-2-5@m hikashop_product_left_part">
<!-- LEFT BEGIN EXTRA DATA -->
<?php if(!empty($this->element->extraData->leftBegin)) { echo implode("\r\n",$this->element->extraData->leftBegin); } ?>
<!-- EO LEFT BEGIN EXTRA DATA -->
<!-- IMAGE -->
<?php
	$this->row =& $this->element;
	$this->setLayout('show_block_img');
	echo $this->loadTemplate();
?>
<!-- EO IMAGE -->
<!-- LEFT END EXTRA DATA -->
<?php if(!empty($this->element->extraData->leftEnd)) { echo implode("\r\n",$this->element->extraData->leftEnd); } ?>
<!-- EO LEFT END EXTRA DATA -->
	</div>

	<div id="hikashop_product_right_part" class="uk-width-1-1 uk-width-expand@m hikashop_product_right_part">

        <div id="hikashop_product_top_part" class="hikashop_product_top_part">
            <!-- TOP BEGIN EXTRA DATA -->
            <?php if(!empty($this->element->extraData->topBegin)) { echo implode("\r\n",$this->element->extraData->topBegin); } ?>
            <!-- EO TOP BEGIN EXTRA DATA -->
            <h1 class="font f500 uk-h2 uk-text-secondary">
                <!-- NAME -->
                <span id="hikashop_product_name_main" class="hikashop_product_name_main" itemprop="name"><?php
                    if(hikashop_getCID('product_id') != $this->element->product_id && isset($this->element->main->product_name))
                        echo $this->element->main->product_name;
                    else
                        echo $this->element->product_name;
                    ?></span>
                <!-- EO NAME -->
                <!-- CODE -->
                <?php if ($this->config->get('show_code')) { ?>
                    <span id="hikashop_product_code_main" class="hikashop_product_code_main"><?php
                        echo $this->element->product_code;
                        ?></span>
                <?php } ?>
                <!-- EO CODE -->
                <meta itemprop="sku" content="<?php echo $this->element->product_code; ?>">
                <meta itemprop="productID" content="<?php echo $this->element->product_code; ?>">
            </h1>
            <!-- TOP END EXTRA DATA -->
            <?php if(!empty($this->element->extraData->topEnd)) { echo implode("\r\n", $this->element->extraData->topEnd); } ?>
            <!-- EO TOP END EXTRA DATA -->
            <!-- SOCIAL NETWORKS -->
            <?php
            $this->setLayout('show_block_social');
            echo $this->loadTemplate();
            ?>
            <!-- EO SOCIAL NETWORKS -->
        </div>
<!-- RIGHT BEGIN EXTRA DATA -->
<?php if(!empty($this->element->extraData->rightBegin)) { echo implode("\r\n",$this->element->extraData->rightBegin); } ?>
<!-- EO RIGHT BEGIN EXTRA DATA -->
<!-- VOTE -->
		<div id="hikashop_product_vote_mini" class="hikashop_product_vote_mini"><?php
	if($this->params->get('show_vote_product')) {
		$js = '';
		$this->params->set('vote_type', 'product');
		$this->params->set('vote_ref_id', isset($this->element->main) ? (int)$this->element->main->product_id : (int)$this->element->product_id );
		echo hikashop_getLayout('vote', 'mini', $this->params, $js);
	}
		?></div>
<!-- EO VOTE -->

        <!-- DESCRIPTION -->
        <div id="hikashop_product_description_main" class="font uk-text-justify hikashop_product_description_main" itemprop="description"><?php
            echo JHTML::_('content.prepare',preg_replace('#<hr *id="system-readmore" */>#i','',$this->element->product_description));
            ?></div>
        <!-- EO DESCRIPTION -->


<!-- PRICE -->
<?php
	$itemprop_offer = '';
	if (!empty($this->element->prices))
		$itemprop_offer = 'itemprop="offers" itemscope itemtype="https://schema.org/Offer"';
?>
        <span class="uk-display-block uk-text-tiny uk-text-accent font f500 uk-text-center uk-text-right@m"><?php echo JText::_('PRICE'); ?></span>
		<span id="hikashop_product_price_main" class="font f500 uk-display-block uk-text-center uk-text-right@m uk-text-secondary uk-margin-bottom uk-text-large hikashop_product_price_main" <?php echo $itemprop_offer; ?>>
<?php
	$main =& $this->element;
	if(!empty($this->element->main))
		$main =& $this->element->main;
	if(!empty($main->product_condition) && !empty($this->element->prices)) {
?>
			<meta itemprop="itemCondition" itemtype="https://schema.org/OfferItemCondition" content="https://schema.org/<?php echo $main->product_condition; ?>" />
<?php
	}
	if($this->params->get('show_price') && (empty($this->displayVariants['prices']) || $this->params->get('characteristic_display') != 'list')) {
		$this->row =& $this->element;
		$this->setLayout('listing_price');
		echo $this->loadTemplate();
		if (!empty($this->element->prices)) {
?>
			<meta itemprop="price" content="<?php echo $this->itemprop_price; ?>" />
			<meta itemprop="availability" content="https://schema.org/<?php echo ($this->row->product_quantity != 0) ? 'InStock' : 'OutOfstock' ;?>" />
			<meta itemprop="priceCurrency" content="<?php echo $this->currency->currency_code; ?>" />                                               
<?php	}
	}
?>		</span>
<!-- EO PRICE -->
<!-- RIGHT MIDDLE EXTRA DATA -->
<?php if(!empty($this->element->extraData->rightMiddle)) { echo implode("\r\n",$this->element->extraData->rightMiddle); } ?>
<!-- EO RIGHT MIDDLE EXTRA DATA -->
<!-- DIMENSIONS -->
<?php
	$this->setLayout('show_block_dimensions');
	echo $this->loadTemplate();
?>
<!-- EO DIMENSIONS -->

        <!-- CHARACTERISTICS -->
<?php
	if($this->params->get('characteristic_display') != 'list') {
		$this->setLayout('show_block_characteristic');
		echo $this->loadTemplate();
?>

    <?php } ?>
<!-- EO CHARACTERISTICS -->
<!-- OPTIONS -->
<?php
	if(hikashop_level(1) && !empty ($this->element->options)) {
?>
		<div id="hikashop_product_options" class="hikashop_product_options uk-margin-medium-bottom uk-form-stacked"><?php
			$this->setLayout('option');
			echo $this->loadTemplate();
		?></div>

        <?php
		$form = ',\'hikashop_product_form\'';
		if($this->config->get('redirect_url_after_add_cart', 'stay_if_cart') == 'ask_user') {
?>
		<input type="hidden" name="popup" value="1"/>
<?php
		}
	}
?>
<!-- EO OPTIONS -->
<!-- CUSTOM ITEM FIELDS -->
<?php
	if(!$this->params->get('catalogue') && ($this->config->get('display_add_to_cart_for_free_products') || ($this->config->get('display_add_to_wishlist_for_free_products', 1) && hikashop_level(1) && $this->params->get('add_to_wishlist') && $this->config->get('enable_wishlist', 1)) || !empty($this->element->prices))) {
		if(!empty($this->itemFields)) {
			$form = ',\'hikashop_product_form\'';
			if ($this->config->get('redirect_url_after_add_cart', 'stay_if_cart') == 'ask_user') {
?>
		<input type="hidden" name="popup" value="1"/>
<?php
			}
			$this->setLayout('show_block_custom_item');
			echo $this->loadTemplate();
		}
	}
?>
<!-- EO CUSTOM ITEM FIELDS -->
<!-- PRICE WITH OPTIONS -->
<?php
	if($this->params->get('show_price')) {
?>
		<span id="hikashop_product_price_with_options_main" class="font uk-text-secondary f500 uk-text-large uk-margin-bottom uk-display-block uk-text-center uk-text-right@m">
		</span>
<?php
	}
?>
<!-- EO PRICE WITH OPTIONS -->
<!-- ADD TO CART BUTTON -->
<?php
	if(empty($this->element->characteristics) || $this->params->get('characteristic_display') != 'list') {
?>
		<div id="hikashop_product_quantity_main" class="hikashop_product_quantity_main uk-grid-small" data-uk-grid>
            <?php
			$this->row =& $this->element;
			$this->formName = $form;
			$this->ajax = 'if(hikashopCheckChangeForm(\'item\',\'hikashop_product_form\')){ return hikashopModifyQuantity(\'' . (int)$this->element->product_id . '\',field,1' . $form . ',\'cart\'); } else { return false; }';
			$this->setLayout('quantity');
			echo $this->loadTemplate();
			?>
        </div>
		<div id="hikashop_product_quantity_alt" class="uk-hidden hikashop_product_quantity_main_alt">
			<?php echo JText::_('ADD_TO_CART_AVAILABLE_AFTER_CHARACTERISTIC_SELECTION'); ?>
		</div>
<?php
	}
?>
<!-- EO ADD TO CART BUTTON -->
<!-- CONTACT US BUTTON -->
		<div id="hikashop_product_contact_main" class="hikashop_product_contact_main"><?php
	$contact = (int)$this->config->get('product_contact', 0);
	if(hikashop_level(1) && ($contact == 2 || ($contact == 1 && !empty($this->element->product_contact)))) {
		$css_button = $this->config->get('css_button', 'hikabtn');
?>
			<a rel="noindex, nofollow" href="<?php echo hikashop_completeLink('product&task=contact&cid=' . (int)$this->element->product_id . $this->url_itemid); ?>" class="<?php echo $css_button; ?>"><?php
				echo JText::_('CONTACT_US_FOR_INFO');
			?></a>
<?php
	}
?>
		</div>
<!-- EO CONTACT US BUTTON -->
<!-- CUSTOM PRODUCT FIELDS -->
<?php
	if(!empty($this->fields)) {
		$this->setLayout('show_block_custom_main');
		echo $this->loadTemplate();
	}
?>
<!-- EO CUSTOM PRODUCT FIELDS -->
<!-- TAGS -->
<?php
	if(HIKASHOP_J30) {
		$this->setLayout('show_block_tags');
		echo $this->loadTemplate();
	}
?>
<!-- EO TAGS -->
<!-- RIGHT END EXTRA DATA -->
<?php if(!empty($this->element->extraData->rightEnd)) { echo implode("\r\n",$this->element->extraData->rightEnd); } ?>
<!-- EO RIGHT END EXTRA DATA -->
<span id="hikashop_product_id_main" class="hikashop_product_id_main">
	<input type="hidden" name="product_id" value="<?php echo (int)$this->element->product_id; ?>" />
</span>
</div>
</div>
<!-- END GRID -->
<div id="hikashop_product_bottom_part" class="hikashop_product_bottom_part">
<!-- BOTTOM BEGIN EXTRA DATA -->
<?php if(!empty($this->element->extraData->bottomBegin)) { echo implode("\r\n",$this->element->extraData->bottomBegin); } ?>
<!-- EO BOTTOM BEGIN EXTRA DATA -->

<!-- MANUFACTURER URL -->
	<span id="hikashop_product_url_main" class="hikashop_product_url_main"><?php
		if(!empty($this->element->product_url)) {
			echo JText::sprintf('MANUFACTURER_URL', '<a href="' . $this->element->product_url . '" target="_blank">' . $this->element->product_url . '</a>');
		}
	?></span>
<!-- EO MANUFACTURER URL -->
<!-- FILES -->
<?php
	$this->setLayout('show_block_product_files');
	echo $this->loadTemplate();
?>
<!-- EO FILES -->
<!-- BOTTOM MIDDLE EXTRA DATA -->
<?php if(!empty($this->element->extraData->bottomMiddle)) { echo implode("\r\n",$this->element->extraData->bottomMiddle); } ?>
<!-- EO BOTTOM MIDDLE EXTRA DATA -->
<!-- BOTTOM END EXTRA DATA -->
<?php if(!empty($this->element->extraData->bottomEnd)) { echo implode("\r\n",$this->element->extraData->bottomEnd); } ?>
<!-- EO BOTTOM END EXTRA DATA -->
</div>
