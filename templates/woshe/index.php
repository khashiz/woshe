<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.cassiopeia
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

$app  = JFactory::getApplication();
$user = JFactory::getUser();
$params = $app->getTemplate(true)->params;
$menu = $app->getMenu();
$active = $menu->getActive();

$pageparams = $menu->getParams( $active->id );
$pageclass = $pageparams->get( 'pageclass_sfx' );

// Browsers support SVG favicons
//$this->addHeadLink(HTMLHelper::_('image', 'favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
//$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
//$this->addHeadLink(HTMLHelper::_('image', 'favicon.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

// Add CSS
JHtml::_('stylesheet', 'uikit-rtl.min.css', array('version' => 'auto', 'relative' => true));
JHtml::_('stylesheet', 'fontawesome.min.css', array('version' => 'auto', 'relative' => true));
JHtml::_('stylesheet', 'woshe.css', array('version' => 'auto', 'relative' => true));

// Add js
JHtml::_('script', 'uikit.min.js', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'uikit-icons.min.js', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'custom.js', array('version' => 'auto', 'relative' => true));

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu     = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';
$netparsi = "<a href='https://netparsi.com' class='uk-text-gray hoverPrimary f700 netparsi' target='_blank' rel='nofollow'>".JTEXT::sprintf('NETPARSI')."</a>";

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />



    <?php if ($pageclass == "home") { ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&family=The+Nautigal:wght@700&display=swap" rel="stylesheet">
    <?php } ?>
</head>
<body class="<?php echo $option
	. ' ' . $wrapper
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($pageclass ? ' ' . $pageclass : '')
	. $hasClass
	. ($this->direction == 'rtl' ? ' rtl' : '');
