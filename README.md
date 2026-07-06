# aveadev/magento-2-austrian-language-pack

Austrian German (`de_AT`) language pack for Magento 2 — **standalone**.

This package ships its own complete `i18n/de_AT.csv` translation file. It does
not depend on any other language package and does not use the `<use vendor=.../>`
inheritance mechanism in `language.xml`. Installing or updating a German (`de_DE`)
pack has no effect on it, and it has no effect on `de_DE`.

## Why this exists

Magento's Locale setting (Stores → Configuration → General → Locale Options)
drives both translation selection and number/date/currency formatting, and
`hreflang` region codes for SEO are derived from it too. Setting the Austrian
store view to the same `de_DE` locale as Germany produces a duplicate `hreflang`
value across two different URLs — search engines treat that as an invalid
signal for the whole cluster, not just the AT/DE pair (`de-AT` and `de-DE` are
distinct IETF BCP 47 language tags for a reason: region subtag `AT` vs `DE`
tells a browser or crawler these are different locales, not different copies
of the same one). Giving Austria its own `de_AT` locale fixes that.

## What's in the box

```
magento-2-austrian-language-pack/
├── i18n/
│   └── de_AT.csv       ← full translation set (not just overrides)
├── language.xml
├── registration.php
└── composer.json
```

`de_AT.csv` needs to contain every string the storefront and admin will
render for that locale — there's no fallback to `de_DE` anymore, so a
missing key here means untranslated English (or the raw key) on the
storefront, not a silent inheritance.

## Installation

```bash
composer require aveadev/magento-2-austrian-language-pack:dev-main
sudo -u www-data php bin/magento setup:upgrade
sudo -u www-data php bin/magento setup:static-content:deploy de_AT
sudo -u www-data php bin/magento cache:flush
```

## Enabling it on the Austrian store view

1. **Stores → Configuration → General → Locale Options**
2. Switch scope (top-left) to your Austrian store view
3. Set **Locale** to `Deutsch (Österreich)` / `de_AT`
4. Save, then flush cache

## Verifying it

View-source a product page on `omegamix.at` and confirm:

```html
<link rel="alternate" hreflang="de-at" href="https://www.omegamix.at/..." />
```

...while `omegamix.de` still shows `hreflang="de-de"`. Also spot-check that
storefront text (checkout, shipping messages, product pages) renders
correctly in Austrian German — since there's no fallback, any gap in
`de_AT.csv` will show up as missing translation, not a silently-inherited
German string.

## Maintaining `de_AT.csv`

Because this pack no longer inherits, updates to a separate `de_DE` pack
(spelling fixes, new Magento core strings after a version upgrade, new
module strings) will **not** propagate here automatically. After any
Magento version upgrade or new module install, diff the new core/module
`i18n/de_DE.csv` files against this one and merge in new keys — otherwise
new strings introduced by an upgrade will render untranslated on the
Austrian storefront until you do.

**Tip:** keep `de_AT.csv` as a single flat file rather than splitting it
by module. Magento's language loader merges everything under `i18n/` for
the locale regardless of how many files it's split across, so there's no
performance benefit to splitting — just more files to search when you're
looking for one string.
