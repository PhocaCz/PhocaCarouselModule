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

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die('Restricted access');// no direct access

$app 						= Factory::getApplication();
$document 					= Factory::getDocument();
$wa                         = $app->getDocument()->getWebAssetManager();


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
$p['background_video'] 		= $params->get( 'background_video', '');
$p['display_view'] 			= $params->get( 'display_view', '');
$p['display_option'] 		= $params->get( 'display_option', '');
$p['display_id'] 			= $params->get( 'display_id', '');
$p['display_min_width'] 	= $params->get( 'display_min_width', 0);
$p['load_swiper_library']	= $params->get( 'load_swiper_library', 1 );
$p['load_animate_library']	= $params->get( 'load_animate_library', 1 );
$p['background_image_position'] 	= $params->get( 'background_image_position', 'center');

$p['transition_effect']                = $params->get( 'transition_effect', 'slide');// slide, fade, flip, cube, coverFlow
$p['effect_options']        = '';
if ($p['transition_effect'] == 'slide') {
    $p['effect_options']        = '';
} else if ($p['transition_effect'] == 'fade') {
    $p['effect_options']        = 'fadeEffect: {crossFade: true}';
} else if ($p['transition_effect'] == 'flip') {
    $p['effect_options']        = 'flipEffect: {slideShadows: false}';
} else if ($p['transition_effect'] == 'cube') {
    $p['effect_options']        = 'flipEffect: {slideShadows: false}';
}
 else if ($p['transition_effect'] == 'coverFlow') {
    $p['effect_options']        = 'coverflowEffect: {rotate: 30, slideShadows: false}';

}

$view 						= $app->input->get('view', '');
$option 					= $app->input->get('option', '');
$idCom						= $app->input->get('id', '');

$optionA 	= array_map('trim', explode(',', $p['display_option']));// Remove spaces
$viewA 		= array_map('trim', explode(',', $p['display_view']));
$idA 		= array_map('trim', explode(',', $p['display_id']));
$optionA	= array_filter($optionA);// Remove empty values from array
$viewA 		= array_filter($viewA);
$idA 		= array_filter($idA);



if (empty($optionA) && empty($viewA) && empty($idA)) {
	// OK - all parameters are not set
} else if (!empty($optionA) && in_array($option, $optionA) && empty($viewA) && empty($idA)) {
	// OK - only option is set and it meets the criteria
} else if (!empty($optionA) && in_array($option, $optionA) && !empty($viewA) && in_array($view, $viewA) && empty($idA)) {
	// OK - option and view is set and it meets the criteria
} else if (!empty($optionA) && in_array($option, $optionA) && !empty($viewA) && in_array($view, $viewA) && !empty($idA) && in_array($idCom, $idA) ) {
	// OK - option and view and ID is set and it meets the criteria
} else {
	return '';
}


$items = (array)$params->get('items');




$path = array();
$path['image'] = Uri::base();// . '/';
$path['media'] = Uri::base();

/*$rand = rand ( 10000 , 99999 );
$id = 'ph-mod-phoca-carousel-'.$rand;
$idJs = 'pS'.$rand;*/

$moduleclass_sfx 			= htmlspecialchars((string)$params->get('moduleclass_sfx', ''), ENT_COMPAT, 'UTF-8');

$id = 'phPhocaCarouselModule'.$module->id;
$uniqueId = 'phPhocaCarouselModuleSwiperContainer'.$module->id;
$idJs = 'phPhocaCarouselSwiper'.$module->id;

