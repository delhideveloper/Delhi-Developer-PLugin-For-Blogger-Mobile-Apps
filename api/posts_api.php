<?php


/* Securing Plugin From Direct Access through the URL Path */
if ( ! defined( 'ABSPATH' ) ) exit;


	
	



/* Creating a new API End Point : User Registration */
add_action( 'rest_api_init', function () {
	register_rest_route( 
		'delhideveloper/v1', 
		'/posts', 
		array(
			'methods' => 'POST',
			'callback' => 'dd_retreive_posts_json',
			'permission_callback' => function() { return true; },
		) 
	);
} );
function dd_retreive_posts_json( $request ) {
	
	
	$posts_per_page = 10;
	$current_page = 1;
	
	$current_page	= ( 
		! is_null( $request->get_param("current_page") ) 
		? 
		$request->get_param("current_page")
		: 
		$current_page
	);
	
	$posts = get_posts(
		array(
			'posts_per_page' => $posts_per_page,
			'offset'=> ($current_page-1) * $posts_per_page ,
		)
	);
	
	if( ! $posts ) {
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code		= 'No Posts Found';
		$response->message	= 'No Posts Could Be Retreived!';
		$response->posts	= array();
		return new WP_REST_Response( $response , 200 );
	}
	
	$posts_array = array();
	foreach( $posts as $post ) {
		
		$posts_object = new stdClass();
		$posts_object->id = $post->ID;
		$posts_object->title = $post->post_title;
		$posts_object->thumbnail = get_the_post_thumbnail($post->ID);
		//$posts_object->url = 'https://prachyakarma.com/'. $post->post_name;
		//$posts_object->content = $post->post_content;
		//$posts_object->excerpt = mb_substr( $post->post_content , 0 , 100 , 'UTF-8' );
		//$posts_object->date = date( 'M j, Y' , strtotime($post->post_date) );
		
		array_push( $posts_array , $posts_object );
		
	}
	
	$response = new stdClass();
	$response->type		= 'Success';
	$response->code		= 'Posts Retreived';
	$response->code		= 'Success'; // Because the app v1 works like this (it reads code instead of type)
	$response->message	= 'Posts have been retreived.';
	$response->posts	= $posts_array;
	return new WP_REST_Response( $response , 200 );
	
	
}




/* Creating a new API End Point : User Registration */
add_action( 'rest_api_init', function () {
	register_rest_route( 
		'delhideveloper/v1', 
		'/posts_by_category', 
		array(
			'methods' => 'POST',
			'callback' => 'dd_retreive_posts_by_category_json',
			'permission_callback' => function() { return true; },
		) 
	);
} );
function dd_retreive_posts_by_category_json( $request ) {
	
	
	
	$posts_per_page = 10;
	$current_page = 1;
	
	$current_page	= ( 
		! is_null( $request->get_param("current_page") ) 
		? 
		$request->get_param("current_page")
		: 
		$current_page
	);
	
	$category_id	= $request->get_param("category_id");
	if( is_null( $category_id ) ) {
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code		= 'Category ID Missing';
		$response->message	= 'Please specify a category ID for the category of which posts are required.';
		$response->posts	= array();
		return new WP_REST_Response( $response , 200 );
	}
	if( ! get_term_by( 'id', $category_id, 'category') ) {
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code		= 'Category ID Does Not Exist';
		$response->message	= 'The category ID specified does not exist in the server.';
		$response->posts	= array();
		return new WP_REST_Response( $response , 200 );
	}
	
	$posts = get_posts(
		array(
			'posts_per_page' => $posts_per_page,
			'offset'=> ($current_page-1) * $posts_per_page ,
			'category' => $category_id
		)
	);
	
	if( ! $posts ) {
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code		= 'No Posts Found';
		$response->message	= 'No posts found in this category!';
		$response->posts	= array();
		return new WP_REST_Response( $response , 200 );
	}
	
	$posts_array = array();
	foreach( $posts as $post ) {
		
		$posts_object = new stdClass();
		$posts_object->id = $post->ID;
		$posts_object->title = $post->post_title;
		$posts_object->thumbnail = get_the_post_thumbnail($post->ID);
		//$posts_object->url = 'https://prachyakarma.com/'. $post->post_name;
		//$posts_object->content = $post->post_content;
		//$posts_object->excerpt = mb_substr( $post->post_content , 0 , 100 , 'UTF-8' );
		//$posts_object->date = date( 'M j, Y' , strtotime($post->post_date) );
		
		array_push( $posts_array , $posts_object );
		
	}
	
	$response = new stdClass();
	$response->type		= 'Success';
	$response->code		= 'Posts Retreived';
	$response->code		= 'Success';
	$response->message	= 'Posts have been retreived!';
	$response->posts	= $posts_array;
	return new WP_REST_Response( $response , 200 );
	
	
}











