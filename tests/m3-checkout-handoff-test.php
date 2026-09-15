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

// A configured store domain is authority-bearing input. Host confusion must fail before network I/O.
foreach ([
 'https://user:pass@example.myshopify.com',
 'example.myshopify.com/attacker-path',
 'example.myshopify.com:8443',
 'example.myshopify.com@attacker.test',
] as $bad) {
 putenv('GSCO_SHOPIFY_STORE_DOMAIN='.$bad); putenv('GSCO_SHOPIFY_STOREFRONT_TOKEN=opaque-test-token'); putenv('GSCO_SHOPIFY_API_VERSION=2026-07'); putenv('GSCO_SHOPIFY_CHECKOUT_HOST=');
 $GLOBALS['gsco_requests']=[];
 $result=gsco_shopify_request('query { shop { name } }');
 assert_true(is_wp_error($result) && $result->code==='gsco_invalid_store_domain','malformed store authority fails closed: '.$bad);
 assert_true(count($GLOBALS['gsco_requests'])===0,'malformed store authority performs zero network calls');
}

putenv('GSCO_SHOPIFY_STORE_DOMAIN=example.myshopify.com'); $GLOBALS['gsco_requests']=[];
$result=gsco_shopify_request('query { shop { name } }');
assert_true(!is_wp_error($result) && count($GLOBALS['gsco_requests'])===1,'canonical Shopify host remains accepted');
assert_true($GLOBALS['gsco_requests'][0]['url']==='https://example.myshopify.com/api/2026-07/graphql.json','request destination is exact configured Shopify host');
fwrite(STDOUT,"PASS: Shopify store-authority host boundary\n");
