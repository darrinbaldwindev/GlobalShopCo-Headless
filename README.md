# GlobalShopCo Headless

Authoritative implementation repository for the GlobalShopCo headless customer-facing layer.

## Architecture

- **Shopify** — commerce/product source of truth and checkout.
- **WordPress** — customer-facing headless frontend/presentation layer.
- **GlobalShopCo** — project governance, architecture, catalogue and Overseer documentation.

The first implementation target is the controlled non-production Shopify product `GSCO-TEST-001`.

## M3 vertical slice

The first slice is intentionally small:

`Shopify test product → WordPress retrieval → product display → Shopify checkout`

It must demonstrate product retrieval, presentation, availability handling and checkout handoff without moving payment/order logic into WordPress.

## Configuration

Secrets must never be committed. Runtime configuration/environment variables are required for Shopify integration values. Use local/environment-specific configuration for development and non-production validation.

## Scope protection

The first slice does **not** include marketplace integrations, advanced catalogue synchronisation, customer accounts, analytics, automation, multi-tenant functionality or production deployment.

## Source of truth

The approved architecture and M3 acceptance criteria live in the `GlobalShopCo` governance repository. Changes to the architecture should be recorded there before implementation decisions are changed here.
