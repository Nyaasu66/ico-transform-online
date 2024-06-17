# favicon.ico 在线转换工具

在线将 jpg png 格式图片转换为 ico 格式图标的开源工具，支持透明背景

## 演示地址

[ico.nyaasu.top](https://ico.nyaasu.top)

## 安装

```bash
sudo apt install php php-fpm php-gd nginx
git clone git@github.com:Nyaasu66/ico-transform-online.git
```

## nginx配置

### 非 https

- 方括号里的内容填你自己的参数

```nginx
server{
  listen 80;
  server_name [ico.nyaasu.top];
  root [/xxx/ico-transform-online];
  index index.php;
  location / {
    try_files $uri $uri/ /index.php$is_args$args;
  }

  location ~ \.php$ {
    include fastcgi.conf;
    include fastcgi_params;
    fastcgi_pass unix:/run/php/php-fpm.sock;
  }
}
```

### https

```nginx
server{
  listen 80;
  server_name [ico.nyaasu.top];
  return 301  https://$host$request_uri;
}

server {
  listen 443 ssl;
  server_name [ico.nyaasu.top];
  ssl_certificate      [xxx/fullchain.pem];
  ssl_certificate_key  [xxx/privkey.pem];
  ssl_dhparam          [xxx/dhparams.pem];

  ssl_ciphers 'ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA:ECDHE-RSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-RSA-AES256-SHA256:DHE-RSA-AES256-SHA:ECDHE-ECDSA-DES-CBC3-SHA:ECDHE-RSA-DES-CBC3-SHA:EDH-RSA-DES-CBC3-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:AES128-SHA256:AES256-SHA256:AES128-SHA:AES256-SHA:DES-CBC3-SHA:!DSS';

  ssl_prefer_server_ciphers  on;
  ssl_protocols        TLSv1 TLSv1.1 TLSv1.2;
  ssl_session_cache          shared:SSL:50m;
  ssl_session_timeout        1d;
  ssl_session_tickets off;
  ssl_stapling               on;
  ssl_stapling_verify        on;
  ssl_trusted_certificate    [xxx/fullchain.pem];

  add_header Strict-Transport-Security max-age=60;
 
  root [xxx/ico-transform-online];
  index index.php;
  location / {
    try_files $uri $uri/ /index.php$is_args$args;
  }

  location ~ \.php$ {
    include fastcgi.conf;
    include fastcgi_params;
    fastcgi_pass unix:/run/php/php-fpm.sock;
  }
}
```

### 其他配置

- 你可能需要将 `/etc/nginx/nginx.conf` 的 user 由 `nginx` 改为 `www-data`, 否则会出现 502 Bat Gateway 错误.
