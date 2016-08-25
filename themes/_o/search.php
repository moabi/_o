<?php
/*
Template Name: Search Page
*/
?>


<?php  get_header(); ?>
  <div id="page-wrapper" class="searchPage">
    <?php if(!is_front_page()): ?>
      <div id="page-header">
        <h1><?php _e( 'Search page', 'twentyfifteen' ); ?></h1>
      </div>
    <?php endif; ?>

    <div class="entry-content default-page inner-content">
      <div class="pure-g">
      <div class="post-content pure-u-1 pure-u-md-12-24">
        <h3><?php _e( 'Search results :', 'twentyfifteen' ); ?></h3>
        <ol>
        <?php while ( have_posts() ) : the_post(); ?>
         <li>
           <div class="pure-g">
             <div class="pure-u-1 pure-u-md-14-24">
           <h4><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h4>
         <p><?php echo get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true); ?></p>
             </div>
             <div class="pure-u-1 pure-u-md-10-24">
           <a class="btn-reg btn" href="<?php echo get_permalink(); ?>"><?php _e( 'Show page', 'twentyfifteen' ); ?></a>
               </div>
             </div>
         </li>

        <?php endwhile; ?>
        </ol>
        <?php
        global $query_string;

        $query_args = explode("&", $query_string);
        $search_query = array();

        foreach($query_args as $key => $string) {
          $query_split = explode("=", $string);
          $search_query[$query_split[0]] = urldecode($query_split[1]);
        } // foreach

        $search = new WP_Query($search_query);
        ?>
      </div>
        <div class="post-content pure-u-1 pure-u-md-12-24">
          <h3><?php _e( 'Please type your query here :', 'twentyfifteen' ); ?></h3>
          <?php get_search_form( true ); ?>
        </div>
      </div>

    </div>
  </div>
  <div class="clearfix"></div>

<?php get_footer(); ?>

