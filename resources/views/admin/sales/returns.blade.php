@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Returns'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            <div class="btn-group btn-group pull-right">
                <button class="btn btn-primary bgm-red" v-on:click.prevent="destroy">{{ trans('messages.DELETE SELECTED') }}</button>
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
                    <th class="selector">
                        <div class="checkbox m-b-15">
                            <label>
                                <input type="checkbox" v-model="selectAllStatus" @click="handleSelectAll">
                                <i class="input-helper"></i>
                            </label>
                        </div>
                    </th>
                    <th>{{ trans('messages.RMA ID') }}</th>
                    <th>{{ trans('messages.Order ID') }}</th>
                    <th>{{ trans('messages.Company Name') }}</th>
                    <th>{{ trans('messages.Name') }}</th>
                    <th>{{ trans('messages.Surname') }}</th>
                    <th>{{ trans('messages.VAT ID') }}</th>
                    <th>{{ trans('messages.Quantity') }}</th>
                    <th>{{ trans('messages.Date') }}</th>
                    <th>{{ trans('messages.Total Refund') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(key, val) in localizedObjects | filterBy searchFilter" class="clickableRow">
                    <td>
                        <div class="checkbox m-b-15">
                            <label>
                                <input type="checkbox" v-model="val.selected">
                                <i class="input-helper"></i>
                            </label>
                        </div>
                    </td>
                    <td v-on:click="gotoDetails(val.id)">@{{ val.rma }}</td>
                    <td v-on:click="gotoDetails(val.id)">#@{{ val.order_id }}</td>
                    <td v-on:click="gotoDetails(val.id)">@{{ val.company }}</td>
                    <td v-on:click="gotoDetails(val.id)">@{{ val.name }}</td>
                    <td v-on:click="gotoDetails(val.id)">@{{ val.surname }}</td>
                    <td v-on:click="gotoDetails(val.id)">@{{ val.vatid }}</td>
                    <td v-on:click="gotoDetails(val.id)">@{{ productQuantity(val) }} {{ trans('messages.products') }}</td>
                    <td v-on:click="gotoDetails(val.id)">@{{ val.created_at }}</td>
                    <td v-on:click="gotoDetails(val.id)">&euro;@{{ val.total.toFixed(4) }}</td>
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
                order_id: {{ $order_id ?? 'null' }},
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
                    var that = this;
                    localized = [];
                    this.objects.forEach(function(object) {
                        object.productQuantity = 0.00;
                        object.total = 0.00;

                        var returnedObjects = object.products;
                        returnedObjects.forEach(function(product) {
                            console.log(product);
                            object.total += (parseFloat(product.pivot.priceEach) * parseFloat(product.pivot.quantity) * (100 + parseFloat(product.pivot.taxPercent)) / 100);
                        });
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
                    Vue.http.get('/api/v3/search/returns/' + this.searchFilter, { limit: this.limit, start: ((this.start -1) * this.limit) }).then(function success(response) {
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
                booleanToHuman(val) {
                    if (val != 0) {
                        return "{{ trans('messages.True') }}"
                    } else {
                        return "{{ trans('messages.False') }}"
                    }
                },
                productQuantity: function productQuantity(object) {
                    return object.products.length;
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
                create: function(event) {
                    if (this.order_id)
                        document.location = '/admin/returns/create?order_id=' + this.order_id;
                    else
                        document.location = '/admin/returns/create';
                },
                gotoDetails: function(element) {
                    document.location = '/admin/returns/' + element + '/edit';
                },
                destroy: function destroy(event) {
                    var that = this;
                    var count = 0;
                    this.objects.forEach(function(object) {
                        if(object.selected) count++;
                    });
                    if (count === 0) return false;
                    swal({
                        title: "{{ trans('messages.title_sure_to_delete?') }}",
                        text: "{{ trans('messages.body_sure_to_delete?') }}",
                        confirmButtonText: "{{ trans('messages.CONFIRM') }}",
                        confirmButtonColor: '#f44336',
                        cancelButtonText: "{{ trans('messages.CANCEL') }}",
                        cancelButtonColor: '#607d8b',
                        showConfirmButton: true,
                        showCancelButton: true,
                        closeOnConfirm: false,
                        closeOnCancel: true,
                        type: 'warning',
                        html: true
                    }, function(choice) {
                        if (!choice) return false;
                        that.objects.forEach(function(object) {
                            if (object.selected) {
                                Vue.http.delete('/api/v3/returns/' + object.id);
                            }
                        });
                        swal({
                            title: "{{ trans('messages.Success!') }}",
                            text: "{{ trans('messages.The element was deleted.') }}",
                            type: 'success',
                            html: true,
                            timer: 10000,
                            confirmButtonColor: '#2196f3'
                        }, function() {
                            location.href = '/admin/returns';
                        });
                    });
                },
                loadObjects: function loadObjects(query) {
                    if (!query) query = '';
                    Vue.http.get('/api/v3/search/returns/' + query, { limit: this.limit, start: this.start - 1, order_id: this.order_id }).then(function success(response) {
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
