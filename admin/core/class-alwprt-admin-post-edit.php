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

class WPM_Admin_Posts {


	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'post_submitbox_misc_actions', array( $this, 'add_lang_indicator' ) );
		add_filter( 'page_link', array( $this, 'translate_post_link' ) );
		add_filter( 'attachment_link', array( $this, 'translate_post_link' ) );
		add_filter( 'post_link', array( $this, 'translate_post_link' ) );
		add_filter( 'post_type_link', array( $this, 'translate_post_link' ) );
		new WPM_Admin_Meta_Boxes();
	}


	/**
	 * Add language column to post type list
	 */
	public function init() {

		$post_types = get_post_types();

		foreach ( $post_types as $post_type ) {


			if ( 'attachment' === $post_type ) {
				add_filter( 'manage_media_columns', array( $this, 'language_columns' ) );
				add_action( 'manage_media_custom_column', array( $this, 'render_language_column' ) );
				continue;
			}

			add_filter( "manage_{$post_type}_posts_columns", array( $this, 'language_columns' ) );
			add_action( "manage_{$post_type}_posts_custom_column", array( $this, 'render_language_column' ) );
		}
	}


	/**
	 * Define language columns for post_types.
	 *
	 * @param  array $columns
	 *
	 * @return array
	 */
	public function language_columns( $columns ) {
		if ( empty( $columns ) && ! is_array( $columns ) ) {
			$columns = array();
		}

		if ( isset( $columns['languages'] ) ) {
			return $columns;
		}

		$language = array( 'languages' => __( 'Languages', 'alwpr-translate' ) );

		if ( isset( $columns['title'] ) ) {
			return wpm_array_insert_after( $columns, 'title', $language );
		}

		if ( isset( $columns['name'] ) ) {
			return wpm_array_insert_after( $columns, 'name', $language );
		}

		$columns = array_merge( $columns, $language );

		return $columns;
	}


	/**
	 * Output language columns for post types.
	 *
	 * @param string $column
	 */
	public function render_language_column( $column ) {

		if ( 'languages' === $column ) {

			$post      = wpm_untranslate_post( get_post() );
			$output    = array();
			$text      = $post->post_title . $post->post_content;
			$strings   = wpm_value_to_ml_array( $text );
			$languages = wpm_get_lang_option();

			foreach ( $languages as $code => $language ) {
				if ( isset( $strings[ $code ] ) && ! empty( $strings[ $code ] ) ) {
					$output[] = '<img src="' . esc_url( wpm_get_flag_url( $language['flag'] ) ) . '" alt="' . esc_attr( $language['name'] ) . '" title="' . $language['name'] . '">';
				}
			}

			if ( ! empty( $output ) ) {
				echo implode( ' ', $output );
			}
		}
	}


	/**
	 * Add indicator for editing post
	 *
	 * @param \WP_Post $post
	 */
	public function add_lang_indicator( $post ) {

		wp_enqueue_style( 'alwprt_translate_menu_style',  ALWPR_TRANSLATE_URL . '/assets/css/style.css');

		$available_lang = get_option ( 'alwpr_langs', array() );

			?>


<ul class="wpr-lang-list wpr-lang-list-flags">
<?php 
foreach ( $available_lang as $key=>$value ) {

  ?>
<li class="wpr-lang-list-element wpr-lang-flag-element">
    <a href="?wpr-lang=<?php echo $key; ?>"
    
    <?php 
    
    if  ( $current_lang  == $key ) {

      echo 'class="selected"';

    } 

    ?>

    >
        <img src="<?php echo ALWPR_TRANSLATE_URL . '/flags/' . $value['flag'] ; ?>" alt="<?php echo $key; ?>">
    </a>
</li>

<?php
}

?>

</ul>

			<?php
	}

	/**
	 * Translate posts link
	 *
	 * @param $permalink
	 *
	 * @return string
	 */
	public function translate_post_link( $permalink ) {
		// return wpm_translate_url( $permalink, wpm_get_language() );
	}
}
