#region Shortcode Lesson Downloads
// Example for a shortcode that allows logged in visitors to download files that were uploaded to an Advanced Custom Field
function lesson_downloads_callback($atts, $content = null) {
	$a = shortcode_atts([
		'title'	=> '',
		'class'	=> ''
	], $atts);
	if($a["title"] == '') {
		$a["title"] = get_field('dt_globals_dl_title', 'option');
	}
	$return_string = '';
	if( have_rows('dt_downloads') ) {
		$return_string = '<div class="lesson_downloads_container et_pb_bg_layout_dark ' . $a["class"] . '">';
		if($a["title"] != '') {
			$return_string .= '<h2 class="fancy_headline lesson_downloads_headline">' . $a["title"] . '</h2>';
		}
		$return_string .= '<ul class="fancy_list lesson_downloads_list ">';
		while( have_rows('dt_downloads') ) : the_row();
			$title = get_sub_field("dt_dls_title");
			$url = get_sub_field("dt_dls_link");
			if($url == '') $url = get_sub_field("dt_dls_download_link");
			if($url == '') continue;
			$type = get_sub_field("dt_dls_type");
			$desc = get_sub_field("dt_dls_desc");
			$return_string .= "<li class='dt_lesson_download $type'><a target='_blank' href='$url' title='$title'>$title</a><div class='download_description'>$desc</div></li>";
		endwhile;
		$return_string .= '</ul> <!-- .lesson_downloads_list --></div> <!-- .lesson_downloads_container -->';
	} // endif have_rows
	return $return_string;
}
add_shortcode('dt_lesson_downloads', 'lesson_downloads_callback');
#endregion Shortcode Lesson Downloads

#region Move uploaded files in ACF field to protected folder automatically
function document_url_upload_prefilter($errors) {
	// in this filter we add a WP filter that alters the upload path
	add_filter('upload_dir', 'document_url_upload_dir');
	return $errors;
}
add_filter('acf/upload_prefilter/name=dt_dls_download_link', 'document_url_upload_prefilter');

// second filter
function document_url_upload_dir($uploads) {
	// here is where we later the path
	$uploads['path'] = $uploads['basedir'].'/protected';
	$uploads['url'] = $uploads['baseurl'].'/protected';
	$uploads['subdir'] = '';
	return $uploads;
}
#endregion Move uploaded files in ACF field to protected folder automatically
