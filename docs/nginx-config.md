# Nginx Configuration

```
server {
    listen 80;
    server_name resque-panel.me;
    set $root_path /projects/resque-panel/web;
    root $root_path;
    index index.html index.php;

    location ~ \.php {
        # fastcgi_pass 127.0.0.1:9000;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        include fastcgi_params;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
    location ~ /\.ht {
        deny all;
    }
}
```