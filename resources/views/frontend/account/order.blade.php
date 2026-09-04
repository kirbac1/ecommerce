@extends('layouts.default') @section('content')
<div class="carta-container"><div id="container" class="container j-container">
  <ul class="breadcrumb">
        <li><a href="http://journal.digital-atelier.com/3/index.php?route=common/home">Home</a></li>
        <li><a href="http://journal.digital-atelier.com/3/index.php?route=account/account">Account</a></li>
        <li><a href="http://journal.digital-atelier.com/3/index.php?route=account/order">Order History</a></li>
        <li><a href="http://journal.digital-atelier.com/3/index.php?route=account/order/info&amp;order_id=1839">Order Information</a></li>
      </ul>
      <div class="row">                <div id="content" class="col-sm-12">
      <h1 class="heading-title">Order Information</h1>
            <table class="table table-bordered table-hover list">
        <thead>
          <tr>
            <td class="text-left" colspan="2">Order Details</td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left" style="width: 50%;">              <b>Order ID:</b> @{{displayedOrder.id}}<br>
              <b>Date Added:</b> @{{displayedOrder.created_at}}</td>
            <td class="text-left">              <b>Payment Method:</b> Cash On Delivery<br>
                                           </td>
          </tr>
        </tbody>
      </table>
      <table class="table table-bordered table-hover list">
        <thead>
          <tr>
            <td class="text-left" style="width: 50%;">Payment Address</td>
                        <td class="text-left">Shipping Address</td>
                      </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left">@{{displayedOrder.name}}<br>@{{displayedOrder.company}}<br>@{{displayedOrder.street1}}<br>@{{displayedOrder.city}} @{{displayedOrder.zipcode}}<br>@{{displayedOrder.country}}</td>
                        <td class="text-left">Example Customer<br>Example Oy<br>Esimerkkikatu 1<br>Tampere 33210<br>Pirkanmaa<br>Finland</td>
                      </tr>
        </tbody>
      </table>
      <div class="table-responsive">
        <table class="table table-bordered table-hover list">
          <thead>
            <tr>
              <td class="text-left">Product Name</td>
              <td class="text-left">Model</td>
              <td class="text-right">Quantity</td>
              <td class="text-right">Price</td>
              <td class="text-right">Total</td>
                            <td style="width: 20px;"></td>
                          </tr>
          </thead>
          <tbody>
                        <tr>
              <td class="text-left">Beige Prom Dress                </td>
              <td class="text-left">Model 51</td>
              <td class="text-right">1</td>
              <td class="text-right">$101.00</td>
              <td class="text-right">$101.00</td>
              <td class="text-right" style="white-space: nowrap;">                <a href="http://journal.digital-atelier.com/3/index.php?route=account/order/reorder&amp;order_id=1839&amp;order_product_id=13549" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Reorder"><i class="fa fa-shopping-cart"></i></a>
                                <a href="http://journal.digital-atelier.com/3/index.php?route=account/return/add&amp;order_id=1839&amp;product_id=57" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="Return"><i class="fa fa-reply"></i></a></td>
            </tr>
                                  </tbody>
          <tfoot>
                        <tr>
              <td colspan="3"></td>
              <td class="text-right"><b>Sub-Total</b></td>
              <td class="text-right">$101.00</td>
                            <td></td>
                          </tr>
                        <tr>
              <td colspan="3"></td>
              <td class="text-right"><b>Free Shipping</b></td>
              <td class="text-right">$0.00</td>
                            <td></td>
                          </tr>
                        <tr>
              <td colspan="3"></td>
              <td class="text-right"><b>Total</b></td>
              <td class="text-right">$101.00</td>
                            <td></td>
                          </tr>
                      </tfoot>
        </table>
      </div>
                  <h3>Order History</h3>
      <div class="table-responsive">
        <table class="table table-bordered table-hover list">
          <thead>
          <tr>
            <td class="text-left">Date Added</td>
            <td class="text-left">Order Status</td>
            <td class="text-left">Comment</td>
          </tr>
          </thead>
          <tbody>
                    <tr>
            <td class="text-left">07/05/2016</td>
            <td class="text-left">Pending</td>
            <td class="text-left"></td>
          </tr>
                    </tbody>
        </table>
      </div>
            <div class="buttons">
        <div class="pull-right"><a href="http://journal.digital-atelier.com/3/index.php?route=account/order" class="btn btn-primary button">Continue</a></div>
      </div>
      </div>
    </div>
</div>
</div>
@stop @section('footer')

<script type="text/javascript">

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

        Vue.http.get('/api/v3/categories').then(function success(response) {


            this.$set('categories', response.data[1].children);

        }.bind(this), function error(response) {
            console.log('FAILURE', response);
        });
        Vue.http.get('/api/v3/products').then(function success(response) {
            this.productCount = response.data.count;
            this.$set('products', response.data.result);
        }.bind(this), function error(response) {
            console.log('FAILURE', response);
        });

        Vue.http.get('/api/v3/customers/' + this.customer.id).then(function success(response) {

            this.$set('orders', response.data.orders);
        }.bind(this), function error(response) {
            console.log('FAILURE', response);
        });

        Vue.http.get('/api/v3/manufacturers').then(function success(response) {

            this.$set('manufacturers', response.data);

            setInterval(function () {
         $('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    autoplay:true,
    autoplayTimeout:1000,
    autoplayHoverPause:true,
    responsiveClass:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:3,
            nav:false
        },
        1000:{
            items:5,
            nav:true,
            loop:false
        }
    }
}) 
    },4000);

          
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
        this.orderId = this.getParameterByName("order");

        if (this.orderId != null && this.orderId != undefined) {
            Vue.http.get('/api/v3/search/orders/' + this.orderId).then(function success(response) {
                this.displayedOrder = response.data;

            }.bind(this), function error(response) {
                console.log('FAILURE', response);
            });


        }

    },

});



</script>
@stop
