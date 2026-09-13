# GlobalShopCo-Headless — AgentOS Level-2 bounded acceptance workload

Status: READY AS NON-PRODUCTION FIXTURE
Date: 2026-09-13

## Architectural boundary
Shopify remains the canonical commerce backend/source of truth. The headless site may present catalogue/cart UX but must not create duplicate inventory, order or payment authority.

## Exact workload
1. Create or update only `fixtures/level2/headless-checkout-boundary.json`.
2. Record deterministic fixture data:
```json
{
  "schema": "globalshopco-headless.level2.v1",
  "cart_source": "headless-fixture",
  "checkout_authority": "shopify",
  "inventory_authority": "shopify",
  "order_authority": "shopify",
  "payment_authority": "shopify",
  "production_write": false
}
```
3. Reread and parse the fixture.
4. Produce a diff proving no commerce state or other project file changed.

## Acceptance
PASS requires only the named fixture mutation, valid JSON, exact task/mission/result correlation, approved-root confinement, durable receipt, replay protection and independent Green then PRS verification. Any mutation to real cart/order/inventory/payment state, credentials or deployment is FAIL/BLOCKED.

## Next product gate
The real M3 cart-to-Shopify-checkout proof remains separate and must preserve Shopify as the sole checkout/order authority.