?>">
    <header class="header container-header uk-text-zero" data-uk-sticky="top: 114; animation: uk-animation-slide-top; show-on-up: true;">
        <div>
            <div class="uk-container">
                <div>
                    <div class="uk-grid-small" data-uk-grid>
                        <div class="uk-width-auto uk-hidden@m uk-flex uk-flex-middle">
                            <a href="#hamMenu" data-uk-toggle class="uk-display-block uk-text-dark hoverDark hamMenuToggler"><img src="<?php echo JURI::base().'images/sprite.svg#menu'; ?>" width="24" height="24" alt="<?php echo $sitename; ?>" data-uk-svg></a>
                        </div>
                        <div class="uk-width-expand logo uk-visible@m">
                            <a href="<?php echo JUri::base(); ?>" class="uk-display-inline-block uk-padding-small uk-padding-remove-horizontal" title="<?php echo $sitename; ?>">
                                <img src="<?php echo JUri::base().'images/sprite.svg#logoFull'; ?>" width="110" height="84" alt="<?php echo $sitename; ?>" data-uk-svg>
                            </a>
                        </div>
                        <div class="uk-width-expand logo uk-hidden@m">
                            <a href="<?php echo JUri::base(); ?>" class="uk-display-inline-block uk-padding-small uk-padding-remove-horizontal" title="<?php echo $sitename; ?>">
                                <img src="<?php echo JUri::base().'images/sprite.svg#logoText'; ?>" width="110" height="29" alt="<?php echo $sitename; ?>" data-uk-svg>
                            </a>
                        </div>
                        <div class="uk-width-auto uk-flex uk-flex-middle uk-visible@m">
                            <div class="uk-margin-large-left">
                                <div class="uk-grid-medium" data-uk-grid>
                                    <jdoc:include type="modules" name="menu" style="none" />
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-1-3 uk-width-auto@m uk-flex uk-flex-middle uk-flex-left">
                            <div>
                                <div class="uk-grid-medium" data-uk-grid>
                                    <jdoc:include type="modules" name="header" style="none" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</header>
    <?php if ($pageparams->get('show_page_heading', 1)) { ?>
        <section class="uk-background-muted uk-padding uk-padding-remove-horizontal pageHead">
            <div>
                <div class="uk-container">
                    <h1 class="f600 font uk-h3 uk-text-center uk-text-primary"><?php echo $pageparams->get('page_heading'); ?></h1>
                </div>
            </div>
        </section>
    <?php } ?>
    <?php if ($this->countModules('topout', true)) : ?>
        <jdoc:include type="modules" name="topout" style="html5" />
    <?php endif; ?>
    <main class="uk-padding-large uk-padding-remove-horizontal" data-uk-height-viewport="expand: true">
        <div class="uk-container">
            <?php if ($this->countModules('topout', true)) : ?>
                <jdoc:include type="modules" name="topin" style="html5" />
            <?php endif; ?>
            <div>
                <div data-uk-grid>
                    <?php if ($this->countModules('sidestart', true)) : ?>
                        <aside class="uk-width-1-1 uk-width-1-4@m"><jdoc:include type="modules" name="sidestart" style="none" /></aside>
                    <?php endif; ?>
                    <article class="uk-width-1-1 uk-width-expand@m">
                        <jdoc:include type="message" />
                        <jdoc:include type="component" />
                    </article>
                    <?php if ($this->countModules('sideend', true)) : ?>
                        <aside class="uk-width-1-1 uk-width-1-4@m"><jdoc:include type="modules" name="sideend" style="none" /></aside>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <?php if ($this->countModules('bottomout', true)) : ?>
        <jdoc:include type="modules" name="bottomout" style="html5" />
    <?php endif; ?>
    <footer class="uk-text-zero">
        <div class="uk-background-light">
            <div class="uk-container">
                <div class="uk-padding uk-padding-remove-horizontal">
                    <div class="uk-child-width-1-1 uk-child-width-1-5@m" data-uk-grid>
                        <jdoc:include type="modules" name="footer" style="html5" />
                        <div>
                            <div class="uk-height-1-1 uk-flex uk-flex-center uk-flex-middle">
                                <a referrerpolicy="origin" target="_blank" href="https://trustseal.enamad.ir/?id=248582&amp;Code=uFgUCDp67Qy6aiNLUJnL"><img referrerpolicy="origin" src="https://Trustseal.eNamad.ir/logo.aspx?id=248582&amp;Code=uFgUCDp67Qy6aiNLUJnL" alt="" style="cursor:pointer" id="uFgUCDp67Qy6aiNLUJnL"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="uk-margin-remove">
                <div class="uk-padding-small uk-padding-remove-horizontal uk-text-center uk-text-right@m">
                    <div class="uk-grid-row-collapse uk-grid-column-medium" data-uk-grid>
                        <div class="uk-width-1-1 uk-width-expand@m">
                            <p class="uk-margin-remove uk-text-tiny uk-text-gray font"><?php echo JText::sprintf('COPYRIGHT', $sitename); ?></p>
                        </div>
                        <div class="uk-width-1-1 uk-width-auto@m">
                            <p class="uk-margin-remove uk-text-tiny uk-text-gray font"><?php echo JText::sprintf('DEVELOPER', $netparsi); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
	<jdoc:include type="modules" name="debug" style="none" />

    <div id="hamMenu" data-uk-offcanvas="overlay: true">
        <div class="uk-offcanvas-bar uk-card uk-card-default uk-padding-remove bgWhite">
            <div class="uk-flex uk-flex-column uk-height-1-1">
                <div class="uk-width-expand">
                    <div class="offcanvasTop uk-box-shadow-small uk-position-relative uk-flex-stretch">
                        <div class="uk-grid-collapse uk-height-1-1 uk-grid uk-grid-stack" data-uk-grid="">
                            <div class="uk-flex uk-width-1-3 uk-flex uk-flex-center uk-flex-middle"><a onclick="UIkit.offcanvas('#hamMenu').hide();" class="uk-flex uk-flex-center uk-flex-middle uk-height-1-1 uk-width-1-1 uk-margin-remove"><img src="<?php echo JURI::base().'images/sprite.svg#chevron-right'; ?>" width="24" height="24" data-uk-svg></a></div>
                            <div class="uk-flex uk-width-1-3 uk-flex uk-flex-center uk-flex-middle"><a href="<?php echo JRoute::_("index.php?Itemid=167"); ?>" class="uk-flex uk-flex-center uk-flex-middle uk-height-1-1 uk-width-1-1 uk-margin-remove"><img src="<?php echo JURI::base().'images/sprite.svg#shopping-cart'; ?>" width="24" height="24" data-uk-svg></a></div>
                            <div class="uk-flex uk-width-1-3 uk-flex uk-flex-center uk-flex-middle"><a href="" class="uk-flex uk-flex-center uk-flex-middle uk-height-1-1 uk-width-1-1 uk-margin-remove"><img src="<?php echo JURI::base().'images/sprite.svg#phone'; ?>" width="24" height="24" data-uk-svg></a></div>
                        </div>
                    </div>
                    <div class="uk-padding-small"><jdoc:include type="modules" name="offcanvas" style="xhtml" /></div>
                </div>
                <div class="uk-text-center uk-padding">
                    <a href="<?php echo JURI::base(); ?>" title="<?php echo $sitename; ?>" class="uk-display-inline-block logo" target="_self"><img src="<?php echo JURI::base().'images/sprite.svg#logo'.$languageCode; ?>" width="150" alt="<?php echo $sitename; ?>" data-uk-svg></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
