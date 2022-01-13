<?php
/**
* @package RSForm! Pro
* @copyright (C) 2007-2019 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

$app  = JFactory::getApplication();
$user = JFactory::getUser();
// Getting params from template
$params = $app->getTemplate(true)->params;
$menu = $app->getMenu();
$active = $menu->getActive();
$pageparams = $menu->getParams( $active->id );
?>
    <div class="uk-grid-divider uk-grid-column-large uk-grid-row-small" data-uk-grid>
        <div class="uk-width-1-1 uk-width-expand@m"><?php echo RSFormProHelper::displayForm($this->formId); ?></div>
        <div class="uk-width-1-1 uk-width-1-3@m">
            <div>
                <div class="uk-child-width-1-1 uk-grid-divider" data-uk-grid>
                    <div>
                        <h2 class="uk-text-accent font uk-h5 f500"><?php echo JText::sprintf('CONTACTINFO'); ?></h2>
                        <div>
                            <div>
                                <div>
                                    <div class="uk-child-width-1-1 uk-grid-medium" data-uk-grid>
                                        <?php if (!empty($params->get('address')) || !empty($params->get('phone')) || !empty($params->get('fax')) || !empty($params->get('cellphones')) || !empty($params->get('email'))) { ?>
                                            <div>
                                                <div>
                                                    <div class="uk-grid-small uk-text-zer" data-uk-grid>
                                                        <?php if (!empty($params->get('address'))) { ?>
                                                            <div class="uk-width-1-1">
                                                                <div>
                                                                    <div class="uk-grid-small contactFields" data-uk-grid>
                                                                        <div class="uk-width-auto uk-text-primary"><span data-uk-icon="icon: location; ratio: 1.2;"></span></div>
                                                                        <div class="uk-width-expand"><span class="uk-text-small value font"><?php echo $params->get('address'); ?></span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if (!empty($params->get('phone'))) { ?>
                                                            <div class="uk-width-1-1 uk-width-auto@m">
                                                                <div>
                                                                    <div class="uk-grid-small contactFields" data-uk-grid>
                                                                        <div class="uk-width-auto uk-text-primary"><span data-uk-icon="icon: receiver; ratio: 1.2;"></span></div>
                                                                        <div class="uk-width-expand uk-flex uk-flex-middle"><span class="uk-text-small f500 value font ltr"><?php echo nl2br($params->get('phone'));; ?></span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if (!empty($params->get('fax'))) { ?>
                                                            <div class="uk-width-1-1 uk-width-auto@m">
                                                                <div class="uk-grid-small contactFields" data-uk-grid>
                                                                    <div class="uk-width-auto uk-text-primary"><span data-uk-icon="icon: fax; ratio: 1.2;"></span></div>
                                                                    <div class="uk-width-expand uk-flex uk-flex-middle"><span class="uk-text-small f500 value font ltr"><?php echo $params->get('fax'); ?></span></div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if (!empty($params->get('cellphones'))) { ?>
                                                            <div class="uk-width-1-1 uk-width-auto@m">
                                                                <div class="uk-grid-small contactFields" data-uk-grid>
                                                                    <div class="uk-width-auto uk-text-primary"><span data-uk-icon="icon: phone; ratio: 1.2;"></span></div>
                                                                    <div class="uk-width-expand uk-flex uk-flex-middle"><span class="uk-text-small f500 value font ltr"><?php echo nl2br($params->get('cellphones')); ?></span></div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($params->get('lat')) && !empty($params->get('lng'))) { ?>
                        <div class="uk-hidden@m">
                            <h2 class="uk-text-accent font uk-h5 f500"><?php echo JText::sprintf('PATHFINDER'); ?></h2>
                            <div>
                                <div class="uk-grid-small uk-child-width-1-1 uk-child-width-1-2@m" data-uk-grid>
                                    <div><a href="https://waze.com/ul?ll=<?php echo $params->get('lat'); ?>,<?php echo $params->get('lng'); ?>&navigate=yes" class="uk-width-1-1 uk-padding-small uk-button uk-button-default uk-text-zero" target="_blank"><img src="<?php echo JURI::base().'images/waze-logo.svg' ?>" width="100" alt=""></a></div>
                                    <div><a href="http://maps.google.com/maps?daddr=<?php echo $params->get('lat'); ?>,<?php echo $params->get('lng'); ?>" class="uk-width-1-1 uk-padding-small uk-button uk-button-default uk-text-zero" target="_blank"><img src="<?php echo JURI::base().'images/google-maps-logo.svg'; ?>" width="100" alt=""></a></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div>
                        <h2 class="uk-text-accent font uk-h5 f500"><?php echo JText::sprintf('INSTAGRAM_ACCOUNTS'); ?></h2>
                        <div>
                            <div class="uk-grid-small uk-child-width-1-1 uk-child-width-1-2@m" data-uk-grid>
                                <?php foreach ($params->get('instagrams') as $item) : ?>
                                    <?php if ($item->icon != '') { ?>
                                        <div><a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>" class="uk-width-1-1 uk-padding-tiny uk-lineheight-zero uk-button uk-button-instagram ltr font f500" target="_blank" id="<?php echo $item->title; ?>"><img src="<?php echo JURI::base().'images/sprite.svg#'.$item->icon; ?>" alt="<?php echo $item->title; ?>" width="32" height="32" class="uk-preserve-width uk-margin-small-right" data-uk-svg><?php echo $item->title; ?></a></div>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h2 class="uk-text-accent font uk-h5 f500"><?php echo JText::sprintf('SOCIAL_ACCOUNTS'); ?></h2>
                        <div class="uk-grid-small uk-child-width-auto uk-flex-around" data-uk-grid>
                            <?php foreach ($params->get('socials') as $item) : ?>
                                <?php if ($item->icon != '') { ?>
                                    <div><a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>" class="uk-width-1-1 uk-button uk-text-zero uk-lineheight-zero uk-padding-remove uk-button-<?php echo $item->icon; ?> uk-height-1-1" target="_blank" id="<?php echo $item->title; ?>"><img src="<?php echo JURI::base().'images/sprite.svg#'.$item->icon; ?>" alt="<?php echo $item->title; ?>" width="32" height="32" class="uk-preserve-width" data-uk-svg></a></div>
                                <?php } ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>