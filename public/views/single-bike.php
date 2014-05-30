
<?php $bike_photo_url = get_post_meta($post->ID, 'bike_photo', true); ?>

<?php $bike_description = get_post_meta($post->ID, 'bike_description', true); ?>
<?php if($bike_description): ?>
	<div class="bike_meta">
		<h3>Description</h3>
		<p><?php echo $bike_description; ?></p>
	</div>
<?php endif; ?>

<?php if($bike_photo_url): ?>
<div class="bike_photo">
	<img src="<?php echo $bike_photo_url; ?>">
</div>
<?php endif; ?>

<h3>Bike Stats</h3>

<ul class="bike-stats">

	<?php $bike_manufacturer_name = get_post_meta($post->ID, 'bike_manufacturer_name', true); ?>
	<?php if($bike_manufacturer_name): ?>
	<li>
		<span class="bike_attribute">Manufacturer: <strong><?php echo $bike_manufacturer_name; ?></strong></span>
	</li>
	<?php endif; ?>

	<?php $bike_year = get_post_meta($post->ID, 'bike_year', true); ?>
	<?php if($bike_year): ?>
	<li>
		<span class="bike_attribute">Year: <strong><?php echo $bike_year; ?></strong></span>
	</li>
	<?php endif; ?>

	<?php $bike_serial = get_post_meta($post->ID, 'bike_serial', true); ?>
	<?php if($bike_serial): ?>
	<li>
		<span class="bike_attribute">Serial Number: <strong><?php echo $bike_serial; ?></strong></span>
	</li>
	<?php endif; ?>

	<?php $bike_rear_tire_narrow = get_post_meta($post->ID, 'bike_rear_tire_narrow', true); ?>
	<?php if($bike_rear_tire_narrow == '1'): ?>
	<li>
		<span class="bike_attribute">Bike Tire Style: <strong>Narrow</strong></span>
	</li>
	<?php endif; ?>

</ul>

<h3>Theft Details</h3>

<?php if(get_post_meta($post->ID, 'bike_stolen_record_theft_longitude', true) != "" && get_post_meta($post->ID, 'bike_stolen_record_theft_latitude', true) != ""): ?>
	<img src="https://maps-api-ssl.google.com/maps/api/staticmap?center=<?php echo get_post_meta($post->ID, 'bike_stolen_record_latitude', true); ?>,<?php echo get_post_meta($post->ID, 'bike_stolen_record_longitude', true); ?>&amp;zoom=15&amp;size=640x480&amp;markers=<?php echo get_post_meta($post->ID, 'bike_stolen_record_latitude', true); ?>,<?php echo get_post_meta($post->ID, 'bike_stolen_record_longitude', true); ?>&amp;maptype=roadmap&amp;sensor=false&amp;scale=1">
<?php endif; ?>

<div class="dl-horizontal">
	<dt>Locking description</dt><dd><?php echo (get_post_meta($post->ID, 'bike_stolen_record_locking_description', true) != "") ? get_post_meta($post->ID, 'bike_stolen_record_locking_description', true) : "Not Available"; ?></dd>
	<dt>Locking circumvented</dt><dd><?php echo (get_post_meta($post->ID, 'bike_stolen_record_theft_description', true)) ? get_post_meta($post->ID, 'bike_stolen_record_theft_description', true) : "Not Available"; ?></dd>
	<dt>Date stolen</dt><dd><?php echo (get_post_meta($post->ID, 'bike_stolen_record_date_stolen', true) != "") ? $this->bikeindex_formatted_date(get_post_meta($post->ID, 'bike_stolen_record_date_stolen', true)) : "Not Available"; ?></dd>
	<dt>Police report #</dt><dd><?php echo (get_post_meta($post->ID, 'bike_stolen_record_police_report_number', true) != "") ? get_post_meta($post->ID, 'bike_stolen_record_police_report_number', true) : "Not Available"; ?></dd>
	<dt>Department &amp; city</dt><dd><?php echo (get_post_meta($post->ID, 'bike_stolen_record_police_report_department', true) != "") ? get_post_meta($post->ID, 'bike_stolen_record_police_report_department', true) : "Not Available"; ?></dd>
</div>

<a href="<?php echo get_post_meta($post->ID, 'bike_url', true); ?>" class="btn">Check it out on the Bike Index</a>
