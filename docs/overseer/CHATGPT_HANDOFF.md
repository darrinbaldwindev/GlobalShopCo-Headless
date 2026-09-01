# CHATGPT Overseer Handoff

## Operating chain
CHATGPT Head Overseer → GlobalShopCo-Headless Project Overseer → authorised worker(s) → verification → project log → CHATGPT Head Overseer.

## Rules
- Fresh repository state before substantive work.
- Explicit task identity, scope, authority and acceptance criteria.
- Non-production validation must precede any production action.
- Shopify remains the backend/checkout boundary; WordPress remains the headless presentation layer.
- No credentials, production configuration or destructive changes without owner gates.

## Current focus
M3 minimum Shopify cart/checkout handoff remains the next vertical-slice implementation target. Do not expand into unrelated catalogue, accounts or analytics work until M3 is validated.
