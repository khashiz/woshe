<?php
/**
 * @package    logregsms
 * @subpackage C:
 * @author     Mohammad Hosein Mir {@link https://joomina.ir}
 * @author     Created on 22-Feb-2019
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

$session = JFactory::getSession(); 
$referer = $session->get('smsregReferer', ''); //die(var_dump($referer));
if($referer){
    $referer = $referer;
}
else{
   $referer = $_SERVER['HTTP_REFERER'];
}
?>
<div id="logregsms" class="validation-mobile">
	<div class="uk-width-1-1 uk-width-1-3@m uk-margin-auto">
		<form class="noFieldset" action="<?php echo JRoute::_('index.php?option=com_logregsms&task=validation_mobile.step1'); ?>" method="post" name="step1form" id="step1form" onSubmit="return ValidationMobileForm()">
            <fieldset class="formContainer uk-form-stacked">
                <div class="uk-child-width-1-1 uk-grid-small" data-uk-grid>
                    <div class="uk-text-center">
                        <span class="uk-text-accent" data-uk-icon="icon: phone; ratio: 3;"></span>
                    </div>
                    <div>
                        <label class="uk-form-label uk-text-center uk-margin-small-bottom" for="mobilenum"><?php echo JText::_('AUTH_ENTER_YOUR_MONILE'); ?></label>
                        <div>
                            <input type="tel" name="mobilenum" autocomplete="off" class="uk-input uk-text-center uk-form-large ltr f600 pureNumber" id="mobilenum" onKeyPress="numberValidate(event)" autofocus maxlength="11">
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1 formSubmit"><span><?php echo JText::_('AUTH_CHECK_NUMBER'); ?></span></button>
                    </div>
                </div>
            </fieldset>
            <input type="hidden" name="referer" value="<?php echo $referer; ?>">
		</form>
	</div>
</div>