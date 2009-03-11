<?php
	if(!is_page() and !is_single()) :
		include "extra/sidebar-home.php";
	else :
		include "extra/sidebar-page.php";
	endif;
?>