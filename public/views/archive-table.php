    <?php
    global $wp_query;
    global $post;
    global $iterate;
    $counter = 0;

    $temp_query = $wp_query;
      $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
      $args = array(

        'posts_per_page' => -1,
        'post_type' => 'bikeindex_bike',

     );

      /* make a new query for the events */

      $wp_query = new WP_Query( $args );

    $i = 1;
    ?>



<table class="bikeindex-list" cellpadding="2" cellspacing="1" border="0">
<thead>
<tr class="bike-row-odd">
<td align="left" class="mobile-hidden" valign="top"><b>Stolen Date</b></td>
<td align="left" class="mobile-hidden" valign="top"><b>Manufacturer Name</b></td>
<td align="left" class="mobile-hidden" valign="top"><b>Model</b></td>
<td align="left" valign="top"><b>Overview</b></td>
</tr>
</thead>
<tbody>

<?php while ( have_posts() ) : the_post(); ?>
<?php include('archive-table-row.php'); ?>
<?php $counter++; endwhile; wp_reset_postdata(); ?>
    <?php $wp_query = $temp_query; ?>

</tbody></table>