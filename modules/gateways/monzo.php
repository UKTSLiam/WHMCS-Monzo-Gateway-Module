<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function monzo_MetaData() {
    return array(
        'DisplayName' => 'Monzo',
        'APIVersion' => '1.0',
        'DisableLocalCredtCardInput' => true,
        'TokenisedStorage' => false,
    );
}

function monzo_config() {
    return array(
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'Monzo',
        ),
        'accountID' => array(
            'FriendlyName' => 'Account ID',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your account ID here (monzo.me/exampleaccountid)',
        )
    );
}

function monzo_link($params) {

    $accountId = $params['accountID'];
    $invoiceId = $params['invoiceid'];
    $amount = $params['amount'];
    $currencyCode = $params['currency'];

    $companyName = $params['companyname'];
    $systemUrl = $params['systemurl'];

    $new_amount = monzo_convert($amount, $currencyCode, "GBP");

    return '<form method="post" action="https://monzo.me/' . $accountId . '/' . $new_amount . '/billing?d=' . $companyName . ' - ' . $invoiceId . '">
        <input type="image" name="submit" width="35%" src="' . $systemUrl . '/modules/gateways/monzo/monzo.png" border="0" alt="Submit" /><br>
        <input type="submit" value="' . $params['langpaynow'] . '" />
        </form>';
}

function monzo_convert($amount, $from, $to) {
  
  if($from == $to) {
      return ($amount * round($amount, 4));
  }
    
  $conv_id = "{$from}_{$to}";
  $string = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=$conv_id&compact=ultra");
  $json_a = json_decode($string, true);
  return $amount * round($json_a[$conv_id], 4);

}
