# favicon.ico 在线转换工具

## 演示地址

[ico.nyaasu.top](https://ico.nyaasu.top)

## 安装

```bash
$ sudo apt-get install php
$ sudo apt-get install php-fpm
$ mkdir /app/ico-tool
$ cd /app/ico-tool
$ git clone git@github.com:Nyaasu66/ico-transform-online.git
```

## nginx配置 

### 非https

- 方括号里的内容填你自己的参数

```  

server{
        listen 80;
        server_name [ico.nyaasu.top];
        root [/xxx/ico-transform-online;
        index index.php;
        location / {
                try_files $uri $uri/ /index.php$is_args$args;
        }

        location ~ \.php$ {
                include fastcgi.conf;
                include fastcgi_params;
                fastcgi_pass unix:/run/php/php7.0-fpm.sock;
        }
}

```

### https

```

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
		fastcgi_pass unix:/run/php/php7.0-fpm.sock;
	}

}



```
