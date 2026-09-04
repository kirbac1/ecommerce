  jQuery(document).ready(function() {

function foo(argument) {

      var d = new Date();

      var month = d.getMonth() + 1;
      var day = d.getDate();

      var output = (('' + day).length < 2 ? '0' : '') + day + '/' +
          (('' + month).length < 2 ? '0' : '') + month + '/' +
          d.getFullYear();

      $(".date").text(output);

      var returnCart = localStorage.getItem("returnCart");
      var customer = localStorage.getItem("returnCustomer");

      customer = JSON.parse(customer);
      returnCart = JSON.parse(returnCart);


      $(".company").text(customer.company);
      $(".street").text(customer.street1);
      $(".zipcode").text(customer.zipcode);
      $(".phone").text(customer.phone);
      $(".vatid").text(customer.vatid);
      $(".total").text((returnCart.totalTaxedAmount).toFixed(4));
      $(".tax").text((returnCart.totalTaxAmount).toFixed(4));
      $(".nonTaxed").text((returnCart.totalNonTaxedAmount).toFixed(4));


      $.each(returnCart.products, function(i, product) {

        var id = product.id;
          var name = product.name;
          var nonTaxedSum = product.totalWithoutTaxes;
          var taxedPriceSum = product.taxedPriceTotal;
          var taxSum = product.taxAmountTotal;

          var totalQty = product.qty;
          var taxedPrice = product.taxedPriceEach;

          var priceEach = product.priceEach;

          var taxPercent = product.taxPercent;



          var productTemplate = '<tr><td>' + id+ '</td>' +
              '<td><span style="float: left;">' + name + '</span></td>' +
              '<td><span>' + totalQty + '</span></td>' +
              '<td><span>' + priceEach + '</span></td>' +
              '<td><span>' + taxedPrice + '</span></td>' +
              '<td><span>' + taxPercent + '</span></td>' +
              '<td><span>' + nonTaxedSum + '</span></td>' +
              '<td><span>' + taxSum + '</span></td>' +
              '<td><span>' + taxedPriceSum + '</span></td></tr>';


          $('.inventory').find('tbody:last').append(productTemplate);

      });
        // body...
}

      $("body").click(function() {


  



               var returnID= localStorage.getItem("returnID");

          // if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) { // Chrome Browser Detected?
          //     window.PPClose = false; // Clear Close Flag
          //     window.onbeforeunload = function() { // Before Window Close Event
          //         if (window.PPClose === false) { // Close not OK?
          //             alert('Leaving this page will block the parent window!\nPlease select "Stay on this Page option" and use the\nCancel button instead to close the Print Preview Window.\n');
          //         }
          //     }
              
          // if (window.print()) {
          //     return false;
          // } else {

          //     location.reload();
          // }

          //     window.PPClose = true; // Set Close Flag to OK.
          // }
   window.location  = "/api/v3/returns/" + returnID + "/generatePDF";

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
