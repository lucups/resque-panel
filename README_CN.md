ResquePanel - 一个 php-resque 的Web 界面监控工具。
==============================================

[![Build Status](https://travis-ci.org/Lucups/resque-panel.svg?branch=master)](https://travis-ci.org/Lucups/resque-panel)


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
nohup php server/rp_server.php > /tmp/resque_panel.log &

# 4. 启动 HTTP 服务器
php -S localhost:8080 -t web/

# 5. 打开浏览器, 查看
http://localhost:8080
```

### 截图

![All](screenshots/ResquePanel-Full.gif)
![Failed Job Detail](screenshots/ResquePanel-FailedJobDetail.gif)

### 主要依赖的项目

- [Wrench](https://github.com/varspool/Wrench) OR [Swoole](http://www.swoole.com/)
- [ECharts](http://echarts.baidu.com/)
- [jsoneditor](https://github.com/josdejong/jsoneditor)
- [jQuery](http://jquery.com/)
- [Bootstrap](https://getbootstrap.com/)
- [Juicer](http://juicer.name)
