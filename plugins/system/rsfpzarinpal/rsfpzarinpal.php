<?php

/**
 * @package RSForm!Pro
 * @copyright (C) 2007-2019 www.rsjoomla.com
 * @license GPL, http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

define('RSFORM_FIELD_PAYMENT_ZARINPAL', 606);

class plgSystemRsfpzarinpal extends JPlugin
{
	protected $componentId 	    = RSFORM_FIELD_PAYMENT_ZARINPAL;
	protected $componentValue   = 'zarinpal';
	protected $log = array();

	protected $autoloadLanguage = true;

	public function onRsformBackendAfterShowComponents()
	{
		$formId = JFactory::getApplication()->input->getInt('formId');

		$link = "displayTemplate('" . $this->componentId . "')";
		if ($components = RSFormProHelper::componentExists($formId, $this->componentId))
			$link = "displayTemplate('" . $this->componentId . "', '" . $components[0] . "')";
?>
		<li><a href="javascript: void(0);" onclick="<?php echo $link; ?>;return false;" id="rsfpc<?php echo $this->componentId; ?>"><span class="rsficon rsficon-zarinpal"></span><span class="inner-text"><?php echo JText::_('RSFP_ZARINPAL_COMPONENT'); ?></span></a></li>
	<?php
	}

	/**
	 * Add a grand total and tax placeholder
	 * @param $args
	 */
	public function onRsformAfterCreatePlaceholders($args)
	{
		$choosePayment = RSFormProHelper::componentExists($args['form']->FormId, RSFORM_FIELD_PAYMENT_CHOOSE);
		$hasZarinpal	   = RSFormProHelper::componentExists($args['form']->FormId, $this->componentId);
		if ($choosePayment || $hasZarinpal) {
			if ($choosePayment) {
				$properties = RSFormProHelper::getComponentProperties($choosePayment[0]);
				$fieldName  = $properties['NAME'];

				if (!isset($args['submission']->values[$fieldName])) {
					return;
				}

				if ($args['submission']->values[$fieldName] != $this->componentValue) {
					return;
				}
			}

			$grandTotal = $this->calcTax($args['submission']->values['rsfp_Total'], RSFormProHelper::getConfig('zarinpal.tax.value'), RSFormProHelper::getConfig('zarinpal.tax.type'));

			$placeholders = &$args['placeholders'];
			$values = &$args['values'];

			$placeholders[] = '{grandtotal}';
			$values[] = $this->number_format($grandTotal);

			$placeholders[] = '{tax}';
			$values[] = $this->number_format($grandTotal - $args['submission']->values['rsfp_Total']);
		}
	}

	public function onRsformBackendAfterShowConfigurationTabs($tabs)
	{
		$tabs->addTitle(JText::_('RSFP_ZARINPAL_LABEL'), 'form-zarinpal');
		$tabs->addContent($this->configurationScreen());
	}

	public function onRsformDefineHiddenComponents(&$hiddenComponents)
	{
		$hiddenComponents[] = $this->componentId;
	}

	/**
	 * @param $placeholders
	 * @param $componentId
	 *
	 * @return mixed
	 * @since 2.0.0
	 */
	public function onRsformAfterCreateQuickAddPlaceholders(&$placeholders, $componentId)
	{
		if ($componentId == $this->componentId) {
			$placeholders['display'][] = '{grandtotal}';
			$placeholders['display'][] = '{tax}';
		}

		return $placeholders;
	}

	public function onRsformGetPayment(&$items, $formId)
	{
		if ($components = RSFormProHelper::componentExists($formId, $this->componentId)) {
			$data = RSFormProHelper::getComponentProperties($components[0]);

			$item 			= new stdClass();
			$item->value 	= $this->componentValue;
			$item->text 	= $data['LABEL'];

			// add to array
			$items[] = $item;
		}
	}

	/**
	 * @go to bank
	 */
	public function onRsformDoPayment($payValue, $formId, $SubmissionId, $price, $products, $code)
	{
		// execute only for our plugin
		if ($payValue != $this->componentValue) {
			return;
		}

		$app = JFactory::getApplication();
		$siteConfig = JFactory::getConfig();
		$session = JFactory::getSession();
		
		if ($price > 0) {
			list($replace, $with) = RSFormProHelper::getReplacements($SubmissionId);

			// nusoap
			if (!class_exists('nusoap_client')) {
				require_once JPATH_PLUGINS . "/system/rsfpzarinpal/lib/nusoap.php";
			}

			$merchant = RSFormProHelper::getConfig('zarinpal.merchant');
			$currency = RSFormProHelper::getConfig('zarinpal.currency');
			$sandbox = RSFormProHelper::getConfig('zarinpal.test');
			$merchant = trim($merchant);

			if (!$merchant || !$currency) {
				$app->enqueueMessage('لطفا اطلاعات تنظیمات پلاگین پرداخت را تکمیل کنید', 'error');
				$app->redirect(JRoute::_('index.php?option=com_rsform&view=rsform&formId=' . $formId));
				exit;
			}

			// remove decimal from price
			$price = round($price, 0);
			if($currency == "R") {
				$price = $price / 10;
			}

			// some data
			$MerchantID = $merchant;
			$Amount = $price;
			$Description = 'پرداخت - ' . $siteConfig->get('sitename');
			$Email = '';
			$Mobile = '';

			// callback URL
			$callback = JUri::root() . 'index.php?option=com_rsform&formId=' . $formId . '&task=plugin&plugin_task=zarinpal.notify&code=' . $code;
//			$callback = JUri::root() . 'courses/?code='.$code;

			$zarinpal_url = "https://zarinpal.com";
			if ($sandbox == "1") {
				$zarinpal_url = 'https://sandbox.zarinpal.com';
			}

			$client = new nusoap_client($zarinpal_url . '/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8'));
			$result = $client->call(
				"PaymentRequest",
				array(
					'MerchantID' 	=> $MerchantID,
					'Amount' 		=> $Amount,
					'Description' 	=> $Description,
					'Email' 		=> $Email,
					'Mobile' 		=> $Mobile,
					'CallbackURL' 	=> $callback
				)
			);

			if ($result['Status'] == 100) {
				// set price to session
				$session->set('rsfp_zarinpal_price',$Amount); 

				// redirect
				$app->redirect($zarinpal_url . '/pg/StartPay/' . $result['Authority']);
				exit;
			} else {
				$error = $this->getzarinpalError($result['Status']);
				$app->enqueueMessage($error, 'error');
				$app->redirect(JRoute::_('index.php?option=com_rsform&view=rsform&formId=' . $formId));
				exit;
			}
		}
	}

	/**
	 * @return from bank
	 */
	public function onRsformFrontendSwitchTasks()
	{
		$app = JFactory::getApplication();
		$input    = JFactory::getApplication()->input;
		$session = JFactory::getSession();

		// Notification receipt from zarinpal
		if ($input->getString('plugin_task', '') == 'zarinpal.notify') {
			// nusoap
			if (!class_exists('nusoap_client')) {
				require_once JPATH_PLUGINS . "/system/rsfpzarinpal/lib/nusoap.php";
			}

			$code 	= $input->getCmd('code');
			$formId = $input->getInt('formId');
			$Authority = $_GET['Authority'];
			
			// get config
			$merchant = RSFormProHelper::getConfig('zarinpal.merchant');
			$currency = RSFormProHelper::getConfig('zarinpal.currency');
			$sandbox = RSFormProHelper::getConfig('zarinpal.test');
			$merchant = trim($merchant);
			
			// get price from session
			$price = $session->set('rsfp_zarinpal_price',0);
			
			if($_GET['Status'] == 'OK'){

				$zarinpal_url = "https://zarinpal.com";
				if ($sandbox == "1") {
					$zarinpal_url = 'https://sandbox.zarinpal.com';
				}

				$client = new nusoap_client($zarinpal_url.'/pg/services/WebGate/wsdl', 'wsdl'); 
				$client->soap_defencoding = 'UTF-8';
				$result = $client->call('PaymentVerification', array(
						array(
							'MerchantID'	 => $merchant,
							'Authority' 	 => $Authority,
							'Amount'	 	 => $price
						)
					)
				);
				
				if ($result['Status'] == '100') {

					// transaction id
					$refid = $result['RefID'];

					$SubmissionId = $this->_getSubmissionId($formId, $code);
					if ($SubmissionId) {
						$db = JFactory::getDbo();
						$query = $db->getQuery(true);
						$query->update($db->qn('#__rsform_submission_values'))
							->set($db->qn('FieldValue') . ' = ' . $db->q(1))
							->where($db->qn('FieldName') . ' = ' . $db->q('_STATUS'))
							->where($db->qn('FormId') . ' = ' . $db->q($formId))
							->where($db->qn('SubmissionId') . ' = ' . $db->q($SubmissionId));
						$db->setQuery($query);
						$db->execute();
		
						$query = $db->getQuery(true);
						$query->update($db->qn('#__rsform_submission_values'))
							->set($db->qn('FieldValue') . ' = ' . $db->q($refid))
							->where($db->qn('FieldName') . ' = ' . $db->q('_TRANSACTION_ID'))
							->where($db->qn('FormId') . ' = ' . $db->q($formId))
							->where($db->qn('SubmissionId') . ' = ' . $db->q($SubmissionId));
						$db->setQuery($query);
						$db->execute();
		
						// trigger event
						JFactory::getApplication()->triggerEvent('onRsformAfterConfirmPayment', array($SubmissionId));

                        JFactory::getApplication()->enqueueMessage(JText::_('SUCCESSFUL_CLASS_MESSAGE'), 'success');
                        /* JFactory::getApplication()->enqueueMessage($refid, 'success'); */
                        print_r($result);
                        $app->redirect(JRoute::_('index.php?Itemid=171'));

					}
					
				} else {
					$error = $this->getzarinpalError($result['Status']);
					'تراکنش با خطا مواجه شد کد خطا :' . $result['Status'] . ' # متن خطا : '.$error;
					$app->enqueueMessage($error, 'error');
					$app->redirect('index.php?option=com_rsform&formId=' . $formId); 
				}
			} else {
				$app->enqueueMessage(JText::_('TRANSACTION_CANCELLED'), 'error');
                $app->redirect(JRoute::_('index.php?Itemid=144'));
				/* $app->redirect('index.php?option=com_rsform&formId=' . $formId); */
			}
		}
	}

	/**
	 * @return string
	 */
	protected function _buildPostData()
	{
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';

		//reading raw POST data from input stream. reading pot data from $_POST may cause serialization issues since POST data may contain arrays
		$raw_post_data = file_get_contents('php://input');
		if ($raw_post_data) {
			$raw_post_array = explode('&', $raw_post_data);

			$myPost = array();
			foreach ($raw_post_array as $keyval) {
				$keyval = explode('=', $keyval, 2);
				if (count($keyval) == 2) {
					$myPost[$keyval[0]] = urldecode($keyval[1]);
				}
			}
		} else {
			$myPost = $_POST;
		}

		foreach ($myPost as $key => $value) {
			if ($key == 'limit' || $key == 'limitstart' || $key == 'option') {
				continue;
			}

			$value = urlencode($value);

			$req .= "&$key=$value";
		}

		return $req;
	}

	private function loadFormData()
	{
		$data 	= array();
		$db 	= JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__rsform_config'))
			->where($db->qn('SettingName') . ' LIKE ' . $db->q('zarinpal.%', false));
		if ($results = $db->setQuery($query)->loadObjectList()) {
			foreach ($results as $result) {
				$data[$result->SettingName] = $result->SettingValue;
			}
		}

		return $data;
	}

	protected function configurationScreen()
	{
		ob_start();

		JForm::addFormPath(__DIR__ . '/forms');

		$form = JForm::getInstance('plg_system_rsfpzarinpal.configuration', 'configuration', array('control' => 'rsformConfig'), false, false);
		$form->bind($this->loadFormData());

	?>
		<div id="page-zarinpal" class="form-horizontal">
			<?php
			foreach ($form->getFieldsets() as $fieldset) {
				if ($fields = $form->getFieldset($fieldset->name)) {
					foreach ($fields as $field) {
						echo $field->renderField();
					}
				}
			}
			?>
		</div>
<?php

		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}

	protected function _getTotal($submissionId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('FieldValue'))
			->from($db->qn('#__rsform_submission_values'))
			->where($db->qn('SubmissionId') . ' = ' . $db->q($submissionId))
			->where($db->qn('FieldName') . ' = ' . $db->q('rsfp_Total'));
		$db->setQuery($query);
		return $db->loadResult();
	}

	protected function _getSubmissionId($formId, $code)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('SubmissionId'))
			->from($db->qn('#__rsform_submissions', 's'))
			->where($db->qn('s.FormId') . ' = ' . $db->q($formId))
			->where('MD5(CONCAT(' . $db->qn('s.SubmissionId') . ',' . $db->qn('s.DateSubmitted') . ')) = ' . $db->q($code));
		$db->setQuery($query);

		if ($SubmissionId = $db->loadResult()) {
			return $SubmissionId;
		}

		return false;
	}

	/**
	 * Helper function to write log entries
	 */
	protected function writeLog()
	{
		// Need to separate IPN entries
		$this->addLogEntry("----------------------------- \n");

		$config   = JFactory::getConfig();
		$log_path = $config->get('log_path') . '/rsformpro_zarinpal_log.php';
		$log      = implode("\n", $this->log);

		/**
		 * If it's the first time we write in it, we need to add die() at the beginning of the file
		 */
		if (is_writable($config->get('log_path'))) {
			if (!file_exists($log_path)) {
				file_put_contents($log_path, "<?php die(); ?>\n");
			}

			/**
			 * we start appending log entries
			 */
			file_put_contents($log_path, $log, FILE_APPEND);
		}
	}

	/**
	 * Helper function to add messages to the log
	 *
	 * @param $message
	 */
	protected function addLogEntry($message)
	{
		$this->log[] = JFactory::getDate()->toSql() . ' : ' . $message;
	}

	/**
	 * @param $price
	 * @param $amount
	 * @param $type
	 *
	 * @return mixed
	 */
	public function calcTax($price, $amount, $type)
	{
		$price = (float) $price;
		$amount = (float) $amount;
		switch ($type)
		{
			case false:
				$price = $price + (($price * $amount) / 100);
				break;

			case true:
				$price = $price + $amount;
				break;
		}

		return $price;
	}
	
	private function number_format($val)
	{
		return number_format((float) $val, RSFormProHelper::getConfig('payment.nodecimals'), RSFormProHelper::getConfig('payment.decimal'), RSFormProHelper::getConfig('payment.thousands'));
	}

	public function getzarinpalError( $id ){
		$errorCode = array(
			-1=>'اطلاعات ارسال شده ناقص است.' ,
			-2=>'آی پی و يا مرچنت كد پذيرنده صحيح نيست' ,
			-3=>'با توجه به محدوديت هاي شاپرك امكان پرداخت با رقم درخواست شده ميسر نمي باشد' ,
			-4=>'سطح تاييد پذيرنده پايين تر از سطح نقره اي است' ,
			-11=>'درخواست مورد نظر يافت نشد' ,
			-21=>'هيچ نوع عمليات مالي براي اين تراكنش يافت نشد' ,
			-22=>'تراكنش نا موفق ميباشد' ,
			-33=>'رقم تراكنش با رقم پرداخت شده مطابقت ندارد' ,
			-34=>'سقف تقسيم تراكنش از لحاظ تعداد يا رقم عبور نموده است' ,
			-40=>'اجازه دسترسي به متد مربوطه وجود ندارد.' ,
			-41=>'اطلاعات ارسال شده مربوط به AdditionalData نامعتبر است' ,
			-54=>'درخواست مورد نظر آرشيو شده' ,
			100=>'عمليات با موفقيت انجام شد' ,
			101=>'عمليات پرداخت موفق بوده و قبلا تایید تراکنش انجام شده است' ,
		);
		
		return $errorCode[$id];
	}	
}
