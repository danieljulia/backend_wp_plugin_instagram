<?php

/**
 * @package Quick_Instagram
 * @version 0.1
 */


/*
Plugin Name: Quick Instagram
Plugin URI: https://github.com/danieljulia/quick-instagram
Description: Shows instagram photos for any user as a Widget or shortcode
Author: Daniel Julià
Version: 0.1
Author URI: http://mosaic.uoc.edu/author/daniel-julia/
*/

require "config.php"; 
require "instagram-api.php";


/**
definició del widget
*/

// Widget molt senzill



//definició 
class quick_instagram_widget extends WP_Widget {


function __construct() {

	parent::__construct(
	// ID del widget
	'quick_instagram_widget', 

	// El nom que apareix a l'escriptori, traduible amb el quick-instagram 'quick-instagram'
	__('Quick Instagram', 'quick-instagram'), 

	// La descripció
	array( 'description' => __( 'Mostra imatges a Instagram d\'un usuari', 'quick-instagram' ), ) 
	);
}

// Creació del front-end del widget
// Això és el que es veu al frontend

public function widget( $args, $instance ) {
	$title = apply_filters( 'widget_title', $instance['title'] );
	$user=$instance['user'];
	$num=$instance['num'];
	// before and after widget arguments are defined by themes
	?>
	<!-- aqui comença el widget -->
	<?php echo $args['before_widget']; ?>

	<?php if ( ! empty( $title ) )
	echo $args['before_title'] . $title . $args['after_title'];
	?>
	
	<!-- inici fotos instagram -->
	<?php
	
    $fotos=instagram_get_photos($user,$num);

    if(!$fotos) print "Username not defined";
	foreach($fotos->data as $foto):
	
	?>
	
	<a href='<?php print $foto->link?>'>
	<img src='<?php print $foto->images->thumbnail->url?>'
	title='<?php print htmlspecialchars($foto->caption->text,ENT_QUOTES)?>' alt='<?php print $foto->caption->text?>'>
	</a>
	

	<?php
	endforeach;
	
?>
	<!-- fi fotos instagram -->

	<?php
	echo $args['after_widget'];
	?>
	<!-- final del widget -->
	<?php
}
		
// El Backend del widget 
public function form( $instance ) {
	if ( isset( $instance[ 'title' ] ) ) {
	$title = $instance[ 'title' ];
	}
	else {
	$title = __( 'New title', 'quick-instagram' );
	}
	$user = $instance[ 'user' ];
	$num = $instance[ 'num' ];

	// Formulari
	?>
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>

		<p>
	<label for="<?php echo $this->get_field_id( 'user' ); ?>"><?php _e( 'Instagram username:' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'user' ); ?>" name="<?php echo $this->get_field_name( 'user' ); ?>" type="text" value="<?php echo esc_attr( $user ); ?>" />
	</p>

<p>
	<label for="<?php echo $this->get_field_id( 'num' ); ?>"><?php _e( 'Number of photos:' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'num' ); ?>" name="<?php echo $this->get_field_name( 'num' ); ?>" type="text" value="<?php echo esc_attr( $num ); ?>" />
	</p>

	<?php 
}
	
// Actualitzant valors del widget
public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	$instance['user'] =  $new_instance['user'];
	$instance['num'] =  $new_instance['num'];
	return $instance;
	}
} 
//final de la definició 


// Registra i carrega el widget
function load_quick_instagram_widget() {
	register_widget( 'quick_instagram_widget' );
}
add_action( 'widgets_init', 'load_quick_instagram_widget' );




/**
definició del shortcode
[instagram user="nom" num=5]

*/

// [instagram user="nom" num=5]
function quick_instagram_func( $atts ) {

	//recoger parámetros y valores por defecto
    $a = shortcode_atts( array(
        'user' => 'danieljulia',
        'num' => 3,
    ), $atts );

    $fotos=instagram_get_photos($a['user'],$a['num']);
	

    ob_start();

   	if(!$fotos) print $a['user']." username not defined in Instagram";

	foreach($fotos->data as $foto):
	 
	?>
	
	<a href='<?php print $foto->link?>'>
	<img src='<?php print $foto->images->thumbnail->url?>'
	title='<?php print htmlspecialchars($foto->caption->text,ENT_QUOTES)?>' alt='<?php print $foto->caption->text?>'>
	</a>
	

	<?php
	endforeach;
	$html = ob_get_clean();
	return $html;



    //return "user = {$a['user']}  num={$a['num']}";
}
add_shortcode( 'instagram', 'quick_instagram_func' );