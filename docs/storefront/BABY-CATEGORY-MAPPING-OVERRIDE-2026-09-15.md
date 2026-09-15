# Baby Category Mapping Override — 2026-09-15

Status: IMPLEMENTATION-READY / NOT LIVE
Branch: `agent/chatgpt/baby-generic-tag-2026-09-14`

## Purpose

This file resolves the remaining documentation mismatch after the Baby category query was widened from safety-only membership.

It supersedes the single outdated mapping line in `CATEGORY-RENDERING-DATA-CONTRACT-2026-09-13.md` that says:

`Baby → tag:baby-safety`

until that parent contract is consolidated.

## Canonical Baby mapping on this branch

`Baby → tag:baby OR tag:baby-safety`

Interpretation:
- `tag:baby` is the ordinary Baby merchandising tag for feeding, bath/care, portable care, nursery, organisation and other non-safety Baby products.
- `tag:baby-safety` is reserved for products with an actual Baby Safety use-case.
- a product tagged only `baby` must not appear on `/safety/`.
- a product tagged `baby-safety` may be eligible for both `/baby/` and `/safety/`, but still must independently pass the owned-site commercial approval gate.

## Safety mapping remains unchanged

`Safety → tag:baby-safety OR tag:pet-safety OR tag:home-proofing OR tag:night-walk-visibility`

The generic `baby` tag is intentionally absent from Safety.

## Fail-closed rule remains unchanged

Category membership never implies commercial approval. Products carrying `review-required`, `not-publication-approved`, `qualification-draft` or `not-for-sale`, products that are ARCHIVED, products with non-positive price, or products without positive commercial approval remain excluded.

## Acceptance cases

1. Ordinary Baby product tagged only `baby`, commercially approved: eligible for `/baby/`, not `/safety/`.
2. Baby-proofing product tagged `baby-safety`, commercially approved: eligible for both `/baby/` and `/safety/`.
3. Product tagged `baby` plus `qualification-draft`: excluded from purchasable rendering everywhere.
4. Non-Baby product with no Baby tags: not eligible for `/baby/`.

No merge or deployment is authorised by this override.