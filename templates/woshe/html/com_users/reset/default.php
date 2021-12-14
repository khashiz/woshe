<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

?>
<div class="uk-width-1-1 uk-width-1-3@m uk-margin-auto">
    <div class="com-users-login login">
        <div class="com-users-reset reset">
            <form id="user-registration" action="<?php echo Route::_('index.php?option=com_users&task=reset.request'); ?>" method="post" class="noFieldset com-users-reset__form form-validate form-horizontal well">
                <?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
                    <fieldset class="formContainer uk-form-stacked">
                        <div class="uk-child-width-1-1 uk-grid-small" data-uk-grid>
                            <?php echo $this->form->renderFieldset($fieldset->name); ?>
                            <div class="com-users-reset__submit control-group">
                                <div class="controls">
                                    <button type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1 validate"><?php echo Text::_('JSUBMIT'); ?></button>
                                </div>
                            </div>
                            <?php if (isset($fieldset->label)) : ?>
                                <div>
                                    <p class="uk-text-tiny uk-text-muted font uk-margin-remove uk-text-center"><?php echo Text::_($fieldset->label); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </fieldset>
                <?php endforeach; ?>
                <?php echo HTMLHelper::_('form.token'); ?>
            </form>
        </div>
    </div>
</div>