<?php
/**
 * Plugin Name: GlobalShopCo Headless
 * Description: Minimal Shopify Storefront API integration for the GlobalShopCo M3 vertical slice.
 * Version: 0.2.0
 */

defined('ABSPATH') || exit;

function gsco_shopify_config() {
    return [
        'store_domain' => defined('GSCO_SHOPIFY_STORE_DOMAIN') ? GSCO_SHOPIFY_STORE_DOMAIN : getenv('GSCO_SHOPIFY_STORE_DOMAIN'),
        'token' => defined('GSCO_SHOPIFY_STOREFRONT_TOKEN') ? GSCO_SHOPIFY_STOREFRONT_TOKEN : getenv('GSCO_SHOPIFY_STOREFRONT_TOKEN'),
        'api_version' => defined('GSCO_SHOPIFY_API_VERSION') ? GSCO_SHOPIFY_API_VERSION : (getenv('GSCO_SHOPIFY_API_VERSION') ?: '2026-07'),
    ];
}

function gsco_shopify_request($query, $variables = []) {
    $config = gsco_shopify_config();
    if (empty($config['store_domain']) || empty($config['token'])) {
        return new WP_Error('gsco_not_configured', 'Shopify integration is not configured.');
    }

    $domain = preg_replace('#^https?://#', '', trim($config['store_domain']));
    $url = 'https://' . rtrim($domain, '/') . '/api/' . rawurlencode($config['api_version']) . '/graphql.json';
    $response = wp_remote_post($url, [
        'timeout' => 10,
        'headers' => [
            'Content-Type' => 'application/json',
            'X-Shopify-Storefront-Access-Token' => $config['token'],
        ],
        'body' => wp_json_encode(['query' => $query, 'variables' => $variables]),
    ]);

    if (is_wp_error($response)) return $response;
    $status = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);
    if ($status < 200 || $status >= 300 || !is_array($body)) {
        return new WP_Error('gsco_shopify_http', 'Shopify request failed.', ['status' => $status]);
    }
    if (!empty($body['errors'])) {
        return new WP_Error('gsco_shopify_graphql', 'Shopify returned a GraphQL error.');
    }
    return $body['data'] ?? [];
}

function gsco_get_product($handle) {
    $query = <<<'GRAPHQL'
query ProductByHandle($handle: String!) {
  product(handle: $handle) {
    id
    handle
    title
    description
    featuredImage { url altText }
    variants(first: 1) {
      nodes { id sku price { amount currencyCode } availableForSale }
    }
  }
}
GRAPHQL;
    $data = gsco_shopify_request($query, ['handle' => $handle]);
    if (is_wp_error($data)) return $data;
    return $data['product'] ?? null;
}

function gsco_create_cart_checkout_url($variant_id) {
    if (!is_string($variant_id) || strpos($variant_id, 'gid://shopify/ProductVariant/') !== 0) {
        return new WP_Error('gsco_invalid_variant', 'Product variant is unavailable.');
    }

    $mutation = <<<'GRAPHQL'
mutation CartCreate($input: CartInput!) {
  cartCreate(input: $input) {
    cart { id checkoutUrl }
    userErrors { field message }
  }
}
GRAPHQL;
    $data = gsco_shopify_request($mutation, [
        'input' => [
            'lines' => [[
                'quantity' => 1,
                'merchandiseId' => $variant_id,
            ]],
        ],
    ]);
    if (is_wp_error($data)) return $data;

    $payload = $data['cartCreate'] ?? null;
    if (!is_array($payload) || !empty($payload['userErrors'])) {
        return new WP_Error('gsco_cart_create', 'Unable to start Shopify checkout.');
    }
    $checkout_url = $payload['cart']['checkoutUrl'] ?? '';
    if (!is_string($checkout_url) || !preg_match('#^https://#i', $checkout_url)) {
        return new WP_Error('gsco_checkout_url', 'Shopify checkout is unavailable.');
    }
    return $checkout_url;
}

function gsco_product_shortcode($atts) {
    $atts = shortcode_atts(['handle' => 'gsco-test-001'], $atts, 'gsco_product');
    $handle = sanitize_title($atts['handle']);
    if (!$handle) return '<p>Product unavailable.</p>';

    $product = gsco_get_product($handle);
    if (is_wp_error($product)) return '<p>Product unavailable.</p>';
    if (!$product) return '<p>Product not found.</p>';

    $variant = $product['variants']['nodes'][0] ?? null;
    $html = '<article class="gsco-product">';
    if (!empty($product['featuredImage']['url'])) {
        $alt = esc_attr($product['featuredImage']['altText'] ?: $product['title']);
        $html .= '<img src="' . esc_url($product['featuredImage']['url']) . '" alt="' . $alt . '" loading="lazy">';
    }
    $html .= '<h2>' . esc_html($product['title']) . '</h2>';
    $html .= '<p>' . esc_html($product['description']) . '</p>';
    if ($variant) {
        $price = esc_html($variant['price']['amount'] . ' ' . $variant['price']['currencyCode']);
        $html .= '<p><strong>' . $price . '</strong></p>';
        if (!empty($variant['availableForSale'])) {
            $checkout_url = gsco_create_cart_checkout_url($variant['id'] ?? '');
            if (is_wp_error($checkout_url)) {
                $html .= '<p>Checkout temporarily unavailable.</p>';
            } else {
                $html .= '<p><a class="gsco-buy" href="' . esc_url($checkout_url) . '" rel="nofollow">Buy via Shopify</a></p>';
            }
        } else {
            $html .= '<p>Currently unavailable</p>';
        }
    } else {
        $html .= '<p>Product variant unavailable.</p>';
    }
    $html .= '</article>';
    return $html;
}
add_shortcode('gsco_product', 'gsco_product_shortcode');
