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

defined('_JEXEC') or die;
 
class AfzonehaZarinpalHK{
    
   const
        SERVER_IRAN = 'ir', 
        SERVER_OTHER = 'de'; 
   public static function setPayment($merchant,$amount,$desc,$red,$server,$mobile=NULL,$email=NULL){
       
        $amount = number_format($amount,0);
        $amount = str_replace(',','',$amount);
        $session = JFactory::getSession();
        $session->set('amount',$amount);
        if($server == self::SERVER_IRAN){
            $client = new SoapClient('https://ir.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8'));
        }else{
            $client = new SoapClient('https://de.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8'));
        }
        
        $result = $client->PaymentRequest(
                                array(
                                        'MerchantID'     => $merchant,
                                        'Amount'     => $amount,
                                        'Description'     => $desc,
                                        'Email'     => '',
                                        'Mobile'     => '',
                                        'CallbackURL'     => $red
                                    )
            );
       
        if($result->Status == 100){
           $url = 'https://www.zarinpal.com/pg/StartPay/'.$result->Authority; 
           return array(1,$url);
        } else{
            
            $Status = $result->Status;
            $error  = self::getZarinError($Status);
            $error = "خطایی رخ داده است شرح خطا: $error";
            return array(0,$error);
        }
   }
   
   public static function getPayment($merchant,$Authority,$Status,$server){
       $session = JFactory::getSession();
       $Amount = $session->get('amount');
       if($Status=='OK'){
             if($server==self::SERVER_IRAN){
                $client = new SoapClient('https://ir.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8')); 
             }else{
                 $client = new SoapClient('https://de.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8'));
             }
              
             $result = $client->PaymentVerification(
                  array(
                        'MerchantID' => $merchant,
                        'Authority'  => $Authority,
                        'Amount'     => $Amount
                    )
             );
                    
             if($result->Status == 100){
                 
                 return array(1,"پرداخت با موفقیت انجام شد کد رهگیری: {$result->RefID}",$result->RefID);
             }else{
                
                 $Status = $result->Status;
                 $error = self::getZarinError($Status);
                 $error = "خطایی رخ داده است و پرداخت ناموفق بود شرح خطا: : $error";
                 return array(0,$error); 
                 
             }       
           
       }else{
           return array(0,'پرداخت توسط کاربر لغو شده است');
       }
  
   }   
  
   private function getZarinError($num){
        
        return [
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
        ][$num];
   }    
    
}