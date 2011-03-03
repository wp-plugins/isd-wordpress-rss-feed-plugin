<?php
/*
Plugin Name: iSimpleDesign WP RSS Feeds Plugin - Extended
Plugin URI: http://www.isimpledesign.co.uk/blog/wordpress-rss-blog-feed-plugin
Description: I created this plugin to pull in feeds from a category from our blog so that we can promote to our clients from the dashboard.
Version: 2.0
Author: Samuel East - Web Developer South Wales
Author URI: http://www.isimpledesign.co.uk
License: A "Slug" license name e.g. GPL2
*/

/*
This program is free software; you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by 
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
GNU General Public License for more details. 

You should have received a copy of the GNU General Public License 
along with this program; if not, write to the Free Software 
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
*/

// Some Defaults
$feed_link	=  get_bloginfo('url') . "/?feed=rss2";
$head_text	= 'Change this title via the ISD Feed Options';
$icon	= 'http://i688.photobucket.com/albums/vv245/yousaidit/rss.png';
$limit	= '4';
$excerpt = '200';


// Put our defaults in the "wp-options" table
add_option("ISD-feed-link", $feed_link);
add_option("ISD-head-text", $head_text);
add_option("ISD-icon", $icon);
add_option("ISD-limit", $limit);
add_option("ISD-excerpt", $excerpt);

