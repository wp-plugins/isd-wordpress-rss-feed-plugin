<?php

//print_r($_POST);

$urltopost = "http://www.isimpledesign.co.uk/wp-comments-post.php";
 
$datatopost = array ('author' => $_POST['author'], 'email' => $_POST['email'], 'url' => $_POST['url'], 'comment' => $_POST['comment'], 'comment_post_ID' => 196, 'comment_parent' => 0);
 
$ch = curl_init ($urltopost);
 
curl_setopt ($ch, CURLOPT_POST, true);
 
curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
 
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
 
$returndata = curl_exec ($ch);
 

$success = $returndata; 

$message = 'Thanks your comment has been submitted ;) and will show when approved';

$json = array(
			 idata => strip_tags($success),
             imessage => $message
);  
 
echo json_encode($json); 	

?>