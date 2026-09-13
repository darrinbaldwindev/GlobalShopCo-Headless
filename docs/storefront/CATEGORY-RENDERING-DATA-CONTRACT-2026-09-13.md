# GlobalShopCo Headless — Category Rendering & Shopify Data Contract

Date: 2026-09-13
Status: IMPLEMENTATION-READY / NOT LIVE

## Purpose
Define the fail-closed contract for rendering the five priority headless category routes from presentation configuration plus Shopify-owned product data.

This document does not authorize WordPress deployment, Shopify credential use, product publication, production writes, or checkout implementation outside Shopify.

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
- category title/subtitle;
- editorial intro;
- subcategory labels and order;
- category-to-Shopify query/tag/collection mapping;
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

## Category configuration shape
Each category configuration record should contain:

```text
slug
route
label
h1
intro
subcategories[]
shopify_query
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
- category mapping tag/collection signal;
- current checkout/cart handoff target or variant identifier.

## Fail-closed inclusion predicate
A product may render as purchasable only if all required conditions are true:

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

## Explicit exclusion rules
Never show a purchasable card when any of these is true:
- price is A$0;
- tag includes `review-required`;
- tag includes `not-publication-approved`;
- tag includes `qualification-draft`;
- tag includes `not-for-sale`;
- product status is ARCHIVED;
- commercial approval cannot be positively established;
- required price/availability data is missing or malformed.

A DRAFT product must not be assumed safe merely because it has a realistic title or price.

## Zero-product behaviour
If the filtered product set is empty, the category page still renders:
- category H1 and intro;
- subcategory navigation;
- editorial/helpful content;
- safe empty-state copy;
- no fake product cards;
- no placeholder prices;
- no disabled 'Add to cart' controls that imply inventory.

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
Initial presentation mappings may use Shopify tags/product type/collections, but mappings must remain subordinate to Shopify source-of-truth and commercial approval.

Suggested initial mappings:
- Home Organisation → `product_type:"Home Organisation"` and/or `tag:home-organisation`
- Pet → `tag:pet-safety` plus future approved pet merchandise tags
- Baby → `tag:baby-safety`
- Safety → safety-specific approved tags across baby/pet/home products
- Mobile & Computer Accessories → `product_type:"Mobile & Computer Accessories"` and/or `tag:mobile-computer-accessories`

These mappings discover candidates; they do not confer approval.

## Product card contract
A rendered product card may contain only current Shopify-backed commerce fields plus approved presentation text:
- current title;
- current image;
- current price;
- verified delivery badge only when parent commercial evidence permits it;
- stock/availability wording derived from Shopify-supported availability semantics;
- link to product detail page;
- Shopify cart/checkout handoff.

Do not display supplier margin, internal evidence status, research tags, competitor pricing, or internal confidence scores to customers.

## Product detail page contract
The product detail page may enrich Shopify product data with editorial presentation but must not override:
- canonical price;
- canonical SKU/variant identity;
- sale availability;
- inventory;
- checkout destination.

Any claims about delivery, warranty, materials, compliance, compatibility or performance require verified source evidence from the parent GlobalShopCo commercial gate.

## Checkout boundary
The headless storefront does not process payment or maintain an independent cart/order authority.

Purchase flow:

```text
Headless category/product presentation
→ Shopify-backed variant selection
→ Shopify cart/checkout handoff
→ Shopify checkout/payment/order authority
```

## Acceptance tests for implementation
Before any live deployment, verify:
1. A$0 research products are excluded.
2. `review-required` products are excluded.
3. `not-publication-approved` and `qualification-draft` products are excluded.
4. ARCHIVED products are excluded.
5. `exact-competitor-verified` alone does not cause inclusion.
6. Category page renders correctly with zero approved products.
7. One explicitly approved fixture product can render without duplicating Shopify price/inventory state.
8. Shopify outage results in safe editorial/unavailable state, not stale fake-commerce UI.
9. Cart/checkout handoff goes to Shopify.
10. No headless persistence creates a second catalogue/order source of truth.

## Current live-catalogue implication
As of the 2026-09-13 live Shopify review, there are zero fully commercial-verified/launch-ready products. Therefore the five priority categories should currently render editorial/empty states only until a product receives explicit owned-site approval.

Current disposition: CONTRACT READY FOR IMPLEMENTATION / ZERO PRODUCTS AUTHORISED FOR CUSTOMER PURCHASE.
