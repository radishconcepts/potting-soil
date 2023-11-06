<?php

namespace RadishConcepts\PottingSoil\Taxonomies;

use RadishConcepts\PottingSoil\Traits\ArgsProperties;
use RuntimeException;

/**
 *
 *
 * @link https://developer.wordpress.org/reference/functions/register_taxonomy/
 *
 * @property-write string $description
 * @property-write boolean $public
 * @property-write boolean $publicly_queryable
 * @property-write boolean $hierarchical
 * @property-write boolean $show_ui
 * @property-write boolean $show_in_menu
 * @property-write boolean $show_in_nav_menus
 * @property-write boolean $show_in_rest
 * @property-write string $rest_base
 * @property-write string $rest_namespace
 * @property-write string $rest_controller_class
 * @property-write boolean $show_tagcloud
 * @property-write boolean $show_in_quick_edit
 * @property-write boolean $show_admin_column
 * @property-write boolean|callable $meta_box_cb
 * @property-write callable $meta_box_sanitize_cb
 * @property-write string[] $capabilities
 * @property-write bool|array $rewrite
 * @property-write boolean|string $query_var
 * @property-write callable $update_count_callback
 * @property-write string|array $default_term
 * @property-write boolean $sort
 * @property-write array $args
 */
abstract class Taxonomy implements TaxonomyInterface {
	use ArgsProperties;

	private static self $instance;

	/**
	 * The taxonomy slug.
	 *
	 * @var string
	 */
	protected string $taxonomy;

	/**
	 * The post types where the taxonomy should be registered.
	 *
	 * @var string|array
	 */
	protected string|array $post_types;

	/**
	 * Taxonomy constructor.
	 */
	private function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}

	/**
	 * Initialize the taxonomy.
	 *
	 * @return void
	 */
	public function init(): void {
		// Check if the method "setup" is implemented.
		if ( !method_exists( $this, 'setup' ) ) {
			throw new RuntimeException( 'The method "setup" must be implemented.' );
		}

		// Call the "setup" method.
		$this->setup();

		// Parse the arguments with the default arguments.
		$args = wp_parse_args( $this->args, [
			'labels' => $this->labels(),
			'public' => true
		]);

		// Check if the post types is a string or an array. When it is a string, convert it to an array.
		if ( is_string( $this->post_types ) ) {
			$post_types = [ $this->post_types ];
		} else {
			$post_types = $this->post_types;
		}

		// Register the post type.
		register_taxonomy( $this->taxonomy, $post_types, $args );
	}

	/**
	 * Register the taxonomy.
	 *
	 * @return void
	 */
	public static function register(): void {
		self::$instance = new static();
	}

	/**
	 * Get the taxonomy instance.
	 *
	 * @return self
	 */
	public static function get_instance(): self {
		return self::$instance;
	}

	/**
	 * Return the taxonomy labels.
	 *
	 * @return string[]
	 */
	private function labels(): array {
		return [
			'name'                       => '',
			'singular_name'              => '',
			'search_items'               => '',
			'popular_items'              => '',
			'all_items'                  => '',
			'parent_item'                => '',
			'parent_item_colon'          => '',
			'name_field_description'     => '',
			'slug_field_description'     => '',
			'parent_field_description'   => '',
			'desc_field_description'     => '',
			'edit_item'                  => '',
			'view_item'                  => '',
			'update_item'                => '',
			'add_new_item'               => '',
			'new_item_name'              => '',
			'separate_items_with_commas' => '',
			'add_or_remove_items'        => '',
			'choose_from_most_used'      => '',
			'not_found'                  => '',
			'no_terms'                   => '',
			'filter_by_item'             => '',
			'items_list_navigation'      => '',
			'items_list'                 => '',
			'most_used'                  => '',
			'back_to_items'              => '',
			'item_link'                  => '',
			'item_link_description'      => '',
		];
	}
}