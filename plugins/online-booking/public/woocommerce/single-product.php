<?php
/**
 * The Template for displaying animations post.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

<?php
$ux = new online_booking_ux;
$obpp = new Online_Booking_Public('online-booking', 1);
global $post;
$_product = wc_get_product( $post->ID );
$price = $_product->get_price();
?>
<?php if (has_post_thumbnail($post->ID)): ?>
    <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID)); ?>
    <div id="custom-bg" style="background-image: url('<?php echo $image[0]; ?>')"></div>
<?php endif; ?>
<!-- SINGLE RESERVATION -->
<div class="pure-g inner-content">
    <div id="primary-b" class="site-content single-animations pure-u-1 woocommerce-single-product">
        <div id="content" role="main">

            <?php while (have_posts()) :
            the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h2 class="entry-title"><?php the_title(); ?></h2>

                <div class="clearfix"></div>
                <div class="pure-g">

                    <!-- SLIDER -->
                    <div id="activity-gallery" class="pure-u-1 pure-u-md-7-12">
                        <?php echo $ux->slider(); ?>
                    </div><!-- #activity -->
                    <!-- #SLIDER -->

                    <div id="single-top-information" class="pure-u-1 pure-u-md-5-12">
                        <!-- DETAILS -->
                        <div class="box-price">
                          <div class="pure-u-1">
                             <?php echo $ux->get_place($post->ID); ?>
                          </div>
                            <?php if (get_field('duree')): ?>
                                <div class="pure-u-1">
                                    <i class="fa fa-clock-o"></i>
                                    <?php
                                    $time = get_field('duree');
                                    $time_s = get_field('duree-s');
                                    if (is_int($time)) {

                                        echo $time . ' ' . $time_s;

                                    } else {

                                        $timeduration = explode('.', $time);
                                        $mn = (isset($timeduration[1])) ? $timeduration[1] : '';
                                        $duree = $timeduration[0] . ' ' . $time_s . ' ' . $mn;
                                    }
                                    ?>
                                    Durée : <strong><?php echo $ux->get_activity_time(); ?></strong>
                                </div>
                            <?php endif; ?>
                            <?php if (get_field('nombre_de_personnes', $post->ID)): ?>
                                <div class="pure-u-1">
                                    <i class="fa fa-users"></i>
                                    <?php
                                    if (get_field('nombre_de_personnes') == 1) {
                                        echo 'Pour : <strong>' . get_field('nombre_de_personnes') . '</strong> <b>personne</b>';
                                    } else {
                                        echo 'Jusqu’à : <strong>' . get_field('nombre_de_personnes') . '</strong> <b>personnes</b>';
                                    } ?>

                                </div>
                            <?php endif; ?>
                            <div class="pure-u-1">
                                <i class="fa fa-tag"></i>
                                <?php
                                if ($price == 0) {
                                    echo 'Tarif : <strong>gratuit !</strong>';
                                } else {
                                    echo 'Tarif : <strong>' . $price . '€ / pers</strong>';
                                }
                                ?>

                            </div>
                            <?php echo $ux->single_reservation_btn($post->ID); ?>
                            <?php //echo $ux->get_theme_terms($post->ID); ?>
                        </div>
                        <!-- #DETAILS -->
                    </div>


                </div><!-- pure -->
            </div><!-- #post -->



        <div id="main-content">

            <div id="middle-bar" class="pure-g">
                <div class="pure-u-md-15-24">
                    <!-- NAVIGATION -->
                    <div class="pure-g" id="single-tabber">
                        <div class="pure-u-1-4 active">
                            <a href="#" class="tabsto" data-target="0">
                                <i class="fa fa-file-text" aria-hidden="true"></i>
                                <?php _e('Description', 'online-booking'); ?>
                            </a>
                        </div>

                        <?php if (get_field('infos_pratiques')): ?>
                            <div class="pure-u-1-4">
                                <a href="#" class="tabsto" data-target="1">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <?php _e('Infos', 'online-booking'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if (get_field('lieu')): ?>
                            <div class="pure-u-1-4">
                                <a href="#" class="tabsto" data-target="2">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    <?php _e('Lieu', 'online-booking'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                            <div class="pure-u-1-4">
                                <a href="#" class="tabsto" data-target="3">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <?php _e('Responsable', 'online-booking'); ?>
                                </a>
                            </div>

                    </div>

                </div>
                <div class="pure-u-md-9-24">
                    <?php echo $ux->socialShare(); ?>
                </div>
            </div>
            <!-- TABS -->
            <div id="tabs-single" class="slick-single">

                <div class="single-el">
                    <div class="comprend">
                        <div id="animation-text">

                            <?php
                            if (get_the_content()) {
                                the_content();
                            } else {
                                _e('Description non disponible', 'online-booking');
                            }

                            ?>

                        </div>
                    </div>
                </div>

                <?php if (get_field('infos_pratiques')): ?>
                    <div class="single-el">
                        <?php the_field('infos_pratiques'); ?>
                    </div>
                <?php endif; ?>

                <?php if (get_field('lieu')): ?>
                    <div class="single-el">
                        <?php
                        //descriptive field of the place -- string
                        the_field('lieu'); ?>
                    </div>
                <?php endif; ?>


                    <div id="tab-single-responsable" class="single-el">
                    </div>


            </div>

            <script type="text/javascript">
                jQuery(function () {
                    var $ = jQuery;
                    $('.slick-single').slick({
                        dots: false,
                        arrows: false,
                        infinite: true,
                        speed: 500,
                        slidesToShow: 1,
                        slidesToScroll: 1
                    });

                    $('.tabsto').on('click', function (e) {
                        e.preventDefault();
                        $target = $(this).attr('data-target');
                        $(this).parent().addClass('active').siblings().removeClass('active');
                        $('.slick-single').slick('slickGoTo', $target);
                    });
                })
            </script>
            <!-- #tabs -->
        </div>
    </div>


    <?php
    //related post variables
    /**
     * TODO:fix $term_theme && $term_lieu if empty
     */

    $single_lieu = $ux->get_place($post->ID);
    $term_theme = wp_get_post_terms($post->ID, 'theme');
    $term_lieu = wp_get_post_terms($post->ID, 'lieu');
    $term_reservation_type = wp_get_post_terms($post->ID, 'reservation_type');
