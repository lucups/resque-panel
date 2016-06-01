# resque-panel

A monitoring tool that contains a web interface for php-resque.

- [中文说明](README_CN.md)

### ScreenShot

### Install & Configuration

##### 1. Clone code from GitHub
```
git clone https://github.com/Lucups/resque-panel.git
cd resque-panel/
```

##### 2. Create config file, and update
```
cp ./config/config.dist.php ./config/config.php
vim ./config/config.php
```

##### 3. Start WebSocket server by `nohup` command
```
nohup php server/server.php > /tmp/server.log &
```

##### 4. Start PHP web server (you can also use other web server, just like Nginx or Apache)
```
php -S localhost:4000 -t web/
```

### Based Projects List

- [Swoole](#)
- [ECharts](#)
- [jQuery](#)
- [Bootstrap](#)
- [Juicer](#)

### TODO List

- More configuration parameters;
- Refactor service layer;
- Authentication;