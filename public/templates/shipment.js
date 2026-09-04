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
      var customer = localStorage.getItem("shippedCustomer");

      customer = JSON.parse(customer);
      var order = customer.order;

      // get all the products of the current customer
      var products = order.products;


      $(".company").text(customer.company);
      $(".street").text(customer.street1);
      $(".zipcode").text(customer.zipcode);
      $(".phone").text(customer.phone);
      $(".vatid").text(customer.vatid);
      $(".total").text((order.taxedPriceSum).toFixed(4));
      $(".tax").text((order.taxSum).toFixed(4));
      $(".nonTaxed").text((order.nonTaxedSum).toFixed(4));


      $.each(products, function(i, product) {

        var id = product.id;
          var name = product.name;
          var nonTaxedSum = product.nonTaxedSum;
          var taxedPriceSum = product.taxedPriceSum;
          var taxSum = product.taxSum;

          var totalQty = product.totalQty;
          var qtyPerPack = product.qtyPerPack;
          var qty = product.quantity;
          var taxedPrice = product.taxedPrice;

          var priceEach = product.priceEach;

          var taxPercent = product.taxPercent;



          var productTemplate = '<tr><td>' + id+ '</td>' +
              '<td><span style="float: left;">' + name + '</span></td>' +
              '<td><span>' + qtyPerPack + '</span></td>' +
              '<td><span>' + qty + '</span></td>' +
              '<td><span>' + totalQty + '</span></td>' ;


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


      $(".email").click(function() {




      })

      $(".cancel").click(function() {


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

      $(".createInvoice").click(function() {


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



  })
