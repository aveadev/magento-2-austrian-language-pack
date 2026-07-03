# aveadev/magento-2-austrian-language-pack

Austrian German (`de_AT`) language pack for Magento 2.

This package contains **no translation CSV files of its own**. It exists purely to
declare `de_AT` as a distinct, installable locale that inherits 100% of its strings
from [`aveadev/magento-2-german-language-pack`](.) (`de_DE`) via the native
`<use vendor="..." package="..."/>` mechanism in `language.xml`. This mirrors how
Magento's own core `magento/language-de_at` inherits from `magento/language-de_de`.

## Why this exists

Magento's `Locale` field (Stores → Configuration → General → Locale Options) drives
both translation selection *and* number/date/currency formatting, and separately,
`hreflang` region codes for international SEO are derived from the store's locale.
Setting Austria's store view to the same `de_DE` locale as Germany produces a
hreflang collision (`<link hreflang="de-de">` output twice, once per store, with
different URLs) — Google will treat the whole hreflang cluster as invalid,
not just the AT/DE pair. Giving Austria its own `de_AT` locale fixes that without
requiring a second, hand-maintained set of translations.

## Before you deploy — one thing to check

Open the `language.xml` in your existing `aveadev/magento-2-german-language-pack`
package and confirm the `<vendor>` and `<package>` values it declares itself as.
The `<use>` line in this package's `language.xml` **must match those exactly**:

```xml
<use vendor="Aveadev" package="de_de"/>
```

If your German pack declares different values, edit that line before deploying,
or Magento will fail to resolve the inheritance and static content deploy will
error out looking for the parent package.

## Installation

If `aveadev/magento-2-german-language-pack` is installed via a private Packagist/
Satis repository, add this package to the same repository, or add a `path`/`vcs`
repository entry for it in your project's root `composer.json`:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@your-git-host:aveadev/magento-2-austrian-language-pack.git"
    }
]
```

Then, from your Magento root:

```bash
composer require aveadev/magento-2-austrian-language-pack:dev-main
sudo -u www-data php bin/magento setup:upgrade
sudo -u www-data php bin/magento setup:static-content:deploy de_AT
```

## Enabling it on the Austrian store view

1. **Stores → Configuration → General → Locale Options**
2. Switch scope (top-left) to your Austrian store view
3. Set **Locale** to `Deutsch (Österreich)` / `de_AT`
4. Save, then flush cache: `sudo -u www-data php bin/magento cache:flush`

## Verifying the fix

View-source a product page on `omegamix.at` (bypass Varnish via direct-to-Apache
on port 8080 if you want to rule out stale cache) and confirm the `<head>` now
shows:

```html
<link rel="alternate" hreflang="de-at" href="https://www.omegamix.at/..." />
```

...while `omegamix.de` still correctly shows `hreflang="de-de"`. Also spot-check
that Austrian-facing text (checkout, shipping messages, etc.) still renders in
German exactly as before — this package should be visually invisible; the only
observable change should be the hreflang tag and any locale-driven number/date
formatting (e.g. Magento's own `de_AT` formatting conventions differ slightly
from `de_DE` for things like month names).

## Future Austria-specific wording

If you ever want a small Austrian-specific override (e.g. "Jänner" instead of
"Januar", or "Sackerl" instead of "Tüte"), add only that string to a CSV file
under `i18n/de_AT/` in this package — a package-local CSV always takes priority
over an inherited one for the same key, so you only maintain the handful of
lines that actually differ, not a full duplicate translation set.
