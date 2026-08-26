# Headless Storefront Boundary

## Canonical relationship

`GlobalShopCo-Headless` owns only future headless storefront presentation and the bounded Shopify integration surface. `GlobalShopCo` remains the parent architecture/portfolio record. Shopify remains the authoritative commerce backend and checkout destination.

## Non-production first slice

Before any implementation, the responsible owner/delegate must provide a source-controlled, non-production evidence plan for one controlled product retrieval, presentation, approved Shopify checkout handoff, safe failure/empty states, and a no-secret review.

The planned product contract may include only the fields needed for the bounded slice: title, description, image, price/currency, variant/SKU, and availability. A missing product, unavailable variant, missing optional field, API failure/timeout, or checkout-handoff failure must be handled without exposing configuration or sensitive data.

## Configuration and secret rule

Only configuration categories may be documented in source control. Credential values, API tokens, private store identifiers, customer data, payment data, production settings, and raw telemetry are prohibited. The non-production configuration mechanism and provider/host require separate owner decisions.

## Provenance

The approved architecture source is `GlobalShopCo` commit `47013929f7e1d5d50630796bff0227af717163f9`, path `docs/architecture/SHOPIFY_HEADLESS_VERTICAL_SLICE.md`.

The ChatGPT Overseer M3 planning branch `GlobalShopCo:agent/chatgpt/m3-contract-prep` at `3cb489fa6ba4291f68fe4f8897ec732e73f27014` is a non-canonical proposal. Its contract/checklist may be reconciled with the approved architecture only through a future, explicitly authorized documentation change; it must not be copied or merged blindly.

## Exclusions

This boundary does not authorize product creation or publication, Shopify access, checkout activation, payment processing, hosting, deployment, release, marketplace integration, customer accounts, analytics, automation, full catalogue migration, multi-tenancy, or production operation.
