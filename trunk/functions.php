<?php

// Theme Name: Crybook-v2
// Edit this file on your own risk!

add_action('admin_menu', 'ci_options'); // Theme Menu "Crybook-v2 Settings"
add_action('wp_head', 'ci_stream_head');

function ci_stream_head() {
	ci_feedburner_count();
	ci_stream_delicious();
	ci_stream_twitter();
}
function ci_stream_capability() {
	return (!!class_exists("SimpleXMLElement") AND !!function_exists("curl_init") ? TRUE : FALSE);
}
function ci_stream($url) {
	//Initialize the Curl session
	$ch = curl_init();
		
	//Set curl to return the data instead of printing it to the browser.
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	//Set the URL
	curl_setopt($ch, CURLOPT_URL, $url);
	
	//Execute the fetch
	$data = curl_exec($ch);
	
	//Close the connection
	curl_close($ch);
	
	return $data;
}
function ci_stream_posting($title = '', $content = '', $status = 'open', $time = NULL, $cat = array()) {
	$author 	= get_option('ci_stream_author');
	
	if ( !!$author ) :
		$my_post 					= array();
		$my_post['post_title'] 		= $title;
		$my_post['post_content'] 	= $content;
		$my_post['post_status'] 	= 'publish';
		$my_post['post_author']		= $author;
		$my_post['comment_status'] 	= $status;
		$my_post['post_date'] 		= date('Y-m-d H:i:s', $time ? $time : time());
		$my_post['post_category'] 	= $cat;
		
		// Insert the post into the database
		wp_insert_post( $my_post );
	endif;
}
function ci_prettylist($data, $between = ', ', $last) {
	$length = count($data);
	$result = "";
	
	if($length > 1) :
		for($i = 0; $i < $length; $i++) :
			$result = $result . ($i == 0 ? "" : ($i == ($length - 1) ? $last : $between)) . $data[$i];
		endfor;
	else :
		$result = $data[0];
	endif;
	
	return $result;
}

function ci_stream_delicious() {
	$last_update 	= intval(get_option('ci_delicious_lastupdate'));
	$user 			= get_option('ci_delicious_user');
	$pass 			= get_option('ci_delicious_pass');
	$cat 			= get_option('ci_delicious_cat');
	$last_id		= get_option('ci_delicious_lastid');
	$now 			= time();
	$yesterday		= date('Y-m-d', $now - (24 * 60 * 60));
	
	if ( ($cat != -1 AND ($now - $last_update) > (60*60*3)) AND !!ci_stream_capability() ) :
		$feed = "https://$user:$pass@api.del.icio.us/v1/posts/get?dt=$yesterday";
		$data = ci_stream($feed);
		
		if ( !!$data ) :
			$xml 		= new SimpleXMLElement($data);
			$lastID 	= $last_id;
			
			if($xml->post) :
				$title		= 'links for '.date('Y-m-d', $now - (24 * 60 * 60));
				$cat 		= array($cat);
				$time 		= $now;
				
				$link 		= array();
				foreach($xml->post as $post) :
					$timestamp = strtotime($post['time']);
					if ( $lastID < $timestamp ) :
						array_push($link, '<a href="' . $post['href'] . '">'. $post['description'] .'</a>');
						$lastID = $timestamp;
					endif;
				endforeach;
				
				if ( count($link) > 0 ) :
					$content 	= ci_prettylist($link, ', ', ' and ');
					ci_stream_posting($title, $content, 'open', $time, $cat);
				endif;
			endif;
		endif;
		
		while ( $now > ($last_update + (60*60*3)) ) :
			$last_update += (60*60*3);
		endwhile;
		
		update_option("ci_delicious_lastid", $lastID."");
		update_option("ci_delicious_lastupdate", $last_update."");
	endif;
	
}
function ci_stream_twitter() {
	$last_update 	= intval(get_option('ci_twitter_lastupdate'));
	$last_id 		= intval(get_option('ci_twitter_lastid'));
	$cat 			= intval(get_option('ci_twitter_cat'));
	$now 			= time();
	
	if( ($cat != -1 AND ($now - $last_update) > (60*60)) AND !!ci_stream_capability() ) :
		$user 		= get_option('ci_twitter_user');
		$data 		= ci_stream("http://twitter.com/statuses/user_timeline/$user.xml?count=5");
		
		if ( !!$data ) :
			
			$xml 	= new SimpleXMLElement($data);
			$lastID = $last_id;
			
			foreach ($xml->status as $status) :
				// Create post object
				if ( $status->in_reply_to_user_id == '' AND $last_id < intval($status->id) ) :
					$title		= 'Twitter Status On '.$status->created_at;
					$content 	= $status->text;
					$time 		= strtotime($status->created_at);
					$cat 		= array($cat);
					
					ci_stream_posting($title, $content, 'open', $time, $cat);
					
					if ( $lastID < intval($status->id) ) :
						$lastID = $status->id;
					endif;
				endif;
			endforeach;
		endif;
		
		while ( $now > ($last_update + (60*60)) ) :
			$last_update += (60*60);
		endwhile;
		
		update_option("ci_twitter_lastid", $lastID."");
		update_option("ci_twitter_lastupdate", $last_update."");
	endif;
}

