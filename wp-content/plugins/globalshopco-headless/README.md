# GlobalShopCo Headless WordPress plugin

Minimal M3 reference implementation.

## Setup

Define these values in the WordPress runtime configuration (for example `wp-config.php` or environment-backed constants):

- `GSCO_SHOPIFY_STORE_DOMAIN`
- `GSCO_SHOPIFY_STOREFRONT_TOKEN`
- `GSCO_SHOPIFY_API_VERSION` (defaults to `2026-07`)

Never commit the Storefront token.

## Usage

Add `[gsco_product handle="gsco-test-001"]` to a WordPress page.

The shortcode retrieves the product from Shopify's Storefront API and renders title, description, featured image, price/currency and availability. WordPress does not handle payment or order creation.

## M3 limitation

This is intentionally a reference vertical slice. Checkout handoff and automated acceptance tests remain separate tasks; do not treat this plugin as production-ready.