/* Creating a new API End Point : User Registration */
add_action( 'rest_api_init', function () {
	register_rest_route( 
		'delhideveloper/v1', 
		'/post', 
		array(
			'methods' => 'POST',
			'callback' => 'dd_retreive_single_post_json',
			'permission_callback' => function() { return true; },
		) 
	);
} );
function dd_retreive_single_post_json( $request ) {
	
	
	
	
	$comments_per_page = 10;
	$current_comment_page = 1;
	$current_comment_page	= ( 
		! is_null( $request->get_param("current_comment_page") ) 
		? 
		$request->get_param("current_comment_page")
		: 
		$current_comment_page
	);
	
	
	$id	= $request->get_param("id");
	if( is_null( $id ) ) {
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code		= 'Post ID Missing';
		$response->message	= 'PLease specify post ID of the post that is required!';
		$response->post	= array();
		return new WP_REST_Response( $response , 200 );
	}
	
	$post = get_post( $id );
	
	if( ! $post ) {
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code		= 'No Such Post';
		$response->message	= 'No such post could be found!';
		$response->post	= array();
		return new WP_REST_Response( $response , 200 );
	}
	
	$posts_object = new stdClass();
	$posts_object->id = $post->ID;
	$posts_object->title = $post->post_title;
	$posts_object->thumbnail = get_the_post_thumbnail($post->ID);
	$posts_object->url = 'https://prachyakarma.com/'. $post->post_name;
	$posts_object->content = do_shortcode( wpautop( $post->post_content ) );
	$posts_object->excerpt = mb_substr( $post->post_content , 0 , 100 , 'UTF-8' );
	$posts_object->date = date( 'M j, Y' , strtotime($post->post_date) );
	
	
	
	$posts_object->author_id = $post->post_author;
	$posts_object->author_image = get_avatar( get_the_author_meta( 'user_email' , $post->post_author ) );
	$posts_object->author_name =  get_the_author_meta( 'display_name' , $post->post_author );
	$posts_object->author_description =  get_the_author_meta( 'description' , $post->post_author );
	
	$comments = get_comments(array(
		'number'	=> '10',
		'offset'	=> ($current_comment_page-1) * $comments_per_page ,
		'post_id'	=> $id,
		'status'	=> 'approve' // Approved Comments Only
	));
	
	$comments_array = array();
	foreach( $comments as $comment ) {
		$comments_object = new stdClass();
		$comments_object->content = $comment->comment_content;
		$comments_object->author = $comment->comment_author;
		$comments_object->date = date( 'M j, Y' , strtotime($comment->comment_date) );
		array_push( $comments_array , $comments_object );
	}
	$posts_object->comments = $comments_array;
	
	
	$response = new stdClass();
	$response->type		= 'Success';
	$response->code		= 'Post Retreived';
	$response->code		= 'Success';
	$response->message	= 'Post has been retreived!';
	$response->post	= $posts_object;
	return new WP_REST_Response( $response , 200 );
	
	
}





/* Creating a new API End Point : User Registration */
add_action( 'rest_api_init', function () {
	register_rest_route( 
		'delhideveloper/v1', 
		'/comments', 
		array(
			'methods' => 'POST',
			'callback' => 'dd_retreive_comments_json',
			'permission_callback' => function() { return true; },
		) 
	);
} );
function dd_retreive_comments_json( $request ) {
	
	
	
	
	$comments_per_page = 10;
	$current_comment_page = 1;
	$current_comment_page	= ( 
		! is_null( $request->get_param("current_comment_page") ) 
		? 
		$request->get_param("current_comment_page")
		: 
		$current_comment_page
	);
	
	$id	= $request->get_param("id");
	if( is_null( $id ) ) {
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code		= 'Post ID Missing';
		$response->message	= 'PLease specify post ID of the post the comments of which are required!';
		$response->post	= array();
		return new WP_REST_Response( $response , 200 );
	}
	
	$post = get_post( $id );
	
	if( ! $post ) {
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code		= 'No Such Post';
		$response->message	= 'No such post could be found!';
		$response->post	= array();
		return new WP_REST_Response( $response , 200 );
	}
	
	$comments = get_comments(array(
		'number'	=> '10',
		'offset'	=> ($current_comment_page-1) * $comments_per_page ,
		'post_id'	=> $id,
		'status'	=> 'approve' // Approved Comments Only
	));
	
	$comments_array = array();
	foreach( $comments as $comment ) {
		$comments_object = new stdClass();
		$comments_object->content = $comment->comment_content;
		$comments_object->author = $comment->comment_author;
		$comments_object->date = date( 'M j, Y' , strtotime($comment->comment_date) );
		array_push( $comments_array , $comments_object );
	}
	
	
	$response = new stdClass();
	$response->type		= 'Success';
	$response->code		= 'Comments Retreived';
	$response->code		= 'Success';
	$response->message	= 'Comments have been retreived!';
	$response->comments	= $comments_array;
	return new WP_REST_Response( $response , 200 );
	
	
}








