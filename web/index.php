<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ResquePanel</title>
    <link rel="stylesheet" href="/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/libs/loader.min.css" type="text/css">
    <link href="libs/jsoneditor/jsoneditor.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/assets/css/app.css">
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
            <li>
                <a href="javascript:void(0)">
                    <span id="btn-ws-status" style="color: darkred;" class="glyphicon glyphicon-record"></span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="row">

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Jobs Statistics</h3>
                </div>
                <div class="panel-body" id="jobs-statistics">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Workers Statistics</h3>
                </div>
                <div class="panel-body" id="workers-statistics">
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Pending Jobs</h3>
                </div>
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="form-group">
                            <label class="sr-only" for="offset">Queue</label>
                            <select name="sort" id="queue" class="form-control">
                            </select>
                        </div>
                        <a href="javascript:void(0);" id="btn-load-queue-status" class="btn btn-primary">Load</a>
                    </form>
                    <div id="queues-status" style="width: 100%; height: 200px"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Failed Jobs Detail <span id="failed-jobs-size" class="badge"></span></h3>
                </div>
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="form-group">
                            <label class="sr-only" for="offset">Sort</label>
                            <select name="sort" id="sort" class="form-control">
                                <option value="2">Sort by Time DESC</option>
                                <option value="1">Sort by Time ASC</option>
                            </select>
                        </div>
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
                            <th>FailedAt/Worker</th>
                            <th>Exception</th>
                            <th>Error</th>
                            <th>Operation</th>
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
                    <button class="btn btn-xs btn-default" id="btn-clear-console">Clear</button>
                    <button class="btn btn-xs btn-default" id="btn-reset-console">Reset Line Number</button>
                    <button class="btn btn-xs btn-default" id="btn-log-level">Log Level</button>
                    <div id="console"></div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/inc/footer.html'; ?>
</div>

<?php include __DIR__ . '/inc/modals.html'; ?>
<?php include __DIR__ . '/inc/templates.html'; ?>

<script type="text/javascript">
    var WS_URL = 'ws://<?php echo $config['ws']['host']; ?>:<?php echo $config['ws']['port']; ?>';
</script>

<script src="/libs/jquery/jquery-1.11.3.min.js"></script>
<script src="/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="/libs/loader.js" type="text/javascript"></script>
<script src="/libs/juicer-min.js" type="text/javascript"></script>
<script src="/libs/echarts.min.js"></script>
<script src="/libs/jsoneditor/jsoneditor.min.js"></script>
<script src="/assets/js/utils.js"></script>
<script src="/assets/js/ws.js"></script>

<script type="text/javascript">
    // TODO storage, enhanced user experience
    // var storage = window.localStorage;

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

    $('#btn-clear-console').click(function () {
        cs.html('');
    });

    $('#btn-reset-console').click(function () {
        line_index = 1;
    });

    $('#queue').change(function () {
        $('#btn-load-queue-status').html('Load');
        $('#btn-load-queue-status').removeClass('disabled');
    });

    var timer;
    $('#btn-load-queue-status').click(function () {
        var _this = $(this);
        _this.html('Working...');
        _this.addClass('disabled');
        clearInterval(timer);

        if ($('#queue').val() == '') {
            msg('Please select a queue!');
            $('#btn-load-queue-status').html('Load');
            $('#btn-load-queue-status').removeClass('disabled');
            return;
        }

        timer = setInterval(function () {
            socket.send(JSON.stringify({
                mtd: 'queuesStatus',
                params: {
                    queue: $('#queue').val()
                }
            }));
        }, 1000);

        $('#queue').change(function () {
            _this.html('Load');
            _this.removeClass('disabled');
        });
    });

    function msg(text) {
        $('#modal-info-text').html(text);
        $('#modal-info').modal('show');
    }

    $('#btn-log-level').click(function () {
        msg('<p>Sorry, this is a todo feature...</p>');
    });

    $('#btn-load-failed-jobs').click(function () {
        var offset = $('#offset').val();
        var limit = $('#limit').val();
        var sort = $('#sort').val();
        socket.send(JSON.stringify({
            mtd: 'failedJobs',
            params: {
                offset: offset,
                limit: limit,
                sort: sort
            }
        }));
    });

    // create the editor
    var container = document.getElementById("jsoneditor");
    var options = {
        mode: 'view'
    };
    var editor = new JSONEditor(container, options);

    $(document).delegate('.btn-failed-job-detail', 'click', function () {
        var raw_data = $(this).data('raw');
        console.info(raw_data);
        // set json
        editor.set(raw_data);
        $('#modal-failed-job-detail').modal('show');
    });
</script>
</body>
</html>