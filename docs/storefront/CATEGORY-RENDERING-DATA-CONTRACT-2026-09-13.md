# GlobalShopCo Headless — Category Rendering & Shopify Data Contract

Date: 2026-09-13
Status: IMPLEMENTATION-READY / NOT LIVE

## Purpose
Define the fail-closed contract for rendering seven priority headless routes from presentation configuration plus Shopify-owned product data: five product categories plus Shopify-to-eBay and Shopify-to-Amazon channel categories.

This document does not authorize WordPress deployment, Shopify credential use, product publication, production writes, marketplace publication or checkout implementation outside Shopify.

## Authority boundary
Shopify remains the sole authority for:
- product identity and handle;
- variants and SKU;
- price and compare-at price;
- inventory/availability;
- publication/sale state;
- cart, checkout, payment and order state.

The headless layer may own only presentation configuration:
- category slug and route;
- category kind (`product-category` or `channel-category`);
- category title/subtitle;
- editorial intro;
- subcategory labels and order;
- category-to-Shopify query/tag/collection mapping;
- channel-gate reference;
- empty-state copy;
- SEO/AEO content;
- editorial guide links;
- presentation sort/filter defaults.

## Priority routes
1. `/home-organisation/`
2. `/pet/`
3. `/baby/`
4. `/safety/`
5. `/mobile-computer-accessories/`
6. `/shopify-ebay/`
7. `/shopify-amazon/`

## Category kinds

### Product category
Discovers Shopify products by customer-facing theme, then applies the owned-site commercial approval gate.

### Channel category
Discovers only products already in Shopify and then applies an additional marketplace-specific gate. Channel categories must not maintain a separate catalogue.

## Category configuration shape
Each category configuration record should contain:

```text
slug
route
label
kind
h1
intro
subcategories[]
shopify_query OR channel_gate
empty_state
seo_title
seo_description
faq[]
display_order
```

No product price, SKU, inventory, canonical title or availability may be stored here.

## Product retrieval
The category renderer requests products from Shopify through an approved Storefront/Admin boundary appropriate to the implementation environment.

Returned product data must be treated as authoritative only for the fields actually returned from Shopify at request time.

Minimum product fields required for a purchasable card:
- Shopify product ID;
- handle;
- title;
- current status/publication state;
- current price;
- current variant availability/inventory signal appropriate to Storefront API;
- product image/alt text where available;
- category mapping signal;
- current checkout/cart handoff target or variant identifier.

## Owned-site fail-closed inclusion predicate
A product may render as purchasable in an ordinary product category only if all required conditions are true:

```text
shopify_record_present
AND commercially_approved_for_owned_site
AND current_price > 0
AND not_research_only
AND not_not_for_sale
AND not_rejected
AND not_archived
AND current_shopify_sale_state_allows_display
```

Until the parent GlobalShopCo repository defines a machine-readable commercial approval field, implementation must default to exclusion rather than infer approval from ordinary Shopify DRAFT status, tags such as `exact-competitor-verified`, product existence, or non-zero price.

## Shopify-to-eBay inclusion predicate
A Shopify product may render on `/shopify-ebay/` only if the owned-site/base product evidence is sufficient AND all eBay gates are true:

```text
explicit_ebay_channel_approval
AND marketplace_permission_verified
AND fulfilment_model_compatible
AND stock_control_safe
AND freight_and_landed_cost_verified
AND ebay_fee_scenario_resolved
AND positive_conservative_channel_contribution
AND current_shopify_product_state_safe
```

Canonical commercial gate: `darrinbaldwindev/GlobalShopCo#17`.

Do not infer eBay eligibility from ordinary dropshipping support, Shopify existence, supplier integration or a realistic retail price.

## Shopify-to-Amazon inclusion predicate
A Shopify product may render on `/shopify-amazon/` only if:

```text
amazon_channel_setup_complete
AND exact_sku_and_category_eligible
AND seller_account_and_marketplace_requirements_resolved
AND fulfilment_model_approved
AND fees_and_fulfilment_costs_verified
AND restrictions_and_brand_ip_risk_cleared
AND positive_conservative_channel_contribution
AND explicit_amazon_channel_approval
AND current_shopify_product_state_safe
```

Current portfolio state is `PRE-SETUP / RESEARCH`, so this route currently renders editorial/research state only and no Amazon-ready product cards.

## Explicit exclusion rules
Never show a purchasable card when any of these is true:
- price is A$0;
- tag includes `review-required`;
- tag includes `not-publication-approved`;
- tag includes `qualification-draft`;
- tag includes `not-for-sale`;
- product status is ARCHIVED;
- commercial approval cannot be positively established;
- required price/availability data is missing or malformed;
- required marketplace/channel approval is missing for a channel category.

A DRAFT product must not be assumed safe merely because it has a realistic title or price.

## Zero-product behaviour
If the filtered product set is empty, every route still renders:
- category H1 and intro;
- subcategory/channel navigation;
- editorial/helpful content;
- safe empty-state copy;
- no fake product cards;
- no placeholder prices;
- no disabled purchase controls that imply inventory.

