<?php
// include required classes from Facebook SDK
require_once( 'facebook/src/Facebook/FacebookHttpable.php' );
require_once( 'facebook/src/Facebook/FacebookCurl.php' );
require_once( 'facebook/src/Facebook/FacebookCurlHttpClient.php' );
require_once( 'facebook/src/Facebook/FacebookSession.php' );
require_once( 'facebook/src/Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'facebook/src/Facebook/FacebookRequest.php' );
require_once( 'facebook/src/Facebook/FacebookResponse.php' );
require_once( 'facebook/src/Facebook/FacebookSDKException.php' );
require_once( 'facebook/src/Facebook/FacebookRequestException.php' );
require_once( 'facebook/src/Facebook/FacebookOtherException.php' );
require_once( 'facebook/src/Facebook/FacebookAuthorizationException.php' );
require_once( 'facebook/src/Facebook/GraphObject.php' );
require_once( 'facebook/src/Facebook/GraphSessionInfo.php' );

// Called class with namespace
use Facebook\FacebookHttpable;
use Facebook\FacebookCurl;
use Facebook\FacebookCurlHttpClient;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;

// start session
session_start();

// init app with app id and secret
// EDIT THIS LINE ********************************************// EDIT THIS LINE ********************************************
FacebookSession::setDefaultApplication( 'APP ID HERE','APP SECRET HERE' );
 
 // login helper with redirect_uri
 // EDIT THIS LINE ********************************************// EDIT THIS LINE ********************************************
$helper = new FacebookRedirectLoginHelper( 'http://www.URL FORWARD AFTER FACEBOOK LOGGS THEM IN' );
 

// see if a existing session exists
if ( isset( $_SESSION ) && isset( $_SESSION['fb_token'] ) ) {
  // create new session from saved access_token
  $session = new FacebookSession( $_SESSION['fb_token'] );
  
  // validate the access_token to make sure it's still valid
  try {
    if ( !$session->validate() ) {
      $session = null;
    }
  } catch ( Exception $e ) {
    // catch any exceptions
    $session = null;
  }
  
} else {
  // no session exists
  
  try {
    $session = $helper->getSessionFromRedirect();
  } catch( FacebookRequestException $ex ) {
    // When Facebook returns an error
    // handle this better in production code
    print_r( $ex );
  } catch( Exception $ex ) {
    // When validation fails or other local issues
    // handle this better in production code
    print_r( $ex );
  }
  
}
 
// see if we have a session
if ( isset( $session ) ) {
  
  // save the session
  $_SESSION['fb_token'] = $session->getToken();
  // create a session using saved token or the new one we generated at login
  $session = new FacebookSession( $session->getToken() );
  
  // graph api request for user data with response           
$graphObject = (new FacebookRequest( $session, 'GET', '/me?fields=id,first_name,last_name,tagged_places' ))->execute()->getGraphObject()->asArray();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Facebook Login</title>
  </head>
  <body>
  
            <?php
  if (isset($session)){
  // print logout url using session and redirect_uri (logout.php page should destroy the session)
  // EDIT THIS LINE ********************************************// EDIT THIS LINE ********************************************
  echo '<dd><a href="' . $helper->getLogoutUrl( $session, 'http://www.URL/PATH/TO/actions/fbLogout.php' ) . '">Logout' . "&nbsp" .  $graphObject['first_name'] . "&nbsp" . $graphObject['last_name'] . '</a>';
  
} else {
  // show login url
  echo '<a href="' . $helper->getLoginUrl( array( 'email, user_birthday, user_about_me' ) ) . '">Login With Facebook</a>';
}
            ?>
    
          </div>
        </div>
       </menu>              

<?php
// DUMPING ALL DATA IN $graphObject for you to see everything is working.
var_dump($graphObject);
?>

