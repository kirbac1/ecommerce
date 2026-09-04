@extends('layouts.default') @section('content')
<div class="extended-container">
    <div id="container" class="container j-container">
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
        </ul>
        <div class="row">
            <div id="column-left" class="col-sm-3 hidden-xs side-column ">
                <div id="journal-super-filter-54" class="journal-sf" data-filters-action="index.php?route=module/journal2_super_filter/filters&amp;module_id=54" data-products-action="index.php?route=module/journal2_super_filter/products&amp;module_id=54" data-route="product/category" data-path="59" data-full_path="59" data-manufacturer="" data-search="" data-tag="" data-loading-text="Loading..." data-currency-left="$" data-currency-right="" data-currency-decimal="." data-currency-thousand="," data-st="E.R.">
                    <a v-on:click="resetFilter" class="sf-reset hint--top sf-icon" data-hint="{{ trans('messages.Reset Filters') }}"><span class="sf-reset-text">{{ trans('messages.Reset Filters') }}</span><i class="sf-reset-icon"></i></a>
                    <div class="box sf-category sf-list sf-single">
                        <div class="box-heading">{{ trans('messages.brands') }}</div>
                        <div class="box-content">
                            <ul class="">
                                <li v-for="manufacturer in manufacturers">
                                    <label>
                                        <input id="@{{manufacturer.name}}" type="checkbox" v-model="checkedCategories" :value="manufacturer.name"><span class="sf-name">@{{manufacturer.name}}</span> </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="content" class="col-sm-9">
                <h1 class="heading-title">{{ trans('messages.brands') }}</h1>
                <div class="category-info">
                </div>

                <div class="product-filter">
                    <div class="display">
                        <a onclick="Journal.gridView()" class="grid-view active"><i style="margin-right: 5px; color: rgb(255, 255, 255); font-size: 32px" data-icon=""></i></a>
                        <a style="  pointer-events: none; cursor: default; margin-left: 10px; color: white" class="col-sm-6 text-right results">{{ trans('messages.Showing') }} @{{products.length}} {{ trans('messages.Products') }}</a>
                    </div>
                    <div class="limit"><b>Show:</b>
                        <select v-model="limit">
                            <option limit>15</option>
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                    </div>
                    <div class="sort"><b>Sort By:</b>
                        <select>
                            <option value="#" selected="selected">Default</option>
                            <option value="#">Name (A - Z)</option>
                            <option value="#">Name (Z - A)</option>
                            <option value="#">Price (Low &gt; High)</option>
                            <option value="#">Price (High &gt; Low)</option>
                            <option value="#">Rating (Highest)</option>
                            <option value="#">Rating (Lowest)</option>
                            <option value="#">Model (A - Z)</option>
                            <option value="#">Model (Z - A)</option>
                        </select>
                    </div>
                </div>
                <div class="row main-products product-grid" data-grid-classes="xs-100 sm-50 md-50 lg-33 xl-33 display-icon inline-button">
                    <div class="product-grid-item xs-100 sm-50 md-33 lg-25 xl-25 display-icon inline-button" v-for="product in products">
                        <div class="product-thumb  product-wrapper">
                            <div class="image">
                                <div v-on:click="quickView(product.image)" class="quickview-button"><a class="button hint--top" data-hint="{{ trans('messages.QuickView') }}"><i class="button-left-icon"></i><span class="button-cart-text">{{ trans('messages.QuickView') }}</span><i class="button-right-icon"></i></a></div>
                                <div class="p-over p-grid-over"> </div>
                                <img class="lazy first-image" max-width: 200px; height="275" src="/catalog/@{{product.image}}" title="@{{product.name}}" alt="@{{product.name}}" style="display: block;">
                                </a>
                                <div class="wishlist"><a v-on:click="add2Wishlist(product.id)" class="hint--top" data-hint="{{ trans('messages.Add to Wish List') }}"><i class="wishlist-icon"></i><span class="button-wishlist-text">{{ trans('messages.Add to Wish List') }}</span></a></div>
                                <div class="compare"><a v-on:click="quickBuy(product.id)" class="hint--top" data-hint="{{ trans('messages.Quick Buy') }}"><i class="compare-icon"></i><span class="button-compare-text">{{ trans('messages.Quick Buy') }}</span></a></div>
                            </div>
                            <div class="product-details">
                                <div class="caption">
                                    <h4 class="name" style="height: 34px;"><a href="/product?id=@{{product.id}}">@{{product.name}}</a></h4>
                                    <p class="description" style="height: 76px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500..</p>

                               
                                    
                                    <p v-show="isLoggedIn" class="price">
                                        @{{product.priceEach}} <span class="price-tax">Ex Tax: @{{product.priceEach}}</span>
                                    </p>
                               
                                </div>
                                <div class="button-group">
                                    <div class="cart ">
                                        <a :disabled="add2CartDisabled" v-on:click="add2Cart(product.id)" class="button hint--top" data-hint="{{ trans('messages.Add to Cart') }}"><i class="button-left-icon"></i><span class="button-cart-text">{{ trans('messages.Add to Cart') }}</span><i class="button-right-icon"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                    Journal.applyView('grid');
                    </script>
                </div>
                <!--                 <div class="row pagination">
                    <div class="col-sm-6 text-left links">
                        <ul class="">
                            <li class="active"><span>1</span></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li><a href="#">&gt;</a></li>
                            <li><a href="#">&gt;|</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-6 text-right results">Showing 1 to 15 of 70 (5 Pages)</div>
                </div> -->
            </div>
        </div>
    </div>
