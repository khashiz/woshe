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

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon-pinned.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

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
	<header class="uk-background-muted uk-padding header container-header full-width<?php echo $stickyHeader ? ' ' . $stickyHeader : ''; ?>">
        <h1><?php echo $sitename; ?></h1>

		<?php if ($this->params->get('brand', 1)) : ?>
			<div class="grid-child">
				<div class="navbar-brand">
					<a class="brand-logo" href="<?php echo $this->baseurl; ?>/">
						<?php echo $logo; ?>
					</a>
					<?php if ($this->params->get('siteDescription')) : ?>
						<div class="site-description"><?php echo htmlspecialchars($this->params->get('siteDescription')); ?></div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($this->countModules('menu', true) || $this->countModules('search', true)) : ?>
			<div class="grid-child container-nav">
				<?php if ($this->countModules('menu', true)) : ?>
					<jdoc:include type="modules" name="menu" style="none" />
				<?php endif; ?>
				<?php if ($this->countModules('search', true)) : ?>
					<div class="container-search">
						<jdoc:include type="modules" name="search" style="none" />
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</header>
    <main class="uk-padding-large uk-padding-remove-horizontal">
        <section class="pageHead"></section>
        <div class="uk-container">
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
    <footer class="uk-text-zero">
        <div class="uk-background-light">
            <div class="uk-container">
                <div class="uk-padding uk-padding-remove-horizontal">
                    <div class="uk-child-width-1-1 uk-child-width-1-5@m" data-uk-grid><jdoc:include type="modules" name="footer" style="html5" /></div>
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
</body>
</html>
