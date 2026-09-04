//TODO AJAX SEARCH PRODUCTS  and CUSTOMERS

var resource = new RESOURCE();
var cart = new Cart();
var invoice = new Invoice();
var order = new Order();


var currentCustomer = localStorage.getItem("currentCustomer");
var currentCart = localStorage.getItem("currentCart");
if (currentCustomer == undefined && currentCustomer === null) {

    currentCustomer = new Customer();
} else {
    if (currentCart != undefined && currentCart != null) {

        currentCustomer = new Customer(JSON.parse(currentCustomer), JSON.parse(currentCart));
        initPage(currentCustomer);
    } else {
        currentCustomer = new Customer(JSON.parse(currentCustomer));
    }



}


function initPage(currentCustomer) {

    currentCustomer.currentCart().initCart();
    var company = currentCustomer.company;
    var vatid = currentCustomer.vatid;
    $(".currentCustomer .name").html(company);
    $(".currentCustomer .companyId").html(vatid);

    $(".blink_me").addClass("selectCustomer");
    $(".blink_me").removeClass("blink_me");

}



var prodIndex = 0;

function getNewProduct(prodIndex) {
    return {
        "id": prodIndex,
        "name": "Uusi Tuote",
        "basePrice": "0",
        "qtyPerPack": "1",
        "quantity": "1",
        "nonTaxedSum": "0",
        "taxSum": "0",
        "totalQty": "1",
        "taxedPrice": "0",
        "taxPercent": "14",
        "priceEach": "0"
    }
}

var getXsrfToken = function() {
    var cookies = document.cookie.split(';');
    var token = '';

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].split('=');
        if (cookie[0] == 'XSRF-TOKEN') {
            token = decodeURIComponent(cookie[1]);
        }
    }

    return token;
}

$.ajaxSetup({
    headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        'X-XSRF-TOKEN': getXsrfToken(),
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    },

})


var customers = [];
var currentNumber;
var lastClickedInput;




jQuery(document).ready(function() {



    $(document).on({
        ajaxStart: function() {
            $("body").addClass("loading");
        },
        ajaxStop: function() {
            $("body").removeClass("loading");
        }
    });

    (function blink() {
        $('.blink_me').fadeOut(500).fadeIn(500, blink);
    })();

    $("input").click(function() {
        lastClickedInput = this;
    });


    // cannot enter in search input 

    $('#search').submit(function() {
        return false;
    });
    // start searching with any key 

    $("#search").keyup(function(e) {

        var q = $("#search").val();

        if (q === "") {
            $(".products").empty();
            return;
        }

        var key = e.which;

        if (key == 13) // the enter key code
        {

            if (currentCustomer.name === "default") {

                alert("Valitse asiakas");
                return;
            }

            resource.searchProducts(q, currentCustomer).then(function(products) {

                $(".products").empty();
                $.each(products, function(i, product) {

                    var id = product.id;
                    var name = product.name;
                    var koli = product.qtyPerPack;
                    var price = product.taxedPrice;
                    var image = product.image;
                    var productTemplate = ' <div class="col s6">' + '<button id="' + id + '" class="card product-list-cards z-depth-1 waves-effect waves-green">' + '<div class="jzoom card-image">' + '<img src=/catalog/' + image + '/>' + '<span class="badge white-text green darken-4 price-badge">' + price + '&euro; </span>' + '<span class="badge white-text red darken-4 koli">' + koli + '</span>' + '</div>' + '<div class="card-content">' + '<span class="card-title item-list-title grey-text text-darken-4">' + name + '</span>' + '</div>' + '</button>' + '</div>';

                    jQuery('.products').append(productTemplate);
                    /*DEMO END*/

                    $('#' + id).data('product', product);

                });
            }, function(error) {
                alert("Asikas ei löytynyt")
            })

        }

    });


    $("#searchCustomer").keyup(function(e) {

        var q = $("#searchCustomer").val();




          _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#customerTable tbody tr"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
               $(this).hide();
            else
               $(this).show();                
        });


    });

    // $("#searchCustomer").keyup(function(e) {

    //     $("#customerList").empty();
    //     var q = $("#searchCustomer").val();


    //     if (q === "") {
    //         return;
    //     }


    //     resource.searchCustomers(q).then(function(customers) {

    //         $("#customerList").empty();
    //         if (customers != undefined) {


    //             $.each(customers, function(i, customer) {


    //                 var name = customer.name;
    //                 var company = customer.company;
    //                 var companyId = customer.vatid;

    //                 var taxid = customer.taxid;

    //                 var address = customer.street1 + " " + customer.city + " " + customer.zipcode + "" + customer.country;
    //                 var phone = customer.phone;

    //                 var customerTemplate = ' <tr style="cursor: pointer;" id="' + companyId + '" class="customer"> <td class="name">' + name + '</td><td class="company">' + company + '</td><td class="address">' + address + '</td><td class="phone">' + phone + '</td> <td class="companyId">' + companyId + '</td></tr>';

    //                 $('#customerTable').find('tbody:last').append(customerTemplate);

    //                 /*DEMO END*/

    //                 $('#' + companyId).data('customer', customer);


    //             });
    //         }

    //     })

    // });



