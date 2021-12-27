<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

$user = JFactory::getUser();

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->registerAndUseScript('mod_menu', 'mod_menu/menu.min.js', [], ['type' => 'module']);
$wa->registerAndUseScript('mod_menu', 'mod_menu/menu-es5.min.js', [], ['nomodule' => true, 'defer' => true]);

$id = '';

if ($tagId = $params->get('tag_id', ''))
{
	$id = ' id="' . $tagId . '"';
}

if ($user->id) {
    $db = JFactory::getDbo();

    $userCMSidQuery = $db->getQuery(true);
    $userCMSidQuery
        ->select($db->quoteName(array('user_id','user_cms_id')))
        ->from($db->quoteName('#__hikashop_user'))
        ->where($db->quoteName('user_cms_id') . ' = ' . $user->id);
    $userCMSid = $db->setQuery($userCMSidQuery)->loadColumn();
    $userHIKAid = $userCMSid[0];

    $ordersCountQuery = $db->getQuery(true);
    $ordersCountQuery
        ->select($db->quoteName(array('order_user_id')))
        ->from($db->quoteName('#__hikashop_order'))
        ->where($db->quoteName('order_user_id') . ' = ' . $userHIKAid);
    $ordersCount = $db->setQuery($ordersCountQuery)->loadObjectList();
}
?>
<div class="uk-width-auto">
    <?php if ($user->id) { ?>
        <a href="#userMenu" data-uk-toggle class="uk-display-block uk-position-relative uk-text-dark hoverDark">
            <img src="<?php echo JUri::base().'images/sprite.svg#user'; ?>" id="user" name="user" width="20" height="20" data-uk-svg>
            <span class="uk-position-absolute indicator"></span>
        </a>
        <div id="userMenu" data-uk-offcanvas="overlay: true; container: body;">

            <div class="uk-text-zero uk-padding-remove uk-offcanvas-bar uk-flex uk-flex-column uk-flex-between">
                <div>
                    <div class="uk-text-center uk-margin-top uk-margin-bottom">
                        <div class="uk-margin-small-bottom"><img src="<?php echo JUri::base().'images/sprite.svg#user'; ?>" class="uk-text-accent" width="48" height="48" data-uk-svg></div>
                        <h3 class="uk-text-dark font f500 uk-h4 uk-margin-remove"><?php echo $user->name; ?></h3>
                    </div>
                    <div class="uk-background-muted uk-padding-small cartTotalWrapper">
                        <div class="uk-grid-small uk-text-zero uk-text-center" data-uk-grid>
                            <div class="uk-width-1-1 uk-text-tiny uk-text-<?php echo count($ordersCount) == 0 ? 'danger' : 'dark'; ?> font f500">
                                <?php echo count($ordersCount) > 0 ? JText::sprintf('YOU_HAVE_X_ORDERS', count($ordersCount)) : JText::_('YOU_HAVE_NO_ORDERS'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <ul<?php echo $id; ?> class="uk-nav uk-nav-primary uk-nav-center uk-margin-auto-vertical <?php echo $class_sfx; ?>">
                        <?php foreach ($list as $i => &$item)
                        {
                            $itemParams = $item->getParams();
                            $class      = 'nav-item item-' . $item->id;

                            if ($item->id == $default_id)
                            {
                                $class .= ' default';
                            }

                            if ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id))
                            {
                                $class .= ' current';
                            }

                            if (in_array($item->id, $path))
                            {
                                $class .= ' uk-active';
                            }
                            elseif ($item->type === 'alias')
                            {
                                $aliasToId = $itemParams->get('aliasoptions');

                                if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
                                {
                                    $class .= ' uk-active';
                                }
                                elseif (in_array($aliasToId, $path))
                                {
                                    $class .= ' alias-parent-active';
                                }
                            }

                            if ($item->type === 'separator')
                            {
                                $class .= ' divider';
                            }

                            if ($item->deeper)
                            {
                                $class .= ' deeper';
                            }

                            if ($item->parent)
                            {
                                $class .= ' parent';
                            }

                            echo '<li class="' . $class . '">';

                            switch ($item->type) :
                                case 'separator':
                                case 'component':
                                case 'heading':
                                case 'url':
                                    require ModuleHelper::getLayoutPath('mod_menu', 'user_' . $item->type);
                                    break;

                                default:
                                    require ModuleHelper::getLayoutPath('mod_menu', 'user_url');
                                    break;
                            endswitch;

                            // The next item is deeper.
                            if ($item->deeper)
                            {
                                echo '<ul class="mod-menu__sub list-unstyled small">';
                            }
                            // The next item is shallower.
                            elseif ($item->shallower)
                            {
                                echo '</li>';
                                echo str_repeat('</ul></li>', $item->level_diff);
                            }
                            // The next item is on the same level.
                            else
                            {
                                echo '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div>
                    <div class="uk-padding"><?php echo JHtml::_('content.prepare', '{loadposition ocbottom}'); ?></div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <a href="<?php echo JRoute::_('index.php?option=com_users&view=login'); ?>" class="uk-display-block uk-position-relative uk-text-muted hoverDark hoverDark" data-uk-tooltip="title:<?php echo JText::_('JLOGIN'); ?>; pos: right; cls: uk-active font; offset: 10;">
            <img src="<?php echo JUri::base().'images/sprite.svg#user'; ?>" id="user" name="user" width="20" height="20" data-uk-svg>
        </a>
    <?php } ?>
</div>