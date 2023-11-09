<?php

namespace RadishConcepts\PottingSoil\PostTypes;

use RadishConcepts\PottingSoil\Plugin;
use RadishConcepts\PottingSoil\Traits\ArgsProperties;
use RuntimeException;

/**
 *
 *
 * @link https://developer.wordpress.org/reference/functions/register_post_type/
 *
 * @property-write string $description
 * @property-write boolean $public
 * @property-write boolean $hierarchical
 * @property-write boolean $exclude_from_search
 * @property-write boolean $publicly_queryable
 * @property-write boolean|string $show_ui
 * @property-write boolean $show_in_nav_menus
 * @property-write boolean $show_in_admin_bar
 * @property-write boolean $show_in_rest
 * @property-write string $rest_base
 * @property-write string $rest_namespace
 * @property-write string $rest_controller_class
 * @property-write integer $menu_position
 * @property-write string $menu_icon
 * @property-write string|array $capability_type
 * @property-write string[] $capabilities
 * @property-write boolean $map_meta_cap
 * @property-write array $supports
 * @property-write callable $register_meta_box_cb
 * @property-write string[] $taxonomies
 * @property-write boolean|string $has_archive
 * @property-write boolean|array $rewrite
 * @property-write boolean|string $query_var
 * @property-write boolean $can_export
 * @property-write boolean $delete_with_user
 * @property-write array $template
 * @property-write false|string $template_lock
 */
abstract class PostType implements PostTypeInterface {
	use ArgsProperties;

	private static self $instance;

	/**
	 * The post type slug.
	 *
	 * @var string
	 */
	protected string $post_type;

	/**
	 * The post type name in plural format.
	 *
	 * @var string
	 */
	private string $name;

	/**
	 * The post type name in singular format.
	 *
	 * @var string
	 */
	private string $singular_name;

	/**
	 * PostType constructor.
	 */
	private function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}

	/**
	 * Initialize the post type.
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
			'public' => true,
			'supports' => [
				'title',
				'editor',
			]
		]);

		// Register the post type.
		register_post_type( $this->post_type, $args );
	}

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	public static function register(): void {
		self::$instance = new static();
	}

	/**
	 * Get the post type instance.
	 *
	 * @return self
	 */
	public static function get_instance(): self {
		return self::$instance;
	}

	/**
	 * Return the labels for the post type.
	 *
	 * @return array
	 */
	private function labels(): array {
		return [
			'name'                      => $this->name,
			'singular_name'             => $this->singular_name,
			'add_new'                   => __( 'Add new', 'potting-soil' ),
			'add_new_item'              => sprintf( __( 'Add new %s', 'potting-soil' ), $this->singular_name ),
			'edit_item'                 => sprintf( __( 'Edit %s', 'potting-soil' ), $this->singular_name ),
			'new_item'                  => sprintf( __( 'New %s', 'potting-soil' ), $this->singular_name ),
			'view_item'                 => sprintf( __( 'View %s', 'potting-soil' ), $this->singular_name ),
			'view_items'                => sprintf( __( 'View %s',  ), 'potting-soil' ),
			'search_items'              => sprintf( __( 'Search %s', 'potting-soil' ), $this->name ),
			'not_found'                 => sprintf( __( 'No %s found', 'potting-soil' ), $this->name ),
			'not_found_in_trash'        => sprintf( __( 'No %s found in trash', 'potting-soil' ), $this->name ),
			'parent_item_colon'         => sprintf( __( 'Parent %s:', 'potting-soil' ), $this->singular_name ),
			'all_items'                 => sprintf( __( 'All %s', 'potting-soil' ), $this->name ),
			'archives'                  => sprintf( __( '%s archives', 'potting-soil' ), $this->singular_name ),
			'attributes'                => sprintf( __( '%s attributes', 'potting-soil' ), $this->singular_name ),
			'insert_into_item'          => sprintf( __( 'Insert into %s', 'potting-soil' ), $this->singular_name ),
			'uploaded_to_this_item'     => sprintf( __( 'Uploaded to this %s', 'potting-soil' ), $this->singular_name ),
			'featured_image'            => sprintf( __( '%s image', 'potting-soil' ), $this->singular_name ),
			'set_featured_image'        => sprintf( __( 'Set %s image', 'potting-soil' ), $this->singular_name ),
			'remove_featured_image'     => sprintf( __( 'Remove %s image', 'potting-soil' ), $this->singular_name ),
			'use_featured_image'        => sprintf( __( 'Use as %s image', 'potting-soil' ), $this->singular_name ),
			'menu_name'                 => $this->name,
			'filter_items_list'         => sprintf( __( 'Filter %s list', 'potting-soil' ), $this->name ),
			'filter_ny_date'            => sprintf( __( 'Filter by %s date', 'potting-soil' ), $this->singular_name ),
			'items_list_navigation'     => sprintf( __( '%s list navigation', 'potting-soil' ), $this->name ),
			'items_list'                => sprintf( __( '%s list', 'potting-soil' ), $this->name ),
			'item_published'            => sprintf( __( '%s published', 'potting-soil' ), $this->singular_name ),
			'item_published_privately'  => sprintf( __( '%s published privately', 'potting-soil' ), $this->singular_name ),
			'item_reverted_to_draft'    => sprintf( __( '%s reverted to draft', 'potting-soil' ), $this->singular_name ),
			'item_scheduled'            => sprintf( __( '%s scheduled', $this->singular_name ), $this->singular_name ),
			'item_updated'              => sprintf( __( '%s updated', 'potting-soil' ), $this->singular_name ),
			'item_link' 		        => sprintf( __( '%s link', 'potting-soil' ), $this->singular_name ),
			'item_link_description'     => sprintf( __( '%s link description', 'potting-soil' ), $this->singular_name ),
		];
	}
}