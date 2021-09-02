<?php

/**
 * Plugin Name: WP TRANSLATE
 * @function
 *  
 **/

  // Exit if accessed directly
  if ( ! defined( 'ABSPATH' ) ) {	
	exit;
}

class ALWPRT_TRANSLATE_LINK
{

    public $lang;

    public $to_translate;

    public function __construct ( $lang, $to_translate ) {
        
        $this->to_translate = $to_translate;

        $available_lang = get_option ( 'alwpr_langs', array() );

        if ( ! $this->to_translate ) {

            return;

        }



        $this->lang = $lang;

        // one argument in function
        add_filter( 'parent_post_rel_link', array($this, 'add_one_arguments' ) ); // $link
        add_filter( 'index_rel_link', array($this, 'add_one_arguments' ) ); // $link
        add_filter( 'woocommerce_breadcrumb_home_url', array($this, 'add_one_arguments' ) );
        
        foreach ( array( 'feed_link', 'author_link', 'search_link', 'year_link', 'month_link', 'day_link' ) as $filter ) {
            add_filter( $filter, array( $this, 'add_one_arguments' ), 20 );
		}
        add_filter( 'get_pagenum_link', array($this, 'add_one_arguments' ) );

        
        // two arguments in function
        add_filter( 'category_link', array($this, 'add_two_arguments' ), 10, 2 );
        add_filter( 'tag_link', array($this, 'add_two_arguments' ), 10, 2 );
        add_filter( 'woocommerce_loop_product_link', array($this, 'add_two_arguments' ), 10, 2 );

        add_filter( 'woocommerce_product_add_to_cart_url', array($this, 'add_two_arguments' ), 10, 2 ); // add to cart button

        // My filter
        add_filter( 'alwpr_woo_breadcrumb_link', array($this, 'add_two_arguments' ), 10, 2 );

        // three arguments in function 
        add_filter( 'post_link', array($this, 'add_three_arguments' ), 10, 3 );
        add_filter( 'post_link_category', array($this, 'add_three_arguments' ), 10, 3 );
        add_filter( 'pre_post_link', array($this, 'add_three_arguments' ), 10, 3 );
        add_filter( 'page_link', array($this, 'add_three_arguments' ), 10, 3 );
        add_filter( 'author_link', array($this, 'add_three_arguments' ), 10, 3 );

        // four arguments in function
        add_filter( 'nav_menu_link_attributes', array($this, 'add_four_arguments' ), 20, 4 );

        //five arguments in function 
        add_filter( 'page_menu_link_attributes', array($this, 'add_five_arguments' ), 10, 5 );



        add_filter( 'tag_link', array($this, 'add_tag_link' ), 10, 2 );
        add_filter( 'category_link', array($this, 'add_tag_link' ), 10, 2 );
        add_filter( 'term_link', array($this, 'add_tag_link' ), 10, 2 );


        add_filter( 'home_url', array($this, 'home_url' ), 10, 4  );
        


    }


    // add lang attribute to items needed only one argument
    public function add_one_arguments ($link) {
        
        $link = add_query_arg( 'lang', $this->lang , $link );
        
        return $link;
        
    }
    
    // add lang attribute to items needed only two arguments
    public function add_two_arguments ($link, $term_id) {
        
        $link = add_query_arg( 'lang', $this->lang , $link );
        
        return $link;
        
    }

    // add lang attribute to items needed only two arguments
    // apply_filters( 'tag_link', string $termlink, int $term_id )
    public function add_tag_link ($link, $term_id) {
        
        $link = add_query_arg( 'lang', $this->lang , $link );
        

        return $link;
        
    }
    
    // add lang attribute to items needed only three arguments
    public function add_three_arguments ($link, $post, $leavename) {
        
        $link = add_query_arg( 'lang', $this->lang , $link );
        
        return $link;
        
    }
    
    // add lang attribute to items needed only four arguments
    public function add_four_arguments ($atts, $item, $args, $depth) {
        
        $atts ['href'] = add_query_arg( 'lang', $this->lang, $atts ['href'] );
        
        return $atts;
        
    }
    
    // add lang attribute to items needed only five arguments
    public function add_five_arguments ($atts, $page, $depth, $args, $current_page) {

        $atts ['href'] = add_query_arg( 'lang', $this->lang, $atts ['href'] );

        return $atts;

    }


    
    public function home_url ($url, $path, $orig_scheme, $blog_id){

        $url = add_query_arg ( 'lang', $this->lang, $url );

        return $url;

    }



}