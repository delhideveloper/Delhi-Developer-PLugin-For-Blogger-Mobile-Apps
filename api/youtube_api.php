<?php







/* Creating a new API End Point : User Registration */
add_action( 'rest_api_init', function () {
	register_rest_route( 
		'delhideveloper/v1', 
		'/videos', 
		array(
			'methods' => 'POST',
			'callback' => 'dd_get_youtube_channel_videos',
		) 
	);
} );
function dd_get_youtube_channel_videos( $request ) {
	
	$dd_mobile_app_youtube_channel_settings = json_decode(get_option('dd_mobile_app_youtube_channel_settings'));
	
	if( 	! $dd_mobile_app_youtube_channel_settings
		||	$dd_mobile_app_youtube_channel_settings->enable_youtube_channel == "No"
	) {
		$videos = array();
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code		= 'Videos Disabled On Server';
		$response->message	= 'Videos have been disabled for this app on the server!';
		$response->videos	= $videos;
		return $response;
	}
	
	$videos = json_decode( get_option( 'dd_mobile_app_youtube_videos' ) );
	
	if( ! $videos ) {
		$videos = array();
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code		= 'No Videos Found';
		$response->message	= 'No videos found On the server!';
		$response->videos	= $videos;
		return $response;
	}
	
	$response = new stdClass();
	$response->type		= 'Success';
	$response->code		= 'Videos Retreived';
	$response->message	= count($videos) . ' Youtube Videos Retreived!';
	$response->videos	= $videos;
	return $response;
	

	
}






















?>