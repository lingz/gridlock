<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress_Themes
 * @subpackage Gridlock
 */
?>

<div id="id-<?php the_ID(); ?>" <?php post_class(); ?> >
  <div class="row">
    <?php $span =  explode(".", get_post_meta( get_the_ID(), "gridlock", true))[1][1]; ?>
    <?php  if ($span == 1) { ?>
      <div class="article-image col-12">
        <?php the_post_thumbnail(); ?>
        This is the first
      </div>
    <?php } else if ($span == 2) { ?>
      <div class="article-image col-6">
        <?php the_post_thumbnail(); ?>
        The second
      </div>
      <div class="col-6">
      <a href=<?php the_permalink(); ?> title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'gridlock' ),  the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"<?php the_title(); ?> >
        <h4> <?php the_title(); ?> </h4>
      </a>
      <p> <?php the_excerpt(); ?></p>
      </div>
    <?php } else if ($span == 3) { ?>
      <div class="article-image col-4">
        <?php the_post_thumbnail(); ?>
        The third
      </div>
      <div class="col-8">
      <a href=<?php the_permalink(); ?> title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'gridlock' ),  the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"<?php the_title(); ?> >
        <h4> <?php the_title(); ?> </h4>
      </a>
      <p> <?php the_excerpt(); ?></p>
      </div>
    <?php } ?>

  </div>
</div>
