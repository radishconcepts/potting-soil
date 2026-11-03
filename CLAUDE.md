# Potting Soil — notes for Claude

## What this repo is

`radishconcepts/potting-soil` is a **Composer library**, not a site. It's the shared base for Radish Concepts' WordPress
plugins and themes. It doesn't run by itself: almost all code calls WordPress functions (`add_action`,
`plugin_dir_path`, `trailingslashit`, `register_post_type`, …), and those only exist inside a WordPress install that
requires this package.

- Namespace `RadishConcepts\PottingSoil\` → `classes/` (PSR-4). No build step, no assets, no tests, no CI.
- `composer.json` requires `php: ^8.1`. **Keep code compatible with PHP 8.1**: no `readonly` classes (8.2), typed class
  constants / `#[\Override]` (8.3), property hooks / asymmetric visibility (8.4). It does have to run *without
  deprecations* on 8.4 (see `.docs/php8.4-migration.md`).
- `vendor/` is git-ignored and out of date compared to `composer.lock` (it has wp-cli etc.). Ignore it. Never scan it for issues.
- Translations: `languages/nl_NL.po/.mo`, textdomain `potting-soil`.

### Main pieces
- `PottingSoil` — internal singleton. `PottingSoil::bootstrap()` is called automatically by `ExtensionBase`; it loads the textdomain.
- `Plugin` / `Theme` — both `use ExtensionBase` (+ `HasIconsSprite`). Consumers call `Plugin::setup( __FILE__ )` in
  their main plugin file or `functions.php`, then use `Plugin::path()`, `::url()`, `::textdomain()`, `::icon()`, …
  `setup()` decides plugin vs theme by **class-name suffix** (`…Plugin` / `…Theme`).
- `PostTypes\PostType`, `Taxonomies\Taxonomy` — abstract classes. Consumers extend them and set WP args as magic properties
  (`ArgsProperties`). Constructors and `labels()` are public on purpose so consumers can override them.
- `Traits\Singleton` — `final` `get_instance()`/`__construct()`. Consumers override `init()`.
- `Helpers\{String,Array,Number}Helpers` — static helpers.

## Every change is a change for all consumer projects

Public method signatures, return types and behaviour are a public API used by many sites. Before you change one, check
how consumers call it (see "Known consumers" below). For example, before tightening a type, check that no project passes
`null`. Prefer additive, backwards-compatible changes. A breaking change needs a new minor/major version and has to be
called out clearly.

### Known consumers (local)
Reference / template: `~/Projects/scaffold-wordpress` (plugin `bookstore` in `app/www/wp-content/plugins/bookstore`).
Sites that require it (as of 2026-11): `~/Sites/{hollandwarmte,music-all,radishconcepts-v2,morssink,kmp-dashboard,4lof-art,kinderfondsmamas,gebotours,ipskampprinting}`.
To refresh the list:
```bash
find ~/Sites ~/Projects -maxdepth 6 -name composer.json -not -path '*/vendor/*' -not -path '*/node_modules/*' 2>/dev/null \
  | xargs grep -l 'radishconcepts/potting-soil' | grep -v '/potting-soil/'
```
Search consumer code for usages with e.g. `grep -rn 'PottingSoil' --include='*.php' <site>/app/www/wp-content` (zsh: quote the glob).

## Testing locally

There is no test suite. Use these levels, cheapest first:

### 1. Lint + load every class (no WordPress needed)
Catches syntax errors and declaration-time deprecations (e.g. implicitly nullable params), not runtime behaviour.
```bash
find classes -name '*.php' -print0 | xargs -0 -n1 php -d error_reporting=E_ALL -l | grep -v '^No syntax errors'
```
Load script (write it to the scratchpad, not the repo). It stubs the few WP functions needed at load time:
```php
<?php // usage: php -d error_reporting=E_ALL load.php /path/to/potting-soil
error_reporting(E_ALL); $hits = 0;
set_error_handler(function ($no, $str, $f, $l) use (&$hits) { $hits++; echo "[$no] $str in $f:$l\n"; return true; });
foreach (['trailingslashit','add_action','add_filter','__'] as $fn) if (!function_exists($fn)) eval("function $fn(...\$a){return \$a[0]??null;}");
require $argv[1].'/vendor/autoload.php';   // run `composer install` first if vendor/ is missing
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($argv[1].'/classes')) as $f) {
    if ($f->getExtension() !== 'php') continue;
    $c = 'RadishConcepts\\PottingSoil\\'.str_replace(['/','.php'], ['\\',''], substr($f->getPathname(), strlen($argv[1].'/classes/')));
    echo (class_exists($c) || interface_exists($c) || trait_exists($c) ? 'ok   ' : 'FAIL ').$c."\n";
}
echo "deprecations/warnings: $hits\n";
```

### 2. Real WordPress: link this checkout into a sandbox site (recommended for behaviour changes)
The machine uses **Laravel Herd** (`.test` TLD, MySQL 8 on 127.0.0.1 as `root` with no password, `wp` CLI global).
Note: Herd's CLI PHP is 8.4, but its **default web PHP is 8.3**. Always check which version the site actually runs on.