function initCustomers() {

        $("#searchCustomer").val("");
        $("#customerList").empty();
        customers = localStorage.getItem("customersCache");

        if (customers != undefined) {

            customers = JSON.parse(customers);



            $.each(customers, function(i, customer) {

                var id = customer.id;
                var name = customer.name;
                var company = customer.company;
                var companyId = (customer.vatid === null) ? "":customer.vatid ;

                var taxid = customer.taxid;

                var address = customer.street1 + " " + customer.city + " " + customer.zipcode + "" + customer.country;
                var phone = customer.phone;

                var customerTemplate = ' <tr style="cursor: pointer;" id="' + id + '" class="customer"> <td class="name">' + name + '</td><td class="company">' + company + '</td><td class="address">' + address + '</td><td class="phone">' + phone + '</td> <td class="companyId">' + companyId + '</td></tr>';

                $('#customerTable').find('tbody:last').append(customerTemplate);

                /*DEMO END*/

                $('#' + id).data('customer', customer);


            });
        }

        $('#customers').openModal();

            // body...
}





    $(".selectCustomer").click(function(e) {


            initCustomers();

            });



    $(".blink_me").click(function(e) {
   
            initCustomers();
    });


    $(".priceCheck").click(function(e) {

        $("#productDetails").empty();
        $('#priceCheck').find(":input").val("");

        $('#priceCheck').openModal();

    });




    var tmp = {};

    $("body").on('dblclick', '.customer', function(event) {
        tmp = this;

        if (currentCustomer.getName() === "default" || currentCustomer.currentCart().isEmpty()) {

            setNewCustomer(this);

        } else {
            $('#confirmNewCustomer').openModal();

        }

        $('.confirmNewCustomer').click(function() {

            setNewCustomer(tmp);
            resetCart();
            $('#confirmNewCustomer').closeModal();

        });



    });

    function setNewCustomer(obj) {
        var company = $(obj).find(".company").text();
        var companyId = $(obj).find(".companyId").text();
        var name = $(obj).find(".name").text();
        var id = $(obj).closest('tr').attr('id');
        currentCustomer = new Customer($('#' + id).data('customer'));

        if (company === null | company === undefined || company =="null" ) {
             $(".currentCustomer .name").html(name);
        $(".currentCustomer .companyId").html("");
        }
        else { 
        $(".currentCustomer .name").html(company);
        $(".currentCustomer .companyId").html(companyId);
        }
        $(".blink_me").addClass("selectCustomer");
        $(".blink_me").removeClass("blink_me");

        $('#customers').closeModal();
    }

    function resetCart() {
        $("#productList").empty();

        $(".totalTaxed").text("0");

        $(".totalTax").text("0");
        $(".totalNonTaxed").text("0");
        localStorage.removeItem('currentCustomer');

    }


    // add product to cart


    $(".products").on('click', '.product-list-cards', function(event) {

        var productData = $(this).data('product');


        var element = document.getElementById("orders");
        element.scrollTop = element.scrollHeight;
        currentCustomer.addToCart(productData);
        localStorage.setItem("currentCustomer", JSON.stringify(currentCustomer));
        localStorage.setItem("currentCart", JSON.stringify(currentCustomer.currentCart()));

    });


    var curRow = {};
    // click events
    // make products editable in the cart


    $('body').on('click', '.removeProduct', function(e) {
        e.preventDefault();
        curRow = this;
        $('#confirmRemove').openModal();

    });


    $('body').on('hover', '.jzoom', function() {

        $("#zoom_01").elevateZoom();

    });

    $('body').on('click', '.edit', function() {

        $('.edit').editable(function(value, settings) {

            if (!$.isNumeric(value) && !$(this).hasClass("name")) {

                alert("numara girin");
                return value;
            }
            cart = currentCustomer.currentCart();
            cart.editProduct(value, this);
            localStorage.setItem("currentCustomer", JSON.stringify(currentCustomer));
            localStorage.setItem("currentCart", JSON.stringify(currentCustomer.currentCart()));
            return (value);

        }, {
            submit: 'OK',
            cancel: 'Peruttaa',
        })


    })


    //remove the product
    $(document).on('click', '.removeConfirmed', function(e) {


        var id = $(curRow).closest("li").attr("id");
        id = id.replace("_cart", "");
        cart = currentCustomer.currentCart();
        cart.removeFromCartArray(id);
        e.preventDefault();
        $(curRow).closest("li").remove();

        if ($("#productList li").length == 0) {

            $(".order-empty").css("display", "block");
            $('.cart-full').css('display', 'none');

        }

        cart.updateCartSum();

    });


    //write the current cart to local storage for invoice
    $(document).on('click', '.invoice', function(e) {
        e.preventDefault();

        cart = currentCustomer.currentCart();

        if (cart.getProducts().length === 0) {

            return;
        }

        var customer = currentCustomer.toJSON();

        customer = JSON.stringify(customer);

        localStorage.setItem("invoiceCustomer", customer);

        window.open(
            '/cashier/invoice',
            '_blank' // <- This is what makes it open in a new window.
        );


    });

    //write the current cart to local storage for proforma
    $(document).on('click', '.proforma', function(e) {

        cart = currentCustomer.currentCart();

        if (cart.getProducts().length === 0) {

            return;
        }

        var customer = currentCustomer.toJSON();


        localStorage.setItem("proformaCustomer", JSON.stringify(customer));

        window.open(
            '/cashier/proforma',
            '_blank' // <- This is what makes it open in a new window.
        );


    });



    /*keyboard*/

    $(".number").click(function(e) {

        var val = $(lastClickedInput).val();
        currentNumber = $(this).text();

        currentNumber = $.trim(currentNumber);


        $(lastClickedInput).val(val + currentNumber);


    });

    $(".zmdi-tag-close").click(function(e) {

        var val = $(lastClickedInput).val();
        val = val.slice(0, -1)

        $(lastClickedInput).val(val);


    });


    $(".clearAll").click(function(e) {

        $(lastClickedInput).val("");


    });



    $("#checkPrice").keyup(function(e) {

        var q = $("#checkPrice").val();

        if (q === "") {
            $("#productDetails").empty();
            return;
        }

        var key = e.which;

        if (key == 13) // the enter key code
        {

            resource.searchProducts(q, currentCustomer).then(function(products) {

                $("#productDetails").empty();
                $.each(products, function(i, product) {
                    var id = product.id;
                    var name = product.name;
                    var koli = product.qtyPerPack;
                    var nonTaxed = product.priceEach;
                    var price = product.taxedPrice;
                    var image = product.image;
                    var productTemplate = ' <div class="jzoom col s3">' + '<button id="' +
                        id + '" class="card product-list-cards z-depth-1 waves-effect waves-green">' +
                        '<div class="jzoom card-image">' + '<img src=/catalog/' + image + '/>' +
                        '<span class="badge white-text orange darken-4 price-badge">' + nonTaxed +
                        '<span class="badge white-text green darken-4 price-badge">' +
                        price + '&euro; </span>' + '<span class="badge white-text red darken-4 koli">' +
                        koli + '</span>' + '</div>' + '<div class="card-content">' +
                        '<span class="card-title item-list-title grey-text text-darken-4">' +
                        name + '</span>' + '</div>' + '</button>' + '</div>';

                    jQuery('#productDetails').append(productTemplate);
                    /*DEMO END*/

                    $('#' + id).data('product', product);


                });
            }, function(error) {
                alert("Asikas ei löytynyt")
            })

        }
    });


    $(".addCustomer").click(function() {

        $('#customerRegistrationForm').find(":input").val("");
        $('input:checkbox').removeAttr('checked');
        $('#customerRegistrationError').val("");

        $('#customerRegistrationForm').openModal();

    });

    $(".saveCustomer").click(function(e) {

        e.preventDefault();

        var validator = $("#registerForm").validate();
        if (!validator.form()) {
            validator.showErrors({
                "name": "Virhe!"
            });
            return;
        }

        var customer = {};
        customer.name = $('#customerRegistrationForm').find('input[name="name"]').val();
        customer.surname = $('#customerRegistrationForm').find('input[name="surname"]').val();
        customer.type = (document.getElementById('select_person').checked ? "person" : "company");
        customer.vatid = $('#customerRegistrationForm').find('input[name="vatid"]').val();
        customer.taxid = $('#customerRegistrationForm').find('input[name="vatid"]').val();
        customer.street1 = $('#customerRegistrationForm').find('input[name="street1"]').val();
        customer.zipcode = $('#customerRegistrationForm').find('input[name="zipcode"]').val();
        customer.city = $('#customerRegistrationForm').find('input[name="city"]').val();
        customer.phone = $('#customerRegistrationForm').find('input[name="phone"]').val();
        customer.group = $('#customerRegistrationForm').find('input[name="group"]').val();
        customer.country = $('#customerRegistrationForm').find('input[name="country"]').val();
        customer.email = $('#customerRegistrationForm').find('input[name="email"]').val();
        customer.current = document.getElementById('setCurrentCustomer').checked;

        newCustomer = new Customer(customer);

        newCustomer.registerMyself(newCustomer);

        if (customer.current) {
            currentCustomer = newCustomer;
        }

     $(".blink_me").addClass("selectCustomer");
    $(".blink_me").removeClass("blink_me");

    
    });
    $(".pay").click(function() {

        cart = currentCustomer.currentCart();

        if (cart.getProducts().length === 0) {

            return;
        }
        var customer = currentCustomer.toJSON();

        customer = JSON.stringify(customer);

        localStorage.setItem("receiptCustomer", customer);




        window.open(
            '/cashier/receipt',
            '_blank' // <- This is what makes it open in a new window.
        );


    });



    $("#registerForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 2
            },
            surname: {
                required: true,
                minlength: 2
            },
            email: {
                required: true,
                email: true
            },
            crole: "required",
            ccomment: {
                required: true,
                minlength: 15
            },
            cgender: "required",
            cagree: "required",
        },
        //For custom messages
        messages: {
            name: {
                required: "Lisää nimi",
                minlength: "Lisää vähintään 2 kirjaintä"
            },
            email: "Virhe sähköpostissasi",
            surname: {
                required: "Lisää sukunimi",
                minlength: "Lisää vähintään 2 kirjaintä"
            }

        },
        errorElement: 'div',
        errorPlacement: function(error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        }
    });

    $("#registerForm").validate();


    $(".newSale").click(function() {

        currentCustomer.addToCart(getNewProduct(prodIndex));
        prodIndex++;

    })


    $(".shipment").click(function() {



        if (currentCustomer.currentCart().getProducts().length === 0) {

            return;
        }

        localStorage.setItem("shippedCustomer", JSON.stringify(currentCustomer));

        window.open(
            '/cashier/shipment',
            '_blank' // <- This is what makes it open in a new window.
        );

    })



    //resource.prepareCache();

    //End of code
});
