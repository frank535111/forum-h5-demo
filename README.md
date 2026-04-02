# 论坛H5宣传页面

基于Vercel部署的H5宣传页面，支持微信分享卡片功能。

## 功能特性

- 📱 **多页面滑动**：5个页面（封面/介绍/嘉宾/议程/报名）
- ⏱️ **倒计时功能**：自动计算距离活动时间
- ✨ **粒子背景动画**：提升视觉效果
- 🎨 **动态效果**：流畅的页面切换动画
- 📤 **系统分享**：支持原生分享API
- 📋 **复制链接**：一键复制分享链接
- 🎯 **微信JSSDK**：支持微信卡片式分享（需配置）

## 在线地址

- **主域名**：https://m.hdavchina.com（待网站工程师部署）
- **备用地址**：https://forum-h5-demo.vercel.app

## 部署文件清单

### 主要文件

| 文件 | 说明 |
|------|------|
| `index.html` | H5主页面 |
| `api/wechat-signature.js` | 微信JSSDK签名接口 |
| `share-preview.jpg` | 分享预览图（需添加） |
| `MP_verify_bGJgJfrlVaTPwaGh.txt` | 微信域名验证文件 |

### 配置文件

| 文件 | 说明 |
|------|------|
| `package.json` | Node.js依赖 |
| `vercel.json` | Vercel部署配置 |
| `.env.example` | 环境变量配置示例 |

## 微信分享配置

### 1. 已配置项

- **AppID**: wx4630de408a4dfed7
- **AppSecret**: 2db763db312f2b4e82f16973121573bc（已在Vercel环境变量中配置）
- **JS接口安全域名**: m.hdavchina.com（已在公众号后台配置）

### 2. 待配置项

- 上传分享预览图 `share-preview.jpg` 到项目根目录
- 建议尺寸：400x400px 或 5:4 比例
- 格式：JPG/PNG，大小不超过500KB

### 3. 部署步骤

1. 在 `m.hdavchina.com` 服务器上部署以下文件：
   - `index.html`
   - `share-preview.jpg`
   - `api/wechat-signature.js`（需配置Node.js环境）

2. 确保以下接口可访问：
   - `https://m.hdavchina.com/` （H5主页面）
   - `https://m.hdavchina.com/api/wechat-signature` （微信签名接口）

3. 在微信中测试分享功能

## 开发说明

### 本地开发

直接打开 `index.html` 即可预览效果。

### API接口

**获取微信签名**:
```
GET /api/wechat-signature?url=当前页面URL
```

返回格式：
```json
{
  "success": true,
  "appId": "wx4630de408a4dfed7",
  "timestamp": 1234567890,
  "nonceStr": "random_string",
  "signature": "sha1_signature"
}
```

### 环境变量

在Vercel项目设置中配置以下环境变量：

| 变量名 | 值 |
|--------|-----|
| `WECHAT_APP_ID` | wx4630de408a4dfed7 |
| `WECHAT_APP_SECRET` | 2db763db312f2b4e82f16973121573bc |

## 注意事项

⚠️ **安全提醒**：
- AppSecret 是敏感信息，请勿泄露
- 微信签名接口需要HTTPS访问
- 确保域名已在公众号后台正确配置

## 技术栈

- **前端**：原生HTML + CSS + JavaScript
- **部署**：Vercel Serverless Functions
- **微信SDK**：JSSDK 1.6.0

## 更新日志

- 2026-04-02: 创建H5页面，配置微信JSSDK
- 2026-04-02: 修改域名为 m.hdavchina.com，适配已配置的JS接口安全域名
