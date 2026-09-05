@extends('layouts.default') @section('content')
<div class="carta-container">
    <div id="container" class="container j-container">
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/search">Search</a></li>
        </ul>
        <div class="row">
            <div id="content" class="col-sm-12 search-page">
                <h1 class="heading-title">{{  trans('messages.Search') }} - @{{query}}</h1>
                <h2>{{  trans('messages.Product Results') }}</h2>
                <div class="product-filter">
                    <div class="display">
                        <a class="grid-view active"><i style="margin-right: 5px; color: rgb(255, 255, 255); font-size: 32px" data-icon=""></i></a>
                        <a style="  pointer-events: none; cursor: default; margin-left: 10px; color: white" class="col-sm-6 text-right results">{{trans('messages.Showing')}}  @{{limit}} {{trans('messages.Products') }}</a>
                    </div>
    
                    <div class="limit"><b>Show:</b>

                        <select v-model="limit">
                            <option limit>15</option>
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                        
                    </div>
<!--                     <div class="sort"><b>Sort By:</b>
                        <select onchange="location = this.value;">
                            <option value="/search" selected="selected">Default</option>
                            <option value="/search">Name (A - Z)</option>
                            <option value="/search">Name (Z - A)</option>
                            <option value="/search">Price (Low &gt; High)</option>
                            <option value="/search">Price (High &gt; Low)</option>
                            <option value="/search">Rating (Highest)</option>
                            <option value="/search">Rating (Lowest)</option>
                            <option value="/search">Model (A - Z)</option>
                            <option value="/search">Model (Z - A)</option>
                        </select>
                    </div> -->
                </div>
                <div class="row main-products product-grid" data-grid-classes="xs-100 sm-50 md-33 lg-25 xl-25 display-icon inline-button">
                    <div class="product-grid-item xs-100 sm-50 md-33 lg-25 xl-25 display-icon inline-button" v-for="product in products | orderBy 'name'" >
                        <div class="product-thumb  product-wrapper">
                            <div class="image">
                                <div v-on:click="quickView(product.sku)" class="quickview-button"><a class="button hint--top" data-hint="{{ trans('messages.QuickView') }}"><i class="button-left-icon"></i><span class="button-cart-text">{{ trans('messages.QuickView') }}</span><i class="button-right-icon"></i></a></div>
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
                                      @if (Auth::check() && Auth::user()->isCustomer)
                                    <p class="price">
                                        626 <span class="price-tax">Ex Tax: @{{product.priceEach}}</span>
                                    </p>
                                    @endif
                                </div>
                                <div class="button-group">
                                    <div class="cart ">
                                    <label style="padding-right: 0px">{{ trans('messages.QTY') }}</label>
                                    <input id="@{{product.id}}" style="width:20px" type="" value="0">
                                        <a v-on:click="add2Cart(product.id)" class="button hint--top" data-hint="{{ trans('messages.Add to Cart') }}"><i class="button-left-icon"></i><span class="button-cart-text">{{ trans('messages.Add to Cart') }}</span><i class="button-right-icon"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pagination">
                    <div class="col-sm-6 text-left links"></div>
                    <div class="col-sm-6 text-right results">Showing @{{limit}} Products</div>
                </div>
            </div>
        </div>
        <script>
        Journal.applyView('grid');
        </script>
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
    </div>

</div>

@stop
@section("footer")

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
        query:"",
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
        register2Newsletter:function (argument) {
        
        },
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
        add2Cart: function(id) {

            qty = parseFloat($("#"+id).val());
            if (qty ==0 ) {
                qty = 1;
            }

            this.add2CartDisabled = true;
            if (this.addQuantity2Existing(id,qty)) {
                return;
            } else {

                Vue.http.get('/api/v3/products/' + id).then(function success(response) {
                    product = response.data;


                    var item = {
                        "quantity": qty,
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
        addQuantity2Existing: function(id,qty) {


            var length = this.cart.products.length;

            for (var i = 0; i < length; i++) {

                // if inside the cart
                if (this.cart.products[i].product.id === id) {

                    this.cart.total += parseFloat(this.cart.products[i].product.priceEach);

                    this.cart.products[i].quantity += qty;
                    localStorage.setItem("cart", JSON.stringify(this.cart));
            
                    return true;
                }

            }
            return false;
        },
        getParameterByName: function(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
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

        Vue.http.get('/api/v3/categories').then(function success(response) {


            this.$set('categories', response.data[1].children);

        }.bind(this), function error(response) {
            console.log('FAILURE', response);
        });
        


        this.query = this.getParameterByName("search");

        if (this.query != null && this.query != undefined) {
            Vue.http.get('/api/v3/search/products/' + this.query + "?limit=" + this.limit).then(function success(response) {
                this.productCount = response.data.count;
                this.$set('products', response.data.result);
            }.bind(this), function error(response) {
                console.log('FAILURE', response);
            });


        }

    }

});


</script>
@stop
