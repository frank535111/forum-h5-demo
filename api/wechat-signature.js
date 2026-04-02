const crypto = require('crypto');

export default async function handler(req, res) {
    // 只支持GET请求
    if (req.method !== 'GET') {
        return res.status(405).json({ error: 'Method not allowed' });
    }

    const { url } = req.query;
    
    if (!url) {
        return res.status(400).json({ error: 'URL is required' });
    }

    try {
        // 微信公众号配置
        const appId = 'wx4630de408a4dfed7';
        const appSecret = 'YOUR_APP_SECRET'; // 需要替换为真实的AppSecret
        
        // 生成timestamp和nonceStr
        const timestamp = Math.floor(Date.now() / 1000);
        const nonceStr = Math.random().toString(36).substring(2, 15);
        
        // 获取access_token
        const tokenRes = await fetch(
            `https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=${appId}&secret=${appSecret}`
        );
        const tokenData = await tokenRes.json();
        
        if (!tokenData.access_token) {
            console.error('获取access_token失败:', tokenData);
            return res.status(500).json({ 
                error: 'Failed to get access_token',
                details: tokenData 
            });
        }
        
        // 获取jsapi_ticket
        const ticketRes = await fetch(
            `https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=${tokenData.access_token}&type=jsapi`
        );
        const ticketData = await ticketRes.json();
        
        if (!ticketData.ticket) {
            console.error('获取jsapi_ticket失败:', ticketData);
            return res.status(500).json({ 
                error: 'Failed to get jsapi_ticket',
                details: ticketData 
            });
        }
        
        // 生成签名
        const string1 = `jsapi_ticket=${ticketData.ticket}&noncestr=${nonceStr}&timestamp=${timestamp}&url=${url}`;
        const signature = crypto
            .createHash('sha1')
            .update(string1)
            .digest('hex');
        
        // 返回签名数据
        res.status(200).json({
            success: true,
            appId,
            timestamp,
            nonceStr,
            signature
        });
        
    } catch (error) {
        console.error('签名生成错误:', error);
        res.status(500).json({ 
            error: 'Internal server error',
            message: error.message 
        });
    }
}
