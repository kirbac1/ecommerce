  class Cart {


      constructor(cart) {
          if(cart === undefined || cart === null){

             this.products = [];
              this.nonTaxedSum = 0;
              this.taxedPriceSum = 0 ;
              this.taxSum =   0;
              this.totalDiscount = 0 ;
              this.isCurrentCart =  true ;            
          }else{ 
              this.products = cart.products;
              this.nonTaxedSum = cart.nonTaxedSum;
              this.taxedPriceSum = cart.taxedPriceSum ;
              this.taxSum =   cart.taxSum ;
              this.totalDiscount = cart.totalDiscount ;
              this.isCurrentCart =  true ;
            }
          }
          // necessary if customer has multiple carts at the same time
      isCurrentCart() {
          return this.isCurrentCart;
      }

      isEmpty() {
          return (this.products.length === 0);
      }
      editProduct(value, obj) {

          var id = $(obj).closest("li").attr("id");
          var debug = '#' + id;
          var product = $(debug).data('product');

          if ($(obj).hasClass("quantity")) {

              var koli = $(obj).closest("li").find(".koli").text();

              if (!isNaN(value) && value.length != 0) {
                  value = parseFloat(value);
                  var totalQty = value * parseFloat(koli);

                  // Calculate the price without tax
                  var priceEach = $(obj).closest("li").find(".priceEach").text();
                  priceEach = parseFloat(priceEach);
                  var nonTaxedSum = totalQty * priceEach;
                  nonTaxedSum = nonTaxedSum.toFixed(4);

                  // Calculate the price with tax
                  var taxedPrice = $(obj).closest("li").find(".taxedPrice").text();
                  taxedPrice = parseFloat(taxedPrice);
                  var taxedPriceSum = totalQty * taxedPrice;
                  taxedPriceSum = taxedPriceSum.toFixed(4);

                  // calculate the total amount of tax to be paid 
                  var taxSum = taxedPriceSum - nonTaxedSum;
                  taxSum = taxSum.toFixed(4);
                  $(obj).closest("li").find(".totalQty").text(totalQty);
                  $(obj).closest("li").find(".taxedPriceSum").text(taxedPriceSum);
                  $(obj).closest("li").find(".quantity").text(value);
                  $(obj).closest("li").find(".taxSum").text(taxSum);
                  $(obj).closest("li").find(".nonTaxedSum").text(nonTaxedSum);


                  product.taxSum = taxSum;
                  product.nonTaxedSum = nonTaxedSum;
                  product.taxedPriceSum = taxedPriceSum;
                  product.totalQty = totalQty;
                  product.quantity = value;

              }

          }
          // unit price without tax has changed 
          else if ($(obj).hasClass("priceEach")) {

              var totalQty = $(obj).closest("li").find(".totalQty").text();
              var taxPercent = $(obj).closest("li").find(".taxPercent").text();

              if (!isNaN(value) && value.length != 0) {
                  // price without tax
                  value = parseFloat(value);
                  totalQty = parseFloat(totalQty);
                  taxPercent = parseFloat(taxPercent);

                  var nonTaxedSum = totalQty * value;
                  nonTaxedSum = nonTaxedSum.toFixed(4)

                  // Calculate the price with tax
                  var taxedPrice = value + value * (taxPercent / 100);

                  taxedPrice = taxedPrice.toFixed(4);
                  // calculate the total price without tax 


                  var taxedPriceSum = totalQty * taxedPrice;
                  taxedPriceSum = taxedPriceSum.toFixed(4);
                  var taxSum = parseFloat(taxedPriceSum) - parseFloat(nonTaxedSum);
                  taxSum = taxSum.toFixed(4);
                  $(obj).closest("li").find(".priceEach").text(value);
                  $(obj).closest("li").find(".taxedPriceSum").text(taxedPriceSum);
                  $(obj).closest("li").find(".taxedPrice").text(taxedPrice);
                  $(obj).closest("li").find(".taxSum").text(taxSum);
                  $(obj).closest("li").find(".nonTaxedSum").text(nonTaxedSum);

                  product.taxedPrice = taxedPrice;
                  product.nonTaxedSum = nonTaxedSum;
                  product.taxedPriceSum = taxedPriceSum;
                  product.priceEach = value;
                  product.taxSum = taxSum;


              }
          } else if ($(obj).hasClass("totalQty")) {

              var koli = $(obj).closest("li").find(".koli").text();

              // TODO : check again for kg and litre

              koli = parseFloat(koli);

              if (!isNaN(value) && value.length != 0) {
                  // price without tax
                  value = parseFloat(value);

                  // Calculate the price without tax
                  var priceEach = $(obj).closest("li").find(".priceEach").text();
                  priceEach = parseFloat(priceEach);
                  var nonTaxedSum = value * priceEach;
                  nonTaxedSum = nonTaxedSum.toFixed(4);

                  // Calculate the price with tax
                  var taxedPrice = $(obj).closest("li").find(".taxedPrice").text();
                  taxedPrice = parseFloat(taxedPrice);
                  var taxedPriceSum = value * taxedPrice;
                  taxedPriceSum = taxedPriceSum.toFixed(4);

                  // calculate the total amount of tax to be paid 
                  var taxSum = taxedPriceSum - nonTaxedSum;
                  taxSum = taxSum.toFixed(4);
                  $(obj).closest("li").find(".totalQty").text(value);
                  $(obj).closest("li").find(".taxedPriceSum").text(taxedPriceSum);
                  $(obj).closest("li").find(".nonTaxedSum").text(nonTaxedSum);
                  $(obj).closest("li").find(".taxSum").text(taxSum);

                  if (value % koli === 0) {
                      var qty = value / koli;
                      $(obj).closest("li").find(".quantity").text(qty);
                  } else {
                      $(obj).closest("li").find(".quantity").text("N/A");
                  }

                  // 
                  product.totalQty = value;
                  product.nonTaxedSum = nonTaxedSum;
                  product.taxedPriceSum = taxedPriceSum;
                  product.taxSum = taxSum;
              }


          } else if ($(obj).hasClass("nonTaxedSum")) {

              value = parseFloat(value);
              var totalQty = $(obj).closest("li").find(".totalQty").text();
              totalQty = parseFloat(totalQty);
              var priceEach = $(obj).closest("li").find(".priceEach").text();
              priceEach = parseFloat(priceEach);
              var nonTaxedSum = totalQty * priceEach;

              nonTaxedSum = nonTaxedSum.toFixed(4);

              var discount = nonTaxedSum - value;

              var taxPercent = $(obj).closest("li").find(".taxPercent").text();

              // Calculate the price with tax
              var taxedPriceSum = value + value * (taxPercent / 100);

              var taxSum = taxedPriceSum - nonTaxedSum;

              taxSum = taxSum.toFixed(4);
              taxedPriceSum = taxedPriceSum.toFixed(4);

              $(obj).closest("li").find(".taxedPriceSum").text(taxedPriceSum);
              $(obj).closest("li").find(".taxedPriceSum").text(taxedPriceSum);
              $(obj).closest("li").find(".nonTaxedSum").text(value);
              $(obj).closest("li").find(".taxSum").text(taxSum);

              product.totalQty = value;
              product.nonTaxedSum = nonTaxedSum;
              product.taxedPriceSum = taxedPriceSum;
              product.taxSum = taxSum;
              product.discount = discount;

          }  else if ($(obj).hasClass("taxedPriceSum")) {
              var taxPercent = $(obj).closest("li").find(".taxPercent").text();
              taxPercent = parseFloat(taxPercent);
              value = parseFloat(value);
              var nonTaxedSum = (value / (1+ (taxPercent / 100))) ;

              nonTaxedSum = nonTaxedSum.toFixed(4);
              var taxSum = value - nonTaxedSum;

              var totalQty = $(obj).closest("li").find(".totalQty").text();
              totalQty = parseFloat(totalQty);
              

              var priceEach = nonTaxedSum / totalQty;
              priceEach = priceEach.toFixed(4);

              var taxedPrice = value / totalQty;
              taxedPrice = taxedPrice.toFixed(4);



              $(obj).closest("li").find(".taxedPriceSum").text(value);
              $(obj).closest("li").find(".priceEach").text(priceEach);
              $(obj).closest("li").find(".taxedPrice").text(taxedPrice);
              $(obj).closest("li").find(".nonTaxedSum").text(nonTaxedSum);
              $(obj).closest("li").find(".taxSum").text(taxSum);

              product.priceEach = priceEach;
              product.taxedPrice = taxedPrice;
              product.totalQty = totalQty;
              product.nonTaxedSum = nonTaxedSum;
              product.taxedPriceSum = value;
              product.taxSum = taxSum;
              product.discount = discount;

          }else if ($(obj).hasClass("taxedPrice")) {

              value = parseFloat(value);

              var taxPercent = $(obj).closest("li").find(".taxPercent").text();

              taxPercent = parseFloat(taxPercent);
              var priceEach = value / (1 + (taxPercent / 100));

              priceEach = priceEach.toFixed(4);
              var totalQty = $(obj).closest("li").find(".totalQty").text();
              totalQty = parseFloat(totalQty);


              var taxedPriceSum = totalQty * value;
              taxedPriceSum = taxedPriceSum.toFixed(4);


              // Calculate the price with tax
              nonTaxedSum = priceEach * totalQty;

              var taxSum = taxedPriceSum - nonTaxedSum;

              taxSum = taxSum.toFixed(4);
              nonTaxedSum = nonTaxedSum.toFixed(4);

              $(obj).closest("li").find(".taxedPriceSum").text(taxedPriceSum);
              $(obj).closest("li").find(".nonTaxedSum").text(nonTaxedSum);
              $(obj).closest("li").find(".priceEach").text(priceEach);
              $(obj).closest("li").find(".taxedPrice").text(value);

              $(obj).closest("li").find(".taxSum").text(taxSum);
              product.priceEach = priceEach;
              product.taxedPrice = value;
              product.nonTaxedSum = nonTaxedSum;
              product.taxedPriceSum = taxedPriceSum;
              product.taxSum = taxSum;
              product.discount = discount;
          } else if ($(obj).hasClass("name")) {

              product.name = value;

          }


          $('#' + id + '_cart').data('product', product);
          this.updateCart(product);
      }

      removeFromCartArray(id) {


          for (var i = 0; i < this.products.length; i++) {


              if (id == this.products[i].id) {

                  this.products.splice(i, 1);

              }
          }
          this.updateCartSum();
      }
      updateCart(product) {
          var id = product.id;

          for (var i = 0; i < this.products.length; i++) {

              if (id == this.products[i].id) {

                  this.products[i] = product;
                  this.updateCartSum();
                  return;

              }
          }
      }

      updateCartSum() {

          this.nonTaxedSum = 0;
          this.taxedPriceSum = 0;
          this.taxSum = 0;
          this.totalDiscount = 0;
          //get the sum of all the products in cart
          for (var i = 0; i < this.products.length; i++) {

              var nonTaxedSum = parseFloat(this.products[i].nonTaxedSum);

              this.nonTaxedSum += nonTaxedSum;
              this.taxedPriceSum += parseFloat(this.products[i].taxedPriceSum);
              this.taxSum += parseFloat(this.products[i].taxSum);
              this.totalDiscount += parseFloat(this.products[i].discount);
          }


          //write it to total sum 
          $(".totalTaxed").text((this.taxedPriceSum).toFixed(4));

          //write it to total tax 
          $(".totalTax").text((this.taxSum).toFixed(4));
          //write it to total discount
          $(".totalDiscount").text((this.totalDiscount).toFixed(4));

          //write it to total non taxed  
          $(".totalNonTaxed").text((this.nonTaxedSum).toFixed(4));

      }


      isExist(id) {


          for (var i = 0; i < this.products.length; i++) {


              if (id == this.products[i].id) {

                  return true;

              }
          }
          return false;


      }


      addQuantity(productData) {

          var id = productData.id;
          var quantity = $("#" + id + "_cart");
          var val = quantity.find(".quantity").text();

          if (val === "N/A") {

              val = parseFloat(quantity.find(".totalQty").text());
              val = val + parseFloat(productData.qtyPerPack);

              this.editProduct(val, quantity.find("div.totalQty"));
          } else {
              val = parseFloat(quantity.find(".quantity").text());
              val++;

              this.editProduct(val, quantity.find("div.quantity"));
          }

      }

      add(productData) {
          var id = productData.id;

          if (this.isExist(id) && this.products.length != 0) {
              // already in the cart, only add the quantity 
              this.addQuantity(productData);
              return;
          }


          var name = productData.name;
          var koli = productData.qtyPerPack;
          var priceEach = parseFloat(productData.priceEach);
          var taxedPrice = parseFloat(productData.taxedPrice);
          var taxPercent = productData.taxPercent;
          var tax = productData.taxAmount;

          var quantity = 1;
          productData.quantity = 1;
          // total number of items
          var totalQty = quantity * parseFloat(koli);
          productData.totalQty = totalQty;

          // calculate total amount to be paid with tax
          var taxedPriceSum = totalQty * taxedPrice;
          taxedPriceSum = taxedPriceSum.toFixed(4);
          productData.taxedPriceSum = taxedPriceSum;

          // calculate total amount to be paid without tax
          var nonTaxedSum = totalQty * priceEach;
          nonTaxedSum = nonTaxedSum.toFixed(4);
          productData.nonTaxedSum = nonTaxedSum;

          // total amount of tax to be paid
          var taxSum = taxedPriceSum - nonTaxedSum;
          taxSum = taxSum.toFixed(4);
          productData.taxSum = taxSum;


          var product = '<li id="' + id + '_cart" class="orderline active">' + '<div class="row">' +
              '<div class="col id s1">' + id + '</div>' +
              '<div class="name col s1">' + name + '</div>' +
              '<div class="priceEach edit col s1">' + priceEach + '</div>' +
              '<div class="edit taxedPrice col s1">' + taxedPrice + '</div>' +
              '<div class="koli col s1">' + koli + '</div>' +
              '<div class="quantity edit col s1">' + quantity + '</div>' +
              '<div class="totalQty edit col s1"> ' + totalQty + '</div>' +
              '<div class="taxPercent col s1">' + taxPercent + '</div>' +
              '<div class="nonTaxedSum col s1">' + nonTaxedSum + '</div>' +
              '<div class="taxSum col s1">' + taxSum + '</div>' +
              '<div style="" class="edit taxedPriceSum col s1">' + taxedPriceSum + '</div>' +
              '<div class="col s1" ><a  style="margin-left:30px;font-size:15px" class="removeProduct" href="">X</a></div>' +
              '</div></li>';

          // new product added
          this.products.push(productData);



          $('#productList').append(product);


          // for debugging
          var debug = '#' + id + '_cart';
          $(debug).data('product', productData);


          if ($('.order-empty').css('display') == 'block') {

              $(".order-empty").css("display", "none");
              $('.cart-full').css('display', 'block');
          }


          this.updateCartSum();
      }

      initCart() {


          $.each(this.products, function(i, productData) {

  
              var id = productData.id;
              var name = productData.name;
              var koli = productData.qtyPerPack;
              var priceEach = parseFloat(productData.priceEach);
              var taxedPrice = parseFloat(productData.taxedPrice);
              var taxPercent = productData.taxPercent;
              var tax = productData.taxAmount;

              var totalQty = productData.totalQty;;
              var quantity = productData.quantity;
              // total number of items
     

              // calculate total amount to be paid with tax
              var taxedPriceSum = totalQty * taxedPrice;
              taxedPriceSum = taxedPriceSum.toFixed(4);
  
              // calculate total amount to be paid without tax
              var nonTaxedSum = totalQty * priceEach;
              nonTaxedSum = nonTaxedSum.toFixed(4);

              // total amount of tax to be paid
              var taxSum = taxedPriceSum - nonTaxedSum;
              taxSum = taxSum.toFixed(4);

              var product = '<li id="' + id + '_cart" class="orderline active">' + '<div class="row">' +
                  '<div class="col id s1">' + id + '</div>' +
                  '<div class="edit name col s1">' + name + '</div>' +
                  '<div class="priceEach edit col s1">' + priceEach + '</div>' +
                  '<div class="edit taxedPrice col s1">' + taxedPrice + '</div>' +
                  '<div class="koli col s1">' + koli + '</div>' +
                  '<div class="quantity edit col s1">' + quantity + '</div>' +
                  '<div class="totalQty edit col s1"> ' + totalQty + '</div>' +
                  '<div class="taxPercent col s1">' + taxPercent + '</div>' +
                  '<div class="nonTaxedSum edit col s1">' + nonTaxedSum + '</div>' +
                  '<div class="taxSum col s1">' + taxSum + '</div>' +
                  '<div style="" class="taxedPriceSum col s1">' + taxedPriceSum + '</div>' +
                  '<div class="col s1" ><a  style="margin-left:30px;font-size:15px" class="removeProduct" href="">X</a></div>' +
                  '</div></li>';


              $('#productList').append(product);


              // for debugging
              var debug = '#' + id + '_cart';
              $(debug).data('product', productData);



          });


          if ($('.order-empty').css('display') == 'block') {

              $(".order-empty").css("display", "none");
              $('.cart-full').css('display', 'block');
          }
          this.updateCartSum();

      }

      getProducts() {

          return this.products;
      }

      toJSON() {

          var json = {};
          json.name = this.name;
          json.products = this.products;
          json.nonTaxedSum = this.nonTaxedSum;
          json.taxedPriceSum = this.taxedPriceSum;
          json.taxSum = this.taxSum;

          return json;

      }

  }