/* Creating a new API End Point : User Registration */
add_action( 'rest_api_init', function () {
	register_rest_route( 
		'delhideveloper/v1', 
		'/comment_create', 
		array(
			'methods' => 'POST',
			'callback' => 'dd_create_a_comment',
			'permission_callback' => function() { return true; },
		) 
	);
} );
function dd_create_a_comment( $request ) {
	
	
	/************************** TOKEN VERIFICATION **********************************/
	/* Check if token exists */
	$token	= $request->get_param("token");
	if( is_null( $token ) ) {
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code	= 'Token Not Sent';
		$response->message	= 'Token has not been sent with the message.';
		return new WP_REST_Response( $response , 200 );
	}
	/* Get User From Token */
	$user = get_user_object_from_token( $token );
	/* If Token Not Valid */
	if( !$user ) {
		
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code	= 'Invalid Token';
		$response->message	= 'Token is not valid. Please, login to get a new token.';
		return new WP_REST_Response( $response , 200 );
	}
	/************************** /TOKEN VERIFICATION *********************************/
	
	
	$posts_per_page = 10;
	$current_page = 1;
	
	$post_id	= $request->get_param("post_id");
	if( is_null( $id ) ) {
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code		= 'Post ID Missing';
		$response->message	= 'PLease specify post ID of the post for which comment is to be posted!';
		$response->post	= array();
		return new WP_REST_Response( $response , 200 );
	}
	$comment	= $request->get_param("comment");
	if( is_null( $id ) ) {
		$response = new stdClass();
		$response->type		= 'Failure';
		$response->code		= 'Comment Missing';
		$response->message	= 'PLease specify the comment to be posted!';
		$response->post	= array();
		return new WP_REST_Response( $response , 200 );
	}
	
	
	
	$commentdata = array(
		'comment_post_ID' => $post_id, // to which post the comment will show up
		'comment_author' => $user->data->display_name, // 'Another Someone', //fixed value - can be dynamic 
		'comment_author_email' => $user->data->user_email, //'someone@example.com', //fixed value - can be dynamic 
		'comment_author_url' => $user->data->user_url, // 'http://example.com', //fixed value - can be dynamic 
		'comment_content' => $comment, //  'Comment messsage...', //fixed value - can be dynamic 
		'comment_type' => '', //empty for regular comments, 'pingback' for pingbacks, 'trackback' for trackbacks
		'comment_parent' => 0, //0 if it's not a reply to another comment; if it's a reply, mention the parent comment ID here
		'user_id' => $user_id, //passing current user ID or any predefined as per the demand
	) ;
	
	
	
	/* Check if this ne comment is allowed */
	$comment_allowed = wp_allow_comment( $commentdata, true );
	
	
	/* if comment is allowed */
	if( is_wp_error( $comment_allowed ) ) {
		return $comment_allowed;
	}
	
	// Insert new comment and get the comment ID
	$comment_id = wp_new_comment( $commentdata );
	$comment = get_comment( $comment_id );
	/* Approve the new comment */
	wp_set_comment_status( $comment_id, 'approve' );
	//return $comment;
	
	$comment_object = new stdClass();
	$comment_object->author = $comment->comment_author;
	$comment_object->date = date( 'M j, Y' , strtotime($comment->comment_date) );
	$comment_object->content = $comment->comment_content;
	
	$response = new stdClass();
	$response->type		= 'Success';
	$response->code		= 'Comment Created';
	$response->code		= 'Success';
	$response->message	= 'Comment has been created!';
	$response->comment	= $comment_object;
	return new WP_REST_Response( $response , 200 );
	
	
}
































?>