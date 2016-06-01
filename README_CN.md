ResquePanel
============

[![Build Status](https://travis-ci.org/Lucups/resque-panel.svg?branch=master)](https://travis-ci.org/Lucups/resque-panel)

一个有 Web 界面的 php-resque 监控工具。

- [English Version](README.md)

### 安装和配置

```
# 0. 首先确保你安装了 swoole 和 composer

# 1. 克隆代码, 并使用 Composer 安装依赖
git clone https://github.com/Lucups/resque-panel.git
cd resque-panel/
composer install

# 2. 创建配置文件, 并更新它
cp ./config/config.dist.php ./config/config.php
vim ./config/config.php

# 3. 启动 WebSocket
nohup php server/resque_panel.php > /tmp/resque_panel.log &

# 4. 启动 HTTP 服务器
php -S localhost:8080 -t web/

# 5. 打开浏览器, 查看
http://localhost:8080
```

### ScreenShots

![All](screenshots/ResquePanel-ScreenShot03.gif)
![Failed Job Detail](screenshots/ResquePanel-ScreenShot02.gif)

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