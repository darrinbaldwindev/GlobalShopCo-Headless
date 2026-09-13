# GlobalShopCo Headless — Category-First Storefront Architecture

Date: 2026-09-13
Status: IMPLEMENTATION-READY CONTENT / NOT LIVE

## Purpose
Build the headless storefront around useful category destinations before the commercial catalogue is fully qualified. Shopify remains the sole authority for products, variants, pricing, inventory, cart, checkout and orders.

This document defines presentation structure only. It does not authorize product publication, duplicate catalogue state, checkout implementation outside Shopify, production WordPress writes, deployment, credentials or live customer traffic.

## Category priority

1. Home Organisation
2. Pet
3. Baby
4. Safety
5. Mobile & Computer Accessories
6. Other categories only after evidence-backed assortment exists

## Customer-facing route model

- `/` — home / category discovery
- `/home-organisation/`
- `/pet/`
- `/baby/`
- `/safety/`
- `/mobile-computer-accessories/`

No empty generic department-store categories should be promoted in primary navigation until they have a coherent assortment or useful editorial value.

## Category page contract

Each category page should support:

1. category title and one-sentence value proposition;
2. concise editorial intro;
3. subcategory navigation;
4. optional featured-guide/editorial cards;
5. Shopify-backed product grid;
6. safe zero-product state;
7. trust strip covering free-delivery policy, Shopify checkout handoff and returns/support wording only when those claims are verified;
8. FAQ/editorial block suitable for SEO/AEO without inventing product claims;
9. canonical metadata/schema generated from page content and Shopify product data where applicable.

## Product inclusion rule

A product may appear as purchasable only when the parent GlobalShopCo commercial gate has advanced it to an explicitly approved owned-site state.

Minimum rule:

`Shopify product exists + commercial qualification state approved + current price/stock safe to expose -> render product`

Otherwise:

- do not invent a product card;
- do not expose A$0 research candidates;
- do not expose archived/rejected candidates;
- do not interpret `exact-competitor-verified` as launch-ready;
- do not turn draft research data into a sales claim.

## Zero-product state

A category can be live as an editorial/navigation destination before it has purchasable products. Use useful customer copy such as:

> We are selecting products for this category against supplier, delivery, value and quality checks. Browse the guides and check back as products are added.

Do not show fake inventory, fake countdowns, placeholder prices or disabled product cards that look purchasable.

## Shopify mapping

The headless layer may maintain presentation configuration such as:

- category slug;
- heading/subheading;
- editorial content;
- display order;
- Shopify collection/tag/query mapping;
- empty-state copy;
- SEO/AEO fields.

It must not maintain an independent copy of:

- product title as canonical product identity;
- price;
- SKU/variant state;
- inventory;
- sale availability;
- cart/order/payment state.

Those remain Shopify-owned.

## Initial subcategory model

### Home Organisation
- Pantry & Kitchen Storage
- Bathroom Organisation
- Under-Sink Storage
- Shelving & Vertical Storage
- Wardrobe & Clothing Storage
- Desk & Small-Space Organisation

### Pet
- Toys & Enrichment
- Feeding & Hydration
- Walking & Travel
- Pet Storage
- Home & Cleanup
- Safety & Visibility

### Baby
- Feeding
- Bath & Care
- Travel & Portable Care
- Nursery
- Home Organisation

### Safety
- Baby Proofing
- Pet Travel Safety
- Home Containment
- Night Visibility
- Monitoring & Alerts

### Mobile & Computer Accessories
- Laptop Desks & Stands
- Desk Organisation
- Charging & Power
- Cables & Connectivity
- Device Protection
- Travel Accessories

## Navigation rule

Primary navigation should initially surface only the five priority destinations. Secondary/footer navigation may include About, Delivery, Returns, Contact, Privacy and Terms once the actual customer-facing policy pages exist.

## Commercial sequencing

1. Build category routes and editorial content.
2. Implement safe Shopify-backed product-grid contract.
3. Render zero-product states where no products are qualified.
4. Feed products into categories only as GlobalShopCo qualification advances them.
5. Prove Shopify cart/checkout handoff with one approved product.
6. Expand category breadth after real assortment depth exists.

## Acceptance criteria

Category-first structure is ready for implementation when:

- the five priority category routes are defined;
- every route has subcategory, editorial and empty-state content;
- no presentation config duplicates commerce authority;
- product inclusion fails closed;
- A$0/draft/research-only records cannot leak into purchasable UI;
- Shopify checkout remains the purchase boundary;
- no claim says the pages are live until a WordPress implementation/deployment actually exists.

Current disposition: READY FOR HEADLESS IMPLEMENTATION, NOT LIVE.