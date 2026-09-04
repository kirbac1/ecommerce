@extends('partials.admin.page')
@section('page.title', 'Dashboard')
@section('sidebar.username', $user->nameAndSurname)

@section('page.content')
    <div class="block-header">
        <h2>{{ trans('messages.Dashboard') }}</h2>

        <ul class="actions">
            <li>
                <a href="">
                    <i class="zmdi zmdi-trending-up"></i>
                </a>
            </li>
            <li>
                <a href="">
                    <i class="zmdi zmdi-check-all"></i>
                </a>
            </li>
            <li class="dropdown">
                <a href="" data-toggle="dropdown">
                    <i class="zmdi zmdi-more-vert"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a href="">{{ trans('messages.Refresh') }}</a>
                    </li>
                    <li>
                        <a href="">{{ trans('messages.Manage Widgets') }}</a>
                    </li>
                    <li>
                        <a href="">{{ trans('messages.Widgets Settings') }}</a>
                    </li>
                </ul>
            </li>
        </ul>

    </div>
    <div class="card">
        <div class="card-header">
            <h2>{{ trans('messages.Sales Statistics') }} <small>{{ trans('messages._invoices_subtitle') }}</small></h2>

            <ul class="actions">
                <li>
                    <a href="">
                        <i class="zmdi zmdi-refresh-alt"></i>
                    </a>
                </li>
                <li>
                    <a href="">
                        <i class="zmdi zmdi-download"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="" data-toggle="dropdown">
                        <i class="zmdi zmdi-more-vert"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a href="">Change Date Range</a>
                        </li>
                        <li>
                            <a href="">Change Graph Type</a>
                        </li>
                        <li>
                            <a href="">Other Settings</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="chart-edge">
                <div id="curved-line-chart" class="flot-chart " style="padding: 0px; position: relative;">
                    <canvas class="flot-base" width="1595" height="200" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 1595px; height: 200px;"></canvas>
                    <canvas class="flot-overlay" width="1595" height="200" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 1595px; height: 200px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{--<div class="mini-charts">
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="mini-charts-item bgm-cyan">
                    <div class="clearfix">
                        <div class="chart stats-bar"><canvas width="83" height="45" style="display: inline-block; width: 83px; height: 45px; vertical-align: top;"></canvas></div>
                        <div class="count">
                            <small>Website Traffics</small>
                            <h2>987,459</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="mini-charts-item bgm-green">
                    <div class="clearfix">
                        <div class="chart stats-bar"><canvas width="83" height="45" style="display: inline-block; width: 83px; height: 45px; vertical-align: top;"></canvas></div>
                        <div class="count">
                            <small>Website Traffics</small>
                            <h2>987,459</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="mini-charts-item bgm-orange">
                    <div class="clearfix">
                        <div class="chart stats-bar"><canvas width="83" height="45" style="display: inline-block; width: 83px; height: 45px; vertical-align: top;"></canvas></div>
                        <div class="count">
                            <small>Website Traffics</small>
                            <h2>987,459</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="mini-charts-item bgm-bluegray">
                    <div class="clearfix">
                        <div class="chart stats-bar"><canvas width="83" height="45" style="display: inline-block; width: 83px; height: 45px; vertical-align: top;"></canvas></div>
                        <div class="count">
                            <small>Website Traffics</small>
                            <h2>987,459</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>--}}

    <div class="dash-widgets">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div id="site-visits" class="dash-widget-item bgm-teal">
                    <div class="dash-widget-header">
                        <div class="p-20">
                            <div class="dash-widget-monthly-sales"><canvas width="223" height="95" style="display: inline-block; width: 223px; height: 95px; vertical-align: top;"></canvas></div>
                        </div>

                        <div class="dash-widget-title">{{ trans('messages.Last 30 days sales') }}</div>
                    </div>

                    <div class="p-20">
                        <small>{{ trans('messages.Gross Profits') }}</small>
                        <h3 class="m-0 f-400">&euro; {{ $grossRevenues}}</h3>
                        <br>

                        <small>{{ trans('messages.Net Profits') }}</small>
                        <h3 class="m-0 f-400">&euro; {{ $netRevenues }}</h3>
                        <br>

                        <small>{{ trans('messages.Sales') }} / {{ trans('messages.Orders') }}</small>
                        <h3 class="m-0 f-400">{{ $sells }} / {{ $ordersCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div id="pie-charts" class="dash-widget-item">
                    <div class="bgm-pink">
                        <div class="dash-widget-header">
                            <div class="dash-widget-title">{{ trans('messages.Returns') }}</div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="text-center p-20 m-t-25">
                            <div class="easy-pie main-pie" data-percent="{{ $returnedPercent }}">
                                <div class="percent">{{ $returnedPercent }}</div>
                                <div class="pie-title">{{ trans('messages.Percent of returned products') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-t-20 p-b-20 text-center">
                        <div class="easy-pie sub-pie-1" data-percent="{{ $completedOrdersPercent }}">
                            <div class="percent">{{ $completedOrdersPercent }}</div>
                            <div class="pie-title">{{ trans('messages.Completed Orders') }}</div>
                        </div>
                        <div class="easy-pie sub-pie-2" data-percent="{{ $paidInvoicesPercent }}">
                            <div class="percent">{{ $paidInvoicesPercent }}</div>
                            <div class="pie-title">{{ trans('messages.Paid Invoices') }}</div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div id="best-selling" class="dash-widget-item">
                    <div class="dash-widget-header">
                        <div class="dash-widget-title">{{ trans('messages.Best Sellings') }}</div>
                        @if($bestSellers)
                            <img src="/catalog/{{ $bestSellers[0]->image }}" alt="{{ $bestSellers[0]->name }}">
                            <div class="main-item">
                                <small>{{ $bestSellers[0]->name }}</small>
                                <h2>&euro;{{ number_format($bestSellers[0]->priceEach, 2) }} ({{ round($bestSellers[0]->quantity) . ' ' . trans('messages.items sold') }})</h2>
                            </div>
                        @endif
                    </div>
                    <div class="listview p-t-5">
                        @foreach($bestSellers as $i => $product)
                            <?php if ($i < 1) continue; ?>
                            <a class="lv-item" href="">
                                <div class="media">
                                    <div class="pull-left">
                                        <img class="lv-img-sm" src="/catalog/{{ $product->image }}" alt="{{ $product->name }}">
                                    </div>
                                    <div class="media-body">
                                        <div class="lv-title">{{ $product->name }}</div>
                                        <small class="lv-small">&euro;{{ number_format($product->priceEach, 2) }} ({{ round($product->quantity) . ' ' . trans('messages.items sold') }})</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div id="worst-selling" class="dash-widget-item">
                    <div class="dash-widget-header">
                        <div class="dash-widget-title">{{ trans('messages.Worst Sellings') }}</div>
                        @if ($worstSellers)
                            <img src="{{ $worstSellers[0]->image }}" alt="">
                            <div class="main-item">
                                <small>{{ $worstSellers[0]->name }}</small>
                                <h2>&euro;{{ number_format($worstSellers[0]->priceEach, 2) }} ({{ round($worstSellers[0]->quantity) . ' ' . trans('messages.items sold') }})</h2>
                            </div>
                        @endif
                    </div>
                    <div class="listview p-t-5">
                        @foreach($worstSellers as $i => $product)
                            <?php if ($i < 1) continue; ?>
                            <a class="lv-item" href="">
                                <div class="media">
                                    <div class="pull-left">
                                        <img class="lv-img-sm" src="/catalog/{{ $product->image }}" alt="{{ $product->name }}">
                                    </div>
                                    <div class="media-body">
                                        <div class="lv-title">{{ $product->name }}</div>
                                        <small class="lv-small">&euro;{{ number_format($product->priceEach, 2) }} ({{ round($product->quantity) . ' ' . trans('messages.items sold') }})</h2></small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page.footer')
    <script type="text/javascript">
        // Real sales for the last 31 days, oldest first. Read by curved-line-chart.js.
        window.salesChartData = {
            revenue: [{{ $revenueDays }}],
            invoices: [{{ $salesDays }}],
            revenueLabel: '{{ trans('messages.Gross Profits') }} (€)',
            invoicesLabel: '{{ trans('messages.Sales') }}'
        };
    </script>
    <!-- Data Table -->
    <script type="text/javascript">
        $(document).ready(function(){
            if ($('.dash-widget-monthly-sales')[0]) {
                sparklineLine('dash-widget-monthly-sales', [{{ $salesDays }}], '100%', '95px', 'rgba(255,255,255,0.7)', 'rgba(0,0,0,0)', 2, 'rgba(255,255,255,0.4)', 'rgba(255,255,255,0.4)', 'rgba(255,255,255,0.4)', 5, 'rgba(255,255,255,0.4)', '#fff');
            }

            //Basic Example
            $("#data-table-basic").bootgrid({
                css: {
                    icon: 'md icon',
                    iconColumns: 'md-view-module',
                    iconDown: 'md-expand-more',
                    iconRefresh: 'md-refresh',
                    iconUp: 'md-expand-less'
                },
            });

            //Selection
            $("#data-table-selection").bootgrid({
                css: {
                    icon: 'md icon',
                    iconColumns: 'md-view-module',
                    iconDown: 'md-expand-more',
                    iconRefresh: 'md-refresh',
                    iconUp: 'md-expand-less'
                },
                selection: true,
                multiSelect: true,
                rowSelect: true,
                keepSelection: true
            });

            //Command Buttons
            $("#data-table-command").bootgrid({
                css: {
                    icon: 'md icon',
                    iconColumns: 'md-view-module',
                    iconDown: 'md-expand-more',
                    iconRefresh: 'md-refresh',
                    iconUp: 'md-expand-less'
                },
                formatters: {
                    "commands": function(column, row) {
                        return "<button type=\"button\" class=\"btn btn-icon command-edit\" data-row-id=\"" + row.id + "\"><span class=\"md md-edit\"></span></button> " +
                                "<button type=\"button\" class=\"btn btn-icon command-delete\" data-row-id=\"" + row.id + "\"><span class=\"md md-delete\"></span></button>";
                    }
                }
            });
        });

        function sparklineLine(id, values, width, height, lineColor, fillColor, lineWidth, maxSpotColor, minSpotColor, spotColor, spotRadius, hSpotColor, hLineColor) {
            $('.'+id).sparkline(values, {
                type: 'line',
                width: width,
                height: height,
                lineColor: lineColor,
                fillColor: fillColor,
                lineWidth: lineWidth,
                maxSpotColor: maxSpotColor,
                minSpotColor: minSpotColor,
                spotColor: spotColor,
                spotRadius: spotRadius,
                highlightSpotColor: hSpotColor,
                highlightLineColor: hLineColor
            });
        }

    </script>
@stop