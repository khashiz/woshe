
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
class AfzonehaZarinTest{
    
    public static function setPayment($MerchantID='test',$Amount,$Description,$CallbackURL,$Email=null,$Mobile=null){
        
        $session = JFactory::getSession();
        $session->set('Amount',$Amount);
        $client = new SoapClient('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);
        $result = $client->PaymentRequest(
            [
                'MerchantID' => $MerchantID,
                'Amount' => $Amount,
                'Description' => $Description,
                'Email' => $Email,
                'Mobile' => $Mobile,
                'CallbackURL' => $CallbackURL,
            ]
        );
        if ($result->Status == 100) {
            return [1,'https://sandbox.zarinpal.com/pg/StartPay/'.$result->Authority];
        } else {
            return [0,'خطایی در اتصال به درگاه رخ داده است::'.$result->Status];
        }
    }
    
    public static function getPayment($MerchantID='test',$Authority,$Status){
        
        $session = JFactory::getSession();
        $Amount = $session->get('Amount');
        
        if ($Status == 'OK') {

            $client = new SoapClient('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

            $result = $client->PaymentVerification(
                [
                    'MerchantID' => $MerchantID,
                    'Authority' => $Authority,
                    'Amount' => $Amount,
                ]
            );
 
        
            if ($result->Status == 100) {
                return [1,"پرداخت با موفقیت انجام شد. کد رهگیری پرداخت {$result->RefID}",$result->RefID];
            } else {
                return [0,"پرداخت ناموفق بود شرح خطا:: ".$result->Status];
            }
        } else {

            return [0,"پرداخت توسط کاربر لغو شده است"];
        }
            
    }
}