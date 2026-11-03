# PHP 8.4 migration – Potting Soil

_Last checked: 2026-11-03, PHP 8.4.27 (Herd), against `main` @ 6a4b681 (tag 1.1.8)._

## Findings

| Issue | Location | Status |
|---|---|---|
| Implicitly nullable parameter `string $append = null` | `classes/Traits/Bases/ExtensionBase.php` `url()` | Fixed in 6a4b681 → `string $append = ''` (same as `path()`), docblock `@param string`. Released as **1.1.8** (2026-08-04). |

No caller in the package passes `null` to `url()`, so `''` as the default is safe.

## Checks performed

All 14 PHP files in `classes/` (vendor/ excluded):

- Implicitly nullable parameters (`Type $x = null` without `?`/`|null`): none left. The only `= null` defaults are typed `?self` properties.
- `fputcsv` / `fgetcsv` / `str_getcsv` / `SplFileObject` CSV without `$escape`: not used.
- `E_STRICT`: not used.
- `trigger_error(..., E_USER_ERROR)`: not used.
- `_` as a class name: none.
- `php -l` on every file with `error_reporting=E_ALL`: no errors or deprecations.
- Loaded every class, interface and trait through the Composer autoloader with `E_ALL` and an error handler that catches everything (minimal WP function stubs): 14/14 loaded, 0 deprecations or warnings.

### Runtime check in WordPress (2026-11-03)
Sandbox `~/Sites/potting-soil-sandbox` (scaffold-wordpress + WordPress 7.1.4, served on PHP 8.4.27, `WP_DEBUG_LOG` on,
Potting Soil symlinked to this checkout; see CLAUDE.md). Tested: `Plugin::setup()`, `Plugin::url()/path()/textdomain()`,
`PottingSoil::path()`, a `PostType` subclass (registration, labels, REST, single page), `Singleton` classes.
Frontend, REST, login and the admin pages for the post type all return 200. **0 log entries from Potting Soil.**

## Config changes

None. `composer.json` still says `"php": "^8.1"`, and the fix also works on 8.1.

## Open items

- No test suite in the repo.
- Projects that use Potting Soil must update to ≥ 1.1.8 (`composer update radishconcepts/potting-soil`) to get rid of the deprecation.
