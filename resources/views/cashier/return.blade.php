<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
       <meta name="_token" content="{{ csrf_token() }}" />
       
    <!-- Import materialize.css -->
    <link type="text/css" rel="stylesheet" href="/templates/assets/css/materialize.css" media="screen,projection" />
    <!-- Import Material Design Iconic Font -->
    <!-- Import Material Design Iconic Font -->
    <link rel="stylesheet" href="/templates/assets/css/material-design-iconic-font.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <!-- Import Application CSS -->
    <link rel="stylesheet" href="/templates/assets/css/app.css" />
    <!-- Import Application CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/css/materialize.min.css">
    <link rel="stylesheet" href="/assets/css/classic.css" />
    <!-- Let browser know website is optimized for mobile -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <div id="app">
        <section id="header">
            <div class="header row">
                <div class="brand col s6">
                    <div class="header row">
                        <div class="brand col s6">
                            <h4>
                        <a href="/cashier"> Cemet Oy</a>
                    </h4>
                        </div>
                        <div class="currentCustomer brand col s6">
                            <div style=" margin-bottom: 0px;" class="row">
                                <div class="col s10">
                                    <h5 class="name blink_me"> Valitse asiakas</h5>
                                    <p style="margin-bottom: 0px;" class="companyId"></p>
                                </div>
                                <div class="addCustomer col s2" style="float:right"><i class="material-icons left">add_box</i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--                 <div class="order-selector col s6" style="display: none">
                    <ul class="tabs">
                        <li id="order-1" class="order-tab tab col s3">
                            <a class="active">
                                1
                            </a>
                        </li>
                        <li id="order-2" class="order-tab tab col s3">
                            <a class="">
                                2
                            </a>
                        </li>
                        <div id="order-add" class="order-button">
                            <i @click="add" id="add_tab" class="material-icons">
                                add
                            </i>
                        </div>
                        <div class="order-button">
                            <i @click="remove" id="remove_tab" class="material-icons">
                                indeterminate_check_box
                            </i>
                        </div>
                    </ul>
                </div> -->
            </div>
        </section>
        <div class="row">
            <div class="col s3">
                <div id="orderSearchBoxes" style="width: 100%">
                    <div class="sw row">
                        <input class="col s12" id="orderSearch" v-on:keyup.13="fetchOrders" v-model="orderQuery" type="search" name="barcode" class="search" placeholder="Search orders..." />
                    </div>
                </div>
                <div class="row">
                    <section>
                        <div id="customers" class="row">
                        </div>
                    </section>
                    <section id="customerDetails" style="display: none">
                        <div class="row">
                            <a style="background-color: #9edad5 !important;" class=" claswaves-effect btn-large btn-flat col grey lighten-5 black-text s3 backToCustomerList">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                        </div>
                        <div class="row">
                            <div class="col s6">
                                <div class="customerDetails">
                                    <span class="name"> Test Oriental Market</span>
                                    <br>
                                    <span class="street"> Kastelholmantie 31</span>
                                    <br>
                                    <span class="postal"> 00100</span>
                                    <span class="city"> Helsinki</span>
                                    <br>
                                    <span class="country"> Finland</span>
                                </div>
                            </div>
                            <div class="customerDetails col s6">
                                <label>GroupId: </label><span class="groupId">TOP1</span>
                                <br>
                                <label>Date: </label><span class="date">  20.08.2010</span>
                                <br>
                                <label>Total: </label><span class="totalAmount">712378,99€</span>
                            </div>
                        </div>
                        <div id="orderTable" class="row">
                        </div>
                    </section>
                </div>
                <section id="orderDetails" v-show="showOrderDetails">
                    <div class="row">
                        <div class="col s6">
                            <div class="customerDetails">
                                <span class="name"> @{{order.company}}</span>
                                <br>
                                <span class="street"> @{{order.street1}}</span>
                                <br>
                                <span class="postal"> @{{order.zipcode}}</span>
                                <span class="city"> @{{order.city}}</span>
                                <br>
                                <span class="country"> @{{order.country}}</span>
                            </div>
                        </div>
                        <div class="customerDetails col s6">
                            <label>GroupId: </label><span class="groupId">TOP1</span>
                            <br>
                            <label>Date: </label><span class="date">  @{{order.created_at}}</span>
                            <br>
                            <label>Total: </label><span class="totalAmount">@{{order.taxed_total}}</span>
                        </div>
                    </div>
                    <div id="productsTable" class="row">
                        <!-- demo root element -->
                        <div id="demo">
                            <form id="search">
                                Search
                                <input name="query" v-model="searchQuery">
                            </form>
                            <demo-grid :data="gridData" :columns="gridColumns" :filter-key="searchQuery" v-bind:when-applied="add2Cart">
                            </demo-grid>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col s9">
                <div id="options" style="height:100px">
                    <div class="options col col-small-padding">
                        <a style="background-color: #CE9869" target="_blank" href="../admin" class="waves-effect waves-light btn-large">
                            <i class="material-icons left">whatshot</i>Asetukset
                        </a>
                    </div>
                    <div class="options col col-small-padding">
                        <a style="background-color: #69CE8D" class="proforma  waves-effect waves-light btn-large" class="card-title item-list-title grey-text text-darken-4" v-on:click="createReturn">
                            <i class="material-icons left">assignment_turned_in</i> Vahvista
                        </a>
                    </div>
                    <div class="options col col-small-padding">
                        <a style="background-color: #69A9CE" class="waves-effect waves-light btn-large" href="/cashier">
                            <i class="material-icons left">eject</i> Cashier
                        </a>
                    </div>
                    <!--                     <div class="options col col-small-padding">
                        <a class="newSale waves-effect waves-light btn-large"><i class="material-icons left">card_travel</i>Uusi Myynti</a>
                    </div> -->
                </div>
                <section>
                    <div class=" row" style="margin-bottom:0px">
                        <div class="orders-header row" style="position:relative">
                            <div class="col s1">
                                Code
                            </div>
                            <div class="col s3">
                                Nimi
                            </div>
                            <div class="col s1">
                                A-hinta
                                <br>veroton
                            </div>
                            <div class="col s1">
                                A-hinta
                                <br>verollinen
                            </div>
                            <div class="col s1">
                                KPL Yhteensä
                            </div>
                            <div class="col s1">
                                Alv%
                            </div>
                            <div class="col s1">
                                Veroton
                                <br>yhteensä
                            </div>
                            <div class="col s1">
                                ALV
                                <br>yhteensä
                            </div>
                            <div class="col s1">
                                Verollinen
                                <br> yhteensä
                            </div>
                            <div style="    padding-left: 30px;" class="col s1">
                                Sil
                            </div>
                        </div>
                        <div id="orders" class="orders row">
                            <ul id="productList">
                                <li v-for="product in cart.products" id=@{{product.id}} class="orderline active">
                                    <div class="row">
                                        <div class="col sku s1"> @{{product.id}}</div>
                                        <div class=" name col s3"> @{{product.name}} </div>
                                        <div class="priceEach  col s1"> @{{product.priceEach}} </div>
                                        <div class="taxedPrice col s1"> @{{product.taxedPriceEach}} </div>
                                        <div class="totalQty edit col s1" v-on:click="editMe(product)">@{{product.qty}} </div>
                                        <div class="taxPercent col s1"> @{{product.taxPercent}} </div>
                                        <div class="nonTaxedSum  col s1">@{{product.totalWithoutTaxes}} </div>
                                        <div class="taxSum col s1">@{{product.taxAmountTotal}} </div>
                                        <div style="" class="taxedPriceSum col s1"> @{{product.taxedPriceTotal}} </div>
                                        <div class="col s1"><a style="margin-left:30px;font-size:15px; cursor: pointer; " v-on:click="removeMe(product.id)" class="removeProduct">X</a></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="total-price row">
                            <div class="total-price col s12 product-name">
                                <span> VEROTON YHTEENSÄ: @{{cart.totalNonTaxedAmount}}</span>
                            </div>
                            <div class="col s12">
                                <span>VERO YHTEENSÄ: @{{cart.totalTaxAmount }}</span>
                            </div>
                            <div class="col s12">
                                <span> VEROLLINEN YHTEENSÄ: @{{cart.totalTaxedAmount }}

                         </span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- Modal Structure -->
    <div id="confirmRemove" class="modal">
        <div class="modal-content">
            <h4>Are you sure?</h4>
            <p>The prouct will be deleted from cart.</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class=" removeConfirmed modal-action modal-close waves-effect waves-green btn-flat">OK</a>
        </div>
    </div>
    <!-- Modal Structure -->
    <div id="customerSearchTable" class="modal bottom-sheet">
        <div class="modal-content">
            <div class="container">
                <div id="top" class="row">
                    <div class="col s11 right-align">
                        <input id="searchCustomer" placeholder="Etsi asiakas...">
                    </div>
                    <div class="addCustomer col s1 right-align" style="cursor:pointer;height: 50px; background-color: gray;">
                        <i style="font-size:35px" class="material-icons">add</i>
                    </div>
                </div>
                <!-- customer details end-->
                <!-- Customer list section -->
                <section>
                    <div class="row">
                        <table id="customerTable" class="striped bordered responsive-table">
                            <thead>
                                <tr>
                                    <th data-field="id">Name</th>
                                    <th data-field="name">Address</th>
                                    <th data-field="price">Phone</th>
                                    <th data-field="price">Ytunnus</th>
                                </tr>
                            </thead>
                            <tbody id="customerList">
                            </tbody>
                        </table>
                    </div>
                </section>
                <!-- Customer list section end-->
            </div>
            <!-- Container end -->
        </div>
        <div class="modal-footer">
        </div>
    </div>
    <script type="text/javascript" src="/assets/js/vue-1.0.17.js"></script>
    <script src="/assets/js/vue-resource-0.7.0.js"></script>
    <script type="/templates/text/javascript" src="types/cart.js">
    </script>
    <script type="text/javascript" src="/templates/types/customer.js">
    </script>
    <script type="text/javascript" src="/templates/types/product.js">
    </script>
    <script type="text/javascript" src="/templates/types/invoice.js">
    </script>
    <script type="text/javascript" src="/templates/types/order.js">
    </script>
    <script type="text/javascript" src="/templates/types/proforma.js">
    </script>
    <script type="text/javascript" src="/templates/assets/js/jquery-2.1.1.min.js">
    </script>
    <script type="text/javascript" src="/templates/assets/js/materialize.js">
    </script>
    <script src="/templates/assets/lib/jquery.columns.min.js"></script>
    <script src="/templates/assets/lib/ajaxpaging.js"></script>
    <script src="/templates/assets/lib/gotopage.js"></script>
    <script type="text/javascript" src="/templates/utility/RESOURCE.js"></script>
    <!-- <script type="text/javascript" src="return.js">
