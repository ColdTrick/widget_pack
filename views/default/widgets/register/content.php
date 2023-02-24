<?php

echo elgg_is_logged_in() ? elgg_echo('widgets:register:loggedout') : elgg_view_form('register');
