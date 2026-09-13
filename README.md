# GlobalShopCo-Headless

This private repository is the designated future source location for the **Global Shop Co headless WordPress storefront implementation**.

## Current sequencing

The storefront now follows a **category-first** preparation strategy while commercial product qualification continues in the parent `GlobalShopCo` project.

Launch-priority presentation routes:

1. Home Organisation
2. Pet
3. Baby
4. Safety
5. Mobile & Computer Accessories

Implementation-ready category architecture and copy are recorded in:

- [`docs/storefront/CATEGORY-ARCHITECTURE-2026-09-13.md`](docs/storefront/CATEGORY-ARCHITECTURE-2026-09-13.md)
- [`docs/storefront/CATEGORY-CONTENT-PACK-2026-09-13.md`](docs/storefront/CATEGORY-CONTENT-PACK-2026-09-13.md)

Category destinations may exist editorially before products are qualified, using safe zero-product states. Research candidates must not appear purchasable until the parent GlobalShopCo commercial gate explicitly approves them.

## Scope

The implementation remains strictly governed and non-production until separately authorised:

1. WordPress/headless pages present approved category/editorial content.
2. Approved Shopify product data is retrieved through an authorised Storefront API path.
3. Only commercially qualified products are rendered as purchasable.
4. Purchase actions hand off to the approved Shopify cart/checkout path.

Shopify remains the commerce, catalogue, product, price, inventory, cart, checkout, and order authority. This repository must not duplicate those systems of record or process payment.

## Source of truth

The parent architecture record is [`GlobalShopCo`](https://github.com/darrinbaldwindev/GlobalShopCo), specifically `docs/architecture/SHOPIFY_HEADLESS_VERTICAL_SLICE.md` at commit `47013929f7e1d5d50630796bff0227af717163f9`.

The source-of-truth and non-production boundaries are recorded in [`docs/architecture/HEADLESS_STOREFRONT_BOUNDARY.md`](docs/architecture/HEADLESS_STOREFRONT_BOUNDARY.md). Project governance history is recorded append-only in [`docs/overseer/OVERSEER.md`](docs/overseer/OVERSEER.md).

## Explicit exclusions

No production deployment, provider/hosting selection, WordPress installation, Shopify credential, product publication, payment processing, customer account, marketplace/eBay work, analytics, automation, bulk catalogue migration, multi-tenancy, or release process is authorized by this repository’s existence.

## Ownership

**Source owner/delegate:** Pending a separate owner decision naming an implementation authority. Darrin retains final authority for consequential implementation and operational decisions.
