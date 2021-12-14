<?php/*   Afzoneha.com  Joomla Extensions Developer * @package        Joomla! 2.5x  AND 3.x * @author        Afzoneha.com iran http://www.Afzoneha.com * @copyright    Copyright (c) 2013 Afzoneha.com iran Ltd. All rights reserved. * @license      GNU/GPL license: http://www.gnu.org/copyleft/gpl.html * @link        http://Afzoneha.com * @email      Afzoneha.com@gmail.com*/
defined('_JEXEC') or die('Restricted access');
?>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][url]"><?php
			echo JText::_( 'URL' );
		?></label>
	</td>
	<td>
		<input type="text" name="data[payment][payment_params][url]" value="<?php echo $this->escape(@$this->element->payment_params->url); ?>" />
	</td>
</tr>
<tr>
    <td class="key">
        <label for="data[payment][payment_params][merchant]"><?php
            echo JText::_( 'Merchant' );
        ?></label>
    </td>
    <td>
        <input type="text" name="data[payment][payment_params][merchant]" value="<?php echo $this->escape(@$this->element->payment_params->merchant); ?>" />
    </td>
</tr>

<tr>
    <td class="key">
        <label for="data[payment][payment_params][currency]"><?php
            echo JText::_( 'واحد پول سایت' );
        ?></label>
    </td>
    <td>

        <select name="data[payment][payment_params][currency]">
                <option value="1" <?php if($this->escape(@$this->element->payment_params->currency)) echo 'selected' ?> >ریال</option>
                <option value="0" <?php if(!$this->escape(@$this->element->payment_params->currency)) echo 'selected' ?> >تومان</option>
        </select>
    </td>
</tr>

<tr>
    <td class="key">
        <label for="data[payment][payment_params][server_type]"><?php
            echo JText::_( 'نوع سرور' );
        ?></label>
    </td>
    <td>

        <select name="data[payment][payment_params][server_type]">
                <option value="ir" <?php if($this->escape(@$this->element->payment_params->server_type)=='ir') echo 'selected' ?> >ایران</option>
                <option value="de" <?php if(!$this->escape(@$this->element->payment_params->server_type)=='de') echo 'selected' ?> >خارجی</option>
        </select>
    </td>
</tr>

<tr>
	<td class="key">
		<label for="data[payment][payment_params][zarin_type]"><?php
			echo JText::_( 'نوع پرداخت' );
		?></label>
	</td>
	<td>

	    <select name="data[payment][payment_params][zarin_type]">
                <option value="1" <?php if($this->escape(@$this->element->payment_params->zarin_type)) echo 'selected' ?> >اصلی</option>
                <option value="0" <?php if(!$this->escape(@$this->element->payment_params->zarin_type)) echo 'selected' ?> >تست</option>
        </select>
    </td>
</tr>


<tr>
	<td class="key">
		<label for="data[payment][payment_params][invalid_status]"><?php
			echo JText::_('INVALID_STATUS');
		?></label>
	</td>
	<td><?php
		echo $this->data['order_statuses']->display("data[payment][payment_params][invalid_status]", @$this->element->payment_params->invalid_status);
	?></td>
</tr>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][pending_status]"><?php
			echo JText::_('PENDING_STATUS');
		?></label>
	</td>
	<td><?php
		echo $this->data['order_statuses']->display("data[payment][payment_params][pending_status]", @$this->element->payment_params->pending_status);
	?></td>
</tr>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][verified_status]"><?php
			echo JText::_('VERIFIED_STATUS');
		?></label>
	</td>
	<td><?php
		echo $this->data['order_statuses']->display("data[payment][payment_params][verified_status]", @$this->element->payment_params->verified_status);
	?></td>
</tr>
