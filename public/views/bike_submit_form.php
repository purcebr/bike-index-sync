<?php $options = get_option('bike-index-sync-settings'); ?>
<?php if(isset($options['organization_id'])): ?>
	<iframe class="bikeindex-submission-form" src="https://bikeindex.org/organizations/<?php echo $options['organization_id']; ?>/embed?stolen_first=true"></iframe>
<?php endif; ?>
