server {
    root /var/www/<?=$servername?>/<?=$rootdir?>;
    index index.php index.html;

    server_name <?=$servername?> www.<?=$servername?>;
    access_log  /var/log/nginx/<?=$servername?>.access.log;

    location / {
        try_files $uri $uri/ @php;
        index index.php index.html;
    }

    location ^~ /\. {
        deny all;
    }

    # deny access to .htaccess files, if Apache's document root
    location ~ /\.ht {
        deny all;
    }

    #
    location ~ \.php$ {
    fastcgi_pass <?=$fcginame=?>;
    include fastcgi_params;
    }

    location @php {
        fastcgi_pass   <?=$fcginame?>;
        include fastcgi_params;
        fastcgi_param  SCRIPT_FILENAME  /var/www/<?=$servername?>/<?=$rootdir?>/index.php;
        fastcgi_param  SCRIPT_NAME      /index.php;
        fastcgi_param  QUERY_STRING     $uri&$args;
    }

}