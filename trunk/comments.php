<?php
if ( function_exists('wp_list_comments') ) :
	include "extra/wplist-comments.php";
else :
	include "extra/legacy-comments.php";
endif;
?>