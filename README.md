# favicon.ico 在线转换工具

在线将 jpg png 格式图片转换为 ico 格式图标的开源工具，支持透明背景

## 演示地址

[ico.nyaasu.top](https://ico.nyaasu.top)

## 安装

```bash
# 环境为 ubuntu-server
sudo apt install php php-fpm php-gd nginx
git clone git@github.com:Nyaasu66/ico-transform-online.git
```

## nginx配置

### 非 https

- 方括号里的内容填你自己的参数

```nginx
# 上传速率限制：同一 IP 每分钟最多 10 次请求
limit_req_zone $binary_remote_addr zone=ico_upload:10m rate=10r/m;

server {
  listen 80;
  server_name [ico.nyaasu.top];
  root [/xxx/ico-transform-online];
  index index.php;

  # 限制上传体积，与 PHP 保持一致
  client_max_body_size 2m;
  client_body_timeout  10s;
  client_header_timeout 10s;
  send_timeout         10s;

  # 安全响应头
  add_header X-Content-Type-Options "nosniff" always;
  add_header X-Frame-Options "SAMEORIGIN" always;
  add_header Referrer-Policy "strict-origin-when-cross-origin" always;

  location / {
    try_files $uri $uri/ /index.php$is_args$args;
  }

  # 禁止访问所有隐藏文件和隐藏目录（如 .git、.env、.htaccess 等）
  location ~ /\. {
    deny all;
    access_log off;
    log_not_found off;
  }

  location ~ \.php$ {
    limit_req zone=ico_upload burst=5 nodelay;
    include fastcgi.conf;
    include fastcgi_params;
    fastcgi_pass unix:/run/php/php-fpm.sock;
  }
}
```

### https

```nginx
# 上传速率限制：同一 IP 每分钟最多 10 次请求
limit_req_zone $binary_remote_addr zone=ico_upload:10m rate=10r/m;

server {
  listen 80;
  server_name [ico.nyaasu.top];
  return 301 https://$host$request_uri;
}

server {
  listen 443 ssl;
  server_name [ico.nyaasu.top];
  ssl_certificate      [xxx/fullchain.pem];
  ssl_certificate_key  [xxx/privkey.pem];

  # 仅启用安全协议，禁用已废弃的 TLSv1.0 / TLSv1.1
  ssl_protocols TLSv1.2 TLSv1.3;

  # 仅保留支持前向安全的现代密码套件，移除 3DES 等弱算法
  ssl_ciphers 'ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305';
  ssl_prefer_server_ciphers on;

  ssl_session_cache   shared:SSL:10m;
  ssl_session_timeout 1d;
  ssl_session_tickets off;
  ssl_stapling        on;
  ssl_stapling_verify on;
  ssl_trusted_certificate [xxx/fullchain.pem];

  # 限制上传体积，与 PHP 保持一致
  client_max_body_size 2m;
  client_body_timeout  10s;
  client_header_timeout 10s;
  send_timeout         10s;

  # 安全响应头
  # HSTS：强制 HTTPS 一年，防止 SSL 剥离攻击
  add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
  add_header X-Content-Type-Options "nosniff" always;
  add_header X-Frame-Options "SAMEORIGIN" always;
  add_header Referrer-Policy "strict-origin-when-cross-origin" always;

  root [xxx/ico-transform-online];
  index index.php;

  location / {
    try_files $uri $uri/ /index.php$is_args$args;
  }

  # 禁止访问所有隐藏文件和隐藏目录（如 .git、.env、.htaccess 等）
  location ~ /\. {
    deny all;
    access_log off;
    log_not_found off;
  }

  location ~ \.php$ {
    limit_req zone=ico_upload burst=5 nodelay;
    include fastcgi.conf;
    include fastcgi_params;
    fastcgi_pass unix:/run/php/php-fpm.sock;
  }
}
```

任选一种方案，保存到 `/etc/nginx/conf.d/ico.conf`，然后 `sudo nginx -s reload`，无任何返回则代表 nginx 已正常启动

### 其他配置

- 你可能需要将 `/etc/nginx/nginx.conf` 的 user 由 `nginx` 改为 `www-data`, 否则会出现 502 Bat Gateway 错误.
- 如果有其他错误，可通过 `sudo vi -R /var/log/nginx/error.log` 查看 nginx 日志
