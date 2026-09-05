@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Edit Proforma'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            @if($proforma !== null)
                <div class="btn-group btn-group pull-right">
                    <button class="btn btn-primary bgm-red" v-on:click.prevent="destroy" :disabled="!canBeSaved">{{ trans('messages.DELETE') }}</button>
                    <button class="btn btn-primary bgm-bluegray" v-on:click.prevent="update" :disabled="!canBeSaved">{{ trans('messages.UPDATE') }}</button>
                    @if($proforma)
                        <button class="btn btn-primary bgm-orange pull-right" v-on:click.prevent="getReceipt">{{ trans('messages.PROFORMA PDF') }}</button>
                    @endif
                    @if(!$proforma->order)
                        <button class="btn btn-primary bgm-lightblue pull-right" v-on:click.prevent="convertToOrder">{{ trans('messages.CONVERT TO ORDER') }}</button>
                    @else
                        <button class="btn btn-primary bgm-indigo" v-on:click.prevent="goToOrder">{{ trans('messages.GO TO ORDER') }}</button>
                    @endif
                </div>
            @else
                <button :disabled="!proforma.customer_id" class="btn btn-primary bgm-red pull-right" v-on:click.prevent="create" :disabled="!createbuttonEnabled">{{ trans('messages.CREATE') }}</button>
                <button class="btn btn-primary bgm-purple pull-right" v-on:click.prevent="customerSearch">{{ trans('messages.SEARCH CUSTOMER') }}</button>
            @endif
            <h2>
                {{ trans('messages.Edit Proforma') }}
                <small>
                    {{ trans('messages._edit_proforma_subtitle') }}
                </small>
            </h2>
        </div>

        <form class="card-body card-padding">
            <form role="form">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Name') }}</label>
                            <input type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Enter Name') }}" v-model="proforma.customer.name" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="surname">{{ trans('messages.Surname') }}</label>
                            <input type="text" name="surname" class="form-control input-sm" placeholder="{{ trans('messages.Enter Surname') }}" v-model="proforma.customer.surname" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line" v-if="proforma.customer.type == 'company'">
                            <label for="name">{{ trans('messages.Company Name') }}</label>
                            <input type="text" name="company" class="form-control input-sm" placeholder="{{ trans('messages.Company Name') }}" v-model="proforma.customer.company" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <p class="c-black f-500 m-b-20 select-nomargin">{{ trans('messages.Entity Type') }}</p>
                        <div class="form-group">
                            <div class="select">
                                <div class="form-control">
                                    <select class="form-control" v-model="proforma.entityType" disabled="disabled">
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
                            <input type="text" name="vatid" class="form-control input-sm" placeholder="{{ trans('messages.Enter VAT ID') }}" v-model="proforma.customer.vatid" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email1">{{ trans('messages.Primary E-Mail') }}</label>
                            <input type="text" name="email1" class="form-control input-sm" placeholder="{{ trans('messages.Primary E-Mail') }}" v-model="proforma.customer.email1" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email2">{{ trans('messages.Secondary E-Mail') }}</label>
                            <input type="text" name="email2" class="form-control input-sm" placeholder="{{ trans('messages.Secondary E-Mail') }}" v-model="proforma.customer.email2" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="zipcode">{{ trans('messages.Zipcode') }}</label>
                            <input type="text" name="zipcode" class="form-control input-sm" placeholder="{{ trans('messages.Zipcode') }}" v-model="proforma.customer.zipcode" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street1">{{ trans('messages.Street1') }}</label>
                            <input type="text" name="street1" class="form-control input-sm" placeholder="{{ trans('messages.Street1') }}" v-model="proforma.customer.street1" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street2">{{ trans('messages.Street2') }}</label>
                            <input type="text" name="street2" class="form-control input-sm" placeholder="{{ trans('messages.Street2') }}" v-model="proforma.customer.street2" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="city">{{ trans('messages.City') }}</label>
                            <input type="text" name="city" class="form-control input-sm" placeholder="{{ trans('messages.City') }}" v-model="proforma.customer.city" disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.Phone') }}</label>
                            <input type="text" name="phone" class="form-control input-sm" placeholder="{{ trans('messages.Phone') }}" v-model="proforma.customer.phone" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Mobile') }}</label>
                            <input type="text" name="mobile" class="form-control input-sm" placeholder="{{ trans('messages.Mobile') }}" v-model="proforma.customer.mobile" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.State') }}</label>
                            <input type="text" name="state" class="form-control input-sm" placeholder="{{ trans('messages.State') }}" v-model="proforma.customer.state" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="country">{{ trans('messages.Country') }}</label>
                            <input type="text" name="country" class="form-control input-sm" placeholder="{{ trans('messages.Country') }}" v-model="proforma.customer.country" disabled="disabled">
                        </div>
                    </div>

                </div>
            </form>
        </div>


    <div class="card">
        <div class="card-header">
            <h2>
                {{ trans('messages.Delivery') }}
                <small>
                    {{ trans('messages._edit_proforma_delivery') }}
                </small>
            </h2>
        </div>

        <div class="card-body card-padding">
            <form role="form">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Company Name') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Company Name') }}" v-model="proforma.customer.company" :disabled="!proforma.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Name') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Enter Name') }}" v-model="proforma.customer.name" :disabled="!proforma.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="surname">{{ trans('messages.Surname') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="surname" class="form-control input-sm" placeholder="{{ trans('messages.Enter Surname') }}" v-model="proforma.customer.surname" :disabled="!proforma.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="vatid">{{ trans('messages.VAT ID') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="vatid" class="form-control input-sm" placeholder="{{ trans('messages.VAT ID') }}" v-model="proforma.customer.vatid" :disabled="!proforma.customer_id">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email1">{{ trans('messages.Primary E-Mail') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="email1" class="form-control input-sm" placeholder="{{ trans('messages.Primary E-Mail') }}" v-model="proforma.customer.email1" :disabled="!proforma.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="email2">{{ trans('messages.Secondary E-Mail') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="email2" class="form-control input-sm" placeholder="{{ trans('messages.Secondary E-Mail') }}" v-model="proforma.customer.email2" :disabled="!proforma.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="zipcode">{{ trans('messages.Zipcode') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="zipcode" class="form-control input-sm" placeholder="{{ trans('messages.Zipcode') }}" v-model="proforma.customer.zipcode" :disabled="!proforma.customer_id">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street1">{{ trans('messages.Street1') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="street1" class="form-control input-sm" placeholder="{{ trans('messages.Street1') }}" v-model="proforma.customer.street1" :disabled="!proforma.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street2">{{ trans('messages.Street2') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="street2" class="form-control input-sm" placeholder="{{ trans('messages.Street2') }}" v-model="proforma.customer.street2" :disabled="!proforma.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="city">{{ trans('messages.City') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="city" class="form-control input-sm" placeholder="{{ trans('messages.City') }}" v-model="proforma.customer.city" :disabled="!proforma.customer_id">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.Phone') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="phone" class="form-control input-sm" placeholder="{{ trans('messages.Phone') }}" v-model="proforma.customer.phone" :disabled="!proforma.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Mobile') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="mobile" class="form-control input-sm" placeholder="{{ trans('messages.Mobile') }}" v-model="proforma.customer.mobile" :disabled="!proforma.customer_id">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.State') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="state" class="form-control input-sm" placeholder="{{ trans('messages.State') }}" v-model="proforma.customer.state" :disabled="!proforma.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="country">{{ trans('messages.Country') }}</label>
                            <input :disabled="!canBeSaved" type="text" name="country" class="form-control input-sm" placeholder="{{ trans('messages.Country') }}" v-model="proforma.customer.country" :disabled="!proforma.customer_id">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <textarea :disabled="!canBeSaved" class="form-control" name="notes" placeholder="{{ trans('messages.Notes...') }}" data-autosize-on="true" style="overflow: hidden; word-wrap: break-word; height: 43.8px;" v-model="proforma.notes" :disabled="!proforma.customer_id"></textarea>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <button v-if="canBeSaved" class="btn btn-primary bgm-red pull-right" v-on:click.prevent="addProduct" :disabled="!proforma.customer_id">{{ trans('messages.ADD PRODUCT') }}</button>
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
                    <tr v-for="(key, item) in proforma.products" style="width: 100%;">
                        <td>@{{ item.manufacturer }}</td>
                        <td>@{{ item.name }}</td>
                        <td>@{{ item.sku }}</td>
                        <td>@{{ item.barcode }}</td>
                        <td>
                            <div class="form-group fg-line">
                                <input :disabled="!canBeSaved" type="text" name="@{{ item.id }}-quantity" class="form-control" placeholder="{{ trans('messages.Quantity') }}" v-model="item.quantity" :disabled="!proforma.customer_id">
                            </div>
                        </td>
                        <td>
                            <div class="form-group fg-line">
                                @{{ item.taxedPriceEach | nontaxed item.taxPercent }}
                            </div>
                        </td>
                        <td>
                            <div class="form-group fg-line">
                                <input :disabled="!canBeSaved" type="text" name="@{{ item.id }}-priceEach" class="form-control" placeholder="{{ trans('messages.Price') }}" v-model="item.taxedPriceEach" :disabled="!proforma.customer_id">
                            </div>
                        </td>
                        <td>
                            <div class="form-group fg-line">
                                <span>@{{ item.taxPercent }}</span>
                            </div>
                        </td>
                        <td>&euro;@{{ item.quantity | subtotal item.taxedPriceEach }}</td>
                        <td><button :disabled="!canBeSaved" class="btn btn-sm bgm-bluegray" v-on:click="itemDelete(key)" :disabled="!proforma.customer_id"><i class="md md-close"></i></button></td>
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
                                        <td v-on:click.prevent="addProductToProforma(productFound)">@{{ productFound.manufacturer }}</td>
                                        <td v-on:click.prevent="addProductToProforma(productFound)">@{{ productFound.name }}</td>
                                        <td v-on:click.prevent="addProductToProforma(productFound)">@{{ productFound.sku }}</td>
                                        <td v-on:click.prevent="addProductToProforma(productFound)">@{{ productFound.barcode }}</td>
                                        <td v-on:click.prevent="addProductToProforma(productFound)">&euro;@{{ productFound.priceEach }}</td>
                                        <td v-on:click.prevent="addProductToProforma(productFound)">@{{ productFound.taxPercent }}%</td>
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
                proforma: { products: [], entityType: 'person', customer: {} },
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
                        customer_id: this.proforma.customer_id,
                        id: this.proforma.id,
                        city: this.proforma.city,
                        country: this.proforma.country,
                        email1: this.proforma.email1,
                        email2: this.proforma.email2,
                        entityType: this.proforma.entityType,
                        mobile: this.proforma.mobile,
                        name: this.proforma.name,
                        notes: this.proforma.notes,
                        phone: this.proforma.phone,
                        state: this.proforma.state,
                        street1: this.proforma.street1,
                        street2: this.proforma.street2,
                        surname: this.proforma.surname,
                        taxit: this.proforma.taxid,
                        vatid: this.proforma.vatid,
                        website: this.proforma.website,
                        zipcode: this.proforma.zipcode,
                        products: [],
                    };
                    this.proforma.products.forEach(function(product, key) {
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
                    this.proforma.products.forEach(function(product, key) {
                        subtotal += parseFloat(parseFloat(product.taxedPriceEach) * parseFloat(product.quantity) );
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
                    Vue.http.get('/api/v3/search/products/' + this.addProductSearchFilter, { customer_id: this.proforma.customer_id, limit: this.limit, start: ((this.start -1) * this.limit) }).then(function success(response) {
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
                getReceipt: function getReceipt() {
                    if (this.canBeSaved) {
                        Vue.http.put('/api/v3/proformas/{{ $proforma->id ?? '' }}', this.compose).then(function success(response) {
                            response.data.products.forEach(function(product) {
                                product.priceEach = product.details.priceEach;
                                product.taxPercent = product.details.taxPercent;
                                product.quantity = product.details.quantity;
                            });
                            this.$set('proforma', response.data);
                            location.href="/api/v3/proformas/{{ $proforma->id ?? '' }}/generatePDF";
                        }.bind(this), function error(response) {
                            @include('partials.admin.swalDataSavedFail')
                        });
                    } else {
                        location.href="/api/v3/proformas/{{ $proforma->id ?? '' }}/generatePDF";
                    }
                },
                goToOrder: function goToOrder() {
                    if (this.canBeSaved) {
                        Vue.http.put('/api/v3/proformas/{{ $proforma->id ?? '' }}', this.compose).then(function success(response) {
                            this.$set('proforma', response.data);
                        }.bind(this), function error(response) {
                            @include('partials.admin.swalDataSavedFail')
                        });
                    } else {
                        location.href = '/admin/orders/' + this.proforma.order.id + '/edit';
                    }
                },
                convertToOrder: function convertToOrder() {
                    Vue.http.put('/api/v3/proformas/{{ $proforma->id ?? '' }}', this.compose).then(function success(response) {
                        this.$set('proforma', response.data);
                        Vue.http.get('/api/v3/proformas/{{ $proforma->id ?? '' }}/convertToOrder').then(function success(response) {
                            location.href = '/admin/orders/' + response.data.id + '/edit';
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                itemDelete(item) {
                   this.proforma.products.splice(item, 1);
                },
                update: function(event) {
                    Vue.http.put('/api/v3/proformas/{{ $proforma->id ?? '' }}', this.compose).then(function success(response) {
                        response.data.products.forEach(function(product) {
                            product.priceEach = product.details.priceEach;
                            product.taxPercent = product.details.taxPercent;
                            product.quantity = product.details.quantity;
                        });
                        this.$set('proforma', response.data);
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
                            location.href = '/admin/proformas';
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                create: function(event) {
                    var that = this;
                    this.createbuttonEnabled = false;
                    Vue.http.post('/api/v3/proformas', this.compose).then(function success(response) {
                        response.data.products.forEach(function(product) {
                            product.priceEach = product.details.priceEach;
                            product.taxPercent = product.details.taxPercent;
                            product.quantity = product.details.quantity;
                            product.taxedPriceEach = product.details.taxedPriceEach;
                        });
                        this.$set('proforma', response.data);
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
                            location.href = '/admin/proformas/' + response.data.id + '/edit';
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
                            Vue.http.delete('/api/v3/proformas/' + that.proforma.id).then(function success(response) {
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
                                    location.href = '/admin/proformas';
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
                addProductToProforma: function addProductToProforma(product, key) {
                    var newProduct = Object.assign({}, product, {
                        priceEach: product.priceEach,
                        taxPercent: product.taxPercent,
                        taxedPriceEach: product.taxedPrice,
                        quantity: 1
                    });

                    this.proforma.products.push(newProduct);
                    $('#productSearch').modal('hide');
                },
                selectCustomer: function selectCustomer(customer) {
                    // First use this.$set to notify the changes (is there a another way to do it?)
                    this.$set('proforma.customer_id', customer.id);
                    this.proforma.products = [];
                    this.proforma.customer = {};
                    this.proforma.customer.company = customer.company;
                    this.proforma.customer.name = customer.name;
                    this.proforma.customer.surname = customer.surname;
                    this.proforma.customer.city = customer.city;
                    this.proforma.customer.vatid = customer.vatid;
                    this.proforma.customer.country = customer.country;
                    this.proforma.customer.email1 = customer.email1;
                    this.proforma.customer.email2 = customer.email2;
                    this.proforma.customer.entityType = customer.type;
                    this.proforma.customer.mobile = customer.mobile;
                    this.proforma.customer.name = customer.name;
                    this.proforma.customer.phone = customer.phone;
                    this.proforma.customer.state = customer.state;
                    this.proforma.customer.street1 = customer.street1;
                    this.proforma.customer.street2 = customer.street2;
                    this.proforma.customer.surname = customer.surname;
                    this.proforma.customer.taxid = customer.taxid;
                    this.proforma.customer.vatid = customer.vatid;
                    this.proforma.customer.website = customer.website;
                    this.proforma.customer.zipcode = customer.zipcode;

                    this.proforma.name = customer.name;
                    this.proforma.surname = customer.surname;
                    this.proforma.company = customer.company;
                    this.proforma.city = customer.city;
                    this.proforma.country = customer.country;
                    this.proforma.email1 = customer.email1;
                    this.proforma.email2 = customer.email2;
                    this.proforma.entityType = customer.type;
                    this.proforma.mobile = customer.mobile;
                    this.proforma.name = customer.name;
                    this.proforma.phone = customer.phone;
                    this.proforma.state = customer.state;
                    this.proforma.street1 = customer.street1;
                    this.proforma.street2 = customer.street2;
                    this.proforma.surname = customer.surname;
                    this.proforma.taxid = customer.taxid;
                    this.proforma.vatid = customer.vatid;
                    this.proforma.website = customer.website;
                    this.proforma.zipcode = customer.zipcode;
                    $('#customerSearch').modal('hide');
                },
            },
            ready: function ready() {
                @if($proforma)
                    Vue.http.get('/api/v3/proformas/{{ $proforma->id ?? '' }}').then(function success(response) {
                        if (response.data.order) this.canBeSaved = false;
                        response.data.products.forEach(function(product) {
                            product.priceEach = product.details.priceEach;
                            product.taxPercent = product.details.taxPercent;
                            product.quantity = product.details.quantity;
                            product.taxedPriceEach = product.details.taxedPriceEach;
                        });
                        this.$set('proforma', response.data);
                        this.exists = true;
                    }.bind(this), function error(response) {
                        console.log('FAILURE', response);
                    });
                @endif
                Vue.http.get('/api/v3/customers').then(function success(response) {
                    this.$set('customers', response.data);
                }.bind(this), function error(response) {
                    console.log('FAILURE getting customers', response);
                });
            }
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