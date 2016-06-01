<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ResquePanel</title>
    <link rel="stylesheet" href="/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/libs/loader.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <?php
    define('APP_PATH', __DIR__ . '/../');
    $config = require APP_PATH . 'config/config.php';
    ?>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false"
                aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">ResquePanel</a>
    </div>
    <div id="navbar" class="collapse navbar-collapse pull-right">
        <ul class="nav navbar-nav">
            <!--<li><a href="javascript:void(0)" class="" id="btn-config">Configuration</a></li>-->
            <!--<li class="active"><a href="javascript:void(0)" class="btn-lang" data-lang="en">English</a></li>-->
            <!--<li><a href="javascript:void(0)" class="btn-lang" data-lang="cn">Simple Chinese</a></li>-->
        </ul>
    </div>
</nav>

<div class="container">
    <div class="row">

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Pending Jobs</h3>
                </div>
                <div class="panel-body">
                    <div id="queues-status" style="width: 100%; height: 200px"></div>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Failed Jobs</h3>
                </div>
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="form-group">
                            <label class="sr-only" for="offset">Offset</label>
                            <input type="text" class="form-control" id="offset" placeholder="Offset">
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="limit">Limit</label>
                            <input type="text" class="form-control" id="limit" placeholder="Limit">
                        </div>
                        <a href="javascript:void(0);" id="btn-load-failed-jobs" class="btn btn-primary">Load</a>
                    </form>

                    <table class="table table-bordered" style="margin-top: 1em;">
                        <thead>
                        <tr>
                            <th>failed_at/queue/worker</th>
                            <th>exception</th>
                            <th>error</th>
                        </tr>
                        </thead>
                        <tbody id="list-failed-jobs">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Console</h3>
                </div>
                <div class="panel-body">
                    <div id="console"></div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>
            Powered by <a href="http://tony.engineer" target="_blank">Lucups</a>,
            fork me on <a href="https://github.com/Lucups/resque-panel" target="_blank">Github</a>.
        </p>
    </footer>
</div>

<div id="modal-about" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">About</h4>
            </div>
            <div class="modal-body">
                <p>Author: Tony Lu</p>
                <p>Email: <a href="mailto:dev@tony.engineer">dev@tony.engineer</a></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-config" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Configuration</h4>
            </div>
            <div class="modal-body">
                <p>Redis Config</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Done</button>
            </div>
        </div>
    </div>
</div>

<script id="tpl-resque" type="text/template">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div id="chart-status"></div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div id="chart-statics"></div>
        </div>
    </div>
</script>

<script id="tpl-failed-jobs" type="text/template">
    {@each failed_jobs as job}
    <tr>
        <td>${job.failed_at}/${job.queue}/${job.worker}</td>
        <td>${job.exception}</td>
        <td>${job.error}</td>
    </tr>
    {@/each}
</script>

<script>
    var WS_URL = 'ws://<?php echo $config['ws']['host']; ?>:<?php echo $config['ws']['port']; ?>';
</script>

<script src="/libs/jquery/jquery-1.11.3.min.js"></script>
<script src="/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="/libs/loader.js" type="text/javascript"></script>
<script src="/libs/juicer-min.js" type="text/javascript"></script>
<script src="/libs/echarts.min.js"></script>
<script src="/assets/js/utils.js"></script>
<script src="/assets/js/ws.js"></script>

<script>
    var storage = window.localStorage;
    function log(text) {
        cs.append('<span class="line">' + line_index + '</span> ' + text + '<br>');
        cs.scrollTop(cs[0].scrollHeight);
        line_index++;
    }

    $('#btn-config').click(function () {
        $('#modal-config').modal('show');
    });

    $('#btn-about').click(function () {
        $('#modal-about').modal('show');
    });

    $('#btn-load-failed-jobs').click(function () {
        console.info('btn-load-failed-jobs clicked!');
        socket.send(JSON.stringify({
            mtd: 'failedJobs',
            offset: $('#offset').val(),
            limit: $('#limit').val()
        }));
    });
</script>
</body>
</html>