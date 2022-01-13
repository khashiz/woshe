<?php
ini_set("soap.wsdl_cache_enabled", "0");
$contact_client = new SoapClient('http://api.payamak-panel.com/post/contacts.asmx?wsdl', array('encoding'=>'UTF-8'));
$parameters['username'] = "woshe";
$parameters['password'] = "zUH7rRHtjbA5XMP";
$parameters['mobileNumber'] = "42323424243";
if ($contact_client->CheckMobileExistInContact($parameters)->CheckMobileExistInContactResult) {
    echo 'yes';
} else {
    echo 'no';
}

ini_set("soap.wsdl_cache_enabled", "0");
$contact_client = new SoapClient('http://api.payamak-panel.com/post/contacts.asmx?wsdl', array('encoding'=>'UTF-8'));
$parameters['username'] = "woshe";
$parameters['password'] = "zUH7rRHtjbA5XMP";
$parameters['mobileNumber'] = "42323424243";
if ($contact_client->CheckMobileExistInContact($parameters)->CheckMobileExistInContactResult) {
    $orderClass = hikashop_get('class.order');
    $order = $orderClass->loadFullOrder({order_id}, true, false);




echo $contact_client->AddContact($parameters)->AddContactResult;
}
?>