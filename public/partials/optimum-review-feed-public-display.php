<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       coffeemugvn.com
 * @since      1.0.0
 *
 * @package    Optimum_Review_Feed
 * @subpackage Optimum_Review_Feed/public/partials
 */
?>

<?php if ( isset($data) && is_array($data['reviews']) ) : ?>

<?php
    // process for shortcode wrapper
    $class = '';
    if ( $data['shortcode_wrapper'] ) {
        $class = ' opf-shortcode-wrapper';
    }
?>
<!-- Slider main container -->
<div class="swiper-container<?php echo $class; ?>">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">


    <?php foreach ( $data['reviews'] as $review ) : ?>
        <?php $logo_url = empty($review->review_url) ? 'javascript:void(0)' : urldecode( $review->review_url ); ?>

        <div class="swiper-slide">
            <a href="<?php echo $logo_url; ?>" target="_blank">
                <div class="review-logo" style="background-color: <?php echo $review->platform_button_color; ?>">
                        <img class="platform-logo" src="<?php echo $review->platform_logo_url; ?>">
                </div>
            </a>
            <div class="review-reviewer">
                <div class="reviewer-avatar">
                    <img src="<?php echo $review->review_reviewer_img; ?>">
                </div>
                <div class="reviewer-info">
                    <div class="reviewer-name"><?php echo $review->review_reviewer; ?></div>
                    <div class="reviewer-time"><?php echo $review->platform_name . ' ' . date('F j, Y', strtotime( $review->review_datetime ) ); ?></div>
                </div>
            </div>

            <div class="review-content">
                <div class="reviewer-desc">
                    <?php echo $review->review_desc; ?>

                    <?php if (! empty($review->review_url) && strpos($review->review_desc, ' ...') !== false ) :?>
                        <a target="_blank" href="<?php echo urldecode( $review->review_url ); ?>">more</a>
                    <?php endif; ?>
                </div>
            </div>  

            <?php 
                // Gen width (%) based on rating
                $width = $review->review_normalized_rating / 5 * 100; 
            ?>
            <div class="star-ratings-sprite"><span style="width:<?php echo $width; ?>%" class="star-ratings-sprite-rating"><?php echo $review->review_normalized_rating; ?></span></div>        

            <div class="review-footer">
                <a href="https://optimumfeedback.com/" target="_blank">
                <?php _e('Powered by','opf') ?> <img src="<?php echo plugins_url( '../img/optimum-feedback-logo.png', __FILE__ ); ?>" alt="<?php echo OPF_PLUGIN_NAME ?>">
                </a>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

<?php endif; ?>