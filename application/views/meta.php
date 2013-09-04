<?php

// Load HTML helper
$this->load->helper('html');

// Write DOCTYPE for html5
echo doctype('html5');

?>

<html>

<head>

<title>WebRE | <?php echo $page_title; ?></title>

<?php

// Dynamic META-Tag Generation
$meta = array(
    array('name' => 'robots', 'content' => 'no-cache'),
    array('name' => 'author', 'content' => 'Fachhochschule Nordwestschweiz - WebRE Team'),
    array('name' => 'generator', 'content' => 'FHNW'),
    array('name' => 'description', 'content' => 'WebRE'),
    array('name' => 'keywords', 'content' => 'requirements, engineering, web, fhnw'),
    array('name' => 'robots', 'content' => 'no-cache'),
    array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'),
    array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv')
);

echo meta($meta);

// Dynamic CSS implementation
$css_main = array(
	'href' => base_url().'assets/css/bootstrap.css',
	'rel' => 'stylesheet',
	'type' => 'text/css',
	'media' => 'screen'
);

$css_responsive = array(
	'href' => base_url().'assets/css/bootstrap-responsive.css',
	'rel' => 'stylesheet',
	'type' => 'text/css',
	'media' => 'screen'
);

$css_breadcrumbs = array(
	'href' => base_url().'assets/css/custom.css',
	'rel' => 'stylesheet',
	'type' => 'text/css',
	'media' => 'screen'
);

$css_designer_screen = array(
	'href' => base_url().'assets/css/designer_screen.css',
	'rel' => 'stylesheet',
	'type' => 'text/css',
	'media' => 'screen'
);

$css_designer_jquery = array(
	'href' => base_url().'assets/css/designer_jquery-ui.css',
	'rel' => 'stylesheet',
	'type' => 'text/css',
	'media' => 'screen'
);

$css_designer_colourpicker = array(
    'href' => base_url().'assets/css/colourpicker_spectrum.css',
    'rel' => 'stylesheet',
    'type' => 'text/css',
    'media' => 'screen'    
);



$font_droidsans = array(
	'href' => 'http://fonts.googleapis.com/css?family=Droid+Sans:400,700',
	'rel' => 'stylesheet',
	'type' => 'text/css'
);

$font_droidserif = array(
	'href' => 'http://fonts.googleapis.com/css?family=Droid+Serif',
	'rel' => 'stylesheet',
	'type' => 'text/css'
);

$font_boogaloo = array(
	'href' => 'http://fonts.googleapis.com/css?family=Boogaloo',
	'rel' => 'stylesheet',
	'type' => 'text/css'
);

$font_economica = array(
	'href' => 'http://fonts.googleapis.com/css?family=Economica:700,400italic',
	'rel' => 'stylesheet',
	'type' => 'text/css'
);

$links = array(
    $css_main,
    $css_responsive,
    $css_breadcrumbs,
    $css_designer_screen,
    $css_designer_jquery,
    $css_designer_colourpicker,
    $font_droidsans,
    $font_droidserif,
    $font_boogaloo,
    $font_economica
);

// Implement CSS files in source code
foreach ($links as $link) {
    echo link_tag($link) . "\n";
}

?>

</head>

<body>

<!-- JS Includes -->
<script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>

<?php
if (isset($skip_custom_js) and $skip_custom_js == 1) {
	
} else {
	echo '<script src="' . base_url() . 'assets/js/custom.js"></script>';
}
?>