<?php

namespace RadishConcepts\PottingSoil\Taxonomies;

use RadishConcepts\PottingSoil\Traits\ArgsProperties;

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
abstract class Taxonomy implements TaxonomyInterface
{
	use ArgsProperties;

	private static array $instances = [];

	/**
	 * The internal taxonomy name.
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
	 * The taxonomy name in plural format.
	 *
	 * @var string
	 */
	protected string $name;

	/**
	 * The taxonomy name in singular format.
	 *
	 * @var string
	 */
	protected string $singular_name;

	/**
	 * Taxonomy constructor.
	 */
	private function __construct()
	{
		add_action( 'init', [ $this, 'init' ] );
	}

	/**
	 * Initialize the taxonomy.
	 *
	 * @return void
	 */
	public function init(): void
	{
		// Check if the method "setup" is implemented.
		if ( !method_exists( $this, 'setup' ) ) {
			wp_die( 'The method "setup" must be implemented.' );
		}

		// Call the "setup" method.
		$this->setup();

		// Parse the arguments with the default arguments.
		$this->args = wp_parse_args( $this->args, [
			'labels'       => $this->labels(),
			'public'       => true,
			'show_in_rest' => true,
		]);

		// Check if the post types is a string or an array. When it is a string, convert it to an array.
		if ( is_string( $this->post_types ) ) {
			$post_types = [ $this->post_types ];
		} else {
			$post_types = $this->post_types;
		}

		// Register the post type.
		register_taxonomy( $this->taxonomy, $post_types, $this->args );
	}

	/**
	 * Return the taxonomy labels.
	 *
	 * @return string[]
	 */
	private function labels(): array
	{
		return [
			'name'                       => $this->name,
			'singular_name'              => $this->singular_name,
			'search_items'               => sprintf( __( 'Search %s', 'potting-soil' ), $this->name ),
			'popular_items'              => sprintf( __( 'Popular %s', 'potting-soil' ), $this->name ),
			'all_items'                  => sprintf( __( 'All %s', 'potting-soil' ), $this->name ),
			'parent_item'                => sprintf( __( 'Parent %s', 'potting-soil' ), $this->singular_name ),
			'parent_item_colon'          => sprintf( __( 'Parent %s:', 'potting-soil' ), $this->singular_name ),
			'name_field_description'     => sprintf( __( 'The name of the %s.', 'potting-soil' ), $this->singular_name ),
			'slug_field_description'     => sprintf( __( 'The slug of the %s.', 'potting-soil' ), $this->singular_name ),
			'parent_field_description'   => sprintf( __( 'The parent of the %s.', 'potting-soil' ), $this->singular_name ),
			'desc_field_description'     => sprintf( __( 'The description of the %s.', 'potting-soil' ), $this->singular_name ),
			'edit_item'                  => sprintf( __( 'Edit %s', 'potting-soil' ), $this->singular_name ),
			'view_item'                  => sprintf( __( 'View %s', 'potting-soil' ), $this->singular_name ),
			'update_item'                => sprintf( __( 'Update %s', 'potting-soil' ), $this->singular_name ),
			'add_new_item'               => sprintf( __( 'Add new %s', 'potting-soil' ), $this->singular_name ),
			'new_item_name'              => sprintf( __( 'New %s name', 'potting-soil' ), $this->singular_name ),
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'potting-soil' ), $this->name ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'potting-soil' ), $this->name ),
			'choose_from_most_used'      => sprintf( __( 'Choose from most used %s', 'potting-soil' ), $this->name ),
			'not_found'                  => sprintf( __( 'No %s found', 'potting-soil' ), $this->name ),
			'no_terms'                   => sprintf( __( 'No %s', 'potting-soil' ), $this->name ),
			'filter_by_item'             => sprintf( __( 'Filter by %s', 'potting-soil' ), $this->singular_name ),
			'items_list_navigation'      => sprintf( __( '%s list navigation', 'potting-soil' ), $this->name ),
			'items_list'                 => sprintf( __( '%s list', 'potting-soil' ), $this->name ),
			'most_used'                  => sprintf( __( 'Most used', 'potting-soil' ), $this->name ),
			'back_to_items'              => sprintf( __( '← Back to %s', 'potting-soil' ), $this->name ),
			'item_link'                  => sprintf( __( '%s link', 'potting-soil' ), $this->singular_name ),
			'item_link_description'      => sprintf( __( 'A link to a %s.', 'potting-soil' ), $this->singular_name ),
		];
	}

	/**
	 * Get the internal taxonomy name.
	 *
	 * @return string
	 */
	public static function get_taxonomy(): string
	{
		return self::$instances[ static::class ]->taxonomy;
	}

	/**
	 * Get text label for a specific key.
	 *
	 * @param $key
	 *
	 * @return string
	 */
	public static function get_label( $key ): string
	{
		return self::$instances[ static::class ]->labels()[ $key ] ?? '';
	}

	/**
	 * Register the taxonomy.
	 *
	 * @return void
	 */
	public static function register(): void
	{
		self::$instances[ static::class ] = new static();
	}

	/**
	 * Get the taxonomy instance.
	 *
	 * @return self
	 */
	public static function get_instance(): self
	{
		return self::$instances[ static::class ];
	}
}