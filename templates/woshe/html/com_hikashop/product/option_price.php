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
		if(!headers_sent()){
			header('Content-Type: text/css; charset=utf-8', true, 200);
		}
		$price = hikaInput::get()->getVar( 'price', 0 );
		$currency = hikashop_get('class.currency');
		echo '<span class="uk-display-block uk-text-tiny uk-text-accent font f500">'.JText::_('PRICE_WITH_OPTIONS').'</span><span class="uk-display-block">'.$currency->format($price, hikashop_getCurrency()).'</span>';
		exit;
