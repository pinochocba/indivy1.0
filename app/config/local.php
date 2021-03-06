<?php
$parameters = array(
	'db_driver' => 'pdo_mysql',
	'db_host' => 'localhost',
	'db_table_prefix' => 'inbound_',
	'db_port' => '3306',
	'db_name' => 'mautic',
	'db_user' => 'root',
	'db_password' => 'linux666',
	'db_server_version' => '5.5.50-0+deb8u1',
	'mailer_from_name' => 'Diego Mantovani',
	'mailer_from_email' => 'info@oxford.com.ar',
	'mailer_transport' => 'smtp',
	'mailer_host' => 'mail.grupooxford.com',
	'mailer_port' => '25',
	'mailer_user' => 'anibal@grupooxford.com',
	'mailer_password' => 'oxford123',
	'mailer_encryption' => null,
	'mailer_auth_mode' => 'login',
	'mailer_spool_type' => 'memory',
	'mailer_spool_path' => '%kernel.root_dir%/spool',
	'secret_key' => 'cc37141bf8eca3b91e645ff1d3bb86326356136610ca2990502b11dd0c890c96',
	'site_url' => 'http://admin.indivy.com/',
	'webroot' => null,
	'cache_path' => '/home/buffalo/public_html/symlink/usr/home/masbaratov2/admin/app/cache',
	'log_path' => '/home/buffalo/public_html/symlink/usr/home/masbaratov2/admin/app/logs',
	'image_path' => 'media/images',
	'theme' => 'Mauve',
	'locale' => 'es',
	'dev_hosts' => null,
	'trusted_hosts' => array(

	),
	'trusted_proxies' => array(

	),
	'rememberme_key' => 'e463d68b01c206d810ac3a6959b7eb154ad550ff',
	'rememberme_lifetime' => '31536000',
	'rememberme_path' => '/',
	'rememberme_domain' => null,
	'default_pagelimit' => 30,
	'default_timezone' => 'America/Argentina/Cordoba',
	'date_format_full' => 'F j, Y g:i a T',
	'date_format_short' => 'D, M d',
	'date_format_dateonly' => 'F j, Y',
	'date_format_timeonly' => 'g:i a',
	'ip_lookup_service' => 'maxmind_download',
	'ip_lookup_auth' => null,
	'ip_lookup_config' => array(

	),
	'update_stability' => 'stable',
	'cookie_path' => '/',
	'cookie_domain' => null,
	'cookie_secure' => null,
	'cookie_httponly' => 0,
	'do_not_track_ips' => array(

	),
	'do_not_track_internal_ips' => array(

	),
	'link_shortener_url' => null,
	'cached_data_timeout' => '10',
	'batch_sleep_time' => 1,
	'batch_campaign_sleep_time' => false,
	'cors_restrict_domains' => 1,
	'cors_valid_domains' => array(

	),
	'rss_notification_url' => 'https://mautic.com/?feed=rss2&tag=notification',
	'api_enabled' => 1,
	'api_oauth2_access_token_lifetime' => 60,
	'api_oauth2_refresh_token_lifetime' => 14,
	'upload_dir' => '/home/buffalo/public_html/symlink/usr/home/masbaratov2/admin/app/../media/files',
	'max_size' => '6',
	'allowed_extensions' => array(
		"0" => "csv", 
		"1" => "doc", 
		"2" => "docx", 
		"3" => "epub", 
		"4" => "gif", 
		"5" => "jpg", 
		"6" => "jpeg", 
		"7" => "mpg", 
		"8" => "mpeg", 
		"9" => "mp3", 
		"10" => "odt", 
		"11" => "odp", 
		"12" => "ods", 
		"13" => "pdf", 
		"14" => "png", 
		"15" => "ppt", 
		"16" => "pptx", 
		"17" => "tif", 
		"18" => "tiff", 
		"19" => "txt", 
		"20" => "xls", 
		"21" => "xlsx", 
		"22" => "wav"
	),
	'campaign_time_wait_on_event_false' => 'PT15M',
	'mailer_return_path' => null,
	'mailer_append_tracking_pixel' => 1,
	'mailer_convert_embed_images' => 0,
	'mailer_amazon_region' => 'email-smtp.us-east-1.amazonaws.com',
	'mailer_spool_msg_limit' => null,
	'mailer_spool_time_limit' => null,
	'mailer_spool_recover_timeout' => '900',
	'mailer_spool_clear_timeout' => '1800',
	'unsubscribe_text' => '<a href=\'|URL|\'>Unsubscribe</a> to no longer receive emails from us.',
	'webview_text' => '<a href=\'|URL|\'>Having trouble reading this email? Click here.</a>',
	'unsubscribe_message' => 'We are sorry to see you go! |EMAIL| will no longer receive emails from us. If this was by mistake, <a href=\'|URL|\'>click here to re-subscribe</a>.',
	'resubscribe_message' => '|EMAIL| has been re-subscribed. If this was by mistake, <a href=\'|URL|\'>click here to unsubscribe</a>.',
	'monitored_email' => array(

	),
	'mailer_is_owner' => 0,
	'default_signature_text' => 'Best regards, |FROM_NAME|',
	'email_frequency_number' => null,
	'email_frequency_time' => null,
	'notification_enabled' => 0,
	'notification_app_id' => null,
	'notification_rest_api_key' => null,
	'notification_safari_web_id' => null,
	'cat_in_page_url' => 0,
	'google_analytics' => null,
	'redirect_list_types' => array(
		"301" => "mautic.page.form.redirecttype.permanent", 
		"302" => "mautic.page.form.redirecttype.temporary"
	),
	'sms_enabled' => 0,
	'sms_username' => null,
	'sms_password' => null,
	'sms_sending_phone_number' => null,
	'sms_frequency_number' => null,
	'sms_frequency_time' => null,
	'webhook_start' => 0,
	'webhook_limit' => 1000,
	'webhook_log_max' => 10,
	'queue_mode' => 'immediate_process',
	'twitter_handle_field' => 'twitter',
);
