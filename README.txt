Bike Index Sync Wordpress Plugin
=============

The Bike Index Sync Plugin gives you, a valid organization registered at bikeindex, (bikeindex.org) the awesome ability to sync stolen bikes back to your site for easy access.

Installation
-----------

    * Download the plugin and install in the plugins directory.
    * Visit Settings -> Bike Index Sync Settings and configure the plugin.
    * Because we're downloading bike data periodically, we highly recommend that you set up a real cron job to trigger the wp_cron api every hour. This will ensure that your site stays fast for everyone. For directions that cover this setup, check out: https://rtcamp.com/tutorials/wordpress/wp-cron-crontab/
    Once you set this up, the bike sync will happen entirely behind the scenes.

Usage: Shortcodes
-----

    Once the cron is set up, and the credentials are properly configured, your site will start downloading bikes when they are available. There is a list template view you can set up by including a shortcode on a page of your choice. We recommend that you create a page specifically for this purpose. Simply create a page, and place only the following code inside:

    `[bike_table]`

    A table of bike data will now appear on this page. (As long as the bikes are populating under the "bikes" tab in the Dashboard)

    Another shortcode is available for generating a bike submission form for your organization. Once the org data is set up in settings, you can create a blank page, and place the following code inside: 

    `[show_bike_submit_form]`

    A form will appear in that will allow your users to submit bikes to the index, through your organization's accounrt.

More Info
-----
Bikes are stored as custom post types, so you can link to bikes from your existing Wordpress pages by using the "Link to existing content" option when creating links.

Like posts, and pages, you can also include bikes in your menus.