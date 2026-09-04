
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>TILAUSVAHVISTUS</title>
    <link rel="stylesheet" media="all" href="/templates/assets/css/invoice.css">
    
    <link rel="license" href="http://www.opensource.org/licenses/mit-license/">
</head>

<body>

<button class="print">Tulosta</button>
<header>
    <h1 class="headline">{{ Setting::get('store_name') }}</h1>
    <h5 class="subHeadline">{{ Setting::get('store_motto') }}</h5>
    <address class="seller">
        <p>{{ Setting::get('store_name') }}</p>
        <p>{{ Setting::get('store_address_1') }}
            <br> {{ Setting::get('store_address_2') }}
            <br> {{ Setting::get('store_postal_code') }} {{ Setting::get('store_state') }}
            <br> Puh. 09-753 1022
            <br> Ytunnus: {{ Setting::get('store_taxid') }}
        </p>
    </address>
    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG( (string) $proforma->id, "C128", 3, 20) }}" alt="barcode" style="float:right; overflow: hidden; clear: both;" />
    <h1 style="float: left;  letter-spacing: 0em; ">TILAUSVAHVISTUS</h1>
</header>
<article>
    <div>
        <h1>Recipient</h1>
        <address>
            <p>
                <br> {{ $proforma->customer->company }}
                <br> {{ $proforma->customer->street1 }}
                <br>{{$proforma->customer->street2}}
                <br>{{ $proforma->customer->zipcode }} {{ $proforma->customer->city }}
                <br>{{ $proforma->customer->state }} {{ $proforma->customer->country }}
                <br> Ytunnus:{{ $proforma->customer->vatid }}
            </p>
        </address>

         <?php $foreign = Setting::get('store_laskuDestination') ?>
        <table class="info receiver" style="border-style:solid; border-color: black; border-width: 1px; float: right; width: 40%">
            <tbody>
            <tr>

                <td>{{ trans('messages.Bill Number') }}: <span>{{ $proforma->id }}</span></td>
                 @if ($foreign )
                <td>{{ trans('messages.Due') }}: <span>{{ $proforma->totalWithoutTaxes }}</span></td>
                @else
                <td>{{ trans('messages.Due') }}: <span>{{ $proforma->total }}</span></td>
                 @endif
            </tr>
            <tr>
                <td>{{ trans('messages.Customer Number')}}: <span>{{ $proforma->customer->id }}</span></td>
                <td>{{ trans('messages.Due Time') }}: <span>3 vrk</span></td>
            </tr>
            <tr>
                <td>{{ trans('messages.Date') }}:<span>{{ $proforma->created_at->format('d-m-Y')  }}</span></td>
                <td style="font-weight: bold">{{ trans('messages.Interest on late payment') }}: <span>{{ Setting::get('late_payment_percent') }} %</span></td>
            </tr>
            <tr>
                 
                  <td>{{ trans('messages.Valid') }}: <span>{{ Setting::get('valid') }}  </span></td>
                <td style="font-weight: bold">{{ trans('messages.Payment reminder') }}:<span>{{ Setting::get('reminder_fee') }} €</span></td>
            </tr>

            <tr>
               
               
            </tr>
            </tbody>
        </table>
    </div>
    <div>
     <?php
      $index = 0;
      $length = count($proforma->products);
      $productEnded = false;
      $limit = 29;

      ?>
    @while (!$productEnded)
    <?php 
        if ($index >0) {
            $limit = 55;
        }
    ?>
        <table class="inventory">
            <thead>
                    <tr>
                        <th style="width: 4%">{{ trans('messages.SKU') }}</th>
                        <th style="width: 30%">{{ trans('messages.Product Name') }}</th>
                        
                        <th>{{ trans('messages.Qty') }}</th>
                        <th>{{ trans('messages.Qty Per Pack') }}</th>
                        <th>{{ trans('messages.Total Qty') }}</th>
                        <th>{{ trans('messages.Unit Price without taxes') }}</th>
                              @if ( !$foreign ) 
                        <th>{{ trans('messages.Unit Price with taxes') }}</th>
                        <th>{{ trans('messages.Tax Percent') }}</th>
                        @endif
                        <th>{{ trans('messages.Total without taxes') }}</th>
                       
                        @if ( !$foreign ) 
                        <th>{{ trans('messages.Total Taxes') }}</th>
                        <th>{{ trans('messages.Total Price') }}</th>
                        @endif
                    </tr>
            </thead>
            <tbody>
            @for ($i = 0; $i < $limit ; $i++)
            
            <?php

            if ($index == $length ) {
                    $productEnded = true;
                     break;    /* You could also write 'break 1;' here. */
                }  
            $product = $proforma->products[$i];
            $index++;
            $quantity = $product->pivot->quantity;
            $taxPercent = $product->pivot->taxPercent;
            $priceEach = $product->pivot->priceEach;
            $taxedPriceEach = $product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100;
            $totalNonTaxed = $product->pivot->priceEach * $product->pivot->quantity;
            
            $totalTaxAmout = $totalNonTaxed * ($product->pivot->taxPercent / 100);
            $totalTaxed =  $totalNonTaxed + $totalTaxAmout;      

            $totalTaxAmout =  number_format($totalTaxAmout,2,'.','' );
            $totalNonTaxed =  number_format($totalNonTaxed,2,'.','' );
            $taxedPriceEach =  number_format($taxedPriceEach,2,'.','' );
            $priceEach =  number_format($priceEach,2,'.','' );
            $totalTaxed =  number_format($totalTaxed,2,'.','' );




            
            ?>
                <tr>
                        <td>{{ $product->id }}</td>
                        <td><span style="float:left">{{ $product->name }}</span></td>                
                        <td><span>{{ number_format($product->pivot->quantity / $product->qtyPerPack) }}</span></td>
                        <td><span>{{ $product->qtyPerPack }}</span></td>
                        <td><span>{{ $quantity }} </span></td>
                        <td><span>{{ $priceEach }} </span></td>
                        @if ( !$foreign ) 
                        <td><span>{{  $taxedPriceEach }} </span></td>                      
                        <td><span>{{ $taxPercent }}%</span></td>
                        @endif
                        <td><span>{{ $totalNonTaxed }}</span></td>
                         @if ( !$foreign ) 
                        <td><span>{{  $totalTaxAmout   }}</span></td>
                        <td><span>{{  $totalTaxed  }}</span></td>
                        @endif
                    </tr>
            @endfor
            </tbody>
        </table>

        @endwhile
    </div>

    <table class="balance" style="width: 30% !important;
    float: right!important;"">

        <tr>
            <th><span>{{ trans('messages.Order') }} {{ trans('messages.Nontaxed') }}</span></th>
            <td><span data-prefix>€</span><span contenteditable>{{ $proforma->totalWithoutTaxes }}</span></td>
        </tr>
          @if ( !$foreign ) 
        <tr>
            <th><span>{{ trans('messages.Tax') }}</span></th>
            <td><span data-prefix>€</span><span contenteditable>{{ $proforma->taxTotal }}</span></td>
        </tr>

         <tr>
            <th><span>{{ trans('messages.Order') }} {{ trans('messages.Total') }}</span></th>
            <td><span data-prefix>€</span><span contenteditable>{{ $proforma->total }}</span></td>
        </tr>
