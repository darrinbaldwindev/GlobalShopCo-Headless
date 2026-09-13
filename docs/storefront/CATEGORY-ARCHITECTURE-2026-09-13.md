# GlobalShopCo Headless — Category-First Storefront Architecture

Date: 2026-09-13
Status: IMPLEMENTATION-READY CONTENT / NOT LIVE

## Purpose
Build the headless storefront around useful category destinations before the commercial catalogue is fully qualified. Shopify remains the sole authority for products, variants, pricing, inventory, cart, checkout and orders.

This document defines presentation structure only. It does not authorize product publication, duplicate catalogue state, checkout implementation outside Shopify, production WordPress writes, deployment, credentials or live customer traffic.

## Category priority

Product-category destinations:
1. Home Organisation
2. Pet
3. Baby
4. Safety
5. Mobile & Computer Accessories

Channel-category destinations:
6. Shopify to eBay
7. Shopify to Amazon

Other product categories should be added only after evidence-backed assortment exists.

## Customer-facing route model

- `/` — home / category discovery
- `/home-organisation/`
- `/pet/`
- `/baby/`
- `/safety/`
- `/mobile-computer-accessories/`
- `/shopify-ebay/`
- `/shopify-amazon/`

No empty generic department-store categories should be promoted in primary navigation until they have a coherent assortment or useful editorial value. The two marketplace routes are different: they are channel-category pages showing only Shopify products that have separately passed the relevant marketplace gate.

## Category kinds

### Product categories
Product categories organise the owned-site assortment by customer need. They may discover Shopify candidates using product type, tags or collections, but discovery never confers commercial approval.

### Channel categories
`Shopify to eBay` and `Shopify to Amazon` are marketplace/channel views over the same Shopify catalogue. They must never become separate product databases.

A product can therefore be:
- owned-site approved but not eBay approved;
- eBay approved but not Amazon approved;
- Amazon approved but not eBay approved;
- multichannel approved;
- Shopify-only.

Channel eligibility is additional evidence layered on top of Shopify product identity and owned-site/commercial evidence.

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

Channel-category pages additionally require:
- explicit channel state;
- marketplace eligibility gate;
- channel-specific economics gate;
- marketplace permission/policy compatibility where relevant;
- no implication that Shopify approval equals marketplace approval.

## Product inclusion rule

Owned-site product category minimum rule:

`Shopify product exists + commercial qualification state approved + current price/stock safe to expose -> render product`

Shopify-to-eBay minimum rule:

`Shopify product exists + owned-site/commercial evidence sufficient + explicit eBay approval + marketplace permission + fulfilment compatibility + positive eBay economics -> render in Shopify to eBay`

Shopify-to-Amazon minimum rule:

`Shopify product exists + Amazon channel setup complete + exact SKU/category eligible + fulfilment model approved + positive Amazon economics + explicit channel approval -> render in Shopify to Amazon`

Otherwise:
- do not invent a product card;
- do not expose A$0 research candidates;
- do not expose archived/rejected candidates;
- do not interpret `exact-competitor-verified` as launch-ready;
- do not turn draft research data into a sales claim;
- do not infer marketplace approval from ordinary dropshipping support.

## Zero-product state

A category can be live as an editorial/navigation destination before it has purchasable products. Use useful customer copy and explain that products appear only after the relevant evidence gate is passed.

For Shopify to Amazon specifically, current repo evidence says the channel is PRE-SETUP / RESEARCH. It must not present products as Amazon-ready yet.

Do not show fake inventory, fake countdowns, placeholder prices or disabled product cards that look purchasable.

## Shopify mapping

The headless layer may maintain presentation configuration such as:
- category slug;
- heading/subheading;
- editorial content;
- display order;
- Shopify collection/tag/query mapping;
- channel-gate reference;
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

### Shopify to eBay
Repo-backed initial category universe:
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

### Shopify to Amazon
Current state is research-first rather than a fixed final taxonomy. Initial presentation clusters may mirror evidence-qualified GlobalShopCo categories, but final Amazon category mapping must follow verified Amazon marketplace eligibility and exact SKU/category requirements. Initial clusters:
- Home & Household
- Pet
- Baby & Safety
- Mobile & Computer Accessories
- Kitchen & Pantry
- Other evidence-qualified categories

## Portfolio/repository reconciliation

- `GlobalShopCo` is the canonical product/commercial evidence source.
- `shopify_ebay` currently remains inventory-only/empty; it may later become a downstream integration workspace but not a catalogue authority.
- Shopify to eBay already has a canonical commercial gate in `GlobalShopCo#17`.
- Shopify to Amazon is already defined in the portfolio marketing plan as a separate commercial engine, currently PRE-SETUP / RESEARCH.
- `MyPrimeDelivery` is separate: it surfaces top Amazon products/categories eligible for Prime delivery. It is not the Shopify-to-Amazon integration authority.

## Navigation rule

Primary navigation may surface the seven priority destinations: five product-category pages plus Shopify to eBay and Shopify to Amazon. The two marketplace pages must carry channel-specific state and fail closed when zero products pass their gate.

Secondary/footer navigation may include About, Delivery, Returns, Contact, Privacy and Terms once actual customer-facing policy pages exist.

## Commercial sequencing

1. Build the seven category routes and editorial content.
2. Implement safe Shopify-backed product-grid contract.
3. Render zero-product states where no products are qualified.
4. Feed products into owned-site categories only as GlobalShopCo qualification advances them.
5. Feed products into Shopify-to-eBay only after `GlobalShopCo#17` channel approval.
6. Keep Shopify-to-Amazon editorial/research-only until channel setup and SKU eligibility gates exist.
7. Prove Shopify cart/checkout handoff with one approved owned-site product.
8. Expand category breadth after real assortment depth exists.

## Acceptance criteria

Category-first structure is ready for implementation when:
- the seven priority routes are defined;
- every route has editorial and empty-state content;
- channel categories cannot bypass their marketplace gate;
- no presentation config duplicates commerce authority;
- product inclusion fails closed;
- A$0/draft/research-only records cannot leak into purchasable UI;
- Shopify remains the canonical product/commerce authority;
- no claim says the pages are live until a WordPress implementation/deployment actually exists.

Current disposition: READY FOR HEADLESS IMPLEMENTATION, NOT LIVE.