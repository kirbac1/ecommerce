@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Edit Order'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            @if($order !== null)
                <div class="btn-group btn-group pull-right">
                    @if($order->proforma)
                        <button class="btn btn-primary bgm-indigo" v-on:click.prevent="goToProforma" :disabled="!order.proforma">{{ trans('messages.GO TO PROFORMA') }}</button>
                    @endif
                    <button class="btn btn-primary bgm-red" v-on:click.prevent="destroy" :disabled="!canBeSaved">{{ trans('messages.DELETE') }}</button>
                    <button class="btn btn-primary bgm-bluegray" v-on:click.prevent="update" :disabled="!canBeSaved">{{ trans('messages.UPDATE') }}</button>
                    @if($order->invoice)
                        <button class="btn btn-primary bgm-indigo" v-on:click.prevent="goToInvoice" :disabled="!order.invoice">{{ trans('messages.GO TO INVOICE') }}</button>
                        <button :disabled="!order.id" class="btn btn-primary bgm-black pull-right" v-on:click.prevent="goToReturns">{{ trans('messages.RETURNS') }} (<span>@{{ order.returns.length }}</span>)</button>
                    @elseif($order->receipt)
                        <button class="btn btn-primary bgm-indigo" v-on:click.prevent="goToReceipt" :disabled="!order.receipt">{{ trans('messages.GO TO RECEIPT') }}</button>
                        <button :disabled="!order.id" class="btn btn-primary bgm-black pull-right" v-on:click.prevent="goToReturns">{{ trans('messages.RETURNS') }} (<span>@{{ order.returns.length }}</span>)</button>
                    @else
                        <button :disabled="!canBeSaved" class="btn btn-primary bgm-lightblue pull-right" v-on:click.prevent="convertToInvoice">{{ trans('messages.CONVERT TO INVOICE') }}</button>
                        <button :disabled="!canBeSaved" class="btn btn-primary bgm-lightblue pull-right" v-on:click.prevent="convertToReceipt">{{ trans('messages.CONVERT TO RECEIPT') }}</button>
                    @endif
                </div>
            @else
                <button class="btn btn-primary bgm-red pull-right" v-on:click.prevent="create" :disabled="createbuttonEnabled">{{ trans('messages.CREATE') }}</button>
                <button class="btn btn-primary bgm-purple pull-right" v-on:click.prevent="customerSearch">{{ trans('messages.SEARCH CUSTOMER') }}</button>
            @endif
            <h2>
                {{ trans('messages.Edit Order') }}
                <small>
                    {{ trans('messages._edit_order_subtitle') }}
                </small>
            </h2>
        </div>

        <div class="card-body card-padding">
            <form role="form">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Name') }}</label>
                            <input type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Enter Name') }}" v-model="order.name" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="surname">{{ trans('messages.Surname') }}</label>
                            <input type="text" name="surname" class="form-control input-sm" placeholder="{{ trans('messages.Enter Surname') }}" v-model="order.surname" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line" v-if="order.entityType == 'company'">
                            <label for="name">{{ trans('messages.Company Name') }}</label>
                            <input type="text" name="company" class="form-control input-sm" placeholder="{{ trans('messages.Company Name') }}" v-model="order.customer.company" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <p class="c-black f-500 m-b-20 select-nomargin">{{ trans('messages.Entity Type') }}</p>
                        <div class="form-group">
                            <div class="select">
                                <div class="form-control">
                                    <select class="form-control" v-model="order.customer.type" disabled="disabled">
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
                            <input type="text" name="vatid" class="form-control input-sm" placeholder="{{ trans('messages.Enter VAT ID') }}" v-model="order.customer.vatid" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email1">{{ trans('messages.Primary E-Mail') }}</label>
                            <input type="text" name="email1" class="form-control input-sm" placeholder="{{ trans('messages.Primary E-Mail') }}" v-model="order.email1" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email2">{{ trans('messages.Secondary E-Mail') }}</label>
                            <input type="text" name="email2" class="form-control input-sm" placeholder="{{ trans('messages.Secondary E-Mail') }}" v-model="order.email2" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="zipcode">{{ trans('messages.Zipcode') }}</label>
                            <input type="text" name="zipcode" class="form-control input-sm" placeholder="{{ trans('messages.Zipcode') }}" v-model="order.zipcode" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street1">{{ trans('messages.Street1') }}</label>
                            <input type="text" name="street1" class="form-control input-sm" placeholder="{{ trans('messages.Street1') }}" v-model="order.street1" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street2">{{ trans('messages.Street2') }}</label>
                            <input type="text" name="street2" class="form-control input-sm" placeholder="{{ trans('messages.Street2') }}" v-model="order.street2" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="city">{{ trans('messages.City') }}</label>
                            <input type="text" name="city" class="form-control input-sm" placeholder="{{ trans('messages.City') }}" v-model="order.city" disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.Phone') }}</label>
                            <input type="text" name="phone" class="form-control input-sm" placeholder="{{ trans('messages.Phone') }}" v-model="order.phone" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Mobile') }}</label>
                            <input type="text" name="mobile" class="form-control input-sm" placeholder="{{ trans('messages.Mobile') }}" v-model="order.mobile" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.State') }}</label>
                            <input type="text" name="state" class="form-control input-sm" placeholder="{{ trans('messages.State') }}" v-model="order.state" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="country">{{ trans('messages.Country') }}</label>
                            <input type="text" name="country" class="form-control input-sm" placeholder="{{ trans('messages.Country') }}" v-model="order.country" disabled="disabled">
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
                    {{ trans('messages._edit_order_delivery') }}
                </small>
            </h2>
        </div>

        <div class="card-body card-padding">
            <form role="form">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Name') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Enter Name') }}" v-model="order.customer.name" :disabled="!order.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group fg-line">
                            <label for="surname">{{ trans('messages.Surname') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="surname" class="form-control input-sm" placeholder="{{ trans('messages.Enter Surname') }}" v-model="order.customer.surname" :disabled="!order.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group fg-line">
                            <label for="company">{{ trans('messages.Company Name (optional)') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="company" class="form-control input-sm" placeholder="{{ trans('messages.Company Name (optional)') }}" v-model="order.customer.company" :disabled="!order.customer_id">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email1">{{ trans('messages.Primary E-Mail') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="email1" class="form-control input-sm" placeholder="{{ trans('messages.Primary E-Mail') }}" v-model="order.customer.email1" :disabled="!order.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email2">{{ trans('messages.Secondary E-Mail') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="email2" class="form-control input-sm" placeholder="{{ trans('messages.Secondary E-Mail') }}" v-model="order.customer.email2" :disabled="!order.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="zipcode">{{ trans('messages.Zipcode') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="zipcode" class="form-control input-sm" placeholder="{{ trans('messages.Zipcode') }}" v-model="order.customer.zipcode" :disabled="!order.customer_id">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street1">{{ trans('messages.Street1') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="street1" class="form-control input-sm" placeholder="{{ trans('messages.Street1') }}" v-model="order.customer.street1" :disabled="!order.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street2">{{ trans('messages.Street2') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="street2" class="form-control input-sm" placeholder="{{ trans('messages.Street2') }}" v-model="order.customer.street2" :disabled="!order.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="city">{{ trans('messages.City') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="city" class="form-control input-sm" placeholder="{{ trans('messages.City') }}" v-model="order.customer.city" :disabled="!order.customer_id">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.Phone') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="phone" class="form-control input-sm" placeholder="{{ trans('messages.Phone') }}" v-model="order.customer.phone" :disabled="!order.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Mobile') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="mobile" class="form-control input-sm" placeholder="{{ trans('messages.Mobile') }}" v-model="order.customer.mobile" :disabled="!order.customer_id">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.State') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="state" class="form-control input-sm" placeholder="{{ trans('messages.State') }}" v-model="order.customer.state" :disabled="!order.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="country">{{ trans('messages.Country') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="country" class="form-control input-sm" placeholder="{{ trans('messages.Country') }}" v-model="order.customer.country" :disabled="!order.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <textarea :disabled="!canBeSaved" class="form-control" name="notes" placeholder="{{ trans('messages.Notes...') }}" data-autosize-on="true" style="overflow: hidden; word-wrap: break-word; height: 43.8px;" v-model="order.notes" :disabled="!order.customer_id"></textarea>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <button v-if="canBeSaved" class="btn btn-primary bgm-red pull-right" v-on:click.prevent="addProduct" :disabled="!order.customer_id">{{ trans('messages.ADD PRODUCT') }}</button>
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
                <tr v-for="(key, item) in order.products" style="width: 100%;">
                    <td>@{{ item.manufacturer }}</td>
                    <td>@{{ item.name }}</td>
                    <td>@{{ item.sku }}</td>
                    <td>@{{ item.barcode }}</td>
                    <td>
                        <div class="form-group fg-line">
                            <input :disabled="!canBeSaved" type="text" name="@{{ item.id }}-quantity" class="form-control" placeholder="{{ trans('messages.Quantity') }}" v-model="item.quantity" :disabled="!order.customer_id">
                        </div>
                    </td>
                     <td>
                            <div class="form-group fg-line">
                                @{{ item.taxedPriceEach | nontaxed item.taxPercent }}
                            </div>
                    </td>
                    <td>
                        <div class="form-group fg-line">
                            <input :disabled="!canBeSaved" type="text" name="@{{ item.id }}-priceEach" class="form-control" placeholder="{{ trans('messages.Nontaxed') }}" v-model="item.taxedPriceEach" :disabled="!order.customer_id">
                        </div>
                    </td>
                    <td>
                        <div class="form-group fg-line">
                            <span>@{{ item.taxPercent }}</span>
                        </div>
                    </td>
                         <td>&euro;@{{ item.quantity | subtotal item.taxedPriceEach }}</td>
                    <td><button v-if="canBeSaved" class="btn btn-sm bgm-bluegray" v-on:click="itemDelete(key)" :disabled="!order.customer_id"><i class="md md-close"></i></button></td>
                </tr>
                <tr>
                    <td colspan="7"></td>
                    <td><strong>{{ trans('messages.TOTAL') }}</strong></td>
                    <td><strong>&euro;@{{ total }}</strong></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" v-for="(returnkey, return) in order.returns">
        <div class="card-header">
            <h2>
                {{ trans('messages.Return') }} #@{{ return.rma }}
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
                        <th>{{ trans('messages.Price') }}</th>
                        <th>{{ trans('messages.Tax Percent') }}</th>
                        <th>{{ trans('messages.Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(id, item) in return.products" style="width: 100%;">
                        <td>@{{ item.manufacturer }}</td>
                        <td>@{{ item.name }}</td>
                        <td>@{{ item.sku }}</td>
                        <td>@{{ item.barcode }}</td>
                        <td>
                            <div class="form-group fg-line">
                                @{{ item.details.quantity }}
                            </div>
                        </td>
                        <td>
                            <div class="form-group fg-line">
                                @{{ item.details.priceEach }}
                            </div>
                        </td>
                        <td>
                            <div class="form-group fg-line">
                                <span>@{{ item.details.taxPercent }}</span>
                            </div>
                        </td>
                        <td>&euro;@{{ (item.details.quantity * item.details.priceEach * ((100 + parseFloat(item.details.taxPercent)) / 100)).toFixed(4) }}</td>
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
                                    <th>{{ trans('messages.Company') }}</th>
                                    <th>{{ trans('messages.Surname') }}</th>
                                    <th>{{ trans('messages.Name') }}</th>
                                    <th>{{ trans('messages.VAT ID') }}</th>
                                    <th>{{ trans('messages.Customer Group') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(customerKey, customerFound) in customers | filterBy customerFilter" class="clickableRow">
                                    <td v-on:click.prevent="selectCustomer(customerFound)">@{{ customerFound.company }}</td>
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







    <div class="modal fade" id="productSearch" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('messages.Add Product') }}</h4>
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
                                            <span class="md icon input-group-addon md-search"></span>
                                            <input type="text" class="search-field form-control" placeholder="{{ trans('messages.Search') }}" v-model="addProductSearchFilter" debounce="300">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>{{ trans('messages.Manufacturer') }}</th>
                                    <th>{{ trans('messages.Name') }}</th>
                                    <th>{{ trans('messages.SKU') }}</th>
                                    <th>{{ trans('messages.Barcode') }}</th>
                                    <th>{{ trans('messages.Price') }}</th>
                                    <th>{{ trans('messages.Tax Percent') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(keyFound, productFound) in productSearchResults | filterBy addProductSearchFilter" class="clickableRow">
                                    <td v-on:click.prevent="addProductToOrder(productFound)">@{{ productFound.manufacturer }}</td>
                                    <td v-on:click.prevent="addProductToOrder(productFound)">@{{ productFound.name }}</td>
                                    <td v-on:click.prevent="addProductToOrder(productFound)">@{{ productFound.sku }}</td>
                                    <td v-on:click.prevent="addProductToOrder(productFound)">@{{ productFound.barcode }}</td>
                                    <td v-on:click.prevent="addProductToOrder(productFound)">&euro;@{{ productFound.priceEach }}</td>
                                    <td v-on:click.prevent="addProductToOrder(productFound)">@{{ productFound.taxPercent }}%</td>
                                </tr>
                                </tbody>
                            </table>
                            <!-- Pagination -->
                            <div id="data-table-basic-footer" class="bootgrid-footer container-fluid">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <ul class="pagination">
                                            <li class="prev"><a href="#" class="button" @click.prevent="firstPage"><i class="md md-keyboard-backspace"></i></a></li>
                                            <li class="prev"><a href="#" class="button" @click.prevent="prevPage"><i class="md md-chevron-left"></i></a></li>
                                            <li class="page-1" v-for="(index, button) in paginationButtons()" v-bind:class="{ 'active': button == start }">
                                                <a href="#" class="button" @click.prevent="goToPage(button)">@{{ button }}</a>
                                            </li>
                                            <li class="next"><a href="#" class="button" @click.prevent="nextPage"><i class="md md-chevron-right"></i></a></li>
                                            <li class="next"><a href="#" class="button" @click.prevent="lastPage"><i class="md md-keyboard-backspace" style="transform: rotate(180deg)"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-6 infoBar">
                                        <div class="infos">{{ trans('pagination.footer') }}</div>
                                    </div>
                                </div>
                            </div>
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
                order: { products: [], entityType: 'person', customer: {}, returns: []},
                productSearchResults: [],
                createbuttonEnabled: true,
                customers: [],
                customerFilter: '',
                addProductSearchFilter: '',
                start: 1,
                limit: 20,
                total: 0,
                canBeSaved: true,
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
                compose: function() {
                    var that = this;
                    var composed = {
                        customer_id: this.order.customer_id,
                        id: this.order.id,
                        city: this.order.city,
                        country: this.order.country,
                        email1: this.order.email1,
                        email2: this.order.email2,
                        entityType: this.order.entityType,
                        mobile: this.order.mobile,
                        name: this.order.name,
                        notes: this.order.notes,
                        phone: this.order.phone,
                        state: this.order.state,
                        street1: this.order.street1,
                        street2: this.order.street2,
                        surname: this.order.surname,
                        taxit: this.order.taxid,
                        vatid: this.order.vatid,
                        website: this.order.website,
                        zipcode: this.order.zipcode,
                        products: []
                    };
                    this.order.products.forEach(function(product, key) {
                        composed.products.push({
                            id: product.id,
                            priceEach: ((product.taxedPriceEach)/((parseFloat(product.taxPercent)+100)/100)).toFixed(4),
                            quantity: product.quantity
                        });
                    });
                    return composed;
                },
                total: function() {
                    var subtotal = 0.00;
                    this.order.products.forEach(function(product, key) {
                        subtotal += parseFloat(parseFloat(product.taxedPriceEach) * parseFloat(product.quantity));
                    });
                    return subtotal.toFixed(4);
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
                    Vue.http.get('/api/v3/search/products/' + this.addProductSearchFilter, { customer_id: this.order.customer_id, limit: this.limit, start: ((this.start -1) * this.limit) }).then(function success(response) {
                        response.data.result.forEach(function(element) {
                            element.selected = false;
                        });
                        this.$set('productSearchResults', response.data.result);
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
                addReturn: function addReturn() {

                },
                goToReturns: function goToReturns() {
                    location.href = '/admin/returns?order_id=' + this.order.id;
                },
                goToProforma: function goToProforma() {
                    if (this.canBeSaved) {
                        Vue.http.put('/api/v3/orders/{{ $order->id or '' }}', this.compose).then(function success(response) {
                            this.$set('order', response.data);
                            location.href = '/admin/proformas/' + this.order.proforma_id + '/edit';
                        }.bind(this), function error(response) {
                            @include('partials.admin.swalDataSavedFail')
                        });
                    } else {
                        location.href = '/admin/proformas/' + this.order.proforma_id + '/edit';
                    }
                },
                goToInvoice: function goToInvoice() {
                    location.href = '/admin/invoices/' + this.order.invoice.id + '/edit';
                },
                goToReceipt: function goToReceipt() {
                    location.href = '/admin/receipts/' + this.order.receipt.id + '/edit';
                },
                convertToInvoice: function convertToInvoice() {
                    Vue.http.put('/api/v3/orders/{{ $order->id or '' }}', this.compose).then(function success(response) {
                        this.$set('order', response.data);
                        Vue.http.get('/api/v3/orders/{{ $order->id or '' }}/convertToInvoice').then(function success(response) {
                            location.href = '/admin/invoices/' + response.data.id + '/edit';
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                convertToReceipt: function convertToReceipt() {
                    Vue.http.put('/api/v3/orders/{{ $order->id or '' }}', this.compose).then(function success(response) {
                        this.$set('order', response.data);
                        Vue.http.get('/api/v3/orders/{{ $order->id or '' }}/convertToReceipt').then(function success(response) {
                            location.href = '/admin/receipts/' + response.data.id + '/edit';
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                itemDelete(item) {
                    this.order.products.splice(item, 1);
                },
                update: function(event) {
                    Vue.http.put('/api/v3/orders/{{ $order->id or '' }}', this.compose).then(function success(response) {
                        response.data.products.forEach(function(product) {
                            product.priceEach = product.details.priceEach;
                            product.taxPercent = product.details.taxPercent;
                            product.quantity = product.details.quantity;
                            product.taxedPriceEach = product.details.taxedPriceEach;
                        });
                        this.$set('order', response.data);
                        console.log('saved');
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
                            location.href = '/admin/orders';
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                create: function(event) {
                    var that = this;
                    this.createbuttonEnabled = true;
                    Vue.http.post('/api/v3/orders', this.compose).then(function success(response) {
                        response.data.products.forEach(function(product) {
                            product.priceEach = product.details.priceEach;
                            product.taxPercent = product.details.taxPercent;
                            product.quantity = product.details.quantity;
                            product.taxedPriceEach = product.details.taxedPriceEach;
                        });
                        this.$set('order', response.data);
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
                            location.href = '/admin/orders/' + response.data.id + '/edit';
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
                            Vue.http.delete('/api/v3/orders/' + that.order.id).then(function success(response) {
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
                                    location.href = '/admin/orders';
                                });
                            });
                        }
                    }.bind(this));
                },
                addProduct: function addProduct() {
                    this.start = 1;
                    this.goToPage(1);
                    $('#productSearch').modal('show');
                },
                customerSearch: function customerSearch() {
                    $('#customerSearch').modal('show');
                },
                addProductToOrder: function addProductToOrder(product, key) {
                    this.createbuttonEnabled = false;
                    var newProduct = Object.assign({}, product, {
                        priceEach: product.priceEach,
                        taxPercent: product.taxPercent,
                        taxedPriceEach: product.taxedPrice,
                        quantity: 1
                    });
                    this.order.products.push(newProduct);
                    $('#productSearch').modal('hide');
                },
                selectCustomer: function selectCustomer(customer) {
                    // First use this.$set to notify the changes (is there a another way to do it?)
                    this.$set('order.customer_id', customer.id);
                    this.order.products = [];
                    this.order.customer = {};
                    this.order.customer.company = customer.company;
                    this.order.customer.name = customer.name;
                    this.order.customer.surname = customer.surname;
                    this.order.customer.city = customer.city;
                    this.order.customer.vatid = customer.vatid;
                    this.order.customer.country = customer.country;
                    this.order.customer.email1 = customer.email1;
                    this.order.customer.email2 = customer.email2;
                    this.order.customer.type = customer.type;
                    this.order.customer.mobile = customer.mobile;
                    this.order.customer.name = customer.name;
                    this.order.customer.phone = customer.phone;
                    this.order.customer.state = customer.state;
                    this.order.customer.street1 = customer.street1;
                    this.order.customer.street2 = customer.street2;
                    this.order.customer.surname = customer.surname;
                    this.order.customer.taxid = customer.taxid;
                    this.order.customer.vatid = customer.vatid;
                    this.order.customer.website = customer.website;
                    this.order.customer.zipcode = customer.zipcode;

                    this.order.name = customer.name;
                    this.order.surname = customer.surname;
                    this.order.company = customer.company;
                    this.order.city = customer.city;
                    this.order.country = customer.country;
                    this.order.email1 = customer.email1;
                    this.order.email2 = customer.email2;
                    this.order.entityType = customer.type;
                    this.order.mobile = customer.mobile;
                    this.order.name = customer.name;
                    this.order.phone = customer.phone;
                    this.order.state = customer.state;
                    this.order.street1 = customer.street1;
                    this.order.street2 = customer.street2;
                    this.order.surname = customer.surname;
                    this.order.taxid = customer.taxid;
                    this.order.vatid = customer.vatid;
                    this.order.website = customer.website;
                    this.order.zipcode = customer.zipcode;
                    $('#customerSearch').modal('hide');
                }
            },
            ready: function ready() {
                @if($order)
                    Vue.http.get('/api/v3/orders/{{ $order->id or '' }}').then(function success(response) {
                    if ((response.data.invoice) || (response.data.receipt)) this.canBeSaved = false;
                    response.data.products.forEach(function(product) {
                        product.priceEach = product.details.priceEach;
                        product.taxPercent = product.details.taxPercent;
                        product.quantity = product.details.quantity;
                        product.taxedPriceEach = product.details.taxedPriceEach;
                    });
                    this.$set('order', response.data);
                    this.exists = true;
                }.bind(this), function error(response) {
                    console.log('FAILURE', response);
                });
                @endif
                Vue.http.get('/api/v3/search/customers').then(function success(response) {
                    this.$set('customers', response.data.result);
                }.bind(this), function error(response) {
                    console.log('FAILURE getting customers', response);
                });
            },
        });
        vue.$watch('addProductSearchFilter', function() {
            this.start = 1;
            this.goToPage(1);
        });
        Vue.filter('subtotal', function (qty,price) {
            return (qty*price).toFixed(4);
        })
        Vue.filter('nontaxed', function (val,taxPercent) {
            return (val/( (parseFloat(taxPercent)+100)/100 )).toFixed(4);
        })
    </script>
@stop