Radishes need soil, so here is potting soil. The base for your WordPress projects.

# Installation

Add the package to your `composer.json` file:

```json
{
    "require": {
        "radishconcepts/potting-soil": "*"
    }
}
```

# Creating an Custom Post Type

For this you can create a new file in your theme or plugin in the includes/classes/PostTypes folder. The file should be
named after the post type you want to create. For example: `includes/classes/PostTypes/Seedling.php` is for the post type
book.

```php
<?php

namespace MyPlugin\PostTypes;

use RadishConcepts\PottingSoil\PostTypes\PostType;
use RadishConcepts\PottingSoil\Plugin;

class Seedling extends PostType {

    protected string $post_type = 'seedling';

    public function setup(): void {
        $this->singular_name = __( 'Seedling', Plugin::textdomain() );
        $this->plural_name   = __( 'Seedlings', Plugin::textdomain() );

        // You can overwrite default custom post type arguments by using $this->{property_name} = {value}.
    }
}
```

After creating the post type you need to register the custom post type by adding the following code to your main plugin
or theme file:

```php
MyPlugin\PostTypes\Seedling::register();
```