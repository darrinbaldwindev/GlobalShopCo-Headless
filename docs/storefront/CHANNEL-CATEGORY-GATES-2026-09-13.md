# GlobalShopCo Headless — Channel Category Gates

Date: 2026-09-13
Status: IMPLEMENTATION-READY / NOT LIVE

## Purpose
Define the fail-closed rendering rules for the two marketplace channel destinations:

- `/shopify-ebay/`
- `/shopify-amazon/`

These are channel views over Shopify-owned products. They are not separate catalogues and do not create independent price, inventory, SKU, cart, checkout or order authority.

## Shared authority model

Shopify remains authoritative for product identity, variants, SKU, price, inventory, availability, cart, checkout, payment and order state.

GlobalShopCo remains authoritative for commercial qualification. A channel page may only surface a product after the product passes both the ordinary owned-site commercial gate and the extra channel-specific gate.

A product can therefore be:

- Shopify-approved only;
- Shopify + eBay approved;
- Shopify + Amazon approved;
- Shopify + eBay + Amazon approved;
- not approved for customer sale anywhere.

Approval for one channel must never imply approval for another.

## Shopify → eBay gate

Canonical coordination/evidence source: `darrinbaldwindev/GlobalShopCo#17`.

A product may render in `/shopify-ebay/` only when all are true:

```text
shopify_record_present
AND commercially_approved_for_owned_site
AND ebay_channel_approval == approved
AND supplier_marketplace_permission == verified
AND fulfilment_model_compatible == verified
AND inventory_sync_model_safe == verified
AND packing_seller_identity_compatible == verified
AND buyer_data_handling_compatible == verified
AND returns_warranty_process == verified
AND ebay_fee_state_known
AND free_delivery_freight_evidence_known
AND conservative_ebay_contribution > required_buffer
AND listing_identity/category/item-specifics verified
```

Explicit fail-closed conditions include:

- marketplace permission is assumed rather than written/verified;
- trade cost is unknown where it controls margin;
- freight/packed/cubic treatment is unknown where it controls margin;
- eBay fee plan/category is unknown where it controls margin;
- stock ownership or fulfilment model is incompatible;
- seller identity/packing-slip handling is incompatible;
- buyer-data handling is incompatible;
- current stock cannot be safely synchronised;
- product would depend on a sale price materially above public market evidence;
- the product is merely an eBay candidate, shortlist member or `exact-competitor-verified` record.

Current truthful state: zero EBAY-READY products.

### eBay zero-product state

The page may still render:

- eBay channel introduction;
- the ten current eBay research categories;
- buyer guides and editorial content;
- transparent wording that products are being qualified;
- no fake listings, prices, stock counts or eBay badges.

Do not link to an eBay listing until a real approved listing exists.

## Shopify → Amazon gate

Current portfolio state: PRE-SETUP / RESEARCH.

No product may render as Amazon-ready until channel setup and SKU-level eligibility/economics are proven.

Minimum future predicate:

```text
shopify_record_present
AND commercially_approved_for_owned_site
AND amazon_channel_setup == complete
AND amazon_channel_approval == approved
AND seller_account_marketplace == verified
AND category_eligibility == verified
AND sku_or_gtin_identity == verified
AND supplier_amazon_compatibility == verified
AND fulfilment_model in [verified_fbm, verified_fba]
AND referral_fee_known
AND fulfilment_storage_cost_known_or_not_applicable
AND returns_cost_model_known
AND restriction_ip_brand_risk_acceptable
AND conservative_amazon_contribution > required_buffer
```

Until those conditions exist, `/shopify-amazon/` is an editorial/research destination only.

### Amazon zero-product state

The page may render:

- Amazon channel introduction;
- research topics such as FBA vs FBM, fees, restrictions, GTIN/barcodes and supplier compatibility;
- future category structure;
- neutral wording that the channel is being prepared;
- zero product cards labelled or implied as Amazon-ready.

Do not create Amazon listing URLs, Buy Box claims, Prime claims, review/rating claims, FBA badges or advertising claims from research assumptions.

## Relationship to MyPrimeDelivery

`MyPrimeDelivery` is a separate product/research project for surfacing top Amazon products/categories that are eligible for Prime delivery. It is not the Shopify → Amazon integration, seller channel or source of GlobalShopCo Amazon approval.

MyPrimeDelivery evidence may inform research only when independently verified and relevant; it must not confer GlobalShopCo Amazon listing eligibility.

## Acceptance tests

Before either channel page can show a purchasable/listed product, verify:

1. ordinary owned-site commercial approval exists;
2. channel-specific approval is independently positive;
3. channel approval is not inferred from another channel;
4. missing channel evidence excludes only the affected product and does not fabricate readiness;
5. zero-product state renders cleanly;
6. no independent price/inventory/catalogue state is persisted in Headless;
7. Shopify remains the product/commerce source of truth;
8. eBay/Amazon outbound links are emitted only when the corresponding real listing is verified;
9. stale channel evidence fails closed;
10. MyPrimeDelivery cannot accidentally satisfy the Shopify → Amazon approval predicate.

Current disposition: EBAY = RESEARCH-GATED / ZERO READY. AMAZON = PRE-SETUP / ZERO READY.