<?php global $post;?>
<?php global $iterate; ?>
<tr class="<?php echo ($counter % 2 == 0) ? "bike-row-even" : "bike-row-odd"; ?>">
	<td align="left" valign="top">
		<a class="mobile-hidden" style="text-decoration: none;" href="<?php echo get_permalink(); ?>"><?php echo (get_post_meta($post->ID, 'bike_stolen_record_date_stolen', true) != "") ? $this->bikeindex_formatted_date(get_post_meta($post->ID, 'bike_stolen_record_date_stolen', true)) : "Not Available"; ?></a>
		
		<div class="mobile-visible">
			<p>
				Date Stolen: <a style="text-decoration: none;" href="<?php echo get_permalink(); ?>"><?php echo (get_post_meta($post->ID, 'bike_stolen_record_date_stolen', true) != "") ? $this->bikeindex_formatted_date(get_post_meta($post->ID, 'bike_stolen_record_date_stolen', true)) : "Not Available"; ?></a>

				<br />

				<?php if(isset($bike_manufacturer_name) && $bike_manufacturer_name != ''): ?>
					Manufacturer: <a style="text-decoration: none;" href="<?php echo get_permalink(); ?>"><?php echo $bike_manufacturer_name; ?></a>
				<?php else: ?>
					No Manufacturer Available
				<?php endif; ?>

				<br />

				<?php $bike_frame_model = get_post_meta($post->ID, 'bike_frame_model', true); ?>
				<?php $bike_frame_year = get_post_meta($post->ID, 'bike_year', true); ?>
				<?php if(isset($bike_frame_model) && $bike_frame_model != ''): ?>
					Model: <a style="text-decoration: none;" href="<?php echo get_permalink(); ?>"><?php if($bike_frame_year != false) echo $bike_frame_year . ' '; ?><?php echo $bike_frame_model; ?></a>
				<?php else: ?>
					No Model Details Available
				<?php endif; ?>
			</p>

			<?php $description = get_post_meta($post->ID, 'bike_description', true); ?>
			<?php if($description != false && $description != "" && strlen($description) > 100): ?>
				<p><?php echo substr($description, 0, 100) . '...'; ?></p>
			<?php elseif($description != false && $description != ""): ?>
				<p><?php echo $description; ?></p>
			<?php endif; ?>
		</div>
	</td>
	<td align="left" valign="top" class="mobile-hidden"><a style="text-decoration: none;" href="<?php echo get_permalink(); ?>">
		<?php $bike_manufacturer_name = get_post_meta($post->ID, 'bike_manufacturer_name', true); ?>
		<?php if(isset($bike_manufacturer_name) && $bike_manufacturer_name != ''): ?>
			<a style="text-decoration: none;" href="<?php echo get_permalink(); ?>"><?php echo $bike_manufacturer_name; ?></a>
			<?php else: ?>
				No Manufacturer Available
		<?php endif; ?>
	</td>
	<td align="left" valign="top" class="mobile-hidden">
		<?php $bike_frame_model = get_post_meta($post->ID, 'bike_frame_model', true); ?>
		<?php $bike_frame_year = get_post_meta($post->ID, 'bike_year', true); ?>

		<?php if(isset($bike_frame_model) && $bike_frame_model != ''): ?>
			<a style="text-decoration: none;" href="<?php echo get_permalink(); ?>"><?php if($bike_frame_year != false) echo $bike_frame_year . ' '; ?><?php echo $bike_frame_model; ?></a>
		<?php else: ?>
			No Model Details Available
		<?php endif; ?>
	</td>
	<td align="left" valign="top" class="mobile-hidden">
		<?php $description = get_post_meta($post->ID, 'bike_description', true); ?>
		<?php if($description != false && $description != "" && strlen($description) > 100): ?>
			<p><?php echo substr($description, 0, 100) . '...'; ?>
		<?php elseif($description != false && $description != ""): ?></p>
			<p><?php echo $description; ?></p>
		<?php else: ?>
			<p class="bike-description-unavailable">No Description Available</p>
		<?php endif; ?>
	</td>
</tr>