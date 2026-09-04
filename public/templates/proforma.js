  jQuery(document).ready(function() {


      var d = new Date();

      var month = d.getMonth() + 1;
      var day = d.getDate();

      var output = (('' + day).length < 2 ? '0' : '') + day + '/' +
          (('' + month).length < 2 ? '0' : '') + month + '/' +
          d.getFullYear();


      var numberOfDaysToAdd = 6;
      d.setDate(d.getDate() + numberOfDaysToAdd); 

        month = d.getMonth() + 1;
         day = d.getDate();

      var due = (('' + day).length < 2 ? '0' : '') + day + '/' +
          (('' + month).length < 2 ? '0' : '') + month + '/' +
          d.getFullYear();

      $(".date").text(output);
      $(".due").text(due);
      var customer = localStorage.getItem("proformaCustomer");

      customer = JSON.parse(customer);
      var order = customer.order;

      // get all the products of the current customer
      var products = order.products;


      $(".company").text(customer.company);
      $(".street").text(customer.street1);
      $(".zipcode").text(customer.zipcode);
      $(".phone").text(customer.phone);
      $(".vatid").text(customer.vatid);
      $(".total").text((order.taxedPriceSum).toFixed(2));
      $(".tax").text((order.taxSum).toFixed(2));
      $(".nonTaxed").text((order.nonTaxedSum).toFixed(2));


      $.each(products, function(i, product) {

        var id = product.id;
          var name = product.name;
          var nonTaxedSum = product.nonTaxedSum;
          var taxedPriceSum = product.taxedPriceSum;
          var taxSum = product.taxSum;

          var totalQty = product.totalQty;
          var qtyPerPack = product.qtyPerPack;
          var qty = product.quantity;
          var priceEach = product.priceEach;
          var taxedPrice = product.taxedPrice;
          if (taxedPrice ===undefined) {
            taxedPrice = priceEach * 1.14;
            taxedPrice = taxedPrice.toFixed(2);
          }

      

          var taxPercent = product.taxPercent;



          var productTemplate = '<tr><td>' + id+ '</td>' +
              '<td><span style="float: left;">' + name + '</span></td>' +
              '<td><span>' + qtyPerPack + '</span></td>' +
              '<td><span>' + qty + '</span></td>' +
              '<td><span>' + totalQty + '</span></td>'+ 
              '<td><span>' + priceEach + '</span></td>' +
              '<td><span>' + taxedPrice + '</span></td>' +
              '<td><span>' + taxPercent + '</span></td>' +
              '<td><span>' + nonTaxedSum + '</span></td>' +
              '<td><span>' + taxSum + '</span></td>' +
              '<td><span>' + taxedPriceSum + '</span></td>' ;

          $('.inventory').find('tbody:last').append(productTemplate);

      });

      $(".print").click(function() {


          if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) { // Chrome Browser Detected?
              window.PPClose = false; // Clear Close Flag
              window.onbeforeunload = function() { // Before Window Close Event
                  if (window.PPClose === false) { // Close not OK?
                      return 'Leaving this page will block the parent window!\nPlease select "Stay on this Page option" and use the\nCancel button instead to close the Print Preview Window.\n';
                  }
              }
              window.print(); // Print preview
              window.PPClose = true; // Set Close Flag to OK.
          }
      })

    //write the current cart to local storage for proforma
    $(document).on('click', '.proforma', function(e) {

        var currentCustomer = localStorage.getItem("proformaCustomer");

       currentCustomer = JSON.parse(currentCustomer);

        var order = currentCustomer.order;
        order.customer_id = currentCustomer.id;
        order.name = currentCustomer.name;
        order.company = currentCustomer.company;
        order.surname = currentCustomer.surname;
        order.email1 = currentCustomer.email;
        order.zipcode = currentCustomer.zipcode;
        order.vatid = currentCustomer.vatid;
        order.taxid = currentCustomer.taxid;
        order.street1 = currentCustomer.street1;
        order.city = currentCustomer.city;
        order.phone = currentCustomer.phone;
        order.country = currentCustomer.country;

        for (var i = 0; i < order.products.length; i++) {
          
            order.products[i].quantity = order.products[i].totalQty
        }


        $.ajax({
            url: "/api/v3/proformas",
            contentType: 'application/json',
            type: "POST",
            headers:{  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: JSON.stringify(order),

            dataType: "json",
            success: function(data) {
                        localStorage.setItem("proformaID", data.id);
                                        window.open(
                '/api/v3/proformas/'+data.id+'/renderItem',
                '_blank' // <- This is what makes it open in a new window.
            );

                }
        })


    });







  })
