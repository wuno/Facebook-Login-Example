<?php
	session_start();            //start session
    	$_SESSION = array();    //clear session array
    		session_destroy();      //destroy session
				header( "location:../index.php" );
                	?>