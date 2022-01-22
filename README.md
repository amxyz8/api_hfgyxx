### 部署说明

项目使用tp6.0框架

##### 1. clone此项目
```shell script
git clone ssh://git@gitlab.schoolpi.net:222/likun/offiaccount-hfgyxx.git
```

##### 2. 将.env.local或.env.online重命名为.env文件
> 根据需要修改配置文件中各连接参数

##### 3. 执行composer命令载入项目依赖
```shell script
composer install
```

##### 4. 导入sql文件

将根目录的sql文件导入数据库(注意env文件中的配置参数).

##### 5. supervisor配置

1) /etc/supervisord.d/下新增gyjj_lost.conf或者gyjj_lost.ini
```shell script
[program:gyjj_lost]
directory = /home/wwwroot/gyjj
command = php think lost  ; 启动命令
process_name=%(program_name)s_%(process_num)02d
numprocs = 1           ; 开启的进程数量
autostart = true     ; 在supervisord启动的时候也自动启动
startsecs = 5        ; 启动5秒后没有异常退出,就当作已经正常启动了
autorestart = true   ; 程序异常退出后自动重启
startretries = 3     ; 启动失败自动重试次数,默认是3
user = www          ; 用哪个用户启动
redirect_stderr = true  ; 把stderr重定向到stdout,默认false
stdout_logfile_maxbytes = 50MB  ; stdout日志文件大小,默认50MB
stdout_logfile_backups = 20     ; stdout日志文件备份数
; stdout 日志文件,需要手动创建目录(supervisord 会自动创建日志文件)
stdout_logfile = /home/wwwlogs/gyjj_lost.log
loglevel=info
```

2) /etc/supervisord.d/下新增gyjj_lottery.conf或者gyjj_lottery.ini
```shell script
[program:gyjj_lottery]
directory = /home/wwwroot/gyjj
command = php think lottery  ; 启动命令
process_name=%(program_name)s_%(process_num)02d
numprocs = 1           ; 开启的进程数量
autostart = true     ; 在supervisord启动的时候也自动启动
startsecs = 5        ; 启动5秒后没有异常退出,就当作已经正常启动了
autorestart = true   ; 程序异常退出后自动重启
startretries = 3     ; 启动失败自动重试次数,默认是3
user = www          ; 用哪个用户启动
redirect_stderr = true  ; 把stderr重定向到stdout,默认false
stdout_logfile_maxbytes = 50MB  ; stdout日志文件大小,默认50MB
stdout_logfile_backups = 20     ; stdout日志文件备份数
; stdout 日志文件,需要手动创建目录(supervisord 会自动创建日志文件)
stdout_logfile = /home/wwwlogs/gyjj_lottery.log
loglevel=info
```

3) /etc/supervisord.d/下新增gyjj_news_sync.conf或者gyjj_news_sync.ini
```shell script
[program:gyjj_news_sync]
directory = /home/wwwroot/gyjj
command = php think news_sync start  ; 启动命令
process_name=%(program_name)s_%(process_num)02d
numprocs = 1           ; 开启的进程数量
autostart = true     ; 在supervisord启动的时候也自动启动
startsecs = 5        ; 启动5秒后没有异常退出,就当作已经正常启动了
autorestart = true   ; 程序异常退出后自动重启
startretries = 3     ; 启动失败自动重试次数,默认是3
user = www          ; 用哪个用户启动
redirect_stderr = true  ; 把stderr重定向到stdout,默认false
stdout_logfile_maxbytes = 50MB  ; stdout日志文件大小,默认50MB
stdout_logfile_backups = 20     ; stdout日志文件备份数
; stdout 日志文件,需要手动创建目录(supervisord 会自动创建日志文件)
stdout_logfile = /home/wwwlogs/gyjj_news_sync.log
loglevel=info
```

