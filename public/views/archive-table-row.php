<?php global $post;?>
<?php global $iterate; ?>
<?php $stolen_record = unserialize(get_post_meta($post->ID, 'bike_stolen_record',true)); ?>
<?php if(isset($stolen_record->date_stolen)): ?>
	<?php $stolen_date = $stolen_record->date_stolen; ?>
<?php else: ?>
	<?php $stolen_date = "Not Available" ?>
<?php endif; ?>
<tr class="<?php echo ($counter % 2 == 0) ? "bike-row-even" : "bike-row-odd"; ?>">
	<td align="left" valign="top"><a style="text-decoration: none;" href="<?php echo get_permalink(); ?>"><?php echo $stolen_date; ?></a></td>
	<td align="left" valign="top"><a style="text-decoration: none;" href="<?php echo get_permalink(); ?>">
		<?php echo get_post_meta($post->ID, 'bike_frame_model', true); ?></a>
		<br>
		<?php $color = get_post_meta($post->ID, 'bike_color', true); ?>
		<?php if($color): ?>
			<a style="text-decoration: none;" href="<?php echo get_permalink(); ?>">Color: <?php echo get_post_meta($post->ID, 'bike_color', true); ?></a></td>
		<?php endif; ?>
	<td align="left" valign="top"><a style="text-decoration: none;" href="<?php echo get_permalink(); ?>"><?php echo get_post_meta($post->ID, 'bike_description', true); ?></a></td>
</tr>