#### Existing sandbox (Dennis' machine, set up 2026-11-03)
`~/Sites/potting-soil-sandbox` → https://potting-soil-sandbox.test (admin / admin), DB `potting_soil_sandbox`,
WordPress 7.1.4, PHP **8.4** (isolated + secured), `WP_DEBUG` + `WP_DEBUG_LOG` on.
`vendor/radishconcepts/potting-soil` is a **symlink to `~/Projects/potting-soil`**. Every edit and every `git checkout`
of a branch here is live in the site immediately, with no `composer update` needed.
Bookstore plugin + theme from the scaffold are active, and `Example::register()` is turned on in
`app/www/wp-content/plugins/bookstore/classes/Bootstrap.php`, so `PostType` is exercised (post type `example`, a published
post `/example/smoke/`).

#### Setting one up from scratch (tested recipe)
```bash
cd ~/Sites
# The scaffold is NOT on Packagist, so clone it (from GitHub or the local copy) instead of composer create-project
git clone git@github.com:radishconcepts/scaffold-wordpress.git potting-soil-sandbox && cd potting-soil-sandbox && rm -rf .git
composer install
wp core download --skip-content --path=app/www          # WP core is not in the scaffold repo
cp ~/Projects/scaffold-wordpress/app/www/wp-config-local.php app/www/   # git-ignored template; or write it by hand
#   → set DB_HOST 127.0.0.1, DB_USER root, DB_NAME potting_soil_sandbox, WP_DEBUG + WP_DEBUG_LOG true
mysql -uroot -h127.0.0.1 -e 'CREATE DATABASE potting_soil_sandbox CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'
composer config repositories.potting-soil "{\"type\":\"path\",\"url\":\"$HOME/Projects/potting-soil\",\"options\":{\"symlink\":true}}"
composer require radishconcepts/potting-soil:"dev-main as 1.1.99" -W   # name = branch checked out here at that moment (dev-develop on develop)
herd link potting-soil-sandbox && herd secure potting-soil-sandbox && herd isolate 8.4   # or 8.1 = minimum supported
wp core install --url=https://potting-soil-sandbox.test --title="Potting Soil Sandbox" --admin_user=admin --admin_password=admin --admin_email=<you> --skip-email
wp --skip-themes --skip-plugins plugin activate advanced-custom-fields-pro bookstore
```
Gotchas found while setting this up:
- **`herd secure` is required.** Without it there is no 443 server block, and HTTPS requests fall through to the first
  secured site alphabetically. Herd still serves the right files but with *that* site's PHP version (8.3).
  Check with a temp `app/www/_v.php` containing `<?php echo PHP_VERSION;` and remove it afterwards.
- `WP_DEFAULT_THEME` is `bookstore`, and the theme blocks everything (WP-CLI too) until ACF Pro is active. Hence
  `--skip-themes --skip-plugins` on the first activate.
- After `herd isolate`/`secure` nginx restarts. The first curl calls return `000`, so use `curl --retry 10 --retry-all-errors`.
- The scaffold locks old `wp-cli` 2.11 / `mustache` / `php-cli-tools` in `vendor/`, and those log their *own* PHP 8.4
  deprecations on every `wp` call. The bookstore plugin also logs a `bookstore-plugin-styles` dependency notice. That's scaffold noise, not Potting Soil.

#### Smoke test
```bash
cd ~/Sites/potting-soil-sandbox; L=app/www/wp-content/debug.log; : > $L
wp eval 'echo RadishConcepts\PottingSoil\Plugin::url("x"), "\n", RadishConcepts\PottingSoil\PottingSoil::path(), "\n";'
wp post-type list --fields=name | grep example
for u in / /example/smoke/ /wp-json/wp/v2/example /wp-login.php; do curl -sk "https://potting-soil-sandbox.test$u" -o /dev/null -w "$u %{http_code}\n"; done
# logged-in admin pages:
C=$(mktemp); curl -sk -c $C -b $C --cookie 'wordpress_test_cookie=WP Cookie check' -d 'log=admin&pwd=admin&testcookie=1' https://potting-soil-sandbox.test/wp-login.php -o /dev/null
for u in /wp-admin/ '/wp-admin/edit.php?post_type=example' '/wp-admin/post-new.php?post_type=example'; do curl -sk -b $C "https://potting-soil-sandbox.test$u" -o /dev/null -w "$u %{http_code}\n"; done; rm $C
grep -E 'vendor/radishconcepts|Projects/potting-soil' $L || echo "no Potting Soil notices"
```
Filter on `vendor/radishconcepts` / `Projects/potting-soil`, **not** on `potting-soil`, because that also matches the sandbox path.

Same trick for checking against a real consumer site: add the path repository **temporarily** in that site and
**don't commit** its changed `composer.json`/`composer.lock`. Run `git checkout composer.json composer.lock && composer install` afterwards.

Testing on PHP 8.1 without Herd: `docker run --rm -v "$PWD":/app -w /app php:8.1-cli php -l classes/...`.

## Git & release workflow
- Branches: `develop` → merged into `main`. Hotfixes can go straight to `main`.
- Tags are plain semver **without** `v` (`1.1.8`). Packagist picks up tags from GitHub (`radishconcepts/potting-soil`).
  **Never create or push a tag/release without explicit approval.**
- No CHANGELOG file. The release notes are the commit messages and tags.
- Commit message style: English, past tense, full sentence ending with a period ("Fixed the null notice in the ExtensionBase class.").

## Code style
WordPress-style spacing (`foo( $bar )`, `$args[ 'key' ]`, `!isset(`), tabs, opening braces on a new line for
classes/methods, docblocks with `@param`/`@return` on public methods. Match the surrounding code.
