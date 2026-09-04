<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>PALAUTUS</title>
    <link rel="stylesheet" href="/templates/assets/css/invoice.css">
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
        <h1 style="float: left;  letter-spacing: 0em; ">PALAUTUS</h1>
        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG((string)$return->id, "C128", 3, 20) }}" alt="barcode" style="float:right; overflow: hidden; clear: both;" />
    </header>
    <article>
        <div>
            <h1>Recipient</h1>
            <address>
                <p>
                    <br> {{ $return->customer->company }}
                    <br> {{ $return->customer->street1 . ' ' . $return->customer->street2 }}
                    <br>{{ $return->customer->zipcode }} {{ $return->customer->city }}
                    <br>{{ $return->customer->state }} {{ $return->customer->country }}
                    <br> Ytunnus:{{ $return->customer->vatid }}
                </p>
            </address>
            <!--             <table class="meta">
            <tr>
                <th><span >Invoice #</span></th>
                <td><span >101138</span></td>
            </tr>
            <tr>
                <th><span >Date</span></th>
                <td><span contenteditable>January 1, 2012</span></td>
            </tr>
            <tr>
                <th><span >Amount Due</span></th>
                <td><span contenteditable>600.00</span></td>
            </tr>
        </table> -->
         <?php $foreign = Setting::get('store_laskuDestination') ?>
            <table class="info receiver" style="border-style:solid; border-color: black; border-width: 1px; float: right; width: 40%">
                <tbody>
                    <tr>
                        <td>Palautus numero: <span>{{ $return->id }}</span></td>
                         @if ($foreign )
                         <td>Palautettu: <span>{{ $return->totalWithoutTaxes }}€</span></td>
                          @else
                        <td>Palautettu: <span>{{ $return->total }}€</span></td>
                         @endif
                    </tr>
                    <tr>
                        <td>Asiakasnumero: <span>{{ $return->customer_id }}</span></td>
                        <td>Päivämäärä:<span>{{ $return->created_at->format('d-m-Y')  }}</span></td>
                    </tr>
                    <tr>
                        <td>Kotipaikka: Helsinki</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <div>
     <?php
      $index = 0;
      $length = count($return->products);
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
            $product = $return->products[$i];
            $index++;
            $quantity = $product->pivot->quantity;
            $taxPercent = $product->pivot->taxPercent;
            $priceEach = $product->pivot->priceEach;
            $taxedPriceEach = $product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100;
            $totalNonTaxed = $product->pivot->priceEach * $product->pivot->quantity;
            
            $totalTaxAmout = $totalNonTaxed * ($product->pivot->taxPercent/ 100);
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

        <table class="balance">
            <tr>
                <th><span>Yhteensä: </span></th>
                 @if ( !$foreign ) 
                <td><span data-prefix>€ </span><span>-{{ $return->total}}</span></td>
                @else
                <td><span data-prefix>€</span><span>{{ $return->totalWithoutTaxes}}</span></td>
                @endif
            </tr>
            <tr>
                <th><span>Takaisin: </span></th>
                        @if ( !$foreign ) 
                <td><span data-prefix>€</span><span>{{ $return->total}}</span></td>

                @else
                <td><span data-prefix>€</span><span>{{ $return->totalWithoutTaxes}}</span></td>
                @endif
            
            </tr>
                <tr>
                @if ( !$foreign ) 
                <th><span>Vero: </span></th>

                <td><span data-prefix>€</span><span>{{ $return->taxTotal}}</span></td>
                @endif
            
            </tr>
        </table>
    </article>
    <table class="receiver" style="border-width: 1px;border-top-style: solid;border-top-color: black">
       <tbody>
            <tr>
                <td>{{ Setting::get('store_name') }}</td>
                <td>Ytunnus: {{ Setting::get('store_vatid') }}</td>
                <td>Sähköposti: {{ Setting::get('store_email') }}</td>
                <td>Viitenumero: </td>
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
    </table>
</body>
<script type="text/javascript" src="/assets/js/jquery-2.1.1.min.js">
</script>
<script type="text/javascript" src="/templates/return.js"></script>

</html>
