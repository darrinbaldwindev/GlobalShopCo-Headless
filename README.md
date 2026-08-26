# GlobalShopCo-Headless

This private repository is the designated future source location for the **Global Shop Co headless WordPress storefront implementation**.

## Scope

The first planned slice is strictly non-production:

1. A controlled test product is retrieved through an approved Shopify Storefront API path.
2. A WordPress headless storefront presents the approved product fields and safe failure states.
3. The purchase action hands off to the approved Shopify cart/checkout path.

Shopify remains the commerce, catalogue, product, price, inventory, cart, checkout, and order authority. This repository must not duplicate those systems of record or process payment.

## Source of truth

The parent architecture record is [`GlobalShopCo`](https://github.com/darrinbaldwindev/GlobalShopCo), specifically `docs/architecture/SHOPIFY_HEADLESS_VERTICAL_SLICE.md` at commit `47013929f7e1d5d50630796bff0227af717163f9`.

The source-of-truth and non-production boundaries are recorded in [`docs/architecture/HEADLESS_STOREFRONT_BOUNDARY.md`](docs/architecture/HEADLESS_STOREFRONT_BOUNDARY.md). Project governance history is recorded append-only in [`docs/overseer/OVERSEER.md`](docs/overseer/OVERSEER.md).

## Explicit exclusions

No production deployment, provider/hosting selection, WordPress installation, Shopify credential, product publication, payment processing, customer account, marketplace/eBay work, analytics, automation, bulk catalogue migration, multi-tenancy, or release process is authorized by this repository’s existence.

## Ownership

**Source owner/delegate:** Pending a separate owner decision naming an implementation authority. Darrin retains final authority for consequential implementation and operational decisions.
