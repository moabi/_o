<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<header class="entry-header">
			
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<span> <?php the_field('prix'); ?> euros/personne</span>
			
			
		</header><!-- .entry-header -->
<div class="clearfix"></div>
<div class="pure-g">
	<div class="pure-u-1 pure-u-md-1-2">
		<?php the_post_thumbnail(); ?>
		<?php
 $postID = $the_query->post->ID;
                $price = get_field('prix');
                $personnes = get_field('personnes');
                $budget_min = get_field('budget_min');
                $budget_max = get_field('budget_max');
                $budgMin = $budget_min * $personnes;
                $budgMax = $budget_max * $personnes;
                $theme = get_field('theme');
                $lieu = get_field('lieu');
                $rows = get_field('votre_sejour');
                $row_count = count($rows);
                $lastDay = 86400 * $row_count;
                $departure_date = date("d/m/Y", time()+$lastDay); 

                $arrival_date = date("d/m/Y", time()+86400); 
                
					
					$activityObj = 1;
					$dayTrip = '{';
					if( have_rows('votre_sejour') ):
					    while ( have_rows('votre_sejour') ) : the_row();
					    	$calcDay = 86400 * $activityObj;
					    	$actual_date = date("d/m/Y", time()+$calcDay); 
					    	$dayTrip .= '"'.$actual_date.'" : {';
							if( have_rows('activites') ):
					        while ( have_rows('activites') ) : the_row();
					        	$activityArr = get_sub_field('activite');
					        	$i = 0;
								$len = count($activityArr);
					        	foreach($activityArr as $data){
									$field = get_field('prix', $data->ID);
									$comma = ($i == $len - 1) ? '' : ',';
						        	$dayTrip .= '"'.$data->ID.'": { "name" : "'.$data->post_title.'","price": '.$field.'}'.$comma;
						        	$i++;
					        	}
					        endwhile;
					        endif;
							$dayTrip .= '},';
							$activityObj++;
					    endwhile;
					endif;
					$dayTrip .= '}';

					
			?>
	</div>
	<div class="pure-u-1 pure-u-md-1-2">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?>
		
		</div>
</div>
<div class="clearfix"></div>
<div class="comprend">
	
	<h2>La prestation comprend</h2>
	<?php the_field('la_prestation_comprend'); ?>
	<?php
	$images = get_field('gallerie');

if( $images ): ?>
<div class="clearfix"></div>
        <ul class="slick-multi">
            <?php foreach( $images as $image ): ?>
                <li>
                    <img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
                    
                </li>
            <?php endforeach; ?>
        </ul>


<?php endif; ?>
</div>
		



	</article><!-- #post -->
