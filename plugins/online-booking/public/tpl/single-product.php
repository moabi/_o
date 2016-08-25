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
global $post;
?>
<?php if (has_post_thumbnail($post->ID)): ?>
    <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID)); ?>
    <div id="custom-bg" style="background-image: url('<?php echo $image[0]; ?>')">
    </div>
<?php endif; ?>
<!-- SINGLE RESERVATION -->
<div class="pure-g inner-content">
    <div id="primary-b" class="site-content single-animations pure-u-1 ">
        <div id="content" role="main">

            <?php while (have_posts()) :
            the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="entry-title"><?php the_title(); ?></h1>

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
                            <?php echo $ux->get_place($post->ID); ?>

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
                                        echo 'Pour : <strong>' . get_field('nombre_de_personnes') . ' personne</strong>';
                                    } else {
                                        echo 'Jusqu’à : <strong>' . get_field('nombre_de_personnes') . ' personnes</strong>';
                                    } ?>

                                </div>
                            <?php endif; ?>


                            <div class="pure-u-1">
                                <i class="fa fa-tag"></i>
                                <?php
                                if (get_field('prix') == 0) {
                                    echo 'Tarif : <strong>gratuit !</strong>';
                                } else {
                                    echo 'Tarif : <strong>' . get_field('prix') . '€ / pers</strong>';
                                }
                                ?>

                            </div>


                            <?php echo $ux->single_reservation_btn($post->ID); ?>
                            <?php //echo $ux->get_theme_terms($post->ID); ?>
                        </div>
                        <!-- #DETAILS -->
                    </div>


                </div><!-- pure -->

        </div>

        <div id="main-content">

            <div id="middle-bar" class="pure-g">
                <div class="pure-u-md-15-24">
                    <!-- NAVIGATION -->
                    <div class="pure-g" id="single-tabber">
                        <div class="pure-u-1-3 active">
                            <a href="#" class="tabsto" data-target="0">
                                <div class="fs1" aria-hidden="true" data-icon=""></div>
                                <?php _e('Description', 'online-booking'); ?>
                            </a>
                        </div>

                        <?php if (get_field('infos_pratiques')): ?>
                            <div class="pure-u-1-3">
                                <a href="#" class="tabsto" data-target="1">
                                    <div class="fs1" aria-hidden="true" data-icon=""></div>
                                    <?php _e('Informations pratiques', 'online-booking'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if (get_field('lieu')): ?>
                            <div class="pure-u-1-3">
                                <a href="#" class="tabsto" data-target="2">
                                    <div class="fs1" aria-hidden="true" data-icon=""></div>
                                    <?php _e('Lieu', 'online-booking'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (get_field('responsable')): ?>
                            <div class="pure-u-1-3">
                                <a href="#" class="tabsto" data-target="3">
                                    <div class="fs1" aria-hidden="true" data-icon=""></div>
                                    <?php _e('Responsable', 'online-booking'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
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
                        <?php the_field('lieu'); ?>
                    </div>
                <?php endif; ?>

                <?php if (get_field('responsable')): ?>
                    <div class="single-el">
                        <?php //var_dump(get_field('responsable')); ?>
                        <?php
                        echo '<ul>';
                        $user_partner = get_field('responsable');
                        $user_partner_ID = $user_partner['ID'];
                        echo '<li>Nom : ' . $user_partner['user_firstname'] . ' ' . $user_partner['user_lastname'] . '</li>';
                        echo '<li>Site web : ' . $user_partner['user_url'] . '</li>';
                        echo '<li>Téléphone : ' . get_field('telephone', 'user_' . $user_partner_ID) . ' <br />Fax : ' . get_field('fax', 'user_' . $user_partner_ID) . '</li>';
                        echo '<li>' . get_field('ville', 'user_' . $user_partner_ID) . ', ' . get_field('code_postal', 'user_' . $user_partner_ID) . '<br /> ' . get_field('pays', 'user_' . $user_partner_ID) . '</li>';
                        echo '</ul>';
                        ?>
                    </div>
                <?php endif; ?>

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

                    $('.tabsto').on('click', function () {
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
            echo $term_lieu[0]->name;
            ?> /
            <?php
            echo $term_theme[0]->name;
            ?>
        </em>
    </h2>
    <div id="activities-content" class="blocks related-activities slick-multi">
        <?php

        //var_dump($term_lieu[0]->slug);
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 4,
            'orderby' => 'rand',
            'post__not_in' => array($post->ID),
            'tax_query' => array(
                array(
                    'taxonomy' => 'theme',
                    'field' => 'slug',
                    'terms' => $term_theme[0]->slug,
                ),
                array(
                    'taxonomy' => 'lieu',
                    'field' => 'slug',
                    'terms' => $term_lieu[0]->slug,
                ),
            ),
        );
        $obpp = new Online_Booking_Public('online-booking', 1);
        echo $obpp->get_reservation_content($args, $term_reservation_type[0]->slug, $term_reservation_type[0]->name, 0, false);
        ?>
    </div>

    </article><!-- #post -->

    <?php endwhile; // end of the loop. ?>

</div><!-- #content -->
</div><!-- #primary -->
</div>
<?php get_footer(); ?>