</div>
<!-- template for the modal component -->
<script type="x/template" id="modal-template">
    <div class="modal-mask" @click="show = false" v-show="show" transition="modal">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-header">
                    <slot name="header">
                    </slot>
                </div>
                <div class="modal-body">
                    <slot name="body">
                        <img style="max-width:300px; max-height:400px" src="@{{img}}">
                    </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="modal-default-button" @click="show = false">
                            CLOSE
                        </button>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</script>
<!-- use the modal component, pass in the prop -->
<modal :img="img" :show.sync="showModal">
    <!--
      you can use custom content here to overwrite
      default content
    -->
</modal>
@stop @section('footer')

 <script type="text/javascript">
  
    $('#button-search').bind('click', function() {
        url = 'index.php?route=product/search';

        var search = $('#content input[name=\'search\']').prop('value');

        if (search) {
            url += '&search=' + encodeURIComponent(search);
        }

        var category_id = $('#content select[name=\'category_id\']').prop('value');

        if (category_id > 0) {
            url += '&category_id=' + encodeURIComponent(category_id);
        }

        var sub_category = $('#content input[name=\'sub_category\']:checked').prop('value');

        if (sub_category) {
            url += '&sub_category=true';
        }

        var filter_description = $('#content input[name=\'description\']:checked').prop('value');

        if (filter_description) {
            url += '&description=true';
        }

        location = url;
    });

    $('#content input[name=\'search\']').bind('keydown', function(e) {
        if (e.keyCode == 13) {
            $('#button-search').trigger('click');
        }
    });

    $('select[name=\'category_id\']').on('change', function() {
        if (this.value == '0') {
            $('input[name=\'sub_category\']').prop('disabled', true);
        } else {
            $('input[name=\'sub_category\']').prop('disabled', false);
        }
    });

    $('select[name=\'category_id\']').trigger('change');

    </script>

<script type="text/javascript">
// register modal component
Vue.component('modal', {
    template: '#modal-template',
    props: {
        show: {
            type: Boolean,
            required: true,
            twoWay: true
        },
        img: ""
    }
});

Vue.filter('total', function(list, key1, key2, key3) {
    if (this.isLoggedIn) {
        return list.reduce(function(total, item) {
            sub = total + item[key1] * item.product[key2] * item.product[key3];
            return isNaN(sub) ? 0 : parseFloat(sub.toFixed(4))
        }, 0)

    }
})

