<?php

namespace RadishConcepts\PottingSoil\PostTypes;

use RadishConcepts\PottingSoil\Plugin;
use RuntimeException;

/**
 *
 *
 * @link https://developer.wordpress.org/reference/functions/register_post_type/
 *
 * @property-write string $slug
 * @property-write string $singular_name
 * @property-write string $plural_name
 * @property-write string $description
 * @property-write boolean $public
 * @property-write boolean $hierarchical
 * @property-write boolean $exclude_from_search
 * @property-write boolean $publicly_queryable
 * @property-write boolean $show_ui
 * @property-write boolean $show_in_menu
 * @property-write boolean $show_in_nav_menus
 * @property-write boolean $show_in_admin_bar
 * @property-write integer $menu_position
 * @property-write string $menu_icon
 * @property-write string $capability_type
 * @property-write boolean $map_meta_cap
 * @property-write array $register_meta_box_cb
 * @property-write array $taxonomies
 * @property-write boolean|string $has_archive
 * @property-write boolean|array $rewrite
 * @property-write boolean $supports
 */
abstract class PostType {

	private static self $instance;

	protected string $post_type;

	protected array $args = [];

	private function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}

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

	public static function register(): void {
		self::$instance = new static();
	}

	public static function get_instance(): self {
		return self::$instance;
	}

	private function labels(): array {
		return [
			'name'                      => $this->plural_name,
			'singular_name'             => $this->singular_name,
			'add_new'                   => __( 'Add new', Plugin::textdomain() ),
			'add_new_item'              => sprintf( __( 'Add new %s', $this->singular_name ), Plugin::textdomain() ),
			'edit_item'                 => sprintf( __( 'Edit %s', $this->singular_name ), Plugin::textdomain() ),
			'new_item'                  => sprintf( __( 'New %s', $this->singular_name ), Plugin::textdomain() ),
			'view_item'                 => sprintf( __( 'View %s', $this->singular_name ), Plugin::textdomain() ),
			'view_items'                => sprintf( __( 'View %s', $this->plural_name ), Plugin::textdomain() ),
			'search_items'              => sprintf( __( 'Search %s', $this->plural_name ), Plugin::textdomain() ),
			'not_found'                 => sprintf( __( 'No %s found', $this->plural_name ), Plugin::textdomain() ),
			'not_found_in_trash'        => sprintf( __( 'No %s found in trash', $this->plural_name ), Plugin::textdomain() ),
			'parent_item_colon'         => sprintf( __( 'Parent %s:', $this->singular_name ), Plugin::textdomain() ),
			'all_items'                 => sprintf( __( 'All %s', $this->plural_name ), Plugin::textdomain() ),
			'archives'                  => sprintf( __( '%s archives', $this->singular_name ), Plugin::textdomain() ),
			'attributes'                => sprintf( __( '%s attributes', $this->singular_name ), Plugin::textdomain() ),
			'insert_into_item'          => sprintf( __( 'Insert into %s', $this->singular_name ), Plugin::textdomain() ),
			'uploaded_to_this_item'     => sprintf( __( 'Uploaded to this %s', $this->singular_name ), Plugin::textdomain() ),
			'featured_image'            => sprintf( __( '%s image', $this->singular_name ), Plugin::textdomain() ),
			'set_featured_image'        => sprintf( __( 'Set %s image', $this->singular_name ), Plugin::textdomain() ),
			'remove_featured_image'     => sprintf( __( 'Remove %s image', $this->singular_name ), Plugin::textdomain() ),
			'use_featured_image'        => sprintf( __( 'Use as %s image', $this->singular_name ), Plugin::textdomain() ),
			'menu_name'                 => $this->plural_name,
			'filter_items_list'         => sprintf( __( 'Filter %s list', $this->plural_name ), Plugin::textdomain() ),
			'filter_ny_date'            => sprintf( __( 'Filter by %s date', $this->singular_name ), Plugin::textdomain() ),
			'items_list_navigation'     => sprintf( __( '%s list navigation', $this->plural_name ), Plugin::textdomain() ),
			'items_list'                => sprintf( __( '%s list', $this->plural_name ), Plugin::textdomain() ),
			'item_published'            => sprintf( __( '%s published', $this->singular_name ), Plugin::textdomain() ),
			'item_published_privately'  => sprintf( __( '%s published privately', $this->singular_name ), Plugin::textdomain() ),
			'item_reverted_to_draft'    => sprintf( __( '%s reverted to draft', $this->singular_name ), Plugin::textdomain() ),
			'item_scheduled'            => sprintf( __( '%s scheduled', $this->singular_name ), Plugin::textdomain() ),
			'item_updated'              => sprintf( __( '%s updated', $this->singular_name ), Plugin::textdomain() ),
			'item_link' 		        => sprintf( __( '%s link', $this->singular_name ), Plugin::textdomain() ),
			'item_link_description'     => sprintf( __( '%s link description', $this->singular_name ), Plugin::textdomain() ),
		];
	}

	public function __set( string $argument, $value ): void {
		$this->args[ $argument ] = $value;
	}

	public function __get( string $argument ) {
		return $this->args[ $argument ] ?? null;
	}

	public function __isset( string $argument ): bool {
		return isset( $this->args[ $argument ] );
	}
}