For channel categories, show the current channel state truthfully. Amazon must currently say setup/research is still in progress rather than implying listings exist.

## Partial-data behaviour
If Shopify returns some products but one product lacks required fields:
- exclude that product only;
- log/surface a non-customer-facing diagnostic where implementation supports it;
- do not fail the whole category page unless the upstream request itself is unusable.

If Shopify is unavailable:
- render category editorial content and a neutral temporary-unavailable message;
- do not serve stale price/inventory as if current unless an explicitly governed cache policy later exists;
- do not expose checkout controls from stale data.

## Mapping strategy
Initial product-category presentation mappings may use Shopify tags/product type/collections, but mappings remain subordinate to Shopify source-of-truth and commercial approval.

Suggested mappings:
- Home Organisation → `product_type:"Home Organisation"` and/or `tag:home-organisation`
- Pet → `tag:pet-safety` plus future approved pet merchandise tags
- Baby → `tag:baby-safety`
- Safety → safety-specific approved tags across baby/pet/home products
- Mobile & Computer Accessories → `product_type:"Mobile & Computer Accessories"` and/or `tag:mobile-computer-accessories`

Channel categories must be generated from explicit marketplace approval evidence, not broad Shopify queries alone.

### Shopify-to-eBay category universe
Repo-backed initial categories:
- Home Storage & Organisation
- Pet Accessories
- Kids Storage & Organisation
- Kitchen & Pantry Organisation
- Mobile & Computer Accessories
- Garden & Outdoor Utility
- Baby & Home Safety
- Automotive Accessories
- Fitness & Exercise Accessories
- Cleaning & Household Utility

### Shopify-to-Amazon category universe
Amazon is not yet operational. Initial headless clusters may mirror evidence-qualified GlobalShopCo categories for research/navigation, but Amazon marketplace category/eligibility mapping must be verified before any product is represented as Amazon-ready.

## Product card contract
A rendered product card may contain only current Shopify-backed commerce fields plus approved presentation text:
- current title;
- current image;
- current price;
- verified delivery badge only when parent commercial evidence permits it;
- stock/availability wording derived from Shopify-supported availability semantics;
- link to product detail page;
- Shopify cart/checkout handoff for owned-site purchase.

Channel-category pages may additionally display a neutral marketplace availability/status label only when verified. Do not expose internal supplier margin, research tags, competitor pricing, approval notes or confidence scores.

## Product detail page contract
The product detail page may enrich Shopify product data with editorial presentation but must not override:
- canonical price;
- canonical SKU/variant identity;
- sale availability;
- inventory;
- checkout destination.

Any claims about delivery, warranty, materials, compliance, compatibility or performance require verified source evidence from the parent GlobalShopCo commercial gate.

## Checkout / marketplace boundary
The headless storefront does not process payment or maintain an independent cart/order authority.

Owned-site purchase flow:

```text
Headless category/product presentation
→ Shopify-backed variant selection
→ Shopify cart/checkout handoff
→ Shopify checkout/payment/order authority
```

Marketplace category pages may link to verified marketplace listings only after the relevant channel exists and publication is approved. They must not fabricate marketplace listing URLs or status.

## Repository role reconciliation
- `GlobalShopCo` = canonical Shopify product/commercial evidence and channel eligibility decisions.
- `shopify_ebay` = currently empty/inventory-only; future downstream integration workspace only, never catalogue authority.
- `MyPrimeDelivery` = separate Amazon Prime discovery project, not Shopify-to-Amazon integration authority.
- `GlobalShopCo-Headless` = presentation/category layer only.

## Acceptance tests for implementation
Before any live deployment, verify:
1. A$0 research products are excluded.
2. `review-required` products are excluded.
3. `not-publication-approved` and `qualification-draft` products are excluded.
4. ARCHIVED products are excluded.
5. `exact-competitor-verified` alone does not cause inclusion.
6. Product-category page renders correctly with zero approved products.
7. eBay route renders zero products when `EBAY-READY` evidence is absent.
8. Amazon route renders research/pre-setup state and zero Amazon-ready products while setup is incomplete.
9. A product approved for owned-site but not eBay/Amazon cannot leak into those channel pages.
10. One explicitly approved fixture product can render without duplicating Shopify price/inventory state.
11. Shopify outage results in safe editorial/unavailable state, not stale fake-commerce UI.
12. Cart/checkout handoff goes to Shopify.
13. No headless persistence creates a second catalogue/order source of truth.

## Current live-catalogue implication
As of the 2026-09-13 live Shopify review, there are zero fully commercial-verified/launch-ready products. Therefore all seven routes currently render editorial/empty states only. Shopify-to-Amazon additionally remains PRE-SETUP / RESEARCH.

Current disposition: CONTRACT READY FOR IMPLEMENTATION / ZERO PRODUCTS AUTHORISED FOR CUSTOMER PURCHASE.