4) /etc/supervisord.d/下新增gyjj_rss_sync.conf或者gyjj_rss_sync.ini
```shell script
[program:gyjj_rss_sync]
directory = /home/wwwroot/gyjj
command = php think rss_sync start  ; 启动命令
process_name=%(program_name)s_%(process_num)02d
numprocs = 1           ; 开启的进程数量
autostart = true     ; 在supervisord启动的时候也自动启动
startsecs = 5        ; 启动5秒后没有异常退出,就当作已经正常启动了
autorestart = true   ; 程序异常退出后自动重启
startretries = 3     ; 启动失败自动重试次数,默认是3
user = www          ; 用哪个用户启动
redirect_stderr = true  ; 把stderr重定向到stdout,默认false
stdout_logfile_maxbytes = 50MB  ; stdout日志文件大小,默认50MB
stdout_logfile_backups = 20     ; stdout日志文件备份数
; stdout 日志文件,需要手动创建目录(supervisord 会自动创建日志文件)
stdout_logfile = /home/wwwlogs/gyjj_rss_sync.log
loglevel=info
```

5) /etc/supervisord.d/下新增gyjj_video_sync.conf或者gyjj_video_sync.ini
```shell script
[program:gyjj_video_sync]
directory = /home/wwwroot/gyjj
command = php think video_sync start  ; 启动命令
process_name=%(program_name)s_%(process_num)02d
numprocs = 1           ; 开启的进程数量
autostart = true     ; 在supervisord启动的时候也自动启动
startsecs = 5        ; 启动5秒后没有异常退出,就当作已经正常启动了
autorestart = true   ; 程序异常退出后自动重启
startretries = 3     ; 启动失败自动重试次数,默认是3
user = www          ; 用哪个用户启动
redirect_stderr = true  ; 把stderr重定向到stdout,默认false
stdout_logfile_maxbytes = 50MB  ; stdout日志文件大小,默认50MB
stdout_logfile_backups = 20     ; stdout日志文件备份数
; stdout 日志文件,需要手动创建目录(supervisord 会自动创建日志文件)
stdout_logfile = /home/wwwlogs/gyjj_video_sync.log
loglevel=info
```

##### 6. nginx相关配置
```shell script
server
    {
        listen 80;
        server_name gyjj.schoolpi.net ;
        return 301 https://$http_host$request_uri;
    }

server {
    listen       443 ssl;
    server_name gyjj.schoolpi.net ;
    index index.html index.htm index.php default.html default.htm default.php;
    root  /home/wwwroot/gyjj/public;
    ssl_certificate   /home/www/schoolpi.net/schoolpi.net.pem;
    ssl_certificate_key  /home/www/schoolpi.net/schoolpi.net.key;
    ssl_session_timeout 5m;
    ssl_protocols TLSv1 TLSv1.1 TLSv1.2 TLSv1.3;
    ssl_prefer_server_ciphers on;
    ssl_ciphers "TLS13-AES-256-GCM-SHA384:TLS13-CHACHA20-POLY1305-SHA256:TLS13-AES-128-GCM-SHA256:TLS13-AES-128-CCM-8-SHA256:TLS13-AES-128-CCM-SHA256:EECDH+CHACHA20:EECDH+CHACHA20-draft:EECDH+AES128:RSA+AES128:EECDH+AES256:RSA+AES256:EECDH+3DES:RSA+3DES:!MD5";
    ssl_session_cache builtin:1000 shared:SSL:10m;

    charset utf-8;
    access_log  /home/wwwlogs/gyjj.schoolpi.net.log;

    include enable-php-pathinfo.conf;

    location / {
        if (!-e $request_filename){
            rewrite ^(.*)$ /index.php?s=$1 last;
            break;
        }
    }

    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
    {
         expires      30d;
    }

    location ~ .*\.(js|css)?$
    {
        expires      12h;
    }

    location ~ /.well-known {
        allow all;
    }

    location ~ /\.
    {
       deny all;
    }
}
```