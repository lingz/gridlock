<?php
/**
 * The main template file.
 *
 * @package WordPress_Themes
 * @subpackage Gridlock
 */

get_header(); ?>

    <?php
      $meta_query = new WP_Query(array('showposts' => 5, 'orderby' => 'meta_value', 'meta_key' => 'gridlock', 'order' => 'ASC' ));
      $old_row = 0;
      while ( $meta_query->have_posts() ) : $meta_query->the_post(); 
            // get the grid positioning
            // Exmaple input is 32.12, meaning row 32, index starting at 1, spanning 2
            $gridlock =  explode(".", get_post_meta( get_the_ID(), "gridlock", true)); 
            $row = $gridlock[0]; 
            $index = $gridlock[1][0]; 
            $span = $gridlock[1][1];
            if ($old_row != $row) {
              // start a new row if the rows don't match
              echo "<div class='row'>";
              $old_row = $row;
            }
          ?>
          <div class="post-container col-12 
          <?php
            // opening the span tag
            switch ($span) {
            case "1":
              echo "col-sm-4 col-lg-4 small-article";
              break;
            case "2":
              echo "col-sm-8 col-lg-8 medium-article";
              break;
            case "3":
              echo "col-sm12 col-lg-12 large-article";
              break;
            }
          ?>
          ">
          <?php get_template_part( 'content', get_post_format() ); 
          // closing the column tag
          ?>
          </div>
          <?php
            //get_template_part( 'content', get_post_format() );

            if ($index + $span == 4) {
              // closing the row if the post finishes it
              echo "</div>";
            }
        endwhile; 
        ?>
<?php get_footer(); ?>
