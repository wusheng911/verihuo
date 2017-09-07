@extends('layouts.admin')

@section('title', '控制面板')

@push('stylesheets')
    <link media="all" type="text/css" rel="stylesheet" href="/packages/sleepingowl/default/libs/font-awesome/css/font-awesome.min.css">
@endpush

@push('scripts')
    <script type="text/javascript" src="/assets/admin/js/shop/highcharts.js" ></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var nowTime = new Date().getTime();
            var pSTime = new Date(nowTime-(30*24*3600*1000));
            var defaultOptionsZhCn = {
                lang: {
                    loading: '加载中...',
                    months: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                    noData: "没有数据",
                    numericSymbols: ['k', 'M', 'G', 'T', 'P', 'E'],
                    shortMonths: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                    thousandsSep: ',',
                    weekdays: ['星期一', '星期二', '星期三', '星期四', '星期五', '星期六', '星期天'],
                },
                tooltip: {
                    dateTimeLabelFormats: {
                        millisecond: '%A, %b %e, %H:%M:%S.%L',
                        second: '%A, %b %e, %H:%M:%S',
                        minute: '%A, %b %e, %H:%M',
                        hour: '%b %e, %H:%M',
                        day: '%Y-%m-%d',
                        week: 'Week from %A, %b %e, %Y',
                        month: '%m-%Y',
                        year: '%Y'
                    },
                },
                credits: {
                    enabled: false
                },
                xAxis: {
                    dateTimeLabelFormats: {
                        millisecond: '%H:%M:%S.%L',
                        second: '%H:%M:%S',
                        minute: '%H:%M',
                        hour: '%H:%M',
                        day: '%Y-%m-%d',
                        week: '%e. %b',
                        month: '%m-%y',
                        year: '%Y'
                    }
                }
            };
            Highcharts.setOptions(defaultOptionsZhCn);

            //最近30天订单状况
            Highcharts.chart('OrdersTotal', {
                title: {
                    text: '最近30天订单状况'
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                chart: {
                    zoomType: 'x'
                },
                xAxis: {
                    type: 'datetime',
                },
                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: '订单数量'
                    }
                },
                plotOptions: {
                    series: {
                        pointStart: Date.UTC(pSTime.getFullYear(), pSTime.getMonth(), pSTime.getDate()),
                        pointInterval: 24 * 3600 * 1000 // one day
                    }
                },
                series: [{
                        name: '当天总订单量',
                        data: App.lastMonthOrdersTotalData

                }]
            });

            //最近30天文章浏览量
            Highcharts.chart('ArticleViewTotal', {
                title: {
                    text: '最近30天文章阅读状况'
                },
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                if(this.point.name.length > 6) {
                                    return this.point.name.substring(0,6)+'...: '+this.point.percentage.toFixed(1)+'%';
                                } else {
                                    return this.point.name+': '+this.point.percentage.toFixed(1)+'%';
                                }
                            },
                            //format: '<b>{point.name}:</b>{point.percentage:.1f}%',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                                fontSize: '10px',
                            }
                        },
                        events: {
                            click: function(e) {
                                if(e.point.url!='#') {
                                    window.open(e.point.url);
                                }
                            }
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: '所占比例',
                    size: '80%',
                    data: App.lastMonthArticlesViewTotalData
                }]
            });
        });
    </script>
@endpush

@section('content')
    <div class="backend-data-total">
        <div class="row">
            <div class="col-md-4 btn-primary">
                <span>{{ $data['todayCustomersTotal'] or 0 }}</span>今日增加用户
            </div>
            <div class="col-md-4 btn-primary">
                <span>{{ $data['yesterdayCustomersTotal'] or 0 }}</span>昨日增加用户
            </div>
            <div class="col-md-4 btn-success">
                <span>{{ $data['lastWeekCustomersTotal'] or 0 }}</span>最近一周增加用户
            </div>
        </div>
        <!-- <div class="row">
             <div class="col-md-4 btn-primary">
             <span>{{ $data['todayArticleViewTotal'] or 0 }}</span>今日文章总浏览量
             </div>
             <div class="col-md-4 btn-primary">
             <span>{{ $data['yesterdayArticleViewTotal'] or 0 }}</span>昨日文章总浏览量
             </div>
             <div class="col-md-4 btn-success">
             <span>{{ $data['lastWeekArticleViewTotal'] or 0 }}</span>最近一周文章浏览量
             </div>
             </div>
             <div class="row">
             <div class="col-md-4 btn-primary">
             <span>{{ $data['todayOrdersTotal'] or 0 }}</span>今日订单量
             </div>
             <div class="col-md-4 btn-primary">
             <span>{{ $data['yesterdayOrdersTotal'] or 0 }}</span>昨日订单量
             </div>
             <div class="col-md-4 btn-success">
             <span>{{ $data['lastWeekOrdersTotal'] or 0 }}</span>最近一周订单量
             </div>
             </div> -->
        <!-- <div class="">
             <div id="OrdersTotal" class="col-md-6"></div>
             <div id="ArticleViewTotal" class="col-md-6"></div>
             </div> -->
    </div>
@endsection
