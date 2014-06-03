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

      $allowed_sort_meta_keys = array(
        "bike_stolen_record_date_stolen" => true,
        "bike_frame_model"  => true,
        "bike_manufacturer_name"  => true,
      );

      if(isset($_GET['orderby']) && $allowed_sort_meta_keys[$_GET['orderby']] == true) {
        $args['orderby'] = $_GET['orderby'];
      }

      if(isset($_GET['order']) && ($_GET['order'] == 'desc' || $_GET['order'] == 'asc')) {
        $args['order'] = strtoupper($_GET['order']);
      }

      /* make a new query for the events */

      $wp_query = new WP_Query( $args );

    $i = 1;
    ?>


<table width="100%" cellpadding="2" cellspacing="1" border="0">
<tbody>

<tr class="bike-row-odd">
<td align="left" valign="top"><b><a href="?orderby=bike_stolen_record_date_stolen&amp;order=<?php echo (isset($_GET['orderby']) && $_GET['orderby'] == 'bike_stolen_record_date_stolen' && isset($_GET['order']) && $_GET['order'] =='desc') ? 'asc' : 'desc'; ?>">stolen date</a></b></td>
<td align="left" valign="top"><b><a href="?orderby=bike_frame_model&amp;order=<?php echo (isset($_GET['orderby']) && $_GET['orderby'] == 'bike_frame_model' && isset($_GET['order']) && $_GET['order'] =='desc') ? 'asc' : 'desc'; ?>">model</a> / <a href="?orderby=bike_manufacturer_name&amp;order=<?php echo ($_GET['orderby'] =='bike_manufacturer_name' && isset($_GET['orderby']) && $_GET['order'] =='desc' && isset($_GET['order'])) ? 'asc' : 'desc'; ?>">manufacturer name</a></b></td>
<td align="left" valign="top"><b>summary</b></td>
</tr>

<?php while ( have_posts() ) : the_post(); ?>
<?php include('archive-table-row.php'); ?>
<?php $counter++; endwhile; wp_reset_postdata(); ?>
    <?php $wp_query = $temp_query; ?>

</tbody></table>