@endif
    </table>
</article>
<table class="receiver" style="border-width: 1px;border-top-style: solid;border-top-color: black">
        <tbody>
            <tr>
                <td>{{ Setting::get('store_name') }}</td>
                <td>Ytunnus: {{ Setting::get('store_vatid') }}</td>
                <td>Sähköposti: {{ Setting::get('store_email') }}</td>
            </tr>
            <tr>
                <td>{{ Setting::get('store_address_1') }}</td>
                <td>IBAN: {{ Setting::get('store_iban') }}</td>
                <td>TAX: {{ Setting::get('store_taxid') }} </td>
            </tr>
            <tr>
                <td>{{ Setting::get('store_postal_code') }} {{ Setting::get('store_state') }}</td>
                <td>{{ Setting::get('store_link') }}</td>
                <td>Puhelin:{{ Setting::get('store_mobile') }}</td>
                <td>BIC:{{ Setting::get('store_bic') }}</td>
            </tr>
        </tbody>
</table>

</body>  
  <script type="text/javascript" src="/assets/js/jquery-2.1.1.min.js">
    </script>
      <script type="text/javascript" src="../templates/">
    </script>
<script type="text/javascript">


   //        $("body").click(function() {


   //  var proformaID= localStorage.getItem("proformaID");

   // window.location  = "/api/v3/proformas/" + proformaID + "/generatePDF";

   //    })

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

    // $(".download").click(function () {

    //       var specialElementHandlers = {
    //     '#editor': function (element,renderer) {
    //         return true;
    //     }
    // };
    //      var doc = new jsPDF();
    //     doc.fromHTML($('#target').html(), 15, 15, {
    //         'width': 170,'elementHandlers': specialElementHandlers
    //     });
    //     doc.save('proforma.pdf');


    // }) 
</script>
</html>
