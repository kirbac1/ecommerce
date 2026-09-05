@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Edit Return'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            @if($return !== null)
                <div class="btn-group btn-group pull-right">
                    @if($return->order)
                        <button class="btn btn-primary bgm-indigo" v-on:click.prevent="goToOrder">{{ trans('messages.GO TO ORDER') }}</button>
                    @endif
                    <button class="btn btn-primary bgm-red pull-right" v-on:click.prevent="destroy">{{ trans('messages.DELETE') }}</button>
                    @if($return)
                        <button class="btn btn-primary bgm-orange pull-right" v-on:click.prevent="getReceipt">{{ trans('messages.RETURN PDF') }}</button>
                    @endif
                    <!--<button class="btn btn-primary bgm-bluegray pull-right" v-on:click.prevent="update" v-if="!exists">{{ trans('messages.UPDATE') }}</button>-->
                </div>
            @else
                <button class="btn btn-primary bgm-bluegray pull-right" v-on:click.prevent="create" :disabled="!returned.order.id">{{ trans('messages.SAVE') }}</button>
                <button v-if="!order_id" class="btn btn-primary bgm-purple pull-right" v-on:click.prevent="orderSearch">{{ trans('messages.SELECT ORDER') }}</button>
            @endif
            <h2>
                {{ trans('messages.Edit Return') }}
                <small>
                    {{ trans('messages._edit_return_subtitle') }}
                </small>
            </h2>
        </div>

        <div class="card-body card-padding">
            <form role="form">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Name') }}</label>
                            <input type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Enter Name') }}" v-model="returned.order.name" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="surname">{{ trans('messages.Surname') }}</label>
                            <input type="text" name="surname" class="form-control input-sm" placeholder="{{ trans('messages.Enter Surname') }}" v-model="returned.order.surname" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line" v-if="returned.order.entityType == 'company'">
                            <label for="name">{{ trans('messages.Company Name') }}</label>
                            <input type="text" name="company" class="form-control input-sm" placeholder="{{ trans('messages.Company Name') }}" v-model="returned.order.company" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <p class="c-black f-500 m-b-20 select-nomargin">{{ trans('messages.Entity Type') }}</p>
                        <div class="form-group">
                            <div class="select">
                                <div class="form-control">
                                    <select class="form-control" v-model="returned.order.entityType" disabled="disabled">
                                        <option value="person">{{ trans('messages.Person') }}</option>
                                        <option value="company">{{ trans('messages.Company') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="vatid">{{ trans('messages.VAT ID') }}</label>
                            <input type="text" name="vatid" class="form-control input-sm" placeholder="{{ trans('messages.Enter VAT ID') }}" v-model="returned.order.vatid" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email1">{{ trans('messages.Primary E-Mail') }}</label>
                            <input type="text" name="email1" class="form-control input-sm" placeholder="{{ trans('messages.Primary E-Mail') }}" v-model="returned.order.email1" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email2">{{ trans('messages.Secondary E-Mail') }}</label>
                            <input type="text" name="email2" class="form-control input-sm" placeholder="{{ trans('messages.Secondary E-Mail') }}" v-model="returned.order.email2" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="zipcode">{{ trans('messages.Zipcode') }}</label>
                            <input type="text" name="zipcode" class="form-control input-sm" placeholder="{{ trans('messages.Zipcode') }}" v-model="returned.order.zipcode" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street1">{{ trans('messages.Street1') }}</label>
                            <input type="text" name="street1" class="form-control input-sm" placeholder="{{ trans('messages.Street1') }}" v-model="returned.order.street1" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street2">{{ trans('messages.Street2') }}</label>
                            <input type="text" name="street2" class="form-control input-sm" placeholder="{{ trans('messages.Street2') }}" v-model="returned.order.street2" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="city">{{ trans('messages.City') }}</label>
                            <input type="text" name="city" class="form-control input-sm" placeholder="{{ trans('messages.City') }}" v-model="returned.order.city" disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.Phone') }}</label>
                            <input type="text" name="phone" class="form-control input-sm" placeholder="{{ trans('messages.Phone') }}" v-model="returned.order.phone" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Mobile') }}</label>
                            <input type="text" name="mobile" class="form-control input-sm" placeholder="{{ trans('messages.Mobile') }}" v-model="returned.order.mobile" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.State') }}</label>
                            <input type="text" name="state" class="form-control input-sm" placeholder="{{ trans('messages.State') }}" v-model="returned.order.state" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="country">{{ trans('messages.Country') }}</label>
                            <input type="text" name="country" class="form-control input-sm" placeholder="{{ trans('messages.Country') }}" v-model="returned.order.country" disabled="disabled">
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>{{ trans('messages.Products') }}</h2>

 @if(!$return)
    <p>
      <input type="checkbox" class="filled-in" id="filled-in-box" v-model="returnAll" />
      <label for="filled-in-box">{{ trans('messages.Return All') }}</label>
    </p>
  @endif      

        </div>
        <div class="card-body card-padding table-responsive">
            <table class="table">
                <thead>
                <tr style="width: 100%;:">
                    <th>{{ trans('messages.Manufacturer') }}</th>
                    <th>{{ trans('messages.Name') }}</th>
                    <th>{{ trans('messages.SKU') }}</th>
                    <th>{{ trans('messages.Barcode') }}</th>
                    <th>{{ trans('messages.Returned Quantity') }}</th>
                    <th>{{ trans('messages.Nontaxed') }}</th>
                    <th>{{ trans('messages.Taxed') }}</th>
                    <th>{{ trans('messages.Tax Percent') }}</th>
                    <th>{{ trans('messages.Total') }}</th>
                    <!--<th v-if="!exists">{{ trans('messages.Action') }}</th>-->
                </tr>
                </thead>
                <tbody>
                    <tr v-for="(key, item) in returnableProducts" style="width: 100%;">
                        <td>@{{ item.manufacturer }}</td>
                        <td>@{{ item.name }}</td>
                        <td>@{{ item.sku }}</td>
                        <td>@{{ item.barcode }}</td>
                        <td>
                            <div class="form-group fg-line" :class="{ 'has-error': checkMaxQuantities(item) }">
                                <input number type="text" name="@{{ item.id }}-quantity" class="form-control" placeholder="{{ trans('messages.Returned Quantity') }}" v-model="item.quantity" :disabled="exists" v-if="!exists">
                                <input number type="text" name="@{{ item.id }}-quantity" class="form-control" placeholder="{{ trans('messages.Returned Quantity') }}" v-model="item.pivot.quantity" :disabled="exists" v-else>
                            </div>
                        </td>
                        <td>
                            <div class="form-group fg-line">
                                <span>@{{ item.priceEach }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="form-group fg-line">
                                <span>@{{ item.taxedPriceEach }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="form-group fg-line">
                                <span>@{{ item.taxPercent }}</span>
                            </div>
                        </td>
                        <td v-if="!exists">&euro;@{{ (item.quantity * item.priceEach * ((100 + parseFloat(item.taxPercent)) / 100)).toFixed(4) }}</td>
                        <td v-else>&euro;@{{ (item.pivot.quantity * item.priceEach * ((100 + parseFloat(item.taxPercent)) / 100)).toFixed(4) }}</td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                        <td><strong>{{ trans('messages.TOTAL') }}</strong></td>
                        <td><strong>&euro;@{{ subtotal }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>











    <div class="modal fade" id="orderSearch" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('messages.Choose Order') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                        </div>

                        <div id="data-table-basic-header" class="bootgrid-header container-fluid">
                            <div class="row">
                                <div class="col-sm-12 actionBar">
                                    <div class="search form-group">
                                        <div class="input-group">
                                            <span class="md icon input-group-addon glyphicon-search"></span>
                                            <input type="text" class="search-field form-control" placeholder="{{ trans('messages.Choose Order') }}" v-model="orderFilter">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ trans('messages.Order ID') }}</th>
                                        <th>{{ trans('messages.Company Name') }}</th>
                                        <th>{{ trans('messages.Name') }}</th>
                                        <th>{{ trans('messages.Surname') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(orderKey, orderFound) in orders | filterBy orderFilter" class="clickableRow">
                                        <td v-on:click.prevent="selectOrder(orderFound)">@{{ orderFound.id }}</td>
                                        <td v-on:click.prevent="selectOrder(orderFound)">@{{ orderFound.company }}</td>
                                        <td v-on:click.prevent="selectOrder(orderFound)">@{{ orderFound.name }}</td>
                                        <td v-on:click.prevent="selectOrder(orderFound)">@{{ orderFound.surname }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page.footer')
    <script>
        Vue.config.debug = true;

        var vue = new Vue({
            el: 'body',
            data: {
                returnAll:false,
                returned: { entityType: 'person', order: {} },
                returnableProducts: [],
                searchResults: [],
                createbuttonEnabled: true,
                orders: [],
                order_id: {{ $order_id ?? 'null' }},
                orderFilter: '',
                exists: false,
                object: {}
            },
            computed: {
                compose: function() {
                    var that = this;
                    var composed = {
                        order_id: this.returned.order.id,
                        id: this.returned.id,
                        products: [],
                        customer_id: this.returned.order.customer_id,
                    };
                    this.returnableProducts.forEach(function(product, key) {
                        composed.products.push({
                            id: product.id,
                            quantity: product.quantity
                        });
                    });
                    return composed;
                },
                subtotal: function() {
                    var subtotal = 0.00;
                    if (!this.exists) {
                        this.returnableProducts.forEach(function(product) {
                            subtotal += parseFloat(product.priceEach) * parseFloat(product.quantity) * (100 + parseFloat(product.taxPercent)) / 100;
                        });
                    } else {
                        this.returnableProducts.forEach(function (product, key) {
                            subtotal += parseFloat(parseFloat(product.priceEach) * parseFloat(product.pivot.quantity) * (100 + parseFloat(product.taxPercent)) / 100);
                        });
                    }
                    return subtotal.toFixed(4);
                }
            },
            methods: {
                getReceipt: function getReceipt() {
                    location.href="/api/v3/returns/{{ $return->id ?? '' }}/generatePDF";
                },
                checkMaxQuantities: function checkMaxQuantities(item) {
                    return item.quantity > item.maxQuantity;
                },
                goToOrder: function goToOrder() {
                    location.href = '/admin/orders/' + this.returned.order_id + '/edit';
                },
                update: function(event) {
                    Vue.http.put('/api/v3/returns/{{ $return->id ?? '' }}', this.compose).then(function success(response) {
                        response.data.products.forEach(function(product) {
                            product.priceEach = product.details.priceEach;
                            product.taxPercent = product.details.taxPercent;
                            product.quantity = product.details.quantity;
                        });
                        this.$set('returned', response.data);
                        swal({
                            title: "{{ trans('messages.Data saved!') }}",
                            text: "{{ trans('messages.The data you sent was correctly saved!') }}",
                            timer: 3000,
                            confirmButtonColor: '#2196f3',
                            showConfirmButton: true,
                            type: 'success',
                            html: true,
                            closeOnConfirm: false
                        }, function() {
                            location.href = '/admin/returns';
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                create: function(event) {
                    var that = this;
                    var isItReturnable = true;
                    this.returnableProducts.forEach(function(product) {
                        if (product.quantity > product.maxQuantity) {
                            swal({
                                title: "{{ trans('messages.Cannot return!') }}",
                                text: "{{ trans('messages.Cannot return more products than bought ones!') }}",
                                timer: 3000,
                                confirmButtonColor: '#2196f3',
                                showConfirmButton: true,
                                type: 'error',
                                html: true,
                                closeOnConfirm: true
                            });

                            isItReturnable = false;
                        }
                    });
                    if (!isItReturnable) return false;
                    this.createbuttonEnabled = false;
                    Vue.http.post('/api/v3/returns', this.compose).then(function success(response) {
                        response.data.products.forEach(function(product) {
                            product.priceEach = product.details.priceEach;
                            product.taxPercent = product.details.taxPercent;
                            product.quantity = product.details.quantity;
                        });
                        this.$set('object', response.data);
                        swal({
                            title: "{{ trans('messages.Data saved!') }}",
                            text: "{{ trans('messages.The data you sent was correctly saved!') }}",
                            timer: 3000,
                            confirmButtonColor: '#2196f3',
                            showConfirmButton: true,
                            type: 'success',
                            html: true,
                            closeOnConfirm: false
                        }, function() {
                            location.href = '/admin/returns/' + that.object.id + '/edit';
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                destroy: function(event) {
                    var that = this;
                    swal({
                        title: "{{ trans('messages.title_sure_to_delete?') }}",
                        text: "{{ trans('messages.body_sure_to_delete?') }}",
                        confirmButtonText: "{{ trans('messages.CONFIRM') }}",
                        confirmButtonColor: '#f44336',
                        cancelButtonText: "{{ trans('messages.CANCEL') }}",
                        cancelButtonColor: '#607d8b',
                        showConfirmButton: true,
                        showCancelButton: true,
                        type: 'warning',
                        html: true,
                        closeOnConfirm: false
                    }, function(choice) {
                        if (choice) {
                            Vue.http.delete('/api/v3/returns/' + that.returned.id).then(function success(response) {
                                swal({
                                    title: "{{ trans('messages.Success!') }}",
                                    text: "{{ trans('messages.The element was deleted.') }}",
                                    type: 'success',
                                    showConfirmButton: true,
                                    confirmButtonColor: '#2196f3',
                                    html: true,
                                    timer: 3000,
                                    closeOnConfirm: false
                                }, function() {
                                    location.href = '/admin/returns';
                                });
                            });
                        }
                    }.bind(this));
                },
                orderSearch: function orderSearch() {
                    $('#orderSearch').modal('show');
                },
                selectOrder: function selectOrder(order) {
                    var that = this;
                    // First use this.$set to notify the changes (is there a another way to do it?)
                    this.$set('returned.order', order);
                    that.returnableProducts = [];
                    order.products.forEach(function(product) {
                        var retobj = {};
                        retobj = Object.assign({}, retobj, {
                            id: product.id,
                            priceEach: product.details.priceEach,
                            taxedPriceEach: product.details.taxedPriceEach,
                            maxQuantity: parseFloat(product.details.quantity),
                            quantity: 0,
                            taxPercent: product.details.taxPercent,
                            manufacturer: product.manufacturer,
                            name: product.name,
                            sku: product.sku,
                            barcode: product.barcode,
                        });
                        that.returnableProducts.push(retobj);
                    });
                    $('#orderSearch').modal('hide');
                }
            },
            ready: function ready() {
                var that = this;
                @if($return)
                    Vue.http.get('/api/v3/returns/{{ $return->id ?? '' }}').then(function success(response) {
                    this.$set('returned', response.data);
                    this.$set('returnableProducts', response.data.products);
                    this.exists = true;
                    that.returned.products.forEach(function(returnProduct) {
                        that.returned.order.products.forEach(function(orderProduct) {
                            if (returnProduct.id === orderProduct.id) {
                                returnProduct.priceEach = orderProduct.pivot.priceEach;
                                returnProduct.taxPercent = orderProduct.pivot.taxPercent;
                                returnProduct.maxQuantity = orderProduct.pivot.quantity;
                                returnProduct.taxedPriceEach = orderProduct.taxedPrice;
                            }
                        });
                    });
                }.bind(this), function error(response) {
                    console.log('FAILURE', response);
                });
                @endif

                @if(!$order_id)
                    Vue.http.get('/api/v3/orders/paid').then(function success(response) {
                        this.$set('orders', response.data);
                    }.bind(this), function error(response) {
                        console.log('FAILURE getting orders', response);
                    });
                @else
                    Vue.http.get('/api/v3/orders/{{ $order_id }}').then(function success(result) {
                        that.selectOrder(result.data);
                    });
                @endif
            }
        });

        vue.$watch('returnAll', function() {

            if (this.returnAll) {

                for (var i = 0; i < this.returnableProducts.length; i++) {
                    this.returnableProducts[i].quantity = this.returnableProducts[i].maxQuantity;
                }
            }
            else{
                for (var i = 0; i < this.returnableProducts.length; i++) {
                    this.returnableProducts[i].quantity = 0;
                }
            }
        });
    </script>
@stop