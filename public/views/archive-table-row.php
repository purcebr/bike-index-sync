<?php global $post;?>
<?php global $iterate; ?>
<tr class="<?php echo ($counter % 2 == 0) ? "bike-row-even" : "bike-row-odd"; ?>">
	<td align="left" valign="top"><a style="text-decoration: none;" href="<?php echo get_permalink(); ?>"><?php echo (get_post_meta($post->ID, 'bike_stolen_record_date_stolen', true) != "") ? $this->bikeindex_formatted_date(get_post_meta($post->ID, 'bike_stolen_record_date_stolen', true)) : "Not Available"; ?></a></td>
	<td align="left" valign="top"><a style="text-decoration: none;" href="<?php echo get_permalink(); ?>">
		<?php $bike_frame_model = get_post_meta($post->ID, 'bike_frame_model', true); ?>
		<?php if(isset($bike_frame_model) && $bike_frame_model != ''): ?>
		<?php echo $bike_frame_model; ?></a>
		<?php endif; ?>
		<?php $bike_manufacturer_name = get_post_meta($post->ID, 'bike_manufacturer_name', true); ?>

		<?php if(isset($bike_frame_model) && $bike_frame_model != '' && isset($bike_manufacturer_name) && $bike_manufacturer_name != ''): ?>
		<br>
		<?php endif; ?>

		<?php if(isset($bike_manufacturer_name) && $bike_manufacturer_name != ''): ?>
			<a style="text-decoration: none;" href="<?php echo get_permalink(); ?>">Manufacturer: <?php echo $bike_manufacturer_name; ?></a></td>
		<?php endif; ?>
	<td align="left" valign="top"><?php echo substr(get_post_meta($post->ID, 'bike_description', true), 0, 100) . '...'; ?></td>
</tr>