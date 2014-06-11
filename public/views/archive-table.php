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

      if(isset($_GET['bike_orderby']) && $allowed_sort_meta_keys[$_GET['bike_orderby']]) {
        $args['orderby'] = 'meta_value';
        $args['meta_key'] = $_GET['bike_orderby'];
      } else {
        $args['orderby'] = 'meta_value';
        $args['meta_key'] = 'bike_stolen_record_date_stolen';
      }

      if(isset($_GET['bike_order']) && ($_GET['bike_order'] == 'desc' || $_GET['bike_order'] == 'asc')) {
        $args['order'] = strtoupper($_GET['bike_order']);
      } else {
        $args['order'] = "DESC";
      }

      /* make a new query for the events */

      $wp_query = new WP_Query( $args );

    $i = 1;
    ?>

<form method="GET" class="mobile-visible">
<select name="bike_orderby">
  <option value="bike_stolen_record_date_stolen" <?php if(isset($_GET['bike_orderby']) && $_GET['bike_orderby'] == 'bike_stolen_record_date_stolen') echo " selected='selected' "; ?>>Date Stolen</option>
  <option value="bike_manufacturer_name" <?php if(isset($_GET['bike_orderby']) && $_GET['bike_orderby'] == 'bike_manufacturer_name') echo " selected='selected' "; ?>>Manufacturer Name</option>
  <option value="bike_frame_model" <?php if(isset($_GET['bike_orderby']) && $_GET['bike_orderby'] == 'bike_frame_model') echo " selected='selected' "; ?>>Model</option>
</select>
<select name="bike_order">
  <option value="desc" <?php if(isset($_GET['bike_order']) && $_GET['bike_order'] == 'desc') echo " selected='selected' "; ?>>Descending</option>
  <option value="asc" <?php if(isset($_GET['bike_order']) && $_GET['bike_order'] == 'asc') echo " selected='selected' "; ?>>Ascending</option>
</select>
<input type="submit" value="Filter">
</form>



<table width="100%" cellpadding="2" cellspacing="1" border="0">
<tbody>

<tr class="bike-row-odd">
<td align="left" class="mobile-hidden" valign="top"><b><a href="?bike_orderby=bike_stolen_record_date_stolen&amp;bike_order=<?php echo (isset($_GET['bike_orderby']) && $_GET['bike_orderby'] == 'bike_stolen_record_date_stolen' && isset($_GET['bike_order']) && $_GET['bike_order'] =='asc') ? 'desc' : 'asc'; ?>">Stolen Date</a></b></td>
<td align="left" class="mobile-hidden" valign="top"><b><a href="?bike_orderby=bike_manufacturer_name&amp;bike_order=<?php echo ($_GET['bike_orderby'] =='bike_manufacturer_name' && isset($_GET['bike_orderby']) && $_GET['bike_order'] =='asc' && isset($_GET['bike_order'])) ? 'desc' : 'asc'; ?>">Manufacturer Name</a></b></td>
<td align="left" class="mobile-hidden" valign="top"><b><a href="?bike_orderby=bike_frame_model&amp;bike_order=<?php echo (isset($_GET['bike_orderby']) && $_GET['bike_orderby'] == 'bike_frame_model' && isset($_GET['bike_order']) && $_GET['bike_order'] =='asc') ? 'desc' : 'asc'; ?>">Model</a></b></td>
<td align="left" valign="top"><b>Overview</b></td>
</tr>

<?php while ( have_posts() ) : the_post(); ?>
<?php include('archive-table-row.php'); ?>
<?php $counter++; endwhile; wp_reset_postdata(); ?>
    <?php $wp_query = $temp_query; ?>

</tbody></table>