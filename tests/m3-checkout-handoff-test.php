<?php
/**
 * Deterministic no-network tests for the M3 Shopify cart/checkout handoff.
 * Run: php tests/m3-checkout-handoff-test.php
 */

define('ABSPATH', __DIR__ . '/');
putenv('GSCO_SHOPIFY_STORE_DOMAIN=example.myshopify.com');
putenv('GSCO_SHOPIFY_STOREFRONT_TOKEN=test-token-must-never-render');
putenv('GSCO_SHOPIFY_API_VERSION=2026-07');
putenv('GSCO_SHOPIFY_CHECKOUT_HOST=');

class WP_Error {
    public $code;
    public $message;
    public $data;
    public function __construct($code = '', $message = '', $data = null) { $this->code = $code; $this->message = $message; $this->data = $data; }
}
$GLOBALS['gsco_mock_responses'] = [];
$GLOBALS['gsco_requests'] = [];
function is_wp_error($value) { return $value instanceof WP_Error; }
function wp_json_encode($value) { return json_encode($value); }
function wp_remote_post($url, $args) { $GLOBALS['gsco_requests'][] = ['url' => $url, 'args' => $args]; if (!$GLOBALS['gsco_mock_responses']) return new WP_Error('mock_empty', 'No mock response queued.'); return array_shift($GLOBALS['gsco_mock_responses']); }
function wp_remote_retrieve_response_code($response) { return $response['response']['code'] ?? 0; }
function wp_remote_retrieve_body($response) { return $response['body'] ?? ''; }
function shortcode_atts($defaults, $atts, $shortcode = '') { return array_merge($defaults, $atts); }
function sanitize_title($value) { return strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/', '-', (string) $value), '-')); }
function esc_attr($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function esc_url($value) { return filter_var((string) $value, FILTER_SANITIZE_URL); }
function esc_html($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function add_shortcode($tag, $callback) { return true; }
require __DIR__ . '/../wp-content/plugins/globalshopco-headless/globalshopco-headless.php';
function mock_json($payload, $status = 200) { return ['response' => ['code' => $status], 'body' => json_encode($payload)]; }
function product_payload($available = true, $with_variant = true) { return ['data' => ['product' => ['id' => 'gid://shopify/Product/1','handle' => 'gsco-test-001','title' => 'Synthetic Test Product','description' => 'Fixture only','featuredImage' => null,'variants' => ['nodes' => $with_variant ? [['id' => 'gid://shopify/ProductVariant/101','sku' => 'SYNTH-001','price' => ['amount' => '19.95', 'currencyCode' => 'AUD'],'availableForSale' => $available]] : []]]]]; }
function cart_payload($checkout = 'https://example.myshopify.com/checkouts/synthetic', $user_errors = []) { return ['data' => ['cartCreate' => ['cart' => ['id' => 'gid://shopify/Cart/1', 'checkoutUrl' => $checkout], 'userErrors' => $user_errors]]]; }
function reset_mocks($responses) { $GLOBALS['gsco_mock_responses'] = $responses; $GLOBALS['gsco_requests'] = []; }
function assert_true($condition, $message) { if (!$condition) { fwrite(STDERR, "FAIL: {$message}\n"); exit(1); } }

reset_mocks([mock_json(product_payload()), mock_json(cart_payload())]);
$html = gsco_product_shortcode(['handle' => 'gsco-test-001']);
assert_true(strpos($html, 'https://example.myshopify.com/checkouts/synthetic') !== false, 'success path renders Shopify checkout URL');
assert_true(strpos($html, 'Buy via Shopify') !== false, 'success path renders Shopify purchase control');
assert_true(strpos($html, 'test-token-must-never-render') === false, 'Storefront token never appears in rendered HTML');
assert_true(count($GLOBALS['gsco_requests']) === 2, 'success path performs one product query and one cart mutation');
assert_true(($GLOBALS['gsco_requests'][1]['args']['headers']['X-Shopify-Storefront-Access-Token'] ?? null) === 'test-token-must-never-render', 'token stays server-side in request header');

reset_mocks([mock_json(product_payload(true, false))]);
$html = gsco_product_shortcode(['handle' => 'gsco-test-001']);
assert_true(strpos($html, 'Product variant unavailable.') !== false && count($GLOBALS['gsco_requests']) === 1, 'missing variant fails closed');

reset_mocks([mock_json(product_payload(false, true))]);
$html = gsco_product_shortcode(['handle' => 'gsco-test-001']);
assert_true(strpos($html, 'Currently unavailable') !== false && count($GLOBALS['gsco_requests']) === 1, 'unavailable variant fails closed');
assert_true(strpos($html, 'Buy via Shopify') === false && strpos($html, '/checkouts/') === false, 'unavailable variant renders no purchase destination');

reset_mocks([mock_json(product_payload()), mock_json(cart_payload('', [['field' => ['lines'], 'message' => 'Synthetic detailed provider error']]))]);
$html = gsco_product_shortcode(['handle' => 'gsco-test-001']);
assert_true(strpos($html, 'Checkout temporarily unavailable.') !== false && strpos($html, 'Synthetic detailed provider error') === false, 'cart userErrors fail closed without provider detail');

reset_mocks([mock_json(product_payload()), ['response' => ['code' => 200], 'body' => 'not-json']]);
$html = gsco_product_shortcode(['handle' => 'gsco-test-001']);
assert_true(strpos($html, 'Checkout temporarily unavailable.') !== false, 'malformed Shopify response fails closed');

reset_mocks([]);
$result = gsco_create_cart_checkout_url('not-a-shopify-variant');
assert_true(is_wp_error($result) && $result->code === 'gsco_invalid_variant' && count($GLOBALS['gsco_requests']) === 0, 'invalid variant ID cannot reach Shopify');

foreach ([
    ['https://unexpected.example/checkout/synthetic', 'unexpected checkout host'],
    ['https://example.myshopify.com.attacker.test/checkouts/synthetic', 'suffix-confusable checkout host'],
    ['http://example.myshopify.com/checkouts/synthetic', 'protocol downgrade'],
    ['https://user:pass@example.myshopify.com/checkouts/synthetic', 'userinfo credential-host confusion'],
    ['https://example.myshopify.com:8443/checkouts/synthetic', 'unexpected explicit port'],
    ['https:///checkouts/synthetic', 'malformed checkout URL'],
] as [$url, $label]) {
    reset_mocks([mock_json(product_payload()), mock_json(cart_payload($url))]);
    $html = gsco_product_shortcode(['handle' => 'gsco-test-001']);
    assert_true(strpos($html, 'Checkout temporarily unavailable.') !== false, $label . ' fails closed');
    assert_true(strpos($html, 'Buy via Shopify') === false, $label . ' renders no purchase control');
}

reset_mocks([mock_json(product_payload()), mock_json(cart_payload(''))]);
$html = gsco_product_shortcode(['handle' => 'gsco-test-001']);
assert_true(strpos($html, 'Checkout temporarily unavailable.') !== false && strpos($html, 'Buy via Shopify') === false, 'missing checkoutUrl fails closed');

putenv('GSCO_SHOPIFY_CHECKOUT_HOST=checkout.example.test');
reset_mocks([mock_json(product_payload()), mock_json(cart_payload('https://checkout.example.test.attacker.test/cart/synthetic'))]);
$html = gsco_product_shortcode(['handle' => 'gsco-test-001']);
assert_true(strpos($html, 'Checkout temporarily unavailable.') !== false && strpos($html, 'Buy via Shopify') === false, 'configured non-production override cannot widen beyond exact host');
reset_mocks([mock_json(product_payload()), mock_json(cart_payload('https://checkout.example.test/cart/synthetic'))]);
$html = gsco_product_shortcode(['handle' => 'gsco-test-001']);
assert_true(strpos($html, 'https://checkout.example.test/cart/synthetic') !== false, 'exact configured checkout host is accepted');
putenv('GSCO_SHOPIFY_CHECKOUT_HOST=');

fwrite(STDOUT, "PASS: 15 deterministic M3 checkout-handoff cases plus fail-closed output assertions\n");
