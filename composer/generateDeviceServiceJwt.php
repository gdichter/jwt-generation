<?php
require_once ('vendor/autoload.php');

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

$signingSecret = $argv [1];

if (is_null ( $signingSecret )) {
   usage ();
   exit ( 1 );
}
   
printf("\n%s\n",generateDeviceServiceJwt ( $signingSecret ));

exit ( 0 );

function generateDeviceServiceJwt($signingSecret) {
   $signer = new Sha256 ();
   
   $token = (new Builder ())->setHeader('kid','RING_SOLUTIONS')->setIssuedAt( time() )->setExpiration ( 2145946447 )->sign ( $signer, $signingSecret )->getToken ();
   
   return ( string ) $token;
}

function usage() {
   echo "Missing argument.\nUsage: generateDeviceServiceJwt.php signingSecret\n\n";
}

?>