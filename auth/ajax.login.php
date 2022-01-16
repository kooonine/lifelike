<?php
include_once './../common.php';
if ($type == "stipulation") echo conv_content($config['cf_stipulation'], $config['cf_editor']);
else echo conv_content($config['cf_privacy'], $config['cf_editor']);
// else if ($type == "privacy") echo conv_content($config['cf_privacy'], $config['cf_editor']);
