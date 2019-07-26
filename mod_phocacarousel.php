<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die('Restricted access');// no direct access

$app 						= JFactory::getApplication();
$document 					= JFactory::getDocument();
$p 							= array();
$p['animation_speed'] 	    = $params->get( 'animation_speed', 600);
$p['autoplay'] 	            = $params->get( 'autoplay', 1);
$p['autoplay_speed'] 	    = $params->get( 'autoplay_speed', 1000);
$p['nav'] 	                = $params->get( 'nav', 1);
$p['dots'] 	                = $params->get( 'dots', 1);
$p['height'] 	            = $params->get( 'height', '');
$p['fill_rest_page'] 	    = $params->get( 'fill_rest_page', 1);
$p['fill_rest_page_ratio'] 	= $params->get( 'fill_rest_page_ratio', '2');
$p['background_image'] 		= $params->get( 'background_image', '');
$p['display_view'] 			= $params->get( 'display_view', '');
$p['display_option'] 		= $params->get( 'display_option', '');
$view 						= $app->input->get('view', '');
$option 					= $app->input->get('option', '');

$optionA 	= array_map('trim', explode(',', $p['display_option']));// Remove spaces
$viewA 		= array_map('trim', explode(',', $p['display_view']));
$optionA	= array_filter($optionA);// Remove empty values from array
$viewA 		= array_filter($viewA);



if (empty($optionA) && empty($viewA)) {
	// OK - both parameters are not set
} else if (!empty($optionA) && in_array($option, $optionA) && empty($viewA)) {
	// OK - only option is set and it meets the criteria
} else if (!empty($optionA) && in_array($option, $optionA) && !empty($viewA) && in_array($view, $viewA) ) {
	// OK - option and view is set and it meets the criteria
} else {
	return '';
}


$items = (array)$params->get('items');


JHTML::stylesheet( 'media/mod_phocacarousel/css/animate.css' );
JHTML::stylesheet( 'media/mod_phocacarousel/css/swiper.min.css' );
JHTML::stylesheet( 'media/mod_phocacarousel/css/style.css' );

$path = array();
$path['image'] = JUri::base();// . '/';


$id = 'phoca-carousel-'.rand ( 10000 , 99999 );

$s = array();
// STYLE
if (!empty($items)) {

    $i = 0;
    foreach ($items as $k => $v) {

        if (isset($v->item_background_image) && $v->item_background_image != '') {

            $s[] = '#'.$id.' .ph-swiper-slide-box-'.$i.' {';
            $s[] = '    position:relative;';
            $s[] = '    overflow: hidden;';
            $s[] = '}';

            $s[] = '#'.$id.' .ph-swiper-slide-box-bg-'.$i.' {';
            $s[] = '    background-image:url('.$path['image'].$v->item_background_image.');';
            $s[] = '    -webkit-background-size: cover;';
            $s[] = '    background-size: cover;';
            $s[] = '    background-position: center;';
            $s[] = '}';

        }

        $s[] = '#'.$id.' .ph-title-'.$i.' {';
        $s[] = '    '.$v->item_title_css.'';
        $s[] = '    display:none;';
        $s[] = '}';

        $s[] = '#'.$id.' .ph-description-'.$i.' {';
        $s[] = '    '.$v->item_description_css.'';
        $s[] = '    display:none;';
        $s[] = '}';

        $s[] = '#'.$id.' .ph-button-'.$i.' {';
        $s[] = '    '.$v->item_button_css.'';
        $s[] = '    display:none;';
        $s[] = '}';

        $s[] = '#'.$id.' .ph-image1-'.$i.' {';
        $s[] = '    '.$v->item_image1_css.'';
        $s[] = '    display:none;';
        $s[] = '}';

        $s[] = '#'.$id.' .ph-image2-'.$i.' {';
         $s[] = '   '.$v->item_image2_css.'';
        $s[] = '    display:none;';
        $s[] = '}';

        $s[] = '#'.$id.' .ph-image1-'.$i.' img {';
        $s[] = '    '.$v->item_image1_size_css.'';
       // $s[] = '    display:none;';
        $s[] = '}';

        $s[] = '#'.$id.' .ph-image2-'.$i.' img {';
        $s[] = '    '.$v->item_image2_size_css.'';
       // $s[] = '    display:none;';
        $s[] = '}';

        $i++;
    }

    $s[] = '#'.$id.' .swiper-container.ph-module-swiper-container {';
    $s[] = '    width: 100%;';
    if ($p['fill_rest_page'] == 0) {
        $s[] = '    height: '.$height.';';
    }
    $s[] = '    background: transparent;';
    $s[] = '}';

    //$s[] = '#'.$id.' .swiper-slide {}';

    $s[] = '#'.$id.' .parallax-bg {';
    $s[] = '    position: absolute;';
    $s[] = '    left: 0;';
    $s[] = '    top: 0;';
    $s[] = '    width: 130%;';
    $s[] = '    height: 100%;';
    $s[] = '    -webkit-background-size: cover;';
    $s[] = '    background-size: cover;';
    $s[] = '    background-position: center;';
    if($p['background_image'] != '') {
        $s[] = '    background-image:url(' . $path['image'] . $p['background_image'] . ');';
    }
    $s[] = '}';


}

$document->addCustomTag( "\n<style type=\"text/css\">\n\n" . implode("\n", $s) ."\n</style>\n\n");

JHtml::_('jquery.framework', false);

$document->addScript(JURI::root(true) . '/media/mod_phocacarousel/js/swiper.min.js');