</script>
 -->
    <script type="text/javascript" src="/templates/assets/lib/jquery.jeditable.mini.js">
    </script>
    <!-- component template -->
    <script type="text/x-template" id="grid-template">
        <table>
            <thead>
                <tr>
                    <th v-for="key in columns" @click="sortBy(key)" :class="{active: sortKey == key}">
                        @{{key | capitalize}}
                        <span class="arrow" :class="sortOrders[key] > 0 ? 'asc' : 'dsc'">
          </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="
        entry in data
        | filterBy filterKey
        | orderBy sortKey sortOrders[sortKey]" v-on:click="prepareProduct(entry['id'])" class "ss" id="@{{entry['id']}}">
                    <td v-for="key in columns" ">
                        @{{entry[key]}}
                    </td>
                </tr>
            </tbody>
        </table>
    </script>
    <script>
    // register the grid component
    Vue.component('demo-grid', {
           props:['whenApplied','data','columns','filterKey'],
        template: '#grid-template',
     
        data: function() {
            var sortOrders = {}
            this.columns.forEach(function(key) {
                sortOrders[key] = 1
            })
            return {
                sortKey: '',
                sortOrders: sortOrders
            }
        },
        methods: {
            sortBy: function(key) {
                this.sortKey = key
                this.sortOrders[key] = this.sortOrders[key] * -1
            },
            prepareProduct: function (id) {
                  this.whenApplied(id);
                   
            }
        }
    })


            Vue.config.debug = true;
         
            var getXsrfToken = function() {
    var cookies = document.cookie.split(';');
    var token = '';

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].split('=');
        if(cookie[0] == 'XSRF-TOKEN') {
            token = decodeURIComponent(cookie[1]);
        }
    }

    return token;
}

   Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector("meta[name='_token']").getAttribute('content');
    Vue.http.headers.common['X-XSRF-TOKEN'] =getXsrfToken();

    new Vue({
        el: 'body',
        data: {
            RETURN_ENDPOINT:"/api/v3/returns",
            orderQuery: " ",
            customerQuery: " ",
            order: {},
            cart: {
                totalTaxAmount: 0,
                totalTaxedAmount: 0,
                totalNonTaxedAmount: 0,
                products: []
            },
            searchQuery: '',
            gridColumns: ['id', 'name', 'qty', 'total'],
            gridData: [{
                id: '123213',
                name: 'Chuck Norris',
                qty: 122,
                total: 12333
            }, {
                id: '33',
                name: 'Bruce Lee',
                qty: 122,
                total: 12333
            }, {
                id: '333',
                name: 'Jackie Chan',
                qty: 122,
                total: 12333

            }, {
                id: '3332',
                name: 'Jet Li',
                qty: 122,
                total: 12333
            }]

        },
        watch:{
            order:function (argument) {
                
            }

        },
        filters:{

            total: function(list, key1, key2)  {
                return list.reduce(function(total, item) {
            if (key2 === undefined) {
                 sub = total + parseFloat(item[key1]);
                 return isNaN(sub) ? 0 : parseFloat(sub.toFixed(4))
            } 
            else{  sub = total + parseFloat(item[key1]) * parseFloat(item[key2]);
                return isNaN(sub) ? 0 : parseFloat(sub.toFixed(4))
            }  
           
            
        }, 0)
            }
        },
           computed: {
                cQuantity: {
                    set: function setCUntaxedPriceEach(val) {
                        this.cart.priceEach = parseFloat(val);
                    },
                    get: function getCUntaxedPriceEach(id) {
                        return 5;
                    }
                },
                cTaxedPriceSum: {
                    cache: false,
                    get: function uncachedProductImage(id) {
                        if (this.image) {
                            return '/catalog/' + this.image + '?time=' + Date.now();
                        } else {
                            return '/assets/img/image_not_found.png';
                        }
                    },
                    set:function (val) {
                        // body...
                    }
                },  
            showOrderDetails:function (argument) {
              return (this.order != undefined && this.order != null &&  Object.keys(this.order).length != 0) ? true : false;
            }
           

        },

        methods: {

            fetchOrders: function() {

                var that = this;
                Vue.http.get('/api/v3/orders/' + this.orderQuery).then(function success(response) {
                    this.order = response.data;

                    this.initProducts();

                }.bind(this), function error(response) {
                     that.order = {};
                    console.log('FAILURE', response);
                   
                });

            },
            initProducts:function (argument) {
                 var products =[];
                for (var i = 0; i < this.order.products.length; i++) {
                   var product ={};
                    product.name =  this.order.products[i].name;
                    product.id =  this.order.products[i].id;
                    product.qty =  this.order.products[i].details.quantity;
                    product.total =  this.order.products[i].details.taxedPriceTotal;
                    product.priceEach = this.order.products[i].details.priceEach;
                    product.taxPercent = this.order.products[i].details.taxPercent;
                    product.taxedPriceEach = this.order.products[i].details.taxedPriceEach;

                    product.totalWithoutTaxes = this.order.products[i].details.totalWithoutTaxes;
                    product.taxAmountTotal = this.order.products[i].details.taxAmountTotal;
                    product.taxedPriceTotal = this.order.products[i].details.taxedPriceTotal;
                      products.push(product);
                }               

                this.$set("gridData ",products);
            },
            add2Cart: function(id) {

                if (!this.addQuantity2Existing(id)) {


                        for (var i = 0; i < this.gridData.length; i++) {
                             if (this.gridData[i].id == id) {


                        this.cart.products.push(this.gridData[i]);
                        this.cart.totalTaxAmount = this.$options.filters.total(this.cart.products,"taxAmountTotal");
                        this.cart.totalTaxedAmount = this.$options.filters.total(this.cart.products,"taxedPriceTotal");
                        this.cart.totalNonTaxedAmount = this.$options.filters.total(this.cart.products,"totalWithoutTaxes");


                             } 
                        }
                    }

           
            },
            addQuantity2Existing: function(id) {


                var length = this.cart.products.length;

                for (var i = 0; i < length; i++) {

                    // if inside the cart
                    if (this.cart.products[i].id === id) {

                        return true;
                    }

                }
                return false;
            },
            editMe:function (product) {
                var that = this;
                function isNumber(n) {
                return !isNaN(parseFloat(n)) && isFinite(n);
                    }

            $('.edit').editable(function(value, settings) {

                if (that.checkIfGreater(value,product.id) ) {

                    alert("Aldiginizdan fazla urun iade edemezsiniz");
                    return product.qty;
                } 
                if (!isNumber(value)) {
                    alert("Sadece rakam girebilirsiniz!");
                    return product.qty;

                }

                that.editProduct(product.id,value);

             return (value);

         }, {
             submit: 'OK',
             cancel: 'Peruttaa',
         })
            },



            checkIfGreater:function (qty,id) {
                
                len = this.order.products.length;
                products = this.order.products;
                for (var i = 0; i < len; i++) {
                       if (id === products[i].id) {

                            if (parseFloat(qty) > parseFloat(products[i].details.quantity)) {

                                return true;
                            }

                       }     
                }
            },
        removeMe:function (id) {
                 var len = this.cart.products.length;
               
                for (var i = 0; i < len; i++) {
                    if (this.cart.products[i].id == id) {
                        this.cart.products.splice(i, 1);
                    }
                }
            },

            editProduct:function (id,qty) {
                var len = this.cart.products.length;
               
                for (var i = 0; i < len; i++) {
                    if (this.cart.products[i].id == id) {
                        this.cart.products[i].qty = qty ;
                        val = this.cart.products[i].taxedPriceEach * parseFloat(qty);
                        this.cart.products[i].taxedPriceTotal = val.toFixed(4);
                        val = this.cart.products[i].priceEach * parseFloat(qty);
                        this.cart.products[i].totalWithoutTaxes = val.toFixed(4);
                        val = this.cart.products[i].taxedPriceTotal -  this.cart.products[i].totalWithoutTaxes ;;
                        this.cart.products[i].taxAmountTotal =  val.toFixed(4);       
                    }                 
                }
                        this.cart.totalTaxAmount = this.$options.filters.total(this.cart.products,"taxAmountTotal");
                        this.cart.totalTaxedAmount = this.$options.filters.total(this.cart.products,"taxedPriceTotal");
                        this.cart.totalNonTaxedAmount = this.$options.filters.total(this.cart.products,"totalWithoutTaxes");
            },
            createReturn:function () {
                cus = JSON.stringify(this.order.customer);
                cart = JSON.stringify(this.cart);
            localStorage.setItem("returnCustomer", cus);
            localStorage.setItem("returnCart",cart );
            this.$http.post(this.RETURN_ENDPOINT, this.prepareReturn()).then(function(response) {

                
                    localStorage.setItem("returnID", response.data.id);
                    window.open(
                    '/api/v3/returns/'+response.data.id+'/renderItem',
                    '_blank' // <- This is what makes it open in a new window.
                );
            }, function(response) {
                alert('USER CANNOT BE CREATED');

            });

            },
            prepareReturn:function () {

                var returnedOrder = {};         
                var products =[];
                var len = this.cart.products.length;
                for (var i = 0; i < len; i++) {
                    var product ={};
                    product.id = this.cart.products[i].id;
                    product.quantity = this.cart.products[i].qty;
                    products.push(product);
                }
                returnedOrder.order_id = this.order.id;
                returnedOrder.customer_id = this.order.customer_id;
                returnedOrder.products = products;
                return returnedOrder;

        }
    },
        ready: function(argument) {
            // body...
        }

    })



    </script>

    <script type="text/javascript ">
        

     // $('body').on('click', '.edit', function() {

 


     // // })

     // function editProduct(value,obj) {

     //    var id = $(obj).closest("li ").attr("id ");
     //    var debug = '#' + id;
     //    var product = $(debug).data('product');

     //    var debug = $(obj).closest(".nonTaxedSum ");
     //    var nonTaxedSum = debug.text();

     //    var priceEach = $(obj).closest('li').find('.priceEach').text();
     //    priceEach = parseFloat(priceEach);
     //    var taxedPrice = $(obj).closest('li').find('.taxedPrice').text();
     //    taxedPrice = parseFloat(taxedPrice);

     //    var nonTaxedSum = value * priceEach;
     //    nonTaxedSum = nonTaxedSum.toFixed(4);

     //    var taxedPriceSum = value * taxedPrice;
     //    taxedPriceSum = taxedPriceSum.toFixed(4);

     //    var taxSum = taxedPriceSum - nonTaxedSum;
     //    taxSum = taxSum.toFixed(4);
     //    $(obj).closest('li').find('.taxedPriceSum').text(taxedPriceSum);
     //     $(obj).closest('li').find('.taxSum').text(taxSum);
     //       $(obj).closest('li').find('.nonTaxedSum').text(nonTaxedSum);

     //        updateCartSum(nonTaxedSum,taxSum,taxedPriceSum) ;
 
     // }


     //  function updateCartSum(nonTaxedSum,taxSum,taxedPriceSum) {

     //    var totalTaxed = $(".totalTaxed ").text();

     //    totalTaxed = parseFloat(totalTaxed) + parseFloat(taxedPriceSum);

     //    var totalTax =  $(".totalTax ").text();
     //    totalTax = parseFloat(totalTax) + parseFloat(taxSum);
     //      //write it to total discount
     //    var totalDiscount = $(".totalDiscount ").text();
      
     //      //write it to total non taxed  
     //    var totalNonTaxed = $(".totalNonTaxed ").text();
     //    totalNonTaxed = parseFloat(totalNonTaxed) + parseFloat(nonTaxedSum);


     //      //write it to total sum 
     //      $(".totalTaxed ").text(totalTaxed.toFixed(4));

     //      //write it to total tax 
     //      $(".totalTax ").text(totalTax.toFixed(4));
     //      //write it to total discount
 
     //      //write it to total non taxed  
     //      $(".totalNonTaxed ").text(totalNonTaxed.toFixed(4));

     //  }


    </script>
    </body>

</html>
