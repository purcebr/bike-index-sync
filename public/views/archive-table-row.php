<?php global $post;?>
<?php global $iterate; ?>
<tr class="<?php echo ($counter % 2 == 0) ? "bike-row-even" : "bike-row-odd"; ?>">
	<td align="left" valign="top"><a style="text-decoration: none;" href="<?php echo get_permalink(); ?>"><?php echo (get_post_meta($post->ID, 'bike_stolen_record_date_stolen', true) != "") ? $this->bikeindex_formatted_date(get_post_meta($post->ID, 'bike_stolen_record_date_stolen', true)) : "Not Available"; ?></a></td>
	<td align="left" valign="top"><a style="text-decoration: none;" href="<?php echo get_permalink(); ?>">
		<?php echo get_post_meta($post->ID, 'bike_frame_model', true); ?></a>
		<br>
		<?php $color = get_post_meta($post->ID, 'bike_color', true); ?>
		<?php if($color): ?>
			<a style="text-decoration: none;" href="<?php echo get_permalink(); ?>">Color: <?php echo get_post_meta($post->ID, 'bike_color', true); ?></a></td>
		<?php endif; ?>
	<td align="left" valign="top"><?php echo substr(get_post_meta($post->ID, 'bike_description', true), 0, 100) . '...'; ?></td>
</tr>