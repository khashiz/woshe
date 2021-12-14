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


// Define the maximum number of submissions. For this example we'll use 50.
$capacityTotal = $params->get('fc_capacity');

// Get a database connection.
$db = JFactory::getDbo();

// Setup the query. This query counts the number of submissions for the current form.
// $formId contains the ID of the current form.
$db->setQuery("SELECT COUNT(`SubmissionId`) FROM #__rsform_submissions WHERE `FormId`='".(int) $this->formId."'");
$capacityUsed = $db->loadResult();
?>
<?php if ($user->id) { ?>
    <?php if ($params->get('fc_active') && $capacityUsed < $capacityTotal) { ?>
        <div class="uk-grid-divider uk-grid-column-large uk-grid-row-small" data-uk-grid>
            <div class="uk-width-1-1 uk-width-expand@m"><?php echo RSFormProHelper::displayForm($this->formId); ?></div>
            <div class="uk-width-1-1 uk-width-1-3@m">
                <div>
                    <div class="uk-child-width-1-1 uk-grid-divider" data-uk-grid>
                        <?php if (!empty($params->get('fc_capacity'))) { ?>
                            <div>
                                <h2 class="uk-text-accent font uk-h5 f500"><?php echo JText::sprintf('CAPACITY'); ?></h2>
                                <div>
                                    <p class="uk-margin-remove font f500 uk-text-dark uk-text-small"><?php echo JText::sprintf('COURSE_CAPACITY', '<span class="uk-text-primary">'.$capacityTotal.'</span>'); ?></p>
                                    <p class="uk-margin-small-bottom uk-margin-top uk-text-tiny uk-text-muted font f400"><?php echo nl2br(JText::sprintf('CAPACITY_USAGE', $capacityUsed, $capacityTotal-$capacityUsed)); ?></p>
                                    <progress class="uk-progress uk-margin-remove" value="<?php echo $capacityUsed; ?>" max="<?php echo $capacityTotal; ?>"></progress>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (!empty($params->get('fc_finishreg'))) { ?>
                            <div>
                                <h2 class="uk-text-accent font uk-h5 f500"><?php echo JText::sprintf('TIMELIMIT'); ?></h2>
                                <div>
                                    <p class="uk-margin-remove font f500 uk-text-dark uk-text-small"><?php echo JText::sprintf('COURSE_FINISHREG', '<span class="uk-text-primary">'.JHtml::date($params->get('fc_finishreg'), 'D ، j M').'</span>'); ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="uk-text-center uk-margin-medium-bottom">
            <div class="uk-margin-bottom uk-text-danger"><img src="<?php echo JUri::base().'images/sprite.svg#hourglass'; ?>" width="64" height="64" data-uk-svg></div>
            <?php if (!$params->get('fc_active')) { ?>
                <?php echo $params->get('DEACTIVE_MSG', '<p class="font f500 uk-text-dark">'.nl2br(JText::_('DEACTIVE_MSG')).'</p>'); ?>
            <?php } else { ?>
                <?php echo '<p class="font f500 uk-text-dark">'.nl2br(JText::_('CAPACITY_FINISHED_MSG')).'</p>'; ?>
            <?php } ?>
        </div>
        <div class="uk-width-1-1 uk-width-1-3@m uk-margin-auto">
            <hr class="uk-divider-icon uk-margin-remove-top uk-margin-medium-bottom">
            <?php echo RSFormProHelper::displayForm(6); ?>
        </div>
    <?php } ?>
<?php } else { ?>
    <div class="uk-text-center uk-margin-medium-bottom">
        <div class="uk-margin-bottom uk-text-accent"><img src="<?php echo JUri::base().'images/sprite.svg#user'; ?>" width="64" height="64" data-uk-svg></div>
        <p class="font f500 uk-text-dark"><?php echo nl2br(JText::_('PLEASE_LOGIN_FOR_COURSE')); ?></p>
    </div>
    <div class="uk-width-1-1 uk-width-1-3@m uk-margin-auto">
        <a class="uk-width-1-1 rsform-submit-button  uk-button uk-button-primary" href="signin?return=<?php echo base64_encode(JURI::current()) ?>"><?php echo JText::_('JLOGIN_TO_ACCOUNT'); ?></a>
    </div>
<?php } ?>