function ci_feedburner_count() {
	$last_update 	= intval(get_option('ci_feed_lastupdate'));
	$enable 		= get_option('ci_feed_enable');
	$now 			= time();
	
	if ( ($enable === 'yes' AND ($now - $last_update) > (60*60*24)) AND !!ci_stream_capability() ) :
		$user = get_option('ci_feed_user');
		
		if ( get_option('ci_feed_service') !== "google" ) :
			$whaturl = "http://api.feedburner.com/awareness/1.0/GetFeedData?uri=$user";
		else :
			$whaturl = "https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=$user";
		endif;
		
		$data = ci_stream($whaturl);
		
		if ( !!$data ) :
			$xml 	= new SimpleXMLElement($data);
			$fb 	= $xml->feed->entry['circulation'];
			
			if ( !$fb ) :
				$fb = 0;
			endif;
			
			$id = $xml->feed['id'];
			
			update_option("ci_feed_count", $fb." reader".($fb > 1 ? "s" : ""));
			update_option('ci_feed_id', $id."");
		endif;
		
		while ( $now > ($last_update + (60*60*24)) ) :
			$last_update += (60*60*24);
		endwhile;
		
		update_option("ci_feed_lastupdate", $last_update."");
	endif;
}

function ci_setup_page() {
	
	if(isset($_POST['submitted']) and $_POST['submitted'] == 'yes') :
		update_option("ci_feed_user", trim(stripslashes($_POST['feed_user'])));
		update_option("ci_feed_service", trim(stripslashes($_POST['feed_service'])));
		update_option("ci_feed_email", trim(stripslashes($_POST['feed_email'])));
		
		update_option("ci_stream_author", stripslashes($_POST['stream_author']));
		update_option("ci_aside_cat", stripslashes($_POST['aside_cat']));
		update_option("ci_event_cat", stripslashes($_POST['event_cat']));
		update_option("ci_video_cat", stripslashes($_POST['video_cat']));
		
		
		update_option("ci_delicious_cat", stripslashes($_POST['delicious_cat']));
		update_option("ci_delicious_user", trim(stripslashes($_POST['delicious_user'])));
		update_option("ci_delicious_pass", trim(stripslashes($_POST['delicious_pass'])));
		
		update_option("ci_twitter_user", trim(stripslashes($_POST['twitter_user'])));
		update_option("ci_twitter_cat", stripslashes($_POST['twitter_cat']));
		
		update_option("ci_about", stripslashes($_POST['about_site']));
		
		if(isset($_POST['feed_update']) and $_POST['feed_update'] == 'yes') :
			update_option("ci_feed_lastupdate", "0");
		endif;
		
		if(isset($_POST['twitter_update']) and $_POST['twitter_update'] == 'yes') :
			update_option("ci_twitter_lastupdate", "0");
		endif;
		
		if(isset($_POST['delicious_update']) and $_POST['delicious_update'] == 'yes') :
			update_option("ci_delicious_lastupdate", "0");
		endif;
		
		if(isset($_POST['feed_enable']) and $_POST['feed_enable'] == 'yes') :
			update_option("ci_feed_enable", "yes");
		else :
			update_option("ci_feed_enable", "no");
		endif;
		
		echo "<div id=\"message\" class=\"updated fade\"><p><strong>Your settings have been saved.</strong></p></div>";
	endif; 
	$feedApp = array(
		'google' => 'Google.com',
		'feedburner' => 'Feedburner.com'
	);
	$data = array(
		'feed' => array(
			'service' => get_option('ci_feed_service'),
			'user' => get_option('ci_feed_user'),
			'id' => get_option('ci_feed_id'),
			'enable' => get_option('ci_feed_enable'),
			'email' => get_option('ci_feed_email'),
			'lastupdate' => get_option('ci_feed_lastupdate')
		),
		'aside' => get_option('ci_aside_cat'),
		'video' => get_option('ci_video_cat'),
		'event' => get_option('ci_event_cat'),
		'delicious' => array (
			'user' => get_option('ci_delicious_user'),
			'pass' => get_option('ci_delicious_pass'),
			'cat' => get_option('ci_delicious_cat'),
			'lastupdate' => get_option('ci_delicious_lastupdate')
		),
		'twitter' => array (
			'user' => get_option('ci_twitter_user'),
			'cat' => get_option('ci_twitter_cat'),
			'lastupdate' => get_option('ci_twitter_lastupdate')
		),
		'author' => get_option('ci_stream_author'),
		'about' => get_option('ci_about')
	);
?>
<!-- Update Form -->
<div class="wrap">	
	<form method="post" name="update_form" target="_self">	
		<h2>CryBook Settings</h2>
        <h3>About</h3>
		<table class="form-table my">
			<tr>
				<td>
					<textarea name="about_site" rows="10" style="width:95%"><?php echo $data['about']; ?></textarea>
					<br />Display at the top of your site
				</td>
			</tr>
		</table>
		
        <h3>Feedburner</h3>
		<p>Key in your Feedburner data</p>
		<table class="form-table my">
			<tr>
				<th>Service:</th>
				<td>
					<select name="feed_service">
					<?php foreach($feedApp as $key => $val) : ?>
						<option value="<?php echo $key; ?>" <?php echo (trim($data['feed']['service']) == trim($key) ? 'selected="selected"' : ''); ?>>
							<?php echo $val; ?>
						</option>
					<?php endforeach;  ?>
					</select>
				</td>
			</tr>
			<tr>
				<th>Username:</th>
				<td>
					<input type="text" name="feed_user" value="<?php echo $data['feed']['user']; ?>" size="35" />
				</td>
			</tr>
            <tr>
				<th>Last Update:</th>
				<td>
					<strong><?php echo date('Y-m-d H:i:s', $data['feed']['lastupdate']); ?></strong>
				</td>
			</tr>
			<tr>
				<th>Option:</th>
				<td>
					<input type="checkbox" name="feed_enable" <?php echo ($data['feed']['enable'] == 'yes' ? 'checked="checked"' : ''); ?> value="yes" /> Enable feed count (text).
					<br /><input type="checkbox" name="feed_email" <?php echo ($data['feed']['email'] == 'yes' ? 'checked="checked"' : ''); ?> value="yes" /> Allow visitor to subscribe feed using e-mail.
					<br /><input type="checkbox" name="feed_update" value="yes" /> Tick to reset reader count.
				</td>
			</tr>
		</table>
        <h3>Delicious</h3>
        <table class="form-table my">
        	<tr>
                <th>Username:</th>
				<td>
					<input type="text" name="delicious_user" value="<?php echo $data['delicious']['user']; ?>" size="35" />
				</td>
			</tr>
			<tr>
                <th>Password:</th>
				<td>
					<input type="password" name="delicious_pass" value="<?php echo $data['delicious']['pass']; ?>" size="35" />
				</td>
			</tr>
			<tr>
				<th>Last Update:</th>
				<td>
					<strong><?php echo date('Y-m-d H:i:s', $data['delicious']['lastupdate']); ?></strong>
				</td>
			</tr>
			<tr>
				<th>Category:</th>
				<td>
					<?php wp_dropdown_categories('hide_empty=0&show_option_none='.__('No Daily Links').'&hierarchical=1&name=delicious_cat&selected='.$data['delicious']['cat']); ?><br />
                    select which category to show auto posting from del.icio.us
				</td>
			</tr>
            <tr>
				<th>Option:</th>
				<td>
					<input type="checkbox" name="delicious_update" value="yes" /> Tick to force refetch Your Delicious's Update.
				</td>
			</tr>
        </table>
		<h3>Twitter</h3>
        <table class="form-table my">
        	<tr>
                <th>Username:</th>
				<td>
					<input type="text" name="twitter_user" value="<?php echo $data['twitter']['user']; ?>" size="35" />
				</td>
			</tr>
			<tr>
        		<th>Category:</th>
				<td>
					<?php wp_dropdown_categories('hide_empty=0&show_option_none='.__('No Tweet').'&hierarchical=1&name=twitter_cat&selected='.$data['twitter']['cat']); ?><br />
                    select which category to set as twitter category.
				</td>
			</tr>	
			<tr>
				<th>Last Update:</th>
				<td>
					<strong><?php echo date('Y-m-d H:i:s', $data['twitter']['lastupdate']); ?></strong>
				</td>
			</tr>
			<tr>
				<th>Option:</th>
				<td>
					<input type="checkbox" name="twitter_update" value="yes" /> Tick to force refetch Your Twitter's Update.
				</td>
			</tr>
        </table>
        <h3>Other Lifestream</h3>
		<table class="form-table my">
			<tr>
				<th>Posted By:</th>
				<td>
					<?php wp_dropdown_users('show_option_none='.__('No Author').'&name=stream_author&selected='.$data['author']); ?>
				</td>
			</tr>
			<tr>
				<th>Asides:</th>
				<td>
					<?php wp_dropdown_categories('hide_empty=0&show_option_none='.__('No Asides').'&hierarchical=1&name=aside_cat&selected='.$data['aside']); ?><br />
                    select which category to set as asides (twitter alike blogging).
				</td>
			</tr>
			<tr>
				<th>Events:</th>
				<td>
					<?php wp_dropdown_categories('hide_empty=0&show_option_none='.__('No Events').'&hierarchical=1&name=event_cat&selected='.$data['event']); ?><br />
                    select which category to set as events.
				</td>
			</tr>
			<tr>
				<th>Videos:</th>
				<td>
					<?php wp_dropdown_categories('hide_empty=0&show_option_none='.__('No Videos').'&hierarchical=1&name=video_cat&selected='.$data['video']); ?><br />
                    select which category to set as videos.
				</td>
			</tr>
		</table>
        <p class="submit">
			<input name="submitted" type="hidden" value="yes" />
			<input type="submit" name="Submit" value="Update &raquo;" />
		</p>
	</form>
</div>
<?php
}

