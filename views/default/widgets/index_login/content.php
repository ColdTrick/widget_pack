<?php

if (elgg_is_logged_in()) {
	echo elgg_echo('widgets:index_login:welcome', [elgg_get_logged_in_user_entity()->name, elgg_get_site_entity()->name]);
	return;
}

echo elgg_view_form('login', [], ['returntoreferer' => TRUE]);
