@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Support'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            <div class="btn-group btn-group pull-right">
                <button class="btn btn-primary bgm-bluegray pull-right" v-on:click.prevent="create">{{ trans('messages.ADD NEW') }}</button>
            </div>
        </div>

        <div id="data-table-basic-header" class="bootgrid-header container-fluid">
            <div class="row">
                <div class="col-sm-12 actionBar">
                    <div class="search form-group">
                        <div class="input-group">
                            <span class="md icon input-group-addon md-search"></span>
                            <input type="text" class="search-field form-control" placeholder="{{ trans('messages.Search') }}" v-model="searchFilter" debounce="300">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <!--<th class="selector">-->
                        <th>{{ trans('messages.Ticket ID') }}</th>
                        <th>{{ trans('messages.Subject') }}</th>
                        <th>{{ trans('messages.Status') }}</th>
                        <th>{{ trans('messages.Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(key, val) in localizedObjects | filterBy searchFilter" class="clickableRow">
                        <td v-on:click.prevent="gotoDetails(val.id)">@{{ val.code }}</td>
                        <td v-on:click.prevent="gotoDetails(val.id)">@{{ val.subject }}</td>
                        <td v-on:click.prevent="gotoDetails(val.id)">@{{ val.status }}</td>
                        <td v-on:click.prevent="gotoDetails(val.id)">@{{ val.created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('page.footer')
    <script>
        var vue = new Vue({
            el: 'body',
            data: {
                searchFilter: '',
                objects: [],
                selectAllStatus: false,
                start: 1,
                limit: 20,
                total: 0,
            },
            computed: {
                totalPages: function totalPages() {
                    return Math.ceil(this.total / this.limit);
                },
                start_item: function start_item() {
                    return ((this.start - 1) * this.limit)+1;
                },
                end_item: function end_item() {
                    return (this.start) * this.limit < this.total ? ((this.start) * this.limit) : this.total;
                },
                total_items: function total_items() {
                    return this.total;
                },
                localizedObjects: function() {
                    localized = [];
                    this.objects.forEach(function(object) {
                        if (object.status == 'open') {
                            object.status = '{!! trans('messages.prop.open') !!}';
                        } else if (object.status == 'closed') {
                            object.status = '{!! trans('messages.prop.closed') !!}';
                        } else if (object.status == 'awaiting_response') {
                            object.status = '{!! trans('messages.prop.awaiting_response') !!}';
                        } else if (object.status == 'pending_close') {
                            object.status = '{!! trans('messages.prop.pending_close') !!}';
                        }

                        localized.push(object);
                    });
                    return localized;
                }
            },
            methods: {
                paginationButtons: function paginationButtons() {
                    var arr = [];
                    var startPage = this.start;
                    if (startPage <= 5) {
                        for(i=1; (i<=10) && (i <= this.totalPages); i++) {
                            arr.push(i);
                        }
                        return arr;
                    } else {
                        for(i=startPage-5; (i<startPage+5) && (i <= this.totalPages); i++) {
                            arr.push(i);
                        }
                        return arr;
                    }
                },
                goToPage: function goToPage(val) {
                    this.start = val;
                    this.paginationButtons();
                    Vue.http.get('/api/v3/search/products/' + this.searchFilter, { limit: this.limit, start: ((this.start -1) * this.limit) }).then(function success(response) {
                        response.data.result.forEach(function(element) {
                            element.selected = false;
                        });
                        this.$set('objects', response.data.result);
                        this.total = response.data.count;
                    }.bind(this), function error(response) {
                        console.log('FAILURE', response);
                    });
                },
                nextPage: function nextPage() {
                    if (this.start+10 < this.totalPages) {
                        this.start += 10;
                    } else {
                        this.start = this.totalPages;
                    }
                    this.goToPage(this.start);
                },
                prevPage: function prevPage() {
                    if (this.start-10 > 1) {
                        this.start -=10;
                    } else {
                        this.start = 1;
                    }
                    this.goToPage(this.start);
                },
                firstPage: function firstPage() {
                    this.start = 1;
                    this.goToPage(1);
                },
                lastPage: function lastPage() {
                    this.start = this.totalPages;
                    this.goToPage(this.start);
                },
                handleSelectAll: function selectAll(event) {
                    if (this.selectAllStatus) {
                        this.objects.forEach(function(element) {
                            element.selected = false;
                        });
                        this.selectAllStatus = false;
                    } else {
                        this.objects.forEach(function(element) {
                            element.selected = true;
                        });
                        this.selectAllStatus = true;
                    }
                },
                create: function create(event) {
                    document.location = document.location + '/create';
                },
                gotoDetails: function gotoDetails(element) {
                    document.location = document.location + '/' + element + '/edit';
                },
                loadObjects: function loadObjects(query) {
                    if (!query) query = '';
                    Vue.http.get('/api/v3/search/tickets/' + query, { limit: this.limit, start: this.start - 1 }).then(function success(response) {
                        if (response.data.count) {
                            response.data.result.forEach(function (element) {
                                element.selected = false;
                            });
                        } else {
                            this.$set('objects', null);
                        }
                        this.$set('objects', response.data.result);
                        this.total = response.data.count;
                    }.bind(this), function error(response) {
                        console.log('FAILURE', response);
                    });
                }
            },
            ready: function ready() {
                this.loadObjects();
            }
        });
        vue.$watch('searchFilter', function() {
            this.start = 1;
            this.loadObjects(this.searchFilter);
        });
    </script>
@stop