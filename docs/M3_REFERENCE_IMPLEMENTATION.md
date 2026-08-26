# M3 Reference Implementation

## Implemented

The `globalshopco-headless` WordPress plugin provides a minimal Storefront API product retrieval path. It:

1. Reads Shopify connection values from runtime configuration.
2. Requests one product by handle from the Storefront GraphQL API.
3. Handles missing configuration, transport failures, GraphQL errors and missing products without exposing credentials.
4. Renders the approved minimum product fields.

## Not implemented yet

- Shopify checkout URL/cart handoff
- automated browser/integration tests
- production caching strategy
- production observability
- deployment configuration

## Acceptance evidence still required

- A non-production WordPress instance configured with the test Shopify store.
- Successful retrieval of `GSCO-TEST-001`.
- Demonstrated unavailable/missing-product behaviour.
- Demonstrated checkout handoff to Shopify.
- Evidence that no Storefront token is exposed in rendered HTML.
