/**
 * ws.js
 */

var line_index = 1;
var cs = $('#console');

// init echarts
var queues_status = echarts.init(document.getElementById('queues-status'));
var date = [(new Date()).Format('YYYY-MM-dd HH:mm:ss')];
var data = [0];

queues_status.setOption({
    xAxis: {
        type: 'category',
        boundaryGap: false,
        data: date
    },
    yAxis: {
        boundaryGap: [0, '50%'],
        type: 'value'
    },
    series: [
        {
            name: 'Pending Jobs',
            type: 'line',
            smooth: true,
            symbol: 'none',
            stack: 'a',
            areaStyle: {
                normal: {}
            },
            data: data
        }
    ]
});

function update_data(resp_data, limit) {
    date.push(resp_data.time);
    data.push(resp_data.val);
    if (limit && date.length > limit) {
        date.shift();
        data.shift();
    }
}

var socket = new WebSocket(WS_URL);
socket.onopen = function (event) {
    // server_status('已连接');
    log('Connected.');
    $('#btn-ws-status').css('color', 'green');

    socket.send(JSON.stringify({
        mtd: 'jobsStatistics'
    }));

    socket.send(JSON.stringify({
        mtd: 'queuesStatistics'
    }));

    socket.onmessage = function (event) {
        log('Client received a message');
        var resp = JSON.parse(event.data);
        if (resp.action) {
            switch (resp.action) {
                case 'queuesStatus':
                    update_data(resp.data, 20);
                    queues_status.setOption({
                        xAxis: {
                            data: date
                        },
                        series: [
                            {
                                name: 'Pending Jobs',
                                data: data
                            }
                        ]
                    });
                    break;
                case 'queuesStatistics':
                    var js = 1;
                    resp.data.queues.map(function (item) {
                        var slt_html = '';
                        if (js == 1) {
                            slt_html += '<option value="' + item.name + '" selected="selected">' + item.name + '</option>';
                            $('#queue').html(slt_html);
                        } else {
                            slt_html += '<option value="' + item.name + '">' + item.name + '</option>';
                            $('#queue').append(slt_html);
                        }
                        js++;
                    });
                    console.info(resp.data);
                    $('#queues-statistics').html(juicer($('#tpl-queues-statistics').html(), resp.data));
                    break;
                case 'jobsStatistics':
                    $('#jobs-statistics').html(juicer($('#tpl-jobs-statistics').html(), resp.data));
                    break;
                case 'failedJobs':
                    console.info(resp.data);
                    $('#failed-jobs-size').html(resp.data.failed_jobs_size);
                    $('#list-failed-jobs').html(juicer($('#tpl-failed-jobs').html(), resp.data));
                    break;
                case 'output':
                    break;
            }
        }
    };

    socket.onabort = function (event) {
        log('Disconnect.');
        $('#btn-ws-status').css('color', 'darkred');
    };

    socket.onclose = function (event) {
        log('Connection is closed!');
        $('#btn-ws-status').css('color', 'darkred');
    };
    //socket.close()
};