$js = array();

$js[] = ' jQuery( document ).ready(function() {';

$js[] = '   function phReset($elem) {';
$js[] = '       $elem.before($elem.clone(true));';
$js[] = '     	var $newElem = $elem.prev();';
$js[] = '     	$elem.remove();';
$js[] = '     	return $newElem;';
$js[] = ' 	}';

$js[] = '   function phStartAnimation(item, aClass) {';
$js[] = '       var a = jQuery(".swiper-slide-active "+item);';
$js[] = '       var p = jQuery(".swiper-slide-prev "+item);';
$js[] = '       var n = jQuery(".swiper-slide-next "+item);';

$js[] = '       a.removeClass(aClass);';
$js[] = '       a = phReset(a);';
$js[] = '       a.addClass(aClass);';
$js[] = '       a.show();';

$js[] = '       p.hide();';
$js[] = '       n.hide();';
$js[] = '   }';

$js[] = '   function phStartAnimationBackground(item, aClass) {';
$js[] = '   	var a = jQuery(".swiper-slide-active "+item);';
$js[] = '   	a.removeClass(aClass);';
$js[] = '   	a = phReset(a);';
$js[] = '   	a.addClass(aClass);';
$js[] = '   }';

$js[] = '   var swiper = new Swiper(".ph-module-swiper-container", {';
$js[] = '   	speed: '.(int)$p['animation_speed'].',';
if ($p['autoplay'] == 1){
    $js[] = '       autoplay: { delay: '.(int)$p['autoplay_speed'].' },';
} else {
    $js[] = '       autoplay: false,';
}
$js[] = '       parallax: false,';
if ($p['dots'] == 1){
    $js[] = '   	pagination: {';
    $js[] = '    	    el: ".swiper-pagination",';
    $js[] = '       	clickable: true,';
    $js[] = '    	},';
}
if ($p['nav'] == 1){
    $js[] = '     	navigation: {';
    $js[] = '      	    nextEl: ".swiper-button-next",';
    $js[] = '     	    prevEl: ".swiper-button-prev",';
    $js[] = '     	},';
}
$js[] = '   });';


if($p['fill_rest_page'] == 1) {
    //$js[] = '   var phSwiperTop 		= jQuery(".swiper-container").position().top;';
    $js[] = '   var phSwiperTop 		= jQuery("#'.$id.'").offset().top;';
    $js[] = '   var phSwiperWidth		= jQuery(".swiper-container").width();';
    $js[] = '   var phWindowHeight		= jQuery(window).height();';
    $js[] = '   var phRestPageHeight	= phWindowHeight - phSwiperTop;';
    $js[] = '   if (phSwiperWidth < phRestPageHeight) {';
    if ($p['fill_rest_page_ratio'] > 0) {
        $js[] = '       phRestPageHeight = phSwiperWidth/'.$p['fill_rest_page_ratio'].';';
    } else {
        $js[] = '       phRestPageHeight = phSwiperWidth;';
    }
    $js[] = '   }';
    $js[] = '   jQuery(".swiper-container.ph-module-swiper-container").height(phRestPageHeight);';
}

/* Start animation of items when transition of slide ends*/
$js[] = '   swiper.on("transitionEnd", function () {';
if (!empty($items)) {
    $i = 0;
    foreach ($items as $k => $v) {
        $js[] = '      phStartAnimation(".ph-title-'.$i.'", "animated '.$v->item_title_animation.'");';
        $js[] = '      phStartAnimation(".ph-description-'.$i.'", "animated '.$v->item_description_animation.'");';
        $js[] = '      phStartAnimation(".ph-image1-'.$i.'", "animated '.$v->item_image1_animation.'");';
        $js[] = '      phStartAnimation(".ph-button-'.$i.'", "animated '.$v->item_button_animation.'");';
        $js[] = '      phStartAnimation(".ph-image2-'.$i.'", "animated '.$v->item_image2_animation.'");';
        $i++;
    }
}
$js[] = '   });';

$js[] = '   swiper.on("transitionStart", function () {';
if (!empty($items)) {
    $i = 0;
    foreach ($items as $k => $v) {
        $js[] = '   	phStartAnimationBackground(".ph-swiper-slide-box-bg-'.$i.'", "animated '.$v->item_background_image_animation.'");';
        $i++;
    }
}
$js[] = '   });';




/* Start animation of items at start of slideshow */
if (!empty($items)) {
    $i = 0;
    foreach ($items as $k => $v) {
        $js[] = '      phStartAnimation(".ph-title-'.$i.'", "animated '.$v->item_title_animation.'");';
        $js[] = '      phStartAnimation(".ph-description-'.$i.'", "animated '.$v->item_description_animation.'");';
        $js[] = '      phStartAnimation(".ph-image1-'.$i.'", "animated '.$v->item_image1_animation.'");';
        $js[] = '      phStartAnimation(".ph-button-'.$i.'", "animated '.$v->item_button_animation.'");';
        $js[] = '       phStartAnimation(".ph-image2-'.$i.'", "animated '.$v->item_image2_animation.'");';

        if ($v->item_background_image_animation != '') {
            $js[] = '      phStartAnimationBackground(".ph-swiper-slide-box-bg-'.$i.'", "animated '.$v->item_background_image_animation.'");';
        }
        $i++;
    }
}


$js[] = '   });';


$document->addScriptDeclaration(implode("\n", $js));

require(JModuleHelper::getLayoutPath('mod_phocacarousel'));
?>
