<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    
	<title><?php wp_title('--',true,'right'); ?> <?php bloginfo('name');?></title>
	
	<script src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/savvyui.min.js" type="text/javascript"></script>
    
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/js/css/all.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    
	<?php 
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	wp_head(); 
	?>
</head>
<body>

<!--#wrapper:start-->
	<div id="wrapper">
    
<!--#header:start-->
    	<div id="header">
            <h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>

<!--#navigation:start-->
            <div id="navigation">
            	<ul>
                	<li><a href="<?php echo get_option('home'); ?>/" class="<?php echo (is_front_page() ? 'current' : ''); ?>">Home</a></li>
                    <?php wp_list_pages('title_li='); ?>
                </ul>
            </div>
<!--#navigation:end-->

        </div>
<!--#header:end-->