<div id="event_details">
</div>
<div id="popup" class="freeow freeow-top-right"></div>
<?php
$enable_calendar_sharing = $this->config->item('enable_calendar_sharing');
$base = base_url();
$relative = preg_replace('/^http[s]:\/\/[^\/]+/', '', $base);
?>

<script language="JavaScript" type="text/javascript" src="<?php echo
site_url('js_generator/siteconf')?>"></script>
<?php
if (!isset($login_page) || $login_page === false):
?>
<script language="JavaScript" type="text/javascript" src="<?php echo
site_url('js_generator/userprefs')?>"></script>
<?php
endif;

if (ENVIRONMENT === 'production') {
  $js = [];
  echo script_tag('build/js/agendav-' .  AgenDAV\Version::V . '.min.js');
} else {
  $js = Defs::$jsfiles;
}

// Additional JS files
$additional_js = $this->config->item('additional_js');
if ($additional_js !== FALSE && is_array($additional_js)) {
	foreach ($additional_js as $j) {
		$js[] = $j;
	}
}

foreach ($js as $jsfile) {
	echo script_tag('js/' . $jsfile);
}

// Load language
$lang = $this->config->item('default_language');
$lang_rels = $this->config->item('lang_rels');

if (isset($lang_rels[$lang]) && isset($lang_rels[$lang]['fullcalendar'])) {
  echo script_tag('js/fullcalendar/lang/' . $lang_rels[$lang]['fullcalendar'] . '.js');
}
?>

<?php
// Load session refresh code
if (isset($load_session_refresh) && $load_session_refresh === TRUE):
?>
<script language="JavaScript" type="text/javascript" src="<?php echo
site_url('js_generator/session_refresh')?>"></script>
<?php
endif;

$img = array(
		'src' => 'img/agendav_small.png',
		'alt' => 'AgenDAV',
		);
?>
</body>
</html>
