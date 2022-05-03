<?php
class Book_Widget extends WP_Widget {

/**
 * Sets up the widgets name etc
 */
public function __construct() {
    $widget_ops = array( 
        'classname' => 'book_widget',
        'description' => 'Book Widget',
    );
    parent::__construct( 'book_widget', 'Book Widget', $widget_ops );
}


	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$cuntOfBoks=wp_count_posts($post_type ='book')->publish;
        echo $cuntOfBoks;
        echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		
	}

}
add_action( 'widgets_init', function(){
	register_widget( 'Book_Widget' );
});