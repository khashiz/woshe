<?php
/*
   Afzoneha.com  Joomla Extensions Developer
 * @package        Joomla! 2.5x  AND 3.x
 * @author        Afzoneha.com iran http://www.Afzoneha.com
 * @copyright    Copyright (c) 2013 Afzoneha.com iran Ltd. All rights reserved.
 * @license      GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link        http://Afzoneha.com
 * @email      Afzoneha.com@gmail.com
*/

defined('_JEXEC') or die('Restricted access');



include_once(JPATH_PLUGINS.'/hikashoppayment/zarinpal/afzoneha/AfzonehaZarinTest.php');

include_once(JPATH_PLUGINS.'/hikashoppayment/zarinpal/afzoneha/AfzonehaZarinpalHK.php');



class plgHikashoppaymentZarinpal extends hikashopPaymentPlugin

{





    var $multiple = true;

    var $name = 'zarinpal';

    var $doc_form = 'zarinpal';



    function onBeforeOrderCreate(&$order,&$do)

    {

        if(parent::onBeforeOrderCreate($order, $do) === true)

            return true;



        if(empty($this->payment_params->merchant))

        {

            $this->app->enqueueMessage('Please check your &quot;zarinpal&quot; plugin configuration');

            $do = false;

        }

    }



    ### send data to Bank

    function onAfterOrderConfirm(&$order, &$methods, $method_id)

    {

        parent::onAfterOrderConfirm($order, $methods, $method_id);

        $app = JFactory::getApplication();

        $MerchantID = $this->payment_params->merchant;

        $Amount = round($order->cart->full_total->prices[0]->price_value_with_tax,(int)$this->currency->currency_locale['int_frac_digits']);

        $cu = $this->payment_params->currency;

        if($cu) $Amount = $Amount/10;

        $CallbackURL = HIKASHOP_LIVE.'index.php?option=com_hikashop&ctrl=checkout&task=notify&notif_payment='.$this->name.'&tmpl=component&lang='.$this->locale . $this->url_itemid;

        $CallbackURL = $CallbackURL.'&order_id='.$order->order_id.'&amount='.$Amount; ////// zarin set

        

        $Email = $order->customer->email;

        $Mobile = $order->billing_address->address_telephone;

        $config = JFactory::getConfig();

        $siteName = $config->get('sitename');

        $Description = " خرید محصول از وبسایت: $siteName";

        $server_type = $this->payment_params->server_type;

        $zarin_type = $this->payment_params->zarin_type;

        if($zarin_type){

            $setPayment = AfzonehaZarinpalHK::setPayment($MerchantID,$Amount,$Description,$CallbackURL,$server_type,$Email,$Mobile);

        }else{

            $setPayment = AfzonehaZarinTest::setPayment($MerchantID,$Amount,$Description,$CallbackURL,$Email,$Mobile);

        }

        

        if($setPayment[0]){

           $app->redirect($setPayment[1]);

        }else{

           $app->enqueueMessage($setPayment[1],'error');

        }

}



    function onPaymentNotification(&$statuses)

