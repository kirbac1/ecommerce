@extends('layouts.default') @section('content')
<div class="carta-container">
    <div id="container" class="container j-container">
        <div id="column-right" class="col-sm-3 hidden-xs side-column  ">
            <div class="box side-category side-category-right side-category-accordion" id="journal-side-category-2058550345">
                <div class="box-heading">Custom Side Menu</div>
                <div class="box-category">
                    <ul>
                        <li>
                            <a href="/account/address_edit" class="">Delivery Information</a></li>
                        <li>
                            <a href="/account/orders" class="">Returns Information</a></li>
                        <li>
                            <a href="#" class="">Custom Menu</a></li>
                        <li>
                            <a href="#" class="">Another Custom Menu</a></li>
                        <li>
                            <a  class="">A Related Product</a></li>
                    </ul>
                </div>
                <script>
                $('#journal-side-category-2058550345 .box-category a i').click(function(e, first) {
                    e.preventDefault();
                    $('+ ul', $(this).parent()).slideToggle(first ? 0 : 400);
                    $(this).parent().toggleClass('active');
                    $(this).html($(this).parent().hasClass('active') ? "<span>-</span>" : "<span>+</span>");
                    return false;
                });
                $('#journal-side-category-2058550345 .is-active i').trigger('click', true);
                </script>
            </div>


           <div class="box oc-module side-products">
                <div class="box-heading">Special Offers</div>
                <div class="box-content">
                    <div class="box-product">
                        <div class="product-grid-item  display-icon inline-button">
                            <div class="product-wrapper">
                                <div class="image">
                                    <div class="quickview-button"><a class="button hint--top" data-hint="QuickView"><i class="button-left-icon"></i><span class="button-cart-text">QuickView</span><i class="button-right-icon"></i></a></div>
                                    <a href="#">
                                        <div class="p-over p-grid-over"> </div>
                                        <img width="50" height="50" src="/assets/img/no-image.png" title="Bell Sleeve Dress" alt="Bell Sleeve Dress">
                                    </a>
                                </div>
                                <div class="product-details">
                                    <div class="name"><a href="#">Bell Sleeve Dress</a></div>
                                    <div class="price">
                                        <span class="price-old">$200.00</span> <span class="price-new">$149.00</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="product-grid-item  display-icon inline-button">
                            <div class="product-wrapper">
                                <div class="image">
                                    <div class="quickview-button"><a class="button hint--top" data-hint="QuickView"><i class="button-left-icon"></i><span class="button-cart-text">QuickView</span><i class="button-right-icon"></i></a></div>
                                    <a href="#">
                                        <div class="p-over p-grid-over"> </div>
                                        <img width="50" height="50" src="/assets/img/no-image.png" title="Black Fur Collar" alt="Black Fur Collar">
                                    </a>
                                </div>
                                <div class="product-details">
                                    <div class="name"><a href="#">Black Fur Collar</a></div>
                                    <div class="price">
                                        <span class="price-old">$299.00</span> <span class="price-new">$219.00</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="product-grid-item  display-icon inline-button">
                            <div class="product-wrapper">
                                <div class="image">
                                    <div class="quickview-button"><a class="button hint--top" data-hint="QuickView"><i class="button-left-icon"></i><span class="button-cart-text">QuickView</span><i class="button-right-icon"></i></a></div>
                                    <a href="#">
                                        <div class="p-over p-grid-over"> </div>
                                        <img width="50" height="50" src="/assets/img/no-image.png" title="Black Nail Polish" alt="Black Nail Polish">
                                    </a>
                                </div>
                                <div class="product-details">
                                    <div class="name"><a href="#">Black Nail Polish</a></div>
                                    <div class="price">
                                        <span class="price-old">$110.00</span> <span class="price-new">$85.00</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="product-grid-item  display-icon inline-button">
                            <div class="product-wrapper">
                                <div class="image">
                                    <div class="quickview-button"><a class="button hint--top" data-hint="QuickView"><i class="button-left-icon"></i><span class="button-cart-text">QuickView</span><i class="button-right-icon"></i></a></div>
                                    <a href="#">
                                        <div class="p-over p-grid-over"> </div>
                                        <img width="50" height="50" src="/assets/img/no-image.png" title="Brown Leather Purse" alt="Brown Leather Purse">
                                    </a>
                                </div>
                                <div class="product-details">
                                    <div class="name"><a href="#">Brown Leather Purse</a></div>
                                    <div class="price">
                                        <span class="price-old">$569.00</span> <span class="price-new">$299.00</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="product-grid-item  display-icon inline-button">
                            <div class="product-wrapper">
                                <div class="image">
                                    <div class="quickview-button"><a class="button hint--top" data-hint="QuickView"><i class="button-left-icon"></i><span class="button-cart-text">QuickView</span><i class="button-right-icon"></i></a></div>
                                    <a href="#">
                                        <div class="p-over p-grid-over"> </div>
                                        <img width="50" height="50" src="/assets/img/no-image.png" title="Butterfly Ring" alt="Butterfly Ring">
                                    </a>
                                </div>
                                <div class="product-details">
                                    <div class="name"><a href="#">Butterfly Ring</a></div>
                                    <div class="price">
                                        <span class="price-old">$1,490.00</span> <span class="price-new">$990.00</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div id="content" class="product-page-content" itemscope="" itemtype="http://schema.org/Product">
                <h1 class="heading-title" itemprop="name">@{{displayedProduct.name}}</h1>
                <div class="row product-info split-50-50">
                    <div class="left">
                        <div class="image">
                            <a href="/catalog/@{{displayedProduct.image}}" title="@{{displayedProduct.name}}"><img src="/catalog/@{{displayedProduct.image}}" title="@{{displayedProduct.name}}" alt="@{{displayedProduct.name}}" id="image" data-largeimg="/catalog/@{{displayedProduct.image}}" itemprop="image"></a>
                        </div>
                       
                        <div id="product-gallery" class="image-additional journal-carousel owl-carousel owl-theme" style="opacity: 1; display: block;">
                            <div class="owl-wrapper-outer">
                                <div class="owl-wrapper" style="width: 880px; left: 0px; display: block;">
                                    
                                </div>
                            </div>
                            <div class="owl-controls clickable" style="display: none;">
                                <div class="owl-pagination">
                                    <div class="owl-page active"><span class=""></span></div>
                                </div>
                                <div class="owl-buttons side-buttons">
                                    <div class="owl-prev"></div>
                                    <div class="owl-next"></div>
                                </div>
                            </div>
                        </div>
                        <script>
                        (function() {
                            var opts = {
                                itemsCustom: [
                                    [0, parseInt('5', 10)],
                                    [470, parseInt('5', 10)],
                                    [760, parseInt('5', 10)],
                                    [980, parseInt('5', 10)],
                                    [1100, parseInt('5', 10)]
                                ],
                                navigation: true,
                                scrollPerPage: true,
                                navigationText: false,
                                stopOnHover: true,
                                cssAnimation: false,
                                paginationSpeed: 300,
                                margin: parseInt('10', 10)
                            };
                            opts.autoPlay = parseInt('3000', 10);
                            opts.stopOnHover = true;
                            jQuery("#product-gallery").owlCarousel(opts);
                            $('#product-gallery .owl-buttons').addClass('side-buttons');
                        })();
                        </script>


                    </div>
                    <div class="right">
                        <div id="product" class="product-options">

                            <ul class="list-unstyled description">
                                <li class="p-brand">{{ trans('messages.Manufacturer')}}: <a>@{{displayedProduct.manufacturer}}</a></li>
                                <li class="p-model">{{ trans('messages.SKU')}}: <span class="p-model" itemprop="model">@{{displayedProduct.id}}</span></li>
                                <li class="p-stock">{{ trans('messages.Saatavuus')}}: <span class="journal-stock instock">In Stock</span></li>
                                <li class="p-stock">{{ trans('messages.Quantity per pack') }}: <span class="journal-stock instock"> @{{displayedProduct.qtyPerPack}}</span></li>
                                <li v-show="product.barcode != '' " class="p-stock">Barcode: <span class="journal-stock instock"> @{{displayedProduct.barcode}}/span></li>
                            </ul>
                           
                            <ul class="list-unstyled price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                  
                                <li class="product-price" itemprop="price">€ @{{displayedProduct.priceEach}}</li>
                                <li class="price-tax">{{ trans('messages.Taxed') }} € @{{displayedProduct.taxedPrice}}</li>
                            </ul>
                
                            <div class="form-group cart ">
                                <div>
                                    <span class="qty">
              <label class="control-label text-qty" for="input-quantity">{{ trans('messages.Qty') }}</label>
              <a href="javascript:;" class="journal-stepper">-</a><input type="text" name="quantity" value="1" size="2" data-min-value="1" id="input-quantity" class="form-control"><a href="javascript:;" class="journal-stepper">+</a>
              <input type="hidden" name="product_id" value="165">

              </span>
                                    <button v-on:click="add2Cart(displayedProduct.id)" type="button" data-loading-text="Loading..." class="button"><span  class="button-cart-text">{{ trans('messages.Add to cart') }}</span></button>
                        </div>
                    </div>
                    <div class="wishlist-compare">
                        <span class="links">
                  <a v-on:click="add2Wishlist(displayedProduct.id)">Add to Wish List</a>
                  <a v-on:click="quickBuy(displayedProduct.id)">{{ trans('messages.Quick buy') }}</a>
              </span>
                    </div>


            </div>
        </div>
    </div>

    <div class="box related-products journal-carousel">
        <div>
            <div class="box-heading">Related Products</div>
            <div class="box-product owl-carousel owl-theme" style="opacity: 1; display: block;">
                <div class="owl-wrapper-outer" >
                    <div class="owl-wrapper" style="width: 1752px; left: 0px; display: block; transition: all 1000ms ease; transform: translate3d(0px, 0px, 0px);">
                        <div class="owl-item" style="width: 219px;" v-for="relatedProduct in relatedProducts">
                            <div class="product-grid-item  display-icon inline-button">
                                <div class="product-thumb product-wrapper outofstock">
                                    <div class="image">
                                        <div v-on:click="quickView(product.id)" class="quickview-button"><a class="button hint--top" data-hint="QuickView" ><i class="button-left-icon"></i><span class="button-cart-text">QuickView</span><i class="button-right-icon"></i></a></div>
                                        <a href="/product?id=@{{relatedProduct.id}}" class="has-second-image" >
                                            <div class="p-over p-grid-over"> </div>
                                            <img class="lazy first-image" src="/catalog/@{{relatedProduct.image}}" title="@{{relatedProduct.name}}" alt="@{{relatedProduct.name}}" style="display: block;">
                                        </a>
                                        <img class="outofstock" width="120" height="120" style="position: absolute; top: 0; left: 0" src="/assets/img/no-image.png" alt="">
                                        <div class="wishlist"><a v-on:click="add2Wishlist(relatedProduct.id)" class="hint--top" data-hint="Add to Wish List"><i class="wishlist-icon"></i><span class="button-wishlist-text">Add to Wish List</span></a></div>
                                        <div class="compare"><a v-on:click="quickBuy(relatedProduct.id)" class="hint--top" data-hint="Compare this Product"><i class="compare-icon"></i><span class="button-compare-text">Quick Buy</span></a></div>
                                    </div>
                                    <div class="product-details">
                                        <div class="caption">
                                            <h4 class="name" style="height: 34px;"><a href=/product?id=@{{relatedProduct.id}}>@{{relatedProduct.name}}</a></h4>
                                            <p class="description"></p>
                                            <p class="price">
                                               @{{relatedProduct.priceEach}}<span class="price-tax">Inc Tax: @{{relatedProduct.taxedPrice}}</span>
                                            </p>
                                        </div>
                                        <div class="button-group">
                                            <div class="cart outofstock">
                                                <a v-on:click="add2Cart(relatedProduct.id)" class="button hint--top" data-hint="Add to Cart"><i class="button-left-icon"></i><span class="button-cart-text">Add to Cart</span><i class="button-right-icon"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="owl-controls clickable" style="display: none;">
                    <div class="owl-pagination">
                        <div class="owl-page active"><span class=""></span></div>
                    </div>
                    <div class="owl-buttons">
                        <div class="owl-prev"></div>
                        <div class="owl-next"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    (function() {
        var opts = {
            itemsCustom: $.parseJSON('[[0,2],[470,3],[760,3],[980,4],[1100,4]]'),
            navigation: true,
            scrollPerPage: true,
            navigationText: false,
            paginationSpeed: parseInt('400', 10),
            margin: 15
        }
        opts.autoPlay = parseInt('3000', 10);
        opts.stopOnHover = true;
        jQuery(".related-products .box-product").owlCarousel(opts);


    })();
    </script>
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.thumbnails').magnificPopup({
        type: 'image',
        delegate: 'a',
        gallery: {
            enabled: true
        }
    });
});
</script>
</div>
@stop @section('footer')

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
        sorting:"",
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
        sorting:function (val,oldVal) {
            // body...
        },
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
        fetchItems: function(category) {

            Vue.http.get('/api/v3/products/' + category).then(function success(response) {

                this.$set('objects', response.data);
            }.bind(this), function error(response) {
                console.log('FAILURE', response);
            });
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
        addQuantity2Existing: function(id) {


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
        },

        register: function(argument) {

      
          
            this.$http.post(this.REGISTER_USER_ENDPOINT, this.customer, this.options).then(function(response) {

                location.href = "/account";
            }, function(response) {
                alert('USER CANNOT BE CREATED');

            });
        },
        login: function(argument) {
            alert("ss")
            return;
            this.$http.post(this.LOGIN_USER_ENDPOINT, this.customer, this.options).then(function(response) {

                this.customer.id_token= response.data.id_token;

                localStorage.

                location.href = "/account";
            }, function(response) {
                alert('USER CANNOT BE CREATED');

            });
        },
        forgotten: function(argument) {
            alert("ss")
            return;
            this.$http.post(this.LOGIN_USER_ENDPOINT, this.customer, this.options).then(function(response) {

                location.href = "/account";
            }, function(response) {
                alert('USER CANNOT LOGIN');

            });
        },
        updateCustomer: function(argument) {

            alert("ss")
            return;
            this.$http.put(this.LOGIN_USER_ENDPOINT, this.customer, this.options).then(function(response) {

                location.href = "/account";
            }, function(response) {
                alert('USER CANNOT LOGIN');

            });
        }

    },
    ready: function() {
        this.checkCart();
        this.checkWishList();
        this.checkCustomer();


        this.query = this.getParameterByName("id");

        if (this.query != null && this.query != undefined) {
            Vue.http.get('/api/v3/products/' + this.query).then(function success(response) {
                this.displayedProduct = response.data;

                var manufacturer = this.displayedProduct.manufacturer;

                Vue.http.get('/api/v3/search/products/' + manufacturer).then(function success(response) {


                    this.$set('relatedProducts', response.data.result);

                }.bind(this), function error(response) {
                    console.log('FAILURE', response);
                });


            }.bind(this), function error(response) {
                console.log('FAILURE', response);
            });




        }
 

    },

});



</script>
@stop


