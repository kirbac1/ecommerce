@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Edit Invoice'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            @if($invoice !== null)
                <div class="btn-group btn-group pull-right">
                    @if($invoice->order)
                        <button class="btn btn-primary bgm-indigo" v-on:click.prevent="goToOrder">{{ trans('messages.GO TO ORDER') }}</button>
                    @endif
                    <button class="btn btn-primary bgm-red" v-on:click.prevent="destroy">{{ trans('messages.DELETE') }}</button>
                    @if($invoice)
                        <button class="btn btn-primary bgm-orange pull-right" v-on:click.prevent="getReceipt">{{ trans('messages.INVOICE PDF') }}</button>
                    @endif
                    <button class="btn btn-primary bgm-bluegray" v-on:click.prevent="update" id="createbutton">{{ trans('messages.UPDATE') }}</button>
                </div>
            @else
                <button class="btn btn-primary bgm-purple pull-right" v-on:click.prevent="customerSearch">{{ trans('messages.SEARCH CUSTOMER') }}</button>
            @endif
            <h2>
                {{ trans('messages.Edit Invoice') }}
                <small>
                    {{ trans('messages._edit_invoice_subtitle') }}
                </small>
            </h2>
        </div>

        <div class="card-body card-padding">
            <form role="form">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Name') }}</label>
                            <input type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Enter Name') }}" v-model="invoice.name" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="surname">{{ trans('messages.Surname') }}</label>
                            <input type="text" name="surname" class="form-control input-sm" placeholder="{{ trans('messages.Enter Surname') }}" v-model="invoice.surname" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line" v-if="invoice.customer.type == 'company'">
                            <label for="name">{{ trans('messages.Company Name') }}</label>
                            <input type="text" name="company" class="form-control input-sm" placeholder="{{ trans('messages.Company Name') }}" v-model="invoice.customer.company" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <p class="c-black f-500 m-b-20 select-nomargin">{{ trans('messages.Entity Type') }}</p>
                        <div class="form-group">
                            <div class="select">
                                <div class="form-control">
                                    <select class="form-control" v-model="invoice.customer.type" disabled="disabled">
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
                            <input type="text" name="vatid" class="form-control input-sm" placeholder="{{ trans('messages.Enter VAT ID') }}" v-model="invoice.customer.vatid" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email1">{{ trans('messages.Primary E-Mail') }}</label>
                            <input type="text" name="email1" class="form-control input-sm" placeholder="{{ trans('messages.Primary E-Mail') }}" v-model="invoice.email1" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email2">{{ trans('messages.Secondary E-Mail') }}</label>
                            <input type="text" name="email2" class="form-control input-sm" placeholder="{{ trans('messages.Secondary E-Mail') }}" v-model="invoice.email2" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="zipcode">{{ trans('messages.Zipcode') }}</label>
                            <input type="text" name="zipcode" class="form-control input-sm" placeholder="{{ trans('messages.Zipcode') }}" v-model="invoice.zipcode" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street1">{{ trans('messages.Street1') }}</label>
                            <input type="text" name="street1" class="form-control input-sm" placeholder="{{ trans('messages.Street1') }}" v-model="invoice.street1" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street2">{{ trans('messages.Street2') }}</label>
                            <input type="text" name="street2" class="form-control input-sm" placeholder="{{ trans('messages.Street2') }}" v-model="invoice.street2" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="city">{{ trans('messages.City') }}</label>
                            <input type="text" name="city" class="form-control input-sm" placeholder="{{ trans('messages.City') }}" v-model="invoice.city" disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.Phone') }}</label>
                            <input type="text" name="phone" class="form-control input-sm" placeholder="{{ trans('messages.Phone') }}" v-model="invoice.phone" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Mobile') }}</label>
                            <input type="text" name="mobile" class="form-control input-sm" placeholder="{{ trans('messages.Mobile') }}" v-model="invoice.mobile" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.State') }}</label>
                            <input type="text" name="state" class="form-control input-sm" placeholder="{{ trans('messages.State') }}" v-model="invoice.state" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="country">{{ trans('messages.Country') }}</label>
                            <input type="text" name="country" class="form-control input-sm" placeholder="{{ trans('messages.Country') }}" v-model="invoice.country" disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="due_at">{{ trans('messages.Due At') }}</label>
                            <input type="date" name="due_at" class="form-control input-sm" placeholder="{{ trans('messages.Due At') }}" v-model="cdue_at" :disabled="cPaid == '1'">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label>{{ trans('messages.Paid Status') }}</label><br>
                            <select class="form-control" name="paid" v-model="cPaid">
                                <option value="1">{{ trans('messages.Paid') }}</option>
                                <option value="0">{{ trans('messages.Not Paid') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h2>
                {{ trans('messages.Delivery') }}
                <small>
                    {{ trans('messages._edit_invoice_delivery') }}
                </small>
            </h2>
        </div>

        <div class="card-body card-padding">
            <form role="form">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Name') }}</label>
                            <input type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Enter Name') }}" v-model="invoice.customer.name" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="surname">{{ trans('messages.Surname') }}</label>
                            <input type="text" name="surname" class="form-control input-sm" placeholder="{{ trans('messages.Enter Surname') }}" v-model="invoice.customer.surname" disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email1">{{ trans('messages.Primary E-Mail') }}</label>
                            <input type="text" name="email1" class="form-control input-sm" placeholder="{{ trans('messages.Primary E-Mail') }}" v-model="invoice.customer.email1" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email2">{{ trans('messages.Secondary E-Mail') }}</label>
                            <input type="text" name="email2" class="form-control input-sm" placeholder="{{ trans('messages.Secondary E-Mail') }}" v-model="invoice.customer.email2" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="zipcode">{{ trans('messages.Zipcode') }}</label>
                            <input type="text" name="zipcode" class="form-control input-sm" placeholder="{{ trans('messages.Zipcode') }}" v-model="invoice.customer.zipcode" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street1">{{ trans('messages.Street1') }}</label>
                            <input type="text" name="street1" class="form-control input-sm" placeholder="{{ trans('messages.Street1') }}" v-model="invoice.customer.street1" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street2">{{ trans('messages.Street2') }}</label>
                            <input type="text" name="street2" class="form-control input-sm" placeholder="{{ trans('messages.Street2') }}" v-model="invoice.customer.street2" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="city">{{ trans('messages.City') }}</label>
                            <input type="text" name="city" class="form-control input-sm" placeholder="{{ trans('messages.City') }}" v-model="invoice.customer.city" disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.Phone') }}</label>
                            <input type="text" name="phone" class="form-control input-sm" placeholder="{{ trans('messages.Phone') }}" v-model="invoice.customer.phone" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Mobile') }}</label>
                            <input type="text" name="mobile" class="form-control input-sm" placeholder="{{ trans('messages.Mobile') }}" v-model="invoice.customer.mobile" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.State') }}</label>
                            <input type="text" name="state" class="form-control input-sm" placeholder="{{ trans('messages.State') }}" v-model="invoice.customer.state" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="country">{{ trans('messages.Country') }}</label>
                            <input type="text" name="country" class="form-control input-sm" placeholder="{{ trans('messages.Country') }}" v-model="invoice.customer.country" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <textarea class="form-control" name="notes" placeholder="{{ trans('messages.Notes...') }}" data-autosize-on="true" style="overflow: hidden; word-wrap: break-word; height: 43.8px;" v-model="invoice.notes" disabled="disabled"></textarea>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary bgm-red pull-right" v-on:click.prevent="addProduct" disabled="disabled">{{ trans('messages.ADD PRODUCT') }}</button>
            <h2>
                {{ trans('messages.Products') }}
            </h2>
        </div>
        <div class="card-body card-padding table-responsive">
            <table class="table">
                <thead>
                <tr style="width: 100%;:">
                    <th>{{ trans('messages.Manufacturer') }}</th>
                    <th>{{ trans('messages.Name') }}</th>
                    <th>{{ trans('messages.SKU') }}</th>
                    <th>{{ trans('messages.Barcode') }}</th>
                    <th>{{ trans('messages.Quantity') }}</th>
                    <th>{{ trans('messages.Nontaxed') }}</th>
                    <th>{{ trans('messages.Taxed') }}</th>
                    <th>{{ trans('messages.Tax Percent') }}</th>
                    <th>{{ trans('messages.Total') }}</th>
                    <th>{{ trans('messages.Action') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(key, item) in invoice.products" style="width: 100%;">
                    <td>@{{ item.manufacturer }}</td>
                    <td>@{{ item.name }}</td>
                    <td>@{{ item.sku }}</td>
                    <td>@{{ item.barcode }}</td>
                    <td>
                        <div class="form-group fg-line">
                            <input type="text" name="@{{ item.id }}-quantity" class="form-control" placeholder="{{ trans('messages.Quantity') }}" v-model="item.quantity" disabled="disabled">
                        </div>
                    </td>
                    <td>
                        <div class="form-group fg-line">
                            <input type="text" name="@{{ item.id }}-priceEach" class="form-control" placeholder="{{ trans('messages.Nontaxed') }}" v-model="item.priceEach" disabled="disabled">
                        </div>
                    </td>
                    <td>
                        <div class="form-group fg-line">
                            <input type="text" name="@{{ item.id }}-priceEach" class="form-control" placeholder="{{ trans('messages.Taxed') }}" v-model="item.taxedPriceEach" disabled="disabled">
                        </div>
                    </td>
                    <td>
                        <div class="form-group fg-line">
                            <span>@{{ item.taxPercent }}</span>
                        </div>
                    </td>
                    <td>&euro;@{{ item.taxedPriceTotal }}</td>
                    <td><button class="btn btn-sm bgm-bluegray" v-on:click="itemDelete(key)" disabled="disabled"><i class="md md-close"></i></button></td>
                </tr>
                <tr>
                    <td colspan="7"></td>
                    <td><strong>{{ trans('messages.TOTAL') }}</strong></td>
                    <td><strong>&euro;@{{ subtotal }}</strong></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>











    <div class="modal fade" id="customerSearch" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('messages.Choose Customer') }}</h4>
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
                                            <input type="text" class="search-field form-control" placeholder="{{ trans('messages.Search Customer') }}" v-model="customerFilter">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>{{ trans('messages.Surname') }}</th>
                                    <th>{{ trans('messages.Name') }}</th>
                                    <th>{{ trans('messages.VAT ID') }}</th>
                                    <th>{{ trans('messages.Customer Group') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(customerKey, customerFound) in customers | filterBy customerFilter" class="clickableRow">
                                    <td v-on:click.prevent="selectCustomer(customerFound)">@{{ customerFound.surname }}</td>
                                    <td v-on:click.prevent="selectCustomer(customerFound)">@{{ customerFound.name }}</td>
                                    <td v-on:click.prevent="selectCustomer(customerFound)">@{{ customerFound.vatid }}</td>
                                    <td v-on:click.prevent="selectCustomer(customerFound)">@{{ customerFound.customer_group }}</td>
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







    {{--<div class="modal fade" id="productSearch" tabindex="-1" role="dialog" aria-hidden="true">--}}
        {{--<div class="modal-dialog">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<h4 class="modal-title">{{ trans('messages.Add Product') }}</h4>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<div class="card">--}}
                        {{--<div class="card-header">--}}
                        {{--</div>--}}

                        {{--<div id="data-table-basic-header" class="bootgrid-header container-fluid">--}}
                            {{--<div class="row">--}}
                                {{--<div class="col-sm-12 actionBar">--}}
                                    {{--<div class="search form-group">--}}
                                        {{--<div class="input-group">--}}
                                            {{--<span class="md icon input-group-addon md-search"></span>--}}
                                            {{--<input type="text" class="search-field form-control" placeholder="{{ trans('messages.Search') }}" v-model="addProductSearchFilter">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="table-responsive">--}}
                            {{--<table class="table table-hover">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th>{{ trans('messages.Manufacturer') }}</th>--}}
                                    {{--<th>{{ trans('messages.Name') }}</th>--}}
                                    {{--<th>{{ trans('messages.SKU') }}</th>--}}
                                    {{--<th>{{ trans('messages.Barcode') }}</th>--}}
                                    {{--<th>{{ trans('messages.Price') }}</th>--}}
                                    {{--<th>{{ trans('messages.Tax Percent') }}</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--<tr v-for="(keyFound, productFound) in searchResults | filterBy addProductSearchFilter" class="clickableRow">--}}
                                    {{--<td v-on:click.prevent="addProductToInvoice(productFound)">@{{ productFound.manufacturer }}</td>--}}
                                    {{--<td v-on:click.prevent="addProductToInvoice(productFound)">@{{ productFound.name }}</td>--}}
                                    {{--<td v-on:click.prevent="addProductToInvoice(productFound)">@{{ productFound.sku }}</td>--}}
                                    {{--<td v-on:click.prevent="addProductToInvoice(productFound)">@{{ productFound.barcode }}</td>--}}
                                    {{--<td v-on:click.prevent="addProductToInvoice(productFound)">&euro;@{{ productFound.priceEach }}</td>--}}
                                    {{--<td v-on:click.prevent="addProductToInvoice(productFound)">@{{ productFound.taxPercent }}%</td>--}}
                                {{--</tr>--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

@stop

@section('page.footer')
    <script>
        Vue.config.debug = true;

        var vue = new Vue({
            el: 'body',
            data: {
                invoice: { products: [], entityType: 'person', customer: {} },
                searchResults: [],
                createbuttonEnabled: true,
                customers: [],
                customerFilter: '',
                due_at: '2016-01-01 00:00:00',
            },
            computed: {
                cdue_at: {
                    get: function getCDueAt() {
                        return moment(this.due_at, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
                    },
                    set: function setCDueAt(val) {
                        this.due_at = moment(val, 'YYYY-MM-DD').format('YYYY-MM-DD HH:mm:ss');
                    }
                },
                cPaid: {
                    get: function getCPaid() {
                        return this.invoice.paid == '1' ? '1' : '0';
                    },
                    set: function setCPaid(val) {
                        this.invoice.paid = val;
                    }
                },
                compose: function() {
                    var that = this;
                    var composed = {
                        customer_id: this.invoice.customer_id,
                        id: this.invoice.id,
                        city: this.invoice.city,
                        country: this.invoice.country,
                        email1: this.invoice.email1,
                        email2: this.invoice.email2,
                        entityType: this.invoice.entityType,
                        mobile: this.invoice.mobile,
                        name: this.invoice.name,
                        notes: this.invoice.notes,
                        phone: this.invoice.phone,
                        state: this.invoice.state,
                        street1: this.invoice.street1,
                        street2: this.invoice.street2,
                        surname: this.invoice.surname,
                        taxit: this.invoice.taxid,
                        vatid: this.invoice.vatid,
                        website: this.invoice.website,
                        zipcode: this.invoice.zipcode,
                        paid: this.invoice.paid == '1' ? '1' : '0',
                        products: [],
                        due_at: moment(this.due_at, 'YYYY-MM-DD').format('YYYY-MM-DD HH:mm:ss'),
                    };
                    this.invoice.products.forEach(function(product, key) {
                        composed.products.push({
                            id: product.id,
                            priceEach: ((product.taxedPriceEach)/((parseFloat(product.taxPercent)+100)/100)).toFixed(4),
                            quantity: product.quantity
                        });
                    });
                    return composed;
                },
                subtotal: function() {
                    var subtotal = 0.00;
                    this.invoice.products.forEach(function(product, key) {
                        subtotal += parseFloat(parseFloat(product.priceEach) * parseFloat(product.quantity) * (100 + parseFloat(product.taxPercent)) / 100);
                    });
                    return subtotal.toFixed(4);
                }
            },
            methods: {
                setPaid: function setPaid(val) {
                    this.cPaid = val;
                    Vue.http.put('/api/v3/invoices/{{ $invoice->id ?? '' }}', this.compose).then(function success(response) {
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                getReceipt: function getReceipt() {
                    location.href="/api/v3/invoices/{{ $invoice->id ?? '' }}/generatePDF";
                },
                goToOrder: function goToOrder() {
                    location.href = '/admin/orders/' + this.invoice.order_id + '/edit';
                },
                itemDelete(item) {
                    this.invoice.products.splice(item, 1);
                },
                update: function(event) {
                    Vue.http.put('/api/v3/invoices/{{ $invoice->id ?? '' }}', this.compose).then(function success(response) {
                        response.data.products.forEach(function(product) {
                            product.priceEach = product.details.priceEach;
                            product.taxPercent = product.details.taxPercent;
                            product.quantity = product.details.quantity;
                            product.taxedPriceEach = product.details.taxedPriceEach;
                            product.taxedPriceTotal = product.details.taxedPriceTotal;
                            
                        });
                        this.$set('invoice', response.data);
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
                            location.href = '/admin/invoices';
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                create: function(event) {
                    var that = this;
                    this.createbuttonEnabled = false;
                    Vue.http.post('/api/v3/invoices', this.compose).then(function success(response) {
                        response.data.products.forEach(function(product) {
                            product.priceEach = product.details.priceEach;
                            product.taxPercent = product.details.taxPercent;
                            product.quantity = product.details.quantity;
                            product.taxedPriceEach = product.details.taxedPriceEach;
                            product.taxedPriceTotal = product.details.taxedPriceTotal;
                        });
                        this.$set('invoice', response.data);
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
                            location.href = '/admin/invoices/' + that.invoice.id + '/edit';
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
                            Vue.http.delete('/api/v3/invoices/' + that.invoice.id).then(function success(response) {
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
                                    location.href = '/admin/invoices';
                                });
                            });
                        }
                    }.bind(this));
                },
                addProduct: function addProduct() {
                    $('#productSearch').modal('show');
                },
                customerSearch: function customerSearch() {
                    $('#customerSearch').modal('show');
                },
                addProductToInvoice: function addProductToInvoice(product, key) {
                    var newProduct = Object.assign({}, product, {
                        priceEach: product.priceEach,
                        taxPercent: product.taxPercent,
                        quantity: 1
                    });
                    this.invoice.products.push(newProduct);
                    $('#productSearch').modal('hide');
                },
                selectCustomer: function selectCustomer(customer) {
                    // First use this.$set to notify the changes (is there a another way to do it?)
                    this.$set('invoice.customer_id', customer.id);
                    this.invoice.city = customer.city;
                    this.invoice.country = customer.country;
                    this.invoice.email1 = customer.email1;
                    this.invoice.email2 = customer.email2;
                    this.invoice.entityType = customer.type;
                    this.invoice.mobile = customer.mobile;
                    this.invoice.name = customer.name;
                    this.invoice.phone = customer.phone;
                    this.invoice.state = customer.state;
                    this.invoice.street1 = customer.street1;
                    this.invoice.street2 = customer.street2;
                    this.invoice.surname = customer.surname;
                    this.invoice.taxid = customer.taxid;
                    this.invoice.vatid = customer.vatid;
                    this.invoice.website = customer.website;
                    this.invoice.zipcode = customer.zipcode;
                    $('#customerSearch').modal('hide');
                },
            },
            ready: function ready() {
                @if($invoice)
                    Vue.http.get('/api/v3/invoices/{{ $invoice->id ?? '' }}').then(function success(response) {
                    this.due_at = moment(response.data.due_at, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
                    response.data.products.forEach(function(product) {
                        product.priceEach = product.details.priceEach;
                        product.taxPercent = product.details.taxPercent;
                        product.quantity = product.details.quantity;
                        product.taxedPriceEach = product.details.taxedPriceEach;
                        product.taxedPriceTotal = product.details.taxedPriceTotal;
                    });
                    this.$set('invoice', response.data);
                    this.exists = true;
                }.bind(this), function error(response) {
                    console.log('FAILURE', response);
                });
                @endif
                Vue.http.get('/api/v3/products').then(function success(response) {
                    this.$set('searchResults', response.data);
                }.bind(this), function error(response) {
                    console.log('FAILURE getting products', response);
                });
                Vue.http.get('/api/v3/customers').then(function success(response) {
                    this.$set('customers', response.data);
                }.bind(this), function error(response) {
                    console.log('FAILURE getting customers', response);
                });
            }
        });
        vue.$watch('cPaid', function(val) {
            vue.setPaid(val);
        });
    </script>
@stop