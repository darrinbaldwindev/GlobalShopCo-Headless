# M3 Implementation Baseline

**Status:** NON-PRODUCTION PREPARATION

## Canonical relationship

This repository is the implementation location for the GlobalShopCo headless customer-facing layer. The `GlobalShopCo` repository remains the governance and architecture source of truth.

## Required first slice

1. Read a controlled Shopify test product.
2. Resolve the minimum product representation defined by the governance contract.
3. Render the product in WordPress.
4. Represent availability safely.
5. Initiate the approved Shopify cart/checkout handoff.
6. Demonstrate failure and empty states.

## Integration boundary

Shopify owns product, variant, pricing, inventory/availability, cart and checkout. WordPress owns presentation and the customer-facing purchase initiation.

## Security boundary

No credentials, tokens, secrets or production configuration belong in Git. Runtime configuration must be injected through the environment/deployment system.

## Definition of done for M3

- Implementation stack is selected and documented.
- Shopify Storefront API access works against non-production/test data.
- The controlled test product renders correctly.
- Purchase action reaches Shopify checkout/cart flow.
- Unavailable/missing/error states are handled.
- No secret values are committed.
- Reproducible setup and validation instructions exist.

## Deliberate non-goals

Do not implement marketplace integrations, bulk catalogue synchronisation, customer accounts, analytics, automation, multi-tenancy or production deployment in M3.