// Start the plugin
if ( ! class_exists( 'isimpledesign_feeds_class' ) ) {
	
	class isimpledesign_feeds_class {
		
		
		function isimpledesign_feeds_menu() {//Function to create our menu
		
		if ( function_exists('add_menu_page') ) {
			
  add_menu_page('ISD Feeds', 'ISD Feeds', 'manage_options', basename(__FILE__), array('isimpledesign_feeds_class', 'isimpledesign_feeds_options'));
		}
		
		
		if ( function_exists('add_submenu_page') ) {
  add_submenu_page( 'isd-dashboard.php', 'ISD Feeds', 'support', 10, 'manage_options_sub', array( 'isimpledesign_feeds_class','isimpledesign_feeds_sup')); 
		}
}

 

function isimpledesign_feeds_options() {//function to build the contents for the admin window

		if ( isset($_POST['submit']) ) {
				$nonce = $_REQUEST['_wpnonce'];
				if (! wp_verify_nonce($nonce, 'ISD-updatesettings') ) die('Security check failed'); 
				if (!current_user_can('manage_options')) die(__('You cannot edit the search-by-category options.'));
				check_admin_referer('ISD-updatesettings');
				
				
			// Get our new option values
			$feed_link	= $_POST['feed-link'];
			$head_text	= stripslashes($_POST['head-text']);
			$icon	= stripslashes($_POST['icon-text']);
			$limit	= $_POST['limit-text'];
			$excerpt	= $_POST['excerpt-text'];

	        // Update the DB with the new option values
			update_option("ISD-feed-link", mysql_real_escape_string($feed_link));
			update_option("ISD-head-text", mysql_real_escape_string($head_text));
			update_option("ISD-icon", mysql_real_escape_string($icon));
			update_option("ISD-limit", mysql_real_escape_string($limit));
			update_option("ISD-excerpt", mysql_real_escape_string($excerpt));
                
			}

$feed_link	= get_option("ISD-feed-link");
$head_text	= get_option("ISD-head-text");
$icon	= get_option("ISD-icon");
$limit	= get_option("ISD-limit");
$excerpt	= get_option("ISD-excerpt");
?>

<div class="wrap">
  <h2>ISD Feed Options</h2>
  <form action="" method="post" id="isd-config">
    <table class="form-table">
      <?php if (function_exists('wp_nonce_field')) { wp_nonce_field('ISD-updatesettings'); } ?>
      <tr>
        <th scope="row" valign="top"><label for="feed-link">Your Feed URL:</label></th>
        <td><input type="text" name="feed-link" id="feed-link" class="regular-text" value="<?php echo $feed_link; ?>"/>
          <small> Please enter the feed url here! view the samples below...</small></td>
      </tr>
      <tr>
        <th scope="row" valign="top"><label for="icon-text">Your Icon URL:</label></th>
        <td><input type="text" name="icon-text" id="icon-text" class="regular-text" value="<?php echo  htmlentities(stripslashes($icon)); ?>"/>
          <small> This must be the full link to image... size 25px by 25px.</small></td>
      </tr>
      <tr>
        <th scope="row" valign="top"><label for="head-text">Your Header Text:</label></th>
        <td><input type="text" name="head-text" id="head-text" class="regular-text" value="<?php echo htmlentities(stripslashes($head_text)); ?>"/>
          <small> This must be set to something! if left blank the plugin will not show... you can also add html tags to the head to add styling i.e h1 h2 h3 h4 etc or divs</small></td>
      </tr>
      <tr>
        <th scope="row" valign="top"><label for="limit-text">Amount Of Posts to Show:</label></th>
        <td><input type="text" name="limit-text" id="limit-text" class="regular-text" value="<?php echo $limit; ?>"/>
          <small> This is Default to 4!...</small></td>
      </tr>
      <tr>
        <th scope="row" valign="top"><label for="excerpt-text">Excerpt Length:</label></th>
        <td><input type="text" name="excerpt-text" id="excerpt-text" class="regular-text" value="<?php echo $excerpt; ?>"/>
          <small> This is Default to 200!...</small></td>
      </tr>
    </table>
    <br/>
    <span class="submit" style="border: 0;">
    <input type="submit" name="submit" value="Save Settings" />
    </span>
  </form>
</div>
<h2>Examples:</h2>
<span>http://example.com/comments/feed/</span><br />
<span>http://example.com/?feed=commentsrss2</span><br />
<span>http://example.com/post-name/feed/</span><br />
<span>http://example.com/?feed=rss2</span><br />
<span>http://www.example.com/?cat=42&amp;feed=rss2</span><br />
<span>http://www.example.com/?tag=tagname&amp;feed=rss2</span><br />
<span>http://example.com/category/categoryname/feed</span><br />
<span>http://example.com/tag/tagname/feed</span>
<h3>You can also grab any RSS feeds links from the following site</h3>
<span><a href="http://www.feedage.com" target="_blank">http://www.feedage.com/</a></span>
<h3>If you would like to put this feed within your template please use the following code</h3>
<code>&lt;php $isimpledesign = new isimpledesign_feeds_class(); $isimpledesign->isimpledesign_feeds(); ?&gt;</code>
<h3>If you would like to put this feed within a post or page use the following code.</h3>
<code>[isimpledesign_feeds]</code> <br />
<p></p>
<?php	

}

// function to add feeds
function isimpledesign_feeds() {
     
	$feed_link_url = get_option("ISD-feed-link");
	$post_limit = get_option("ISD-limit");
	$excerpt_limit = get_option("ISD-excerpt");
	include_once(ABSPATH.WPINC.'/feed.php');
	$feed = fetch_feed($feed_link_url);

	$limit = $feed->get_item_quantity($post_limit); // specify number of items
	$items = $feed->get_items(0, $limit); // create an array of items
	
	

echo stripslashes(get_option("ISD-head-text")); 
if ($limit == 0) echo '
<div>The feed is either empty or unavailable.</div>
';
else foreach ($items as $item) : 

echo "
<div class='isd-head'> <a href='" . $item->get_permalink() . "' title='" . $item->get_date('j F Y @ g:i a') . "'>" . $item->get_title() . "</a> </div>
<div class='isd-body'>
  <p>" . substr($item->get_description(), 0, $excerpt_limit) . "<span> <a href='" . $item->get_permalink() . "'>Read More</a></span></p>
</div>
";
	  
endforeach;
}

// Feeds for comment from blog support.

function isimpledesign_feeds_sup() {

?>
<div class='wrap'>

<h2>Submit Suport Request</h2>

<div class="message"></div>

     <form method="post" action="" id="target">
    <p><input type="text" name="author">
	<label for="author"><small>Names (required)</small></label></p>
	<p><input type="text" value="" name="email">
	<label for="email"><small>Mail (will not be published) (required)</small></label></p>
	<p><input type="text" value="" name="url" >
	<label for="url"><small>Website</small></label></p>
	<p><textarea rows="10" cols="100%" name="comment"></textarea></p>
	<p><input type="submit" value="Submit"  name="comment_submit"><div class="load">Sending...</div>
	<input type="hidden" id="comment_post_ID" value="31" name="comment_post_ID">
<input type="hidden" value="0" id="comment_parent" name="comment_parent">
	</p>
</form></div>
<style type="text/css">
.message {
	display:none;
}
.load {
    display: none;
    float: left;
    margin: -34px 0 0 65px;
}
</style>
<script type="text/javascript">

$('#target').submit(function() {
							 
	$('.load').fadeIn("slow");						 
						 
	$.post(
              "/wp-content/plugins/isd-wordpress-rss-feed-plugin/isd-process.php",
               $("#target").serialize(),
                function(data){
					
					//alert(data.idata);
					
			   if (data.idata) {
			   $('.message').fadeIn("slow");
               $('.message').html('<p style="background-color:#C00; border:1px solid #F00; color:#FFF; padding:5px;">' +data.idata + '</p>');
               setTimeout(function() {
                $(".message").fadeOut("slow");
    }, 2500);
			   $('.load').fadeOut("slow");
               //alert(data.amessage);
               }else{
				$('.message').fadeIn("slow");   
                $('.message').html('<p style="background-color:#093; border:1px solid #0F0; color:#FFF; padding:5px;">' + data.imessage + '</p>');
				 setTimeout(function() {
                    $(".message").fadeOut("slow");
    }, 3500);
				$('.load').fadeOut("slow");
               
               }
			  
 
          },
          "json"
);						 
  return false;
});
					
</script>
<?php
$head_title = "Below are support comments from my blog please visit to add comments <a href='http://www.isimpledesign.co.uk/blog/wordpress-rss-blog-feed-plugin' target='_blank'>iSimpleDesign</a>";
	$feed_link_url = "http://www.isimpledesign.co.uk/blog/wordpress-rss-blog-feed-plugin/?feed=rss2";
	$post_limit = "50";
	$excerpt_limit = "1000";
	include_once(ABSPATH.WPINC.'/feed.php');
	$feed = fetch_feed($feed_link_url);

	$limit = $feed->get_item_quantity($post_limit); // specify number of items
	$items = $feed->get_items(0, $limit); // create an array of items

echo "
<table class='widefat'>
  <thead>
    <tr>
      <th><h2>" . $head_title . "</h2></th>
    </tr>
  </thead>
  <tbody>
  "; 
  if ($limit == 0) echo '
  <div>The feed is either empty or unavailable.</div>
  ';
  else foreach ($items as $item) : 
  
  echo "
  <tr>
    <td><a href='" . $item->get_permalink() . "' title='" . $item->get_date('j F Y @ g:i a') . "'>" . $item->get_title() . "</a>
      <p>" . substr($item->get_description(), 0, $excerpt_limit) . "<span> <a href='" . $item->get_permalink() . "'>Read More</a></span></p></td>
  </tr>
  ";
  
  endforeach;
  
  echo "
  </tbody> 
  
</table>
";
}  

// function to add feeds
function isimpledesign_feeds_dashboard() {
     
	$feed_link_url = get_option("ISD-feed-link");
	$post_limit = get_option("ISD-limit");
	$excerpt_limit = get_option("ISD-excerpt");
	include_once(ABSPATH.WPINC.'/feed.php');
	$feed = fetch_feed($feed_link_url);

	$limit = $feed->get_item_quantity($post_limit); // specify number of items
	$items = $feed->get_items(0, $limit); // create an array of items

if ($limit == 0) echo '
<div>The feed is either empty or unavailable.</div>
';
else foreach ($items as $item) : 

echo "
<div class='isd-head'> <a href='" . $item->get_permalink() . "' title='" . $item->get_date('j F Y @ g:i a') . "'>" . $item->get_title() . "</a> </div>
<div class='isd-body'>
  <p>" . substr($item->get_description(), 0, $excerpt_limit) . "<span> <a href='" . $item->get_permalink() . "'>Read More</a></span></p>
</div>
";
	  
endforeach;
}

// Add Dashboard widget
function isimpledesign_feeds_dashboard_widgets() {
	$head_text_url = strip_tags(get_option("ISD-head-text")); 
	$head_icon_url = get_option("ISD-icon");
	wp_add_dashboard_widget("example_dashboard_widget", "<img style='float:left;' src='$head_icon_url' width='25' height='25' alt='' /><span class='isd-head'>$head_text_url</span>", array( 'isimpledesign_feeds_class','isimpledesign_feeds_dashboard'));	
} 


} // end class
	
	
} // end if statement

// insert js needed for upload
function isimpledesign_feeds_js() {
		
		echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>';
} 
add_action('admin_head','isimpledesign_feeds_js');

// insert custom stylesheet
function style_insert() {
		$current_path = get_option('siteurl').'/wp-content/plugins/'.basename(dirname(__FILE__));
		
		echo '<link href="'.$current_path.'/css/isd-dashboard-style.css" type="text/css" rel="stylesheet" />';
		
} 
add_action('in_admin_header','style_insert');

// insert into admin panel
add_shortcode( 'isimpledesign_feeds', array('isimpledesign_feeds_class','isimpledesign_feeds'));
add_action('wp_dashboard_setup', array('isimpledesign_feeds_class','isimpledesign_feeds_dashboard_widgets'));
add_action('admin_menu', array('isimpledesign_feeds_class','isimpledesign_feeds_menu'));