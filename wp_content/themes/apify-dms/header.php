<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/open-base.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/common.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/docmtCenter.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/override.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/sh.css" type="text/css" media="screen" />
	<!--[if IE]><link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie.css" type="text/css" media="screen" /><![endif]-->
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<?php //wp_head(); ?>	
	<script type="text/javascript" src="http://a.tbcdn.cn/s/kissy/1.2.0/kissy.js"></script>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/menu-tip.js"></script>
	<!--暂时未使用
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.validate.min.js"></script>
	-->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/jquery-ui.css" type="text/css"/>
</head>
<body>
	<!-- HEADER BEGIN -->
	<div class="top-header-menu">
		<h1 id="top-menu-cout">
			<div id="top-logo">
				<img title="VELA开放平台" alt="VELA开放平台" style="margin:20px 120px" src="<?php bloginfo('stylesheet_directory'); ?>/css/images/logo.png" width="240" height="56">
			</div>
		</h1>
	</div>
	<!-- HEADER END -->
	<!-- breadcrums begin -->
	<div class="crumbs">
		<?php
		$page_path = apify_get_breadcrums_list($_GET);
		if( !empty( $page_path ) ) {
			$depth = count($page_path);
			foreach( $page_path as $i=>$path ) { 
				if( $i == ($depth-1) ) {
					echo '<a class="last" href="'. get_site_url().$path['uri'] .'">'. $path['title'] .'</a>';
				} else {
					echo '<a href="'. get_site_url().$path['uri'] .'">'. $path['title'] .'</a>';
				}
			} //end foreach
		} //end if
		?>
	</div>
	<!-- breadcrums end -->