function ci_options() { 
	// Adds Dashboard Menu Item
	add_theme_page('CryBook Settings', 'CryBook Settings', 'edit_themes', __FILE__, 'ci_setup_page');
}

function lifestream_cat($ext = "") {
	$cat = array('ci_twitter_cat', 'ci_delicious_cat', 'ci_aside_cat', 'ci_event_cat', 'ci_video_cat');
	$return = "";
	
	foreach($cat as $value) :
		$v = get_option($value);
		if(!!$v) :
			$return .= ($return != "" ? "," : "").$ext.$v;
		endif;
	endforeach;
	
	return $return;
}
function ci_list_pings($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?> like this</li>
<?php
}

function ci_list_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<?php echo get_avatar( $comment, 35 ); ?>
		<div class="comments" id="comment-<?php comment_ID(); ?>">
			<div class="comment_author">
				<strong class="author"><?php comment_author_link(); ?></strong>
				<span class="date">said, on <?php comment_date('F jS, Y') ?> at <a href="<?php comment_link() ?>"><?php comment_time() ?></a>. <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></span>
			</div>
			<div class="comment_content">
				<?php if ($comment->comment_approved == '0') : ?>
	            	<p class="commetn_mod">
	            		<em>Your comment is awaiting moderation.</em>
					</p>
	            <?php endif; ?>
				<?php comment_text() ?>
			</div>
		</div>
		<div class="clear-both"></div>
	</li>
<?php
}
function meta_entries($id) {
	$demo = get_post_meta($id, "demo_url", true);
	$source = get_post_meta($id, "source_url", true);
	$download = get_post_meta($id, "download_url", true);
	$output = "";
	
	if(($source != '' and $source != NULL) or ($demo != '' and $demo != NULL) or ($download != '' and $download != NULL)) :
		if($source != '' or $source != NULL) :
			$output .= ' - <a href="'.$source.'" title="View Source Code">View Source Code</a>';
		endif;

		if($demo != '' or $demo != NULL) :
			$output .= ' - <a href="'.$demo.'" title="View Demonstration">View Demonstration</a>';
		endif;
		
		if($download != '' or $download != NULL) :
			$output .= ' - <a href="'.$download.'" title="Download File">Download File</a>';
		endif;
	endif;
	
	print $output;
	edit_post_link(__('Edit'), ' - ', '');
}

if(function_exists('register_sidebar')) :
    register_sidebar(array(
        'name' => 'sidebar-left',
		'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
	register_sidebar(array(
        'name' => 'sidebar-right',
		'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
	
	register_sidebar(array(
        'name' => 'sidebar-page',
		'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
endif;

?>