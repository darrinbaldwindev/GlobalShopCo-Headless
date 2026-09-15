<?php
/** Deterministic no-network authority boundary regression. */
define('ABSPATH', __DIR__ . '/');
class WP_Error { public $code; public $message; public $data; public function __construct($code='', $message='', $data=null){$this->code=$code;$this->message=$message;$this->data=$data;} }
$GLOBALS['gsco_requests']=[];
function is_wp_error($v){return $v instanceof WP_Error;}
function wp_json_encode($v){return json_encode($v);}
function wp_remote_post($url,$args){$GLOBALS['gsco_requests'][]=['url'=>$url,'args'=>$args]; return ['response'=>['code'=>200],'body'=>'{"data":{}}'];}
function wp_remote_retrieve_response_code($r){return $r['response']['code']??0;}
function wp_remote_retrieve_body($r){return $r['body']??'';}
function shortcode_atts($d,$a,$s=''){return array_merge($d,$a);} function sanitize_title($v){return strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/','-',(string)$v),'-'));} function esc_attr($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');} function esc_url($v){return filter_var((string)$v,FILTER_SANITIZE_URL);} function esc_html($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');} function add_shortcode($t,$c){return true;}
require __DIR__.'/../wp-content/plugins/globalshopco-headless/globalshopco-headless.php';
function assert_true($c,$m){if(!$c){fwrite(STDERR,"FAIL: {$m}\n");exit(1);}}

foreach (['https://user:pass@example.myshopify.com','example.myshopify.com/attacker-path','example.myshopify.com:8443','example.myshopify.com@attacker.test'] as $bad) {
 putenv('GSCO_SHOPIFY_STORE_DOMAIN='.$bad); putenv('GSCO_SHOPIFY_STOREFRONT_TOKEN=opaque-test-token'); putenv('GSCO_SHOPIFY_API_VERSION=2026-07'); putenv('GSCO_SHOPIFY_CHECKOUT_HOST=');
 $GLOBALS['gsco_requests']=[]; $result=gsco_shopify_request('query { shop { name } }');
 assert_true(is_wp_error($result) && $result->code==='gsco_invalid_store_domain','malformed store authority fails closed: '.$bad);
 assert_true(count($GLOBALS['gsco_requests'])===0,'malformed store authority performs zero network calls');
}
putenv('GSCO_SHOPIFY_STORE_DOMAIN=example.myshopify.com'); $GLOBALS['gsco_requests']=[];
$result=gsco_shopify_request('query { shop { name } }');
assert_true(!is_wp_error($result) && count($GLOBALS['gsco_requests'])===1,'canonical Shopify host remains accepted');
assert_true($GLOBALS['gsco_requests'][0]['url']==='https://example.myshopify.com/api/2026-07/graphql.json','request destination is exact configured Shopify host');

// ProductVariant identity is authority-bearing. Malformed or resource-swapped IDs must fail before cart/network mutation.
foreach (['gid://shopify/Product/123','gid://shopify/ProductVariant/not-numeric','gid://shopify/ProductVariant/123 ',' gid://shopify/ProductVariant/123','gid://shopify/ProductVariant/0'] as $bad_variant) {
 $GLOBALS['gsco_requests']=[]; $result=gsco_create_cart_checkout_url($bad_variant);
 assert_true(is_wp_error($result) && $result->code==='gsco_invalid_variant','malformed variant identity fails closed: '.$bad_variant);
 assert_true(count($GLOBALS['gsco_requests'])===0,'malformed variant identity performs zero network calls');
}
assert_true(gsco_canonical_variant_id('gid://shopify/ProductVariant/123')==='gid://shopify/ProductVariant/123','canonical Shopify variant identity accepted');

// Checkout host is also authority-bearing configuration. Never parse a host out of malformed input.
foreach (['https://user:pass@checkout.example.com','checkout.example.com/path','checkout.example.com:8443','checkout.example.com@attacker.test',' checkout.example.com'] as $bad) {
 putenv('GSCO_SHOPIFY_CHECKOUT_HOST='.$bad);
 $result=gsco_validate_checkout_url('https://checkout.example.com/cart/c1');
 assert_true(is_wp_error($result) && $result->code==='gsco_checkout_host','malformed checkout authority fails closed: '.$bad);
}
putenv('GSCO_SHOPIFY_CHECKOUT_HOST=checkout.example.com');
assert_true(gsco_validate_checkout_url('https://checkout.example.com/cart/c1')==='https://checkout.example.com/cart/c1','canonical configured checkout authority accepted');
assert_true(is_wp_error(gsco_validate_checkout_url('https://attacker.test/cart/c1')),'checkout destination must match configured authority');

foreach (['http://checkout.example.com/cart/c1','https://localhost/order/123','https://checkout.example.com.attacker.test/cart/c1','https://checkout.example.com@attacker.test/cart/c1','https://user@checkout.example.com/cart/c1','https://checkout.example.com:80/cart/c1'] as $bad_checkout) {
 $GLOBALS['gsco_requests']=[]; $result=gsco_validate_checkout_url($bad_checkout);
 assert_true(is_wp_error($result),'non-canonical/local checkout projection fails closed: '.$bad_checkout);
 assert_true(count($GLOBALS['gsco_requests'])===0,'checkout projection validation performs zero network calls');
}
assert_true(gsco_validate_checkout_url('https://checkout.example.com:443/cart/c1')==='https://checkout.example.com:443/cart/c1','explicit standard TLS port remains bounded to configured authority');
fwrite(STDOUT,"PASS: Shopify request, variant identity and checkout authority boundaries\n");
