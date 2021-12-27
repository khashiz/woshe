<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

$modId = 'mod-custom' . $module->id;

if ($params->get('backgroundimage'))
{
	/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
	$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
	$wa->addInlineStyle('
#' . $modId . '{background-image: url("' . Uri::root(true) . '/' . HTMLHelper::_('cleanImageURL', $params->get('backgroundimage'))->url . '");}
', ['name' => $modId]);
}
?>
<div data-uk-slider>
    <div class="uk-position-relative">
        <div class="uk-slider-container">
            <ul class="uk-slider-items uk-child-width-1-1 uk-child-width-1-<?php echo count((array)$params->get('boxes')); ?>@m uk-grid">
                <?php foreach ($params->get('boxes') as $item) : ?>
                    <?php if ($item->background != '') { ?>
                        <li>
                            <a href="<?php echo $item->link; ?>" title="" class="uk-display-block uk-position-relative uk-transition-toggle">
                                <div class="uk-inline-clip" tabindex="0">
                                    <img class="uk-transition-scale-up uk-transition-opaque" src="<?php echo (HTMLHelper::cleanImageURL($item->background))->url; ?>" alt="<?php echo $item->title; ?>">
                                </div>
                                <?php if (!empty($item->title) || !empty($item->subtitle)) { ?>
                                    <div class="uk-position-center-right uk-padding">
                                        <?php if (!empty($item->title)) { ?>
                                            <h3 class="uk-h5 uk-margin-remove font f500 uk-text-dark" data-uk-slideshow-parallax="opacity: 0,1,0; x: 100,-100;"><?php echo nl2br($item->title); ?></h3>
                                        <?php } ?>
                                        <?php if (!empty($item->subtitle)) { ?>
                                            <p class="uk-margin-remove-bottom uk-margin-small-top uk-text-gray font f400 uk-text-tiny" data-uk-slideshow-parallax="opacity: 0,1,0; x: 200,-200;"><?php echo nl2br($item->subtitle); ?></p>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </a>
                        </li>
                    <?php } ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
</div>