<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

$usersConfig = ComponentHelper::getParams('com_users');

?>
<div class="uk-width-1-1 uk-width-1-3@m uk-margin-auto">
    <div class="com-users-login login">
        <?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
        <div class="com-users-login__description login-description">
            <?php endif; ?>

            <?php if ($this->params->get('logindescription_show') == 1) : ?>
                <?php echo $this->params->get('login_description'); ?>
            <?php endif; ?>

            <?php if ($this->params->get('login_image') != '') : ?>
                <?php $alt = empty($this->params->get('login_image_alt')) && empty($this->params->get('login_image_alt_empty'))
                    ? ''
                    : 'alt="' . htmlspecialchars($this->params->get('login_image_alt'), ENT_COMPAT, 'UTF-8') . '"'; ?>
                <img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="com-users-login__image login-image" <?php echo $alt; ?>>
            <?php endif; ?>

            <?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
        </div>
    <?php endif; ?>

        <form action="<?php echo Route::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="noFieldset com-users-login__form form-validate form-horizontal well" id="com-users-login__form">

            <fieldset class="formContainer uk-form-stacked">
                <div class="uk-child-width-1-1 uk-grid-small" data-uk-grid>
                <?php echo $this->form->renderFieldset('credentials', ['class' => 'com-users-login__input']); ?>

                <?php if ($this->tfa) : ?>
                    <?php echo $this->form->renderField('secretkey', null, null, ['class' => 'com-users-login__secretkey']); ?>
                <?php endif; ?>

                <?php if (PluginHelper::isEnabled('system', 'remember')) : ?>
                    <div class="com-users-login__remember uk-hidden">
                        <div class="form-check">
                            <input class="form-check-input" id="remember" type="checkbox" name="remember" value="yes" checked="checked">
                            <label class="form-check-label" for="remember">
                                <?php echo Text::_('COM_USERS_LOGIN_REMEMBER_ME'); ?>
                            </label>
                        </div>
                    </div>
                <?php endif; ?>

                <?php foreach ($this->extraButtons as $button):
                    $dataAttributeKeys = array_filter(array_keys($button), function ($key) {
                        return substr($key, 0, 5) == 'data-';
                    });
                    ?>
                    <div class="com-users-login__submit control-group">
                        <div class="controls">
                            <button type="button"
                                    class="btn btn-secondary w-100 <?php echo $button['class'] ?? '' ?>"
                            <?php foreach ($dataAttributeKeys as $key): ?>
                                <?php echo $key ?>="<?php echo $button[$key] ?>"
                            <?php endforeach; ?>
                            <?php if ($button['onclick']): ?>
                                onclick="<?php echo $button['onclick'] ?>"
                            <?php endif; ?>
                            title="<?php echo Text::_($button['label']) ?>"
                            id="<?php echo $button['id'] ?>"
                            >
                            <?php if (!empty($button['icon'])): ?>
                                <span class="<?php echo $button['icon'] ?>"></span>
                            <?php elseif (!empty($button['image'])): ?>
                                <?php echo HTMLHelper::_('image', $button['image'], Text::_($button['tooltip'] ?? ''), [
                                    'class' => 'icon',
                                ], true) ?>
                            <?php elseif (!empty($button['svg'])): ?>
                                <?php echo $button['svg']; ?>
                            <?php endif; ?>
                            <?php echo Text::_($button['label']) ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>

                    <div class="com-users-login__submit control-group">
                        <button type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1 hikabtn_checkout_login_register"><?php echo Text::_('JLOGIN_TO_ACCOUNT'); ?></button>
                    </div>
                </div>

                <?php $return = $this->form->getValue('return', '', $this->params->get('login_redirect_url', $this->params->get('login_redirect_menuitem'))); ?>
                <input type="hidden" name="return" value="<?php echo base64_encode($return); ?>">
                <?php echo HTMLHelper::_('form.token'); ?>
            </fieldset>
        </form>
    </div>
    <hr class="uk-divider-icon uk-margin-medium-top uk-margin-medium-bottom">
    <div class="uk-text-zero">
        <div class="com-users-login__options list-group uk-flex-center uk-grid-divider uk-child-width-1-2 uk-grid-small" data-uk-grid>
            <div class="uk-text-left">
                <a class="uk-text-small uk-text-gray font hoverDark com-users-login__reset list-group-item" href="<?php echo Route::_('index.php?option=com_users&view=reset'); ?>"><?php echo Text::_('COM_USERS_LOGIN_RESET'); ?></a>
            </div>
            <?php /* ?>
            <a class="com-users-login__remind list-group-item" href="<?php echo Route::_('index.php?option=com_users&view=remind'); ?>">
                <?php echo Text::_('COM_USERS_LOGIN_REMIND'); ?>
            </a>
            <?php */ ?>
            <?php if ($usersConfig->get('allowUserRegistration')) : ?>
            <div class="uk-text-right">
                <a class="uk-text-small uk-text-gray font hoverDark com-users-login__register list-group-item" href="<?php echo Route::_('index.php?Itemid=130'); // echo Route::_('index.php?option=com_users&view=registration'); ?>"><?php echo Text::_('COM_USERS_LOGIN_REGISTER'); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