$s = array();
// STYLE
if (!empty($items)) {

    $i = 0;
    foreach ($items as $k => $v) {

        if (isset($v->item_background_image) && $v->item_background_image != '') {

            //$s[] = '#'.$id.' .ph-swiper-slide-box-'.$i.' {';
            //$s[] = '    position:relative;';
            //$s[] = '    overflow: hidden;';
            //$s[] = '}';

            //$s[] = '#'.$id.' .ph-swiper-slide-box-'.$i.' {';
			$s[] = '#'.$id.' .ph-swiper-slide-box-bg-'.$i.' {';
            $s[] = '    background-image:url('.$path['image'].$v->item_background_image.');';
            $s[] = '    -webkit-background-size: cover;';
            $s[] = '    background-size: cover;';
            $s[] = '    background-position: '.htmlspecialchars(strip_tags($p['background_image_position'])).';';
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

    $s[] = '#'.$id.' .swiper-container.'.$uniqueId.' .swiper {';
    $s[] = '    width: 100%;';
    if ($p['fill_rest_page'] == 0) {
        $s[] = '    height: '.$p['height'].';';
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
    $s[] = '    background-position: '.htmlspecialchars(strip_tags($p['background_image_position'])).';';
    if($p['background_image'] != '') {
        $s[] = '    background-image:url(' . $path['image'] . $p['background_image'] . ');';
    }
    $s[] = '}';


	$s[] = '#'.$id.' .ph-video-bg {';
	$s[] = '    position: absolute;';
	$s[] = '    width: 100%;';
	$s[] = '    object-fit: cover;';
	$s[] = '    right: 0;';
	$s[] = '    left: 0;';
	$s[] = '    overflow: hidden;';
	if ($p['fill_rest_page'] == 0) {
        $s[] = '    height: '.$p['height'].';';
    }
	$s[] = '}';



}

$document->addCustomTag( "\n<style type=\"text/css\">\n\n" . implode("\n", $s) ."\n</style>\n\n");

HTMLHelper::_('jquery.framework', false);
//$document->addScript(Uri::root(true) . '/media/mod_phocacarousel/js/swiper.min.js');
//JHTML::stylesheet( 'media/mod_phocacarousel/css/animate.min.css' );
//JHTML::stylesheet( 'media/mod_phocacarousel/css/swiper.min.css' );
//JHTML::stylesheet( 'media/mod_phocacarousel/css/style.css' );

if ($p['load_animate_library'] == 1) {
    $wa->registerAndUseStyle('mod_phocacarousel.animate-css', 'media/mod_phocacarousel/css/animate.min.css', array('version' => 'auto'));
}
if ($p['load_swiper_library'] == 1) {
    $wa->registerAndUseStyle('mod_phocacarousel.swiper-css', 'media/mod_phocacarousel/css/swiper.min.css', array('version' => 'auto'));
    $wa->registerAndUseScript('mod_phocacarousel.swiper-js', 'media/mod_phocacarousel/js/swiper.min.js', array('version' => 'auto'), ['defer' => true]);
}
//$wa->registerAndUseStyle('mod_phocacarousel.style-css', 'media/mod_phocacarousel/css/style.css', array('version' => 'auto'));


$js = array();

//$js[] = ' jQuery( document ).ready(function() {';
$js[] = ' ';
$js[] = '   function phReset($elem) {';
$js[] = '       $elem.before($elem.clone(true));';
$js[] = '     	var $newElem = $elem.prev();';
$js[] = '     	$elem.remove();';
$js[] = '     	return $newElem;';
$js[] = ' 	}';

$js[] = '   function phStartAnimation(item, aClass) {';
$js[] = '       var a = jQuery(".swiper-slide-active " + item);';
$js[] = '       var p = jQuery(".swiper-slide-prev " + item);';
$js[] = '       var n = jQuery(".swiper-slide-next " + item);';

$js[] = '       a.removeClass(aClass);';
$js[] = '       a = phReset(a);';
$js[] = '       a.addClass(aClass);';
$js[] = '       a.show();';

$js[] = '       p.hide();';
$js[] = '       n.hide();';
$js[] = '   }';

$js[] = '   function phStartAnimationBackground(item, aClass) {';
$js[] = '   	var a = jQuery(".swiper-slide-active " + item);';
$js[] = '   	a.removeClass(aClass);';
$js[] = '   	a = phReset(a);';
$js[] = '   	a.addClass(aClass);';
$js[] = '   }';


//$js[] = '   var swiper'.$idJs.' = Swiper;';
$js[] = '   var init'.$idJs.' = false;';

$js[] = '   function phSwiperMode'.$idJs.'() {';
$js[] = '      var minWidth = window.matchMedia(\'(min-width: '.(int)$p['display_min_width'].'px)\');';

$js[] = '     if(minWidth.matches) {';
$js[] = '        if (!init'.$idJs.') {';
$js[] = '           init'.$idJs.' = true;';
$js[] = '           jQuery("#'.$id.' .swiper-container.'.$uniqueId.' .swiper").show();';

$js[] = '   		const swiper'.$idJs.' = new Swiper(jQuery(".'.$uniqueId.' .swiper")[0], {';
$js[] = '   			speed: '.(int)$p['animation_speed'].',';



$js[] = '   			effect: \''.$p['transition_effect'].'\',';
if ($p['effect_options'] != '') {
    $js[] = '   			'.$p['effect_options'].',';
}

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
$js[] = '   		});';


if($p['fill_rest_page'] > 0) {
    //$js[] = '   var phSwiperTop 		= jQuery(".swiper-container.'.$uniqueId.' .swiper").position().top;';
    $js[] = '   var phSwiperTop 		= jQuery("#'.$id.'").offset().top;';
    $js[] = '   var phSwiperWidth		= jQuery(".swiper-container.'.$uniqueId.' .swiper").width();';
    $js[] = '   var phWindowHeight		= jQuery(window).height();';

	$js[] = '   var phWindowHeightRatio	= 1;';
	if($p['fill_rest_page'] == 2) {
		$js[] = '   var phWindowHeightRatio	= 0.5';
	} else if ($p['fill_rest_page'] == 3) {
		$js[] = '   var phWindowHeightRatio	= 0.67';
	} else if ($p['fill_rest_page'] == 4) {
		$js[] = '   var phWindowHeightRatio	= 0.75';
	}

    $js[] = '   var phRestPageHeight	= (phWindowHeight - phSwiperTop) * phWindowHeightRatio;';
    $js[] = '   if (phSwiperWidth < phRestPageHeight) {';
    if ($p['fill_rest_page_ratio'] > 0) {
        $js[] = '       phRestPageHeight = phSwiperWidth/'.$p['fill_rest_page_ratio'].';';
    } else {
        $js[] = '       phRestPageHeight = phSwiperWidth;';
    }
    $js[] = '   }';
    $js[] = '   jQuery(".swiper-container.'.$uniqueId.' .swiper").height(phRestPageHeight);';
	$js[] = '   jQuery(".ph-video-bg").height(phRestPageHeight);';
}

/* Start animation of items when transition of slide ends*/
$js[] = '   swiper'.$idJs.'.on("transitionEnd", function () {';
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

$js[] = '   swiper'.$idJs.'.on("transitionStart", function () {';
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


$js[] = '        }';// end Init
$js[] = '     } else {'; // end minWidth.matches

$js[] = '        if(typeof swiper'.$idJs.' !== \'undefined\' && typeof swiper'.$idJs.'.destroy === \'function\'){';
$js[] = '           swiper'.$idJs.'.destroy();';
$js[] = '           swiper'.$idJs.' = undefined;';
$js[] = '        }';
$js[] = '        jQuery("#'.$id.' .swiper-container.'.$uniqueId.' .swiper").hide();';
$js[] = '        init'.$idJs.' = false;';
$js[] = '     }';// end minWidth.matches

$js[] = '  }';// end phSwiperMode

/*
$js[] = ' jQuery( document ).ready(function() {';
$js[] = '  window.addEventListener(\'load\', function() {';
$js[] = '      phSwiperMode'.$idJs.'();';
$js[] = '  });';

$js[] = '  window.addEventListener(\'resize\', function() {';
$js[] = '      phSwiperMode'.$idJs.'();';
$js[] = '  });';

// Disable on small screen, disable when the width is smaller than
//if ((int)$p['hide_small_displays'] > 0) {
//	$js[] = '  } else {';
//	$js[] = '  	  jQuery(".swiper-container.'.$uniqueId.' .swiper").hide();';
//	$js[] = '  }';
//}


$js[] = '   });';// document ready
*/

// Start on document ready/window load
$js[] = ' jQuery(window).on(\'load\', function() {';
$js[] = '      phSwiperMode' . $idJs . '();';
$js[] = '   });';// document ready/window load

//$js[] = ' jQuery( document ).ready(function() {';
//$js[] = '   });';// document ready/window load


//$document->addScriptDeclaration(implode("\n", $js));
$wa->addInlineScript(implode("\n", $js), ['version' => 'auto'], ['type' => 'module']);//, ['defer' => true]

require ModuleHelper::getLayoutPath('mod_phocacarousel', $params->get('layout', 'default'));
?>
