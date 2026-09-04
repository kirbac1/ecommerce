@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Edit Product'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            @if($product !== null)
                <div class="btn-group btn-group pull-right">
                    <button class="btn btn-primary bgm-red" v-on:click.prevent="destroy">{{ trans('messages.DELETE') }}</button>
                    <button class="btn btn-primary bgm-bluegray" v-on:click.prevent="update">{{ trans('messages.SAVE') }}</button>
                </div>
            @else
                <button class="btn btn-primary bgm-red pull-right" v-on:click.prevent="create" :disabled="!createbuttonEnabled">{{ trans('messages.CREATE') }}</button>
            @endif
            <h2>
                {{ trans('messages.Edit Product') }}
                <small>
                    {{ trans('messages._edit_product_subtitle') }}
                </small>
            </h2>
        </div>

        <div class="card-body card-padding">
            <div class="row">
                <div class="col-sm-10">
                    <form role="form">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group fg-line">
                                    <label for="name">{{ trans('messages.Name') }}</label>
                                    <input type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Enter Name') }}" v-model="name">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <p class="f-500 m-b-20 select-nomargin">{{ trans('messages.Manufacturer') }}</p>
                                <div class="form-group">
                                    <div class="select">
                                        <div class="form-control">
                                            <select class="form-control" name="manufacturer_id" v-model="manufacturer_id">
                                                <option v-for="(mkey, mval) in manufacturers" value="@{{ mval.id }}">@{{ mval.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group fg-line">
                                    <label for="sku">{{ trans('messages.SKU') }}</label>
                                    <input type="text" name="sku" class="form-control input-sm" placeholder="{{ trans('messages.SKU') }}" v-model="sku">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group fg-line">
                                    <label for="barcode">{{ trans('messages.Barcode') }}</label>
                                    <input type="text" name="barcode" class="form-control input-sm" placeholder="{{ trans('messages.Barcode') }}" v-model="barcode">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <p class="f-500 m-b-20 select-nomargin">{{ trans('messages.Category') }}</p>
                                <div class="form-group">
                                    <div class="select">
                                        <div class="form-control">
                                            <select class="form-control" name="category_id" v-model="category_id" number>
                                                <option v-for="(key, val) in cMakeCategoryTree" value="@{{ val.id }}">@{{ val.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group fg-line">
                                    <label for="qtyPerPack">{{ trans('messages.Quantity Per Pack') }}</label>
                                    <input type="text" name="qtyPerPack" class="form-control input-sm" placeholder="{{ trans('messages.Quantity Per Pack') }}" v-model="cQtyPerPack">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <p class="f-500 m-b-20 select-nomargin">{{ trans('messages.Measure Unit') }}</p>
                                <div class="form-group">
                                    <div class="select">
                                        <div class="form-control">
                                            <select class="form-control" name="measureunit_id" v-model="measureunit_id">
                                                <option v-for="(mukey, muval) in measureunits" value="@{{ muval.id }}">@{{ muval.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <label>{{ trans('messages.Base Price Each') }}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-euro"></i></span>
                                    <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="{{ trans('messages.Base Price Each') }}" v-model="cBasePrice" number>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group fg-line">
                                    <label for="taxPercent">{{ trans('messages.Tax Percent') }}</label>
                                    <input type="text" name="taxPercent" class="form-control input-sm" placeholder="{{ trans('messages.Tax Percent') }}" v-model="cTaxPercent" number>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <label>{{ trans('messages.Untaxed Price Each') }}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-euro"></i></span>
                                    <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="{{ trans('messages.Price Each') }}" v-model="cUntaxedPrice">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <label>{{ trans('messages.Taxed Price Each') }}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-euro"></i></span>
                                    <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="{{ trans('messages.Taxed Price') }}" v-model="cTaxedPrice">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <label>{{ trans('messages.Pack Base Price') }}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-euro"></i></span>
                                    <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="{{ trans('messages.Pack Base Price') }}" v-model="cPackBasePrice" number>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group fg-line">
                                    <label for="name">{{ trans('messages.Discount Percent') }}</label>
                                    <input type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Discount Percent') }}" v-model="discountPercent">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="col-sm-2">
                    <div class="card blog-post noshadow">
                        <div class="bp-header">
                            <form method="post" id="productImageForm" action="/api/v3/imageUpload" enctype="multipart/form-data">
                                <a @click.prevent="changeImage" href="#">
                                    <div class="image-container-filled">
                                        <img v-bind:src="uncachedProductImage" id="productImage">
                                        <div class="preloader pl-xxl" id="change-image-preloader">
                                            <svg class="pl-circular" viewBox="25 25 50 50">
                                                <circle class="plc-path" cx="50" cy="50" r="20"></circle>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                                <input type="file" style="visibility: hidden;" id="productImageFile" name="productImageFile">
                                <a class="bp-title" @click.prevent="changeImage" href="#">
                                    <h2>{{ trans('messages.Change Image') }}</h2>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page.footer')
    <script>
        var vue = new Vue({
            el: 'body',
            data: {
                createbuttonEnabled: true,
                measureunits: [],
                manufacturers: [],
                categories: { children: [] },
                category_id : null,
                priceEach: 1,
                basePrice: 1,
                taxPercent: 14.00,
                packPrice: 1,
                qtyPerPack: 1,
                filename: null,
                manufacturer_id: null,
                measureunit_id: 1,
                name: '',
                sku: '',
                barcode: '',
                image: null,
                signature: null,
                id: {{ $product->id or 'undefined' }},
            },
            computed: {
                cMakeCategoryTree: {
                    cache: false,
                    get: function cMakeCategoryTree() {
                        var cattree = MakeCategoryTree(this.categories);
                        return cattree;
                    }
                },
                cPackBasePrice: {
                    get: function getCPackBasePrice() {
                        taxed = (this.priceEach * (100 + this.taxPercent) / 100).toFixed(4);
                        return (taxed * this.qtyPerPack).toFixed(4);
                    },
                    set: function setCPackBasePrice(val) {
                        this.priceEach = ((val / this.qtyPerPack)/(1+this.taxPercent)).toFixed(4);
                    }
                },
                cTaxPercent: {
                    get: function getCTaxPercent() {
                        return this.taxPercent;
                    },
                    set: function setCTaxPercent(val) {
                        this.taxPercent = parseFloat(val);
                    }
                },
                cBasePrice: {
                    get: function() {
                        return parseFloat(this.basePrice).toFixed(4);
                    },
                    set: function setCBasePrice(val) {
                        this.basePrice = parseFloat(val);
                    }
                },
                cUntaxedPrice: {
                    set: function setCUntaxedPriceEach(val) {
                        this.priceEach = parseFloat(val);
                    },
                    get: function getCUntaxedPriceEach() {
                        return parseFloat(this.priceEach).toFixed(4);
                    }
                },
                cTaxedPrice: {
                    get: function getCTaxedPrice() {
                        return (this.priceEach * (100 + this.taxPercent) / 100).toFixed(4);
                    },
                    set: function setCTaxedPrice(val) {
                        this.priceEach = parseFloat(val) / (100 + this.taxPercent) * 100;
                    }
                },
                cQtyPerPack: {
                    cache: false,
                    get: function() {
                        return this.qtyPerPack.toFixed(0);
                    },
                    set: function CQtyPerPack(val) {
                        this.qtyPerPack = parseFloat(val);
                    }
                },
                uncachedProductImage: {
                    cache: false,
                    get: function uncachedProductImage() {
                        if (this.image) {
                            return '/catalog/' + this.image + '?time=' + Date.now();
                        } else {
                            return '/assets/img/image_not_found.png';
                        }
                    },
                }
            },
            methods: {
                changeImage: function changeImage() {
                    var that = this;
                    $('#productImageFile').click().change(function(e) {
                        var form = $('#productImageForm'); // $('#productImageForm');
                        var data = new FormData(form.get(0));
                        data.append('productImageFile', $('#productImageFile')[0].files[0]);
                        data.append('image', that.image);
                        data.append('product_id', that.id);
                        data.append('signature', that.signature);
                        $('#productImage').css('opacity', 0.2);
                        $('#change-image-preloader').show();
                        $.ajax({
                            url: form.attr('action'),
                            method: form.attr('method'),
                            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function(data) {
                                setTimeout(function() {
                                    that.image = data.path;
                                    that.signature = data.signature;
                                    $('#productImage').attr('src', '/catalog/' + data.path + '?' + Date.now());
                                    $('#productImage').css('opacity', 1);
                                    $('#change-image-preloader').hide();
                                }, 1000);
                            }
                        });
                        $('#productImageFile').unbind();
                    });
                },
                update: function(event) {
                    var that = this;
                    Vue.http.put('/api/v3/products/{{ $product->id or '' }}', {
                        id: this.id,
                        name: this.name,
                        manufacturer_id: this.manufacturer_id,
                        sku: this.sku,
                        barcode: this.barcode,
                        qtyPerPack: this.qtyPerPack,
                        basePrice: this.basePrice,
                        priceEach: this.priceEach,
                        taxPercent: this.taxPercent,
                        signature: this.signature,
                        image: this.image,
                        category_id: this.category_id,
                    }).then(function success(response) {
                        swal({
                            title: "{{ trans('messages.Data saved!') }}",
                            text: "{{ trans('messages.The data you sent was correctly saved!') }}",
                            timer: 3000,
                            showConfirmButton: true,
                            confirmButtonColor: '#2196f3',
                            type: 'success',
                            html: true,
                            closeOnConfirm: false
                        }, function() {
                            location.href = '/admin/products'
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                create: function(event) {
                    var that = this;
                    this.createbuttonEnabled = false;
                    Vue.http.post('/api/v3/products', {
                        name: this.name,
                        manufacturer_id: this.manufacturer_id,
                        measureunit_id: this.measureunit_id,
                        sku: this.sku,
                        barcode: this.barcode,
                        qtyPerPack: this.qtyPerPack,
                        basePrice: this.basePrice,
                        priceEach: this.priceEach,
                        taxPercent: this.taxPercent,
                        image: this.image,
                        signature: this.signature,
                        category_id: this.category_id,
                    }).then(function success(response) {
                        this.id = response.data.id;
                        this.name = response.data.name;
                        this.manufacturer_id = response.data.manufacturer_id;
                        this.barcode = response.data.barcode;
                        this.qtyPerPack = response.data.qtyPerPack;
                        this.basePrice = response.data.basePrice;
                        this.priceEach = response.data.priceEach;
                        this.taxPercent = response.data.taxPercent;
                        this.image = response.data.image;
                        this.signature = response.data.signature;
                        this.category_id = response.data.category_id;
                        swal({
                            title: "{{ trans('messages.Data saved!') }}",
                            text: "{{ trans('messages.The data you sent was correctly saved!') }}",
                            timer: 3000,
                            showConfirmButton: true,
                            confirmButtonColor: '#2196f3',
                            type: 'success',
                            html: true,
                            closeOnConfirm: false
                        }, function() {
                            location.href = '/admin/products/';
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
                            Vue.http.delete('/api/v3/products/' + that.id).then(function success(response) {
                                swal({
                                    title: "{{ trans('messages.Success!') }}",
                                    text: "{{ trans('messages.The element was deleted.') }}",
                                    type: 'success',
                                    html: true,
                                    timer: 3000,
                                    confirmButtonColor: '#2196f3',
                                    closeOnConfirm: false
                                }, function() {
                                    location.href = '/admin/products';
                                });
                            }.bind(this), function error(response) {
                                @include('partials.admin.swalDataSavedFail')
                            });
                        }
                    });
                }
            },
            ready: function ready() {
                var that = this;
                @if($product !== null)
                    Vue.http.get('/api/v3/products/{{ $product->id }}').then(function success(response) {
                        that.basePrice = parseFloat(response.data.basePrice);
                        that.priceEach = parseFloat(response.data.priceEach);
                        that.qtyPerPack = parseFloat(response.data.qtyPerPack);
                        that.packPrice = that.priceEach * that.qtyPerPack;
                        that.name = response.data.name;
                        that.manufacturer_id = parseFloat(response.data.manufacturer_id);
                        that.sku = response.data.sku;
                        that.barcode = response.data.barcode;
                        that.measureunit_id = parseFloat(response.data.measureunit_id);
                        that.id = response.data.id;
                        that.taxPercent = parseFloat(response.data.taxPercent);
                        that.image = response.data.image == '' ? null : response.data.image;
                        that.signature = response.data.signature;
                        that.category_id = response.data.category_id;
                }, function error(response) {
                        console.error('FAILURE retrieving data.');
                    });
                @endif
                Vue.http.get('/api/v3/measureunits').then(function success(response) {
                    that.$set('measureunits', response.data);
                }, function error(response) {
                    console.error('FAILURE retrieving meaure units.');
                });

                Vue.http.get('/api/v3/manufacturers').then(function success(response) {
                    that.$set('manufacturers', response.data);
                }, function error(response) {
                    console.error('FAILURE retrieving meaure units.');
                });

                Vue.http.get('/api/v3/categories').then(function success(response) {
                    that.$set('categories', response.data['1']);
                }, function error(response) {
                    console.error('FAILURE retrieving categories.');
                });
            }
        });

        // Add _token to jQuery
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function MakeCategoryTree(item, depth) {
            var tree = [];
            if (!depth) depth = 0;
            if (item.children.length != 0) {
                if (depth>0) {
                    tree.push({id: item.id, name: '-'.repeat(depth-1) + '  ' + item.name});
                } else {
                    tree.push({id: item.id, name: '-'.repeat(depth) + '  ' + item.name});
                }
                for (var i = 0; i < item.children.length; i++) {
                    res = MakeCategoryTree(item.children[i], depth + 1);
                    res.forEach(function(opt) {
                        tree.push(opt);
                    });
                }
            } else {
                // No children.
                if (depth>0) {
                    tree.push({id: item.id, name: '-'.repeat(depth-1) + '  ' + item.name});
                } else {
                    tree.push({id: item.id, name: '-'.repeat(depth) + '  ' + item.name});
                }
            }
            if (depth == 0) {
                return tree.splice(1);
            } else {
                return tree;
            }
        }
    </script>
@stop
