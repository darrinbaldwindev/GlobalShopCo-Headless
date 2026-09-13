# GlobalShopCo Headless — Category Content Pack

Date: 2026-09-13
Status: CONTENT READY / NOT LIVE

This pack supplies initial customer-facing copy for the category-first storefront. It is presentation content only. Product availability, pricing, stock, delivery eligibility and checkout remain Shopify-controlled and must be rendered from approved live commerce data.

## Home Organisation
Route: `/home-organisation/`
H1: Make everyday spaces easier to use
Intro: Practical storage and organisation ideas for kitchens, bathrooms, wardrobes, desks and smaller spaces. We are prioritising products that are useful, sensibly sized and commercially viable for Australian delivery.
Subcategories: Pantry & Kitchen Storage; Bathroom Organisation; Under-Sink Storage; Shelving & Vertical Storage; Wardrobe & Clothing Storage; Desk & Small-Space Organisation.
Editorial modules: Small-space storage ideas; pantry organisation basics; choosing shelves, baskets and drawer organisers; bathroom storage for limited bench space.
Empty state: We are qualifying products for this category now. Products will appear here only after supplier, delivery, pricing and quality checks are complete.
Merchandising rule: do not expose archived/rejected qualification drafts or A$0 placeholders.

## Pet
Route: `/pet/`
H1: Practical picks for pets and their people
Intro: Everyday pet products focused on play, feeding, walking, travel, storage, cleanup and visibility. Early assortment should favour compact, lower-return products with clear supplier and delivery evidence.
Subcategories: Toys & Enrichment; Feeding & Hydration; Walking & Travel; Pet Storage; Home & Cleanup; Safety & Visibility.
Editorial modules: Choosing enrichment toys by play style; travel essentials for dogs; organising pet food and accessories; visibility for evening walks.
Empty state: We are checking pet products for supplier reliability, delivery costs and suitability before adding them to the store.
Merchandising rule: researched Southern Pet products remain non-purchasable until the parent commercial gate explicitly approves them.

## Baby
Route: `/baby/`
H1: Everyday baby essentials, selected carefully
Intro: Feeding, care, travel, nursery and organisation products selected with extra attention to suitability, current product information and Australian customer expectations.
Subcategories: Feeding; Bath & Care; Travel & Portable Care; Nursery; Home Organisation.
Editorial modules: Feeding setup checklist; portable care essentials; nursery organisation ideas; what to check before buying baby accessories online.
Empty state: We are reviewing baby products carefully before making them available. Only products with sufficient supplier, commercial and product-safety evidence will be shown for purchase.
Merchandising rule: existing Shopify baby research records are not publication-ready merely because they exist in the catalogue.

## Safety
Route: `/safety/`
H1: Safer routines at home and on the move
Intro: Home-proofing, pet travel, visibility, containment and monitoring products where accurate specifications and responsible claims matter as much as price.
Subcategories: Baby Proofing; Pet Travel Safety; Home Containment; Night Visibility; Monitoring & Alerts.
Editorial modules: Home-proofing planning checklist; pet restraint and travel considerations; improving visibility on evening walks; assessing monitoring products before purchase.
Empty state: Safety-related products stay under review until current specifications, supplier evidence and any relevant compliance or claim requirements are sufficiently clear.
Merchandising rule: never repeat unverified crash-test, certification, monitoring or safety claims from research records as storefront facts.

## Mobile & Computer Accessories
Route: `/mobile-computer-accessories/`
H1: Useful accessories for work, study and everyday tech
Intro: Practical desk, laptop, charging, cable, protection and travel accessories with an emphasis on useful everyday products rather than catalogue clutter.
Subcategories: Laptop Desks & Stands; Desk Organisation; Charging & Power; Cables & Connectivity; Device Protection; Travel Accessories.
Editorial modules: Building a more comfortable laptop setup; cable organisation for small desks; choosing charging accessories carefully; travel-friendly tech organisation.
Empty state: Products are being assessed for supplier reliability, pricing, delivery and compatibility before they are added here.
Merchandising rule: the existing Artiss laptop desk qualification draft is not automatically approved for sale.

## Shared category UX rules
- remain useful when zero products are qualified;
- avoid fake product counts;
- avoid claims such as best, top-rated, safe, certified or free delivery unless supported by exact evidence;
- render Shopify product data only after the product passes the owned-site publication gate;
- use editorial copy for discovery without disguising research candidates as inventory;
- link purchase actions to Shopify cart/checkout only.

## Metadata pattern
Title: `<Category> | GlobalShopCo`
Meta description pattern: `Shop practical <category> products selected for value, usefulness and Australian delivery. New products are added as supplier and commercial checks are completed.`
Do not emit dynamic product-count claims unless the count comes from the approved Shopify-backed result set.

Current status: five launch-priority category destinations now have implementation-ready content. This does not mean the WordPress pages are deployed or that products are commercially approved.