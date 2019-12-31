<?php
require_once ('vendor/autoload.php');

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha512;

$role = $argv [1];
$signingSecret = $argv [2];

if (is_null ( $role ) || is_null ( $signingSecret )) {
   usage ();
   exit ( 1 );
}

if ($role == "CUSTOM") {
   if ($argc > 3) {
      $outputArr = NULL;
      $i = 1;
      foreach ( $argv as $arg ) {
         if ($i > 3) { // skip first three args as they are phpfile role and signingSecret
            $outputArr [$arg] = generateServiceTokenJwt ( $arg, $signingSecret );
         }
         $i ++;
      }
      
      foreach ( $outputArr as $i => $value ) {
         printf("\n[%s]\n%s\n",$i,$value);
      }
   } else {
      usage ();
      exit ( 1 );
   }
} else {
   
   printf("\n[%s]\n%s\n",$role,generateServiceTokenJwt ( $role, $signingSecret ));
}
exit ( 0 );

function generateServiceTokenJwt($role, $signingSecret) {
   $signer = new Sha512 ();
   
   $token = (new Builder ())->setSubject ( "Service Account User" )->set ( 'role', $role )->setExpiration ( 2145946447 )->sign ( $signer, $signingSecret )->getToken ();
   
   return ( string ) $token;
}

function usage() {
   echo "Missing argument.\nUsage: generateServiceAccountJwt.php role signingSecret\n\nIf role is valued to CUSTOM:\ngenerateServiceAccountJwt.php CUSTOM signingSecret role1 [role2 ...]\n";
}

?>