?>
    <h2 class="related-title">
         <i class="fa fa-heart"></i>
        <?php _e('Vous aimerez également', 'online-booking'); ?>
        <em class="tags">
            <?php
            if(isset($term_lieu[0])){
                echo $term_lieu[0]->name;
            }

            ?> /
            <?php
            if(isset($term_theme[0])) {
                echo $term_theme[0]->name;
            }
            ?>
        </em>
    </h2>
    <div id="activities-content" class="blocks related-activities slick-multi">
        <?php
        /**
         * Text if taxonomies exist to filter
         */
        $theme_tax = (isset($term_theme[0])) ? array(
            'taxonomy' => 'theme',
            'field' => 'slug',
            'terms' => $term_theme[0]->slug,
        ) : array();

        $lieu_tax  = (isset($term_theme[0])) ?array(
            'taxonomy' => 'lieu',
            'field' => 'slug',
            'terms' => $term_theme[0]->slug,
        ) : array();
            //var_dump($term_lieu[0]->slug);
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 4,
            'orderby' => 'rand',
            'post__not_in' => array($post->ID),
            'tax_query' => array(
                $theme_tax,
                $lieu_tax,
            ),
        );

        echo $obpp->get_reservation_content($args, $term_reservation_type[0]->slug, $term_reservation_type[0]->name, 0, false);
        ?>
    </div>


    <?php endwhile; // end of the loop. ?>

</div><!-- #content -->
    </div>

<?php get_footer(); ?>
