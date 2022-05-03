<?php
//include widget class
include_once "BookWidget.php";
/**
 * @package booksPlugin
 */
 /*
Plugin Name: Books plugin
 * Plugin URI:  https://wordpress.org/plugins/classic-editor/
 * Description: test
 * Version:     1.0.0
 * Author:      peyman rahmani
 * Author URI:  https://github.com/WordPress/classic-editor/
 * License:     GPLv2 or later
 * License URI: 
 * Text Domain: 
 * Domain Path: /languages
 * Requires at least: 4.9
 * Tested up to: 5.8
 * Requires PHP: 5.2.4
 */
 if(!defined('ABSPATH')){die( 'Invalid request' );};
 class booksPlugin{
        function __construct()
        {
           add_action('init',array($this,'custom_post_type'));
           add_action( 'init', 'add_books_to_json_api', 30 );
           add_shortcode('List_Books', array($this,'show_books_list_shortcode'));
           add_filter( 'single_template', array($this,'override_single_template'));     
        }
        function activate(){
            $this->custom_post_type();
            //generate books cpt
            flush_rewrite_rules();
        }
        function deactivate(){
            flush_rewrite_rules();
        }
        /**
         * @register book post type 
         *
         */
        function custom_post_type(){
            $labels = array(
                'name'                  => __( 'post type books' ),
                'singular_name'         => __( 'post type books' ),
                'menu_name'             => __( 'post type books' ),
                'name_admin_bar'        => __( 'post type books' ),
                'add_new'               => __( 'add new' ),
                'add_new_item'          => __( 'add book' ),
                'new_item'              => __( 'new post' ),
                'edit_item'             => __( 'edit post' ),
                'view_item'             => __( 'view post' ),
                'all_items'             => __( 'all posts' ),
            );
            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'show_in_rest'       =>true,
        //        'rewrite'            => array( 'slug' => 'book' ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'taxonomies'=>array('post_tag','category'),
                'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments','custom-fields' ),
            );
            register_post_type('book',$args);
            // register_taxonomy("categories", array("book"), array(
            //     "hierarchical" => true,
            //     "label" => "Categories",
            //     "singular_label" => "Category",
            //     "rewrite" => array( 'slug' => 'book', 'with_front'=> false ))
            // );
        }
        /**
         * @ display book lists as rest api
         * use : www.domain.com/wp-json/wp/v2/book
         */
        function add_books_to_json_api(){
            global $wp_post_types;
            $wp_post_types['book']->show_in_rest = true;
            $wp_post_types['book']->rest_base = 'book';
            $wp_post_types['book']->rest_controller_class = 'WP_REST_Posts_Controller';
        }
        /**
         * @ create shortcode for book lists
         */
        public function show_books_list_shortcode() {
            $posts = get_posts([
                'post_type' => 'book',
                'post_status' => 'publish',
                'numberposts' => -1
              ]);
              echo '<pre>';
              var_dump($posts);
              echo '</pre>';
             
        //    $quey=new WP_Query(
        //        array(
        //             'post_type'=>'book',
        //             'post_per_page'=>-1
        //        )
        //    );
        //    $res='';
        //    while($quey->have_posts()):
        //         $quey->the_post();
        //         $res.='<div>'.the_title().'</div>';
        //    endwhile;
        // wp_reset_query();
        // return $res;
        }
        /**
         * @override single template for books
         */
        function override_single_template( $single_template ){
            global $post;
           if($post->post_type == 'book'){
               $file = dirname(__FILE__) .'/templates/single-'. $post->post_type .'.php';
               if( file_exists( $file ) ) $single_template = $file;
           }
            return $single_template;
        }
        
 }
// new instanse
 if(class_exists('booksPlugin')){
     $booksPlugin= new booksPlugin();
 }
 //activation plugin
register_activation_hook( __FILE__, array( __CLASS__, 'activate' ) );
 //deactivation plugin
register_activation_hook( __FILE__, array( __CLASS__, 'deactivate' ) );