    {

        //$dbOrder = $this->getOrder($_POST['order_id']);

        

        $orderid = hikaInput::get()->getVar('order_id');

        $pluginsClass = hikashop_get('class.plugins');

        $elements = $pluginsClass->getMethods('payment','zarinpal');

        

        if(empty($elements)) return false;

        $element = reset($elements);

        $MerchantID = $element->payment_params->merchant;

        $server_type = $element->payment_params->server_type;

        $zarin_type = $element->payment_params->zarin_type;



        $orderClass = hikashop_get('class.order');

        $dbOrder = $orderClass->get((int)@$orderid);

       

        if(empty($dbOrder)){

            echo "فاکتور مورد تائید نمیباشد ".@$orderid;

            return false;

        }

        



        #####################################################

        $Authority = hikaInput::get()->getVar('Authority');   

        $Amount = hikaInput::get()->getVar('amount'); 

        $Status = hikaInput::get()->getVar('Status');

        if($zarin_type){

            $getPayment = AfzonehaZarinpalHK::getPayment($MerchantID,$Authority,$Status,$server_type);

        }else{

            $getPayment = AfzonehaZarinTest::getPayment($MerchantID,$Authority,$Status);

        }

        

        if($getPayment[0]){

            

              $order = new stdClass();

                    $order->order_id = $dbOrder->order_id;

                    $order->old_status->order_status=$dbOrder->order_status;

                    $url = HIKASHOP_LIVE.'administrator/index.php?option=com_hikashop&ctrl=order&task=edit&order_id='.$order->order_id;

                    $order_text = "\r\n".JText::sprintf('NOTIFICATION_OF_ORDER_ON_WEBSITE',$dbOrder->order_number,HIKASHOP_LIVE);

                    $order_text .= "\r\n".str_replace('<br/>',"\r\n",JText::sprintf('ACCESS_ORDER_WITH_LINK',$url));

                    /////////////////

                    $RefID = $getPayment[2];  

              

                

                    /////////////

                    

                    

                    echo 'Transation success. RefID:'. $RefID . "\r\n\r\n";

                    

                    $mailer = JFactory::getMailer();

                    $config =& hikashop_config();

                    $sender = array(

                    $config->get('from_email'),

                    $config->get('from_name')

                    );

                    $mailer->setSender($sender);

                    $mailer->addRecipient(explode(',',$config->get('payment_notification_email')));

                    $order->order_status = 'confirmed';

                    $order->history->history_data = "رسید پرداخت درگاه زرین پال:: ".$RefID;

                    $order->history->history_reason = JText::_('پرداخت سفارش با موفقیت تایید شد');

                    $order->history->history_notified=1;

                    $order->history->history_payment_id = $element->payment_id;

                    $order->history->history_payment_method =$element->payment_type;

                    $order->history->history_type = 'payment';



                    $order_status =  $order->order_status;

                    $order->mail_status=$statuses[$order->order_status];

                    $mailer->setSubject(JText::sprintf('PAYMENT_NOTIFICATION_FOR_ORDER','Zarinpal',$order->mail_status,$dbOrder->order_number));



                    $body = str_replace('<br/>',"\r\n",JText::sprintf('PAYMENT_NOTIFICATION_STATUS','Zarinpal',$order->mail_status)).' '.JText::sprintf('ORDER_STATUS_CHANGED',$order->mail_status).' '.JText::sprintf('رسید پرداخت درگاه زرین پال',$trans_id)."\r\n\r\n".$order_text;

                    $mailer->setBody($body);

                    $mailer->Send();

                    $orderClass->save($order);

                    $order_num = $dbOrder->order_number;

      

                    $app =& JFactory::getApplication();

                    $httpsHikashop = HIKASHOP_LIVE;

                    $return_url = $httpsHikashop.'index.php?option=com_hikashop&ctrl=checkout&task=after_end&order_id='.$orderid.$this->url_itemid;

                    /* $app->redirect($return_url,"پرداخت شما با موفقیت انجام شد. کد رهگیری بانکی:: $RefID شماره فاکتور شما:: $order_num"); */
                    $app->enqueueMessage("پرداخت شما با موفقیت انجام شد. کد رهگیری بانکی:: $RefID شماره فاکتور شما:: $order_num");
                    $app->redirect($return_url);

                    return true;

            

        }else{

            

            $order = new stdClass();

            $order->order_id = $dbOrder->order_id;

            $order->old_status->order_status=$dbOrder->order_status;

            $url = HIKASHOP_LIVE.'administrator/index.php?option=com_hikashop&ctrl=order&task=edit&order_id='.$order->order_id;

            $order_text = "\r\n".JText::sprintf('NOTIFICATION_OF_ORDER_ON_WEBSITE',$dbOrder->order_number,HIKASHOP_LIVE);

            $order_text .= "\r\n".str_replace('<br/>',"\r\n",JText::sprintf('ACCESS_ORDER_WITH_LINK',$url));

            /////////////////

            $mailer = JFactory::getMailer();

            $config = hikashop_config();

            $sender = array(

            $config->get('from_email'),

            $config->get('from_name')

            );

            $order_num = $dbOrder->order_number;

            $mailer->setSender($sender);

            $mailer->addRecipient(explode(',',$config->get('payment_notification_email')));

            $order->order_status = 'cancelled';

            $order->history->history_data = $getPayment[1]." شماره فاکتور: $order_num";

            $order->history->history_reason = JText::_($getPayment[1]);

            $order->history->history_notified=1;

            $order->history->history_payment_id = $element->payment_id;

            $order->history->history_payment_method =$element->payment_type;

            $order->history->history_type = 'payment';



            $order_status =  $order->order_status;

            $order->mail_status=$statuses[$order->order_status];

            $mailer->setSubject(JText::sprintf('PAYMENT_NOTIFICATION_FOR_ORDER','zarinpal',$order->mail_status,$dbOrder->order_number));

            $body = str_replace('<br/>',"\r\n",JText::sprintf('PAYMENT_NOTIFICATION_STATUS','zarinpal',$order->mail_status)).' '.JText::sprintf('ORDER_STATUS_CHANGED',$order->mail_status)."\r\n\r\n".$order_text;

            $mailer->setBody($body);

            $mailer->Send();

            $orderClass->save($order);

            $app =& JFactory::getApplication();

            $httpsHikashop = HIKASHOP_LIVE;

            $return_url = HIKASHOP_LIVE.'index.php?option=com_hikashop&ctrl=order&task=cancel_order&order_id='.$orderid.$this->url_itemid;

        

            /* $app->redirect($return_url,$getPayment[1]); */
            $app->enqueueMessage($getPayment[1]);
            $app->redirect($return_url);

            return false;

            

        }

   

    }



    function onPaymentConfiguration(&$element)

    {

          

          $subtask = hikaInput::get()->getCmd('subtask', '');



           parent::onPaymentConfiguration($element);

    }

    

  function getPaymentDefaultValues(&$element){

        $element->payment_name = 'ZarinPal';

        $element->payment_description='You can pay by mellat using this payment method';

        $element->payment_images = 'ZarinPal';



        $element->payment_params->url = 'https://zarinpal.ir/';

        $element->payment_params->invalid_status = 'cancelled';

        $element->payment_params->pending_status = 'created';

        $element->payment_params->verified_status = 'confirmed';

  }

    

  private function getZarinError($num){

        

        $result = array(

        

                '-1'=>'اطلاعات اسال شده ناقص است',

                '-2'=>'IP و یا مرچنت کد پذیرنده صحیح نیست',

                '-3'=>'مبلغ باید بالای 100 تومان باشد',

                '-4'=>'سطح تایید پذیرنده پایین تر از سطح نقره ای است',

                '-11'=>'درخواست مورد نظر یافت نشد',

                '-21'=>'هیچ نوع عملیات مالی برای این تراکنش یافت نشد',

                '-22'=>'تراکنش ناموفق میباشد',

                '-33'=>'رقم تراکنش با رقم پرداخت شده مطابقت ندارد',

                '-54'=>'درخواست مورد نظر آرشیو شده',

                '101'=>'عملیات با موفق انجام شده ولی قبلا عمل وریفای روی این پرداخت انجام شده است'

        );

        

        return $result[$num];

    } 





}

