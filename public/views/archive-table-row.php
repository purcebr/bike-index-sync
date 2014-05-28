<?php global $post;?>
<?php global $iterate; ?>
<tr class="<?php echo ($counter % 2 == 0) ? "bike-row-even" : "bike-row-odd"; ?>">
	<td align="left" valign="top"><a style="text-decoration: none;" href="<?php echo get_permalink(); ?>">04.30.2014</a></td>
	<td align="left" valign="top"><a style="text-decoration: none;" href="<?php echo get_permalink(); ?>">
		<?php echo get_post_meta($post->ID, 'bike_frame_model', true); ?></a>
		<br>
		<?php $color = get_post_meta($post->ID, 'bike_color', true); ?>
		<?php if($color): ?>
			<a style="text-decoration: none;" href="<?php echo get_permalink(); ?>">Color: <?php echo get_post_meta($post->ID, 'bike_color', true); ?></a></td>
		<?php endif; ?>
	<td align="left" valign="top"><a style="text-decoration: none;" href="<?php echo get_permalink(); ?>">Tualatin OR 97062</a></td>
	<td align="left" valign="top"><a style="text-decoration: none;" href="<?php echo get_permalink(); ?>"><?php echo get_post_meta($post->ID, 'bike_description', true); ?></a></td>
</tr>