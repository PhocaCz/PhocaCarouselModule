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
defined('_JEXEC') or die('Restricted access');

?>
<div id="<?php echo $id ?>" class="ph-carousel-module-box <?php echo $moduleclass_sfx ;?>">

    <?php if ($p['background_image'] == '' && $p['background_video'] != '') { ?>
        <video autoplay muted loop id="ModPhocaCarouselVideo" class="ph-video-bg">
            <source src="<?php echo $path['media'] . $p['background_video'] ?>" type="video/mp4">
        </video>
    <?php } ?>
    <div class="swiper-container <?php echo $uniqueId; //ph-module-swiper-container ?> ph-module-swiper-container">

        <div class="swiper">
            <div class="parallax-bg" data-swiper-parallax="-23%"></div>
            <div class="swiper-wrapper">
                <?php
                if (!empty($items)) {
                    $i = 0;

                    foreach ($items as $k => $v) {
                        ?>
                        <div class="swiper-slide ph-swiper-slide-box-<?php echo $i ?>">
                            <div class="swiper-slide ph-swiper-slide-box-bg-<?php echo $i ?>"></div>
                            <?php if ($v->item_title != '') { ?>
                                <div class="ph-title-<?php echo $i ?>" data-swiper-parallax="-300"><?php echo $v->item_title ?></div><?php } ?>
                            <?php if ($v->item_description != '') { ?>
                                <div class="ph-description-<?php echo $i ?>" data-swiper-parallax="-300"><?php echo $v->item_description ?></div><?php } ?>
                            <?php /* <div class="ph-box" data-swiper-parallax="-300">&nbsp;</div> */ ?>
                            <?php if ($v->item_image1 != '') { ?>
                                <div class="ph-image1-<?php echo $i ?>" data-swiper-parallax="-100"><img src="<?php echo $path['image'] . $v->item_image1 ?>" alt=""/></div><?php } ?>
                            <?php if ($v->item_button_title != '') { ?>
                                <?php if ($v->item_button_link != '') { ?><a href="<?php echo $v->item_button_link ?>"><?php } ?>
                                <div class="ph-button-<?php echo $i ?>" data-swiper-parallax="-100">
                                    <div class="ph-button-text ph-button-text-<?php echo $i ?>"><?php echo $v->item_button_title ?></div>
                                </div>
                                <?php if ($v->item_button_link != '') { ?></a><?php } ?>
                            <?php } ?>
                            <?php if ($v->item_image2 != '') { ?>
                                <div class="ph-image2-<?php echo $i ?>" data-swiper-parallax="-100"><img src="<?php echo $path['image'] . $v->item_image2 ?>" alt=""/></div><?php } ?>

                        </div>
                        <?php
                        $i++;
                    } ?>
                <?php } ?>

            </div>

            <?php if ($p['dots'] == 1) { ?>
                <div class="swiper-pagination swiper-pagination-white"></div>
            <?php } ?> &nbsp;<?php /* &nbsp; is here because of IE 11 */ ?>
            <?php if ($p['nav'] == 1) { ?>
                <div class="swiper-button-prev swiper-button-white"></div>
                <div class="swiper-button-next swiper-button-white"></div>
            <?php } ?>
        </div>
    </div>
</div>

