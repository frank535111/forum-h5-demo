<?php
/**
 * 微信JS-SDK签名接口
 * 用于生成H5页面分享所需的签名
 *
 * 使用方法：/api/wechat-signature?url=https://m.hdavchina.com/h5/
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// 微信公众号配置
$appId = 'wx4630de408a4dfed7';
$appSecret = '2db763db312f2b4e82f16973121573bc';

// 获取当前页面的URL
$url = isset($_GET['url']) ? $_GET['url'] : '';

if (empty($url)) {
    echo json_encode(['error' => 'URL parameter is required']);
    exit;
}

try {
    // 1. 获取access_token
    $accessToken = getAccessToken($appId, $appSecret);
    
    // 2. 获取jsapi_ticket
    $ticket = getJsApiTicket($accessToken);
    
    // 3. 生成签名
    $signature = generateSignature($ticket, $url);
    
    // 4. 返回配置信息
    echo json_encode([
        'appId' => $appId,
        'timestamp' => time(),
        'nonceStr' => $signature['nonceStr'],
        'signature' => $signature['signature'],
        'url' => $url
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
    exit;
}

/**
 * 获取微信access_token
 */
function getAccessToken($appId, $appSecret) {
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$appSecret}";
    
    $response = curlGet($url);
    $data = json_decode($response, true);
    
    if (isset($data['errcode'])) {
        throw new Exception('获取access_token失败: ' . $data['errmsg']);
    }
    
    return $data['access_token'];
}

/**
 * 获取jsapi_ticket
 */
function getJsApiTicket($accessToken) {
    $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$accessToken}&type=jsapi";
    
    $response = curlGet($url);
    $data = json_decode($response, true);
    
    if (isset($data['errcode']) && $data['errcode'] != 0) {
        throw new Exception('获取jsapi_ticket失败: ' . $data['errmsg']);
    }
    
    return $data['ticket'];
}

/**
 * 生成签名
 */
function generateSignature($ticket, $url) {
    // 生成随机字符串
    $nonceStr = generateNonceStr();
    $timestamp = time();
    
    // 拼接签名参数
    $string = "jsapi_ticket={$ticket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
    
    // SHA1加密
    $signature = sha1($string);
    
    return [
        'nonceStr' => $nonceStr,
        'signature' => $signature
    ];
}

/**
 * 生成随机字符串
 */
function generateNonceStr($length = 16) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}

/**
 * CURL GET请求
 */
function curlGet($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
?>
