<?php

return [
	
	'widget_pack:readmore' => "Read more",
	
	// plugin settings
	'widget_pack:settings:disable_free_html_filter' => "Disable HTML filtering for Free HTML widgets on index (ADMIN ONLY)",
	'widget_pack:settings:rss:title' => "RSS reader settings",
	'widget_pack:settings:rss:description' => "Below you can configure a few settings with regards to the RSS server widget reader.",
	'widget_pack:settings:rss:proxy_host' => "Proxy host",
	'widget_pack:settings:rss:proxy_port' => "Proxy port",
	'widget_pack:settings:rss:proxy_username' => "Proxy username",
	'widget_pack:settings:rss:proxy_password' => "Proxy password",
	'widget_pack:settings:rss:verify_ssl' => "Verify SSL certificate",
	'widget_pack:settings:rss:verify_ssl:help' => "If you have problems with SSL certificate verification, you can disable it here.",
	'widget_pack:settings:rss:cron' => "Use the cron to fetch RSS feeds periodically",
	'widget_pack:settings:rss:cron:help' => "If your users experience reduced performance while looking at widget pages with RSS widgets, this setting could help reduce the effect on your users.",
	
	// index_login
	'widgets:index_login:name' => "Login",
	'widgets:index_login:description' => "Show a login box",
	'widgets:index_login:welcome' => "<b>%s</b> welcome on the <b>%s</b> community",

	// index_members
	'widgets:index_members:name' => "Members",
	'widgets:index_members:description' => "Show the members of your site",
	'widgets:index_members:user_icon' => "Must the users have a profileicon",
	'widgets:index_members:no_result' => "No users found",

	// index_memebers_online
	'widgets:index_members_online:name' => "Online members",
	'widgets:index_members_online:description' => "Show the online members of your site",
	'widgets:index_members_online:user_icon' => "Must the users have a profileicon",
	'widgets:index_members_online:no_result' => "No users found",

	// index_activity
	'widgets:index_activity:name' => "Activity",
	'widgets:index_activity:description' => "Show the latest activity on your site",

	// image_slider
	'widgets:image_slider:name' => "Image Slider",
	'widgets:image_slider:description' => "Show an Image Slider",
	'widgets:image_slider:title' => "Slide",
	'widgets:image_slider:label:url' => "Image url",
	'widgets:image_slider:label:text' => "Text",
	'widgets:image_slider:label:link' => "Link",
	'widgets:image_slider:label:file' => "or file",

	// twitter_search
	'widgets:twitter_search:name' => "Twitter search",
	'widgets:twitter_search:description' => "Display a custom search from Twitter",

	'widgets:twitter_search:embed_code' => "Twitter Widget Embed Code",
	'widgets:twitter_search:embed_code:help' => "Create a widget on Twitter.com and paste your embed code here",
	'widgets:twitter_search:embed_code:error' => "Could not extract the widget id from the embed code",
	'widgets:twitter_search:height' => "Widget height (pixels)",
	'widgets:twitter_search:not_configured' => "This widget is not yet configured",

	// content_by_tag
	'widgets:content_by_tag:name' => "Content by tag",
	'widgets:content_by_tag:description' => "Find content by a tag",

	'widgets:content_by_tag:owner_guids' => "Limit the content to the following authors",
	'widgets:content_by_tag:owner_guids:description' => "Search for a user who is the author of the content. Leave blank if you don't wish to limit based on authors.",
	'widgets:content_by_tag:container_guids' => "Only show content from the following groups",
	'widgets:content_by_tag:container_guids:description' => "Search for a group in which the content was placed. Leave blank if you don't wish to limit based on groups.",
	'widgets:content_by_tag:group_only' => "Only show content from this group",
	'widgets:content_by_tag:entities' => "Which entities to show",
	'widgets:content_by_tag:tags' => "Tag(s) (comma separated)",
	'widgets:content_by_tag:tags_option' => "How to use the tag(s)",
	'widgets:content_by_tag:tags_option:and' => "AND",
	'widgets:content_by_tag:tags_option:or' => "OR",
	'widgets:content_by_tag:excluded_tags' => "Excluded tags",
	'widgets:content_by_tag:display_option' => "How to list the content",
	'widgets:content_by_tag:display_option:normal' => "Normal",
	'widgets:content_by_tag:display_option:simple' => "Simple",
	'widgets:content_by_tag:display_option:slim' => "Slim (single line)",
	'widgets:content_by_tag:show_search_link' => "Show search link",
	'widgets:content_by_tag:show_search_link:disclaimer' => "Search results may vary from widget content",
	'widgets:content_by_tag:show_avatar' => "Show user avatar",
	'widgets:content_by_tag:show_timestamp' => "Show content timestamp",
	'widgets:content_by_tag:order_by' => "How to order the content",
	'widgets:content_by_tag:order_by:time_created' => "Time created",
	'widgets:content_by_tag:order_by:alpha' => "Alphabetically",

	// RSS widget
	'widgets:rss:name' => "RSS Feed",
	'widgets:rss:description' => "Show a RSS feed",
	'widgets:rss:error:notset' => "No RSS Feed URL provided",

	'widgets:rss:settings:rss_count' => "Number of feeds to show",
	'widgets:rss:settings:rssfeed' => "URL of the RSS feed",
	'widgets:rss:settings:rss_cachetimeout' => "RSS Cache Timeout in seconds (leave blank for default)",
	'widgets:rss:settings:show_feed_title' => "Show feed title",
	'widgets:rss:settings:excerpt' => "Show an excerpt",
	'widgets:rss:settings:show_item_icon' => "Show item icon (if available)",
	'widgets:rss:settings:post_date' => "Show post date",
	'widgets:rss:settings:show_in_lightbox' => "Show full text in lightbox when clicking on the link",

	// RSS widget
	'widgets:rss_server:name' => "RSS Feed",
	'widgets:rss_server:description' => "Show a RSS feed (fetching it server-side)",
	'widgets:rss_server:settings:show_author' => "Show item author",
	'widgets:rss_server:settings:show_source' => "Show item source",

	// Free HTML
	'widgets:free_html:name' => "Free HTML",
	'widgets:free_html:description' => "Type your own content in HTML",
	'widgets:free_html:settings:html_content' => "Please supply the HTML to display",

	// entity_statistics widget
	"widgets:entity_statistics:name" => "Statistics",
	"widgets:entity_statistics:description" => "Shows site statistics",
	"widgets:entity_statistics:settings:selected_entities" => "Select the entities you wish to show",

	// messages widget
	"widgets:messages:name" => "Messages",
	"widgets:messages:description" => "Shows your latest messages",
	"widgets:messages:not_logged_in" => "You need to be logged in to use this widget",
	"widgets:messages:settings:only_unread" => "Only show unread messages",

	// likes widget
	"widgets:likes:name" => "Likes",
	"widgets:likes:description" => "Shows information about liked content",
	"widgets:likes:settings:interval" => "Interval",
	"widgets:likes:settings:interval:week" => "Last week",
	"widgets:likes:settings:interval:month" => "Last month",
	"widgets:likes:settings:interval:3month" => "Last 3 months",
	"widgets:likes:settings:interval:halfyear" => "Last half year",
	"widgets:likes:settings:interval:year" => "Last year",
	"widgets:likes:settings:interval:unlimited" => "Unlimited",
	"widgets:likes:settings:like_type" => "Content Type",
	"widgets:likes:settings:like_type:user_likes" => "Recently liked by you",
	"widgets:likes:settings:like_type:most_liked" => "Content with the most likes",
	"widgets:likes:settings:like_type:recently_liked" => "Content that is recently liked",

	// user search widget
	"widgets:user_search:name" => "User Search",
	"widgets:user_search:description" => "Search all user on your site (including disabled and unvalidated users)",
	"widgets:user_search:validated" => "Validated",

	// iframe widget
	"widgets:iframe:name" => "Iframe",
	"widgets:iframe:description" => "Show an url in an iframe",
	"widgets:iframe:settings:iframe_url" => "Enter the iframe URL",
	"widgets:iframe:settings:iframe_height" => "Enter the (optional) iframe height (in pixels)",
	
	// register widget
	'widgets:register:name' => "Register",
	'widgets:register:description' => "Show a register box",
	'widgets:register:loggedout' => "You need to be logged out to use this widget",
	
	// Friends of widget
	'widgets:friends_of:name' => "Friends of",
	'widgets:friends_of:description' => "Show who made you a friend",
	'widgets:friends_of:num_display' => "Number of users to show",
];