new Vue({
    el: 'body',
    data: {
        showModal: false,
        add2CartDisabled:false,
        limit: "15",
        img: "",
        productsInCart: [],
        checkedCategories: [],
        categories: [],
        products: [],
        relatedProducts: [],
        orders: [],
        displayedProduct: {},
        displayedOrder: {},
        REGISTER_USER_ENDPOINT: "/api/v3/customer/register",
        LOGIN_USER_ENDPOINT: "/api/v3/customer/login",
        query: "",
        manufacturers:[],
        customer: {
            "id": 2,
            "customer_group_id": "",
            "type": "",
            "name": "",
            "surname": "",
            "company": "",
            "email1": "haylie83@example.net",
            "email2": "olga48@example.com",
            "website": "http://www.morar.com/atque-quae-dignissimos-similique-nam",
            "phone": "+1-534-380-6317",
            "mobile": "1-215-798-8898 x56543",
            "vatid": "VAT93287",
            "taxid": "TAX64315",
            "street1": "982 Pouros Courts Apt. 374",
            "street2": "74316 Kohler Mall Apt. 123",
            "city": "Charityland",
            "state": "East Toyborough",
            "zipcode": "03553-1815",
            "country": "Lao People's Democratic Republic",
            "notes": "Quia quia repellat voluptatum placeat dignissimos rerum assumenda. Voluptas possimus fuga consectetur illum omnis est id. Recusandae aut ipsa omnis culpa quia dolorem.",
            "enabled": "1",
            "created_at": "2016-05-02 04:34:44",
            "updated_at": "2016-05-02 04:34:44",
            "discountPercent": "7.00",
            "customer_group": "TOP1",
            "id_token": null
        },
        wishlist: {
            "products":  [],
            "length": ""
        },

        cart: {
            "total": 0,
            "products":  [],
            "length": 0
        },
        total: 123
    },
    watch: {
        checkedCategories: function(val, oldVal) {


            var url = "/api/v3/products";

            if (this.checkedCategories.length != 0) {

                url = "/api/v3/search/products/" + this.checkedCategories[0];
            }


            Vue.http.get(url).then(function success(response) {

                this.productCount = response.data.count;
                this.$set('products', response.data.result);

            }.bind(this), function error(response) {
                console.log('FAILURE', response);
            });
        },
        limit: function(argument) {


            Vue.http.get('/api/v3/search/products?limit=' + this.limit).then(function success(response) {
                this.productCount = response.data.count;
                this.$set('products', response.data.result);
            }.bind(this), function error(response) {
                console.log('FAILURE', response);
            });


        }
    },
    computed: {
        isLoggedIn: function() {

            return (this.customer.id_token === null || this.customer.id_token === undefined) ? false : true;

        },
        localizedObjects: function() {
            // localized = [];
            // this.objects.forEach(function(object) {
            //     if (object.type == 'company') {
            //         object.type = '{!! trans('
            //         messages.prop.company ') !!}';
            //     } else if (object.type == 'person') {
            //         object.type = '{!! trans('
            //         messages.prop.person ') !!}';
            //         object.company_name = 'N/A';
            //     }

            //     localized.push(object);
            // });
            // return localized;
        },
        isCartEmpty: function(argument) {

            return (this.cart.products.length === 0) ? true : false;
        },
        cartLength: function() {
            return this.cart.products.length;
        },
        wishlistLength: function() {
            return this.wishlist.products.length;
        }
    },
    methods: {
        searchSubmit:function (argument) {
            
            if (this.query != "") {
                location.href = "/search?search="+this.query;
            }
        },
        resetFilter: function(argument) {
            this.checkedCategories = [];
        },
        quickBuy: function(id) {
            this.add2Cart(id);
            setTimeout(function(argument) {
                location.href = "/account/checkout";
            }, 1000);


        },
        fetchItems: function(category) {

            Vue.http.get('/api/v3/products/' + category).then(function success(response) {

                this.$set('objects', response.data);
            }.bind(this), function error(response) {
                console.log('FAILURE', response);
            });
        },
        add2Cart: function(id) {

                event.preventDefault();
            event.target.disabled = true;
            this.add2CartDisabled = true;
            if (this.addQuantity2Existing(id)) {

                return;
            } else {

                Vue.http.get('/api/v3/products/' + id).then(function success(response) {
                    product = response.data;


                    var item = {
                        "quantity": 1,
                        "product": product
                    };

                    this.cart.products.push(item);
                    this.cart.length = this.cart.products.length;

                    localStorage.setItem("cart", JSON.stringify(this.cart));
 this.add2CartDisabled = false;
                }.bind(this), function error(response) {
                    console.log('FAILURE', response);
                });

            }

        },
        checkCart: function() {

            // get from local in case browser is reopened
            var cart = localStorage.getItem("cart");

            if (cart === null || cart === undefined || cart === "{}") {
                return;
            }

            cart = JSON.parse(cart)
            this.$set('cart', cart);
            // this.productsInCart = this.cart.products;


        },
        removeFromCart: function(id) {


            var length = this.cart.products.length;

            for (var i = 0; i < length; i++) {

                // if inside the cart
                if (this.cart.products[i].product.id === id) {

                    this.cart.products.splice(i, 1);

                    localStorage.setItem("cart", JSON.stringify(this.cart));

                    break;

                }

            }
        },
        isExistInWishList: function(id) {

            var length = this.wishlist.products.length;

            for (var i = 0; i < length; i++) {

                // if inside the cart
                if (this.wishlist.products[i].id === id) {
                    return true;

                }

            }
            return false;

        },
        add2Wishlist: function(id) {

            if (this.isExistInWishList(id)) {
                return;
            } else {

                Vue.http.get('/api/v3/products/' + id).then(function success(response) {
                    product = response.data;

                    this.wishlist.products.push(product);
                    this.wishlist.length = this.wishlist.products.length;

                    localStorage.setItem("wishlist", JSON.stringify(this.wishlist));

                }.bind(this), function error(response) {
                    console.log('FAILURE', response);
                });

            }

        },
        checkWishList: function() {

            // get from local in case browser is reopened
            var wishlist = localStorage.getItem("wishlist");

            if (wishlist === null || wishlist === undefined || wishlist === "{}") {
                return;
            }
            wishlist = JSON.parse(wishlist)
            this.$set('wishlist', wishlist);

        },
        removeFromWishlist: function(id) {


            var length = this.wishlist.products.length;

            for (var i = 0; i < length; i++) {

                // if inside the cart
                if (this.wishlist.products[i].id === id) {

                    this.wishlist.products.splice(i, 1);

                    localStorage.setItem("wishlist", JSON.stringify(this.wishlist));
                    break;

                }

            }
        },
        addQuantity2Existing: function(id) {


            var length = this.cart.products.length;

            for (var i = 0; i < length; i++) {

                // if inside the cart
                if (this.cart.products[i].product.id === id) {

                    this.cart.total += parseFloat(this.cart.products[i].product.priceEach);

                    this.cart.products[i].quantity += 1;
                    localStorage.setItem("cart", JSON.stringify(this.cart));

                    return true;
                }

            }
            return false;
        },
        quickView: function(image) {
            this.img = "/catalog/"+image ;
            this.showModal = true;
            console.log(id);
        },
        modalClose: function() {
            this.showModal = false;
        },
        checkboxToggle: function() {
            setTimeout(alert(this.checkedCategories[0]), 3000);

        },
        calculatePrice: function(qty, price, qtyPerPack) {

            if (this.isLoggedIn) {
                val = parseFloat(qty) * parseFloat(price) * parseFloat(qtyPerPack);
                return val.toFixed(4);
            }

        },
        calculateQty: function(qty, qtyPerPack) {
            val = parseFloat(qty) * parseFloat(qtyPerPack);
            return val.toFixed(4);
        },
        checkCustomer: function(argument) {


            // get from local in case browser is reopened
            var customer = localStorage.getItem("carta_customer");

            if (customer === null || customer === undefined || customer === "{}") {
                return;
            }
            customer = JSON.parse(customer)
            this.$set('customer', customer);
        }

    },
    ready: function() {
        this.checkCart();
        this.checkWishList();
        this.checkCustomer();
        
        Vue.http.get('/api/v3/manufacturers').then(function success(response) {

            this.$set('manufacturers', response.data);
        }.bind(this), function error(response) {
            console.log('FAILURE', response);
        });

        Vue.http.get('/api/v3/products').then(function success(response) {
            this.productCount = response.data.count;
            this.$set('products', response.data.result);
        }.bind(this), function error(response) {
            console.log('FAILURE', response);
        });
}

});



</script>
@stop


