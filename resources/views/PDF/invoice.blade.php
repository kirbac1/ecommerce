<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <link rel="stylesheet" href="/templates/assets/css/invoice.css">
    <link rel="license" href="http://www.opensource.org/licenses/mit-license/">
</head>


<body>
<button class="print" style="display:none"></button>
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
        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG((string)$invoice->id, "C128", 3, 20) }}" alt="barcode" style="float:right; overflow: hidden; clear: both;" />
        <h1 style="float: left;  letter-spacing: 0em; font-size:20px;">LASKU</h1>
    </header>
    <article>
        <div>
            <h1>Recipient</h1>
            <address>
                <p>
                    <br> {{ $invoice->customer->company }}
                    <br> {{ $invoice->customer->street1}}
                    <br>{{$invoice->customer->street2}}
                    <br>{{ $invoice->customer->zipcode }} {{ $invoice->customer->city }}
                    <br>{{ $invoice->customer->state }} {{ $invoice->customer->country }}
                    <br> Ytunnus:{{ $invoice->customer->vatid }}
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
                        <td>{{ trans('messages.Invoice ID') }}: <span>{{ $invoice->id }}</span></td>
                        @if ($foreign )
                        <td>{{ trans('messages.Due') }}: <span>{{ $invoice->totalWithoutTaxes }}</span></td>
                        @else 
                        <td>{{ trans('messages.Due') }}: <span>{{ $invoice->total }}</span></td>
                        @endif
                    </tr>
                    <tr>
                        <td>{{ trans('messages.Customer Number')}}: <span>{{ $invoice->customer->id }}</span></td>
                        <td>{{ trans('messages.Due Time') }}: <span>3 vrk</span></td>
                    </tr>
                    <tr>
                        <td>{{ trans('messages.Date') }}:<span>{{ $invoice->created_at->format('d-m-Y')  }}</span></td>
                        <td style="font-weight: bold">{{ trans('messages.Interest on late payment') }}: <span>{{ Setting::get('late_payment_percent') }} %</span></td>
                    </tr>
                    <tr>
                        <td>{{ trans('messages.Payment terms') }}:<span>{{ Setting::get('due_date') }} </span></td>
                        <td style="font-weight: bold">{{ trans('messages.Payment reminder') }}:<span>{{ Setting::get('reminder_fee') }} €</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold">{{ trans('messages.Due Date') }}: <span >{{ $invoice->dueDate  }}</span></td>
                        <td>{{ trans('messages.Domicile') }}: Helsinki</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
     <?php
      $index = 0;
      $length = count($invoice->products);
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
            $product = $invoice->products[$i];
            $index++;
            $quantity = $product->pivot->quantity;
            $taxPercent = $product->pivot->taxPercent;
            $priceEach = $product->pivot->priceEach;
            $taxedPriceEach = $product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100;
            $totalNonTaxed = $product->pivot->priceEach * $product->pivot->quantity;
            
            $totalTaxAmout = $totalNonTaxed * ( $product->pivot->taxPercent / 100);

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


        <table class="balance" style="float:right">
            <tr>
                <th><span>{{ trans('messages.Nontaxed') }}:</span></th>
                <td><span data-prefix></span><span>&euro; {{ $invoice->totalWithoutTaxes }} </span></td>
            </tr>
              @if ( !$foreign ) 
            <tr>
                <th><span>{{ trans('messages.Tax') }}: </span></th>
                <td><span data-prefix>&euro;</span><span>{{ $invoice->taxTotal }}</span></td>
            </tr>
            <tr>
                <th><span>{{ trans('messages.Total') }} : </span></th>
                <td><span data-prefix>&euro;</span><span contenteditable>{{ $invoice->total }}</span></td>
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
                <td>Viitenumero: {{ $invoice->referenceNumber }}</td>
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
    <!-- <div class="page">
    <div class="content">
    </div>
    <div class="invoice">
        <label class="invoice--label">
            TILISIIRTO
        </label>
            <span class="invoice--terms">
                The payment will be cleared for the recipient in accordance with the
                General terms for payment transmission and only on the basis of the
                account number given by the payer.
            </span>
        <div class="recipient-bank">
            <label class="recipient-bank--label">
                <span>Saajan tilinumero</span>
            </label>
            <label class="recipient-bank--iban--label">
                IBAN
            </label>
            <span class="recipient-bank--iban">
                <ul class="recipient-bank--iban--list">
                    <li>
                        <span class="recipient-bank--iban--list--bank">
                            SAMPO PANKKI
                        </span>
                        <span class="recipient-bank--iban--list--number">
                            FI16 8000 1400 0502 67
                        </span>
                    </li>
                    <li>
                        <span class="recipient-bank--iban--list--bank">
                            NORDEA
                        </span>
                        <span class="recipient-bank--iban--list--number">
                            FI00 2001 3000 0012 34
                        </span>
                    </li>
                    <li>
                        <span class="recipient-bank--iban--list--bank">
                            OP
                        </span>
                        <span class="recipient-bank--iban--list--number">
                            FI21 5234 5600 0007 85
                        </span>
                    </li>
                    <li>
                        <span class="recipient-bank--iban--list--bank">
                            AKTIA
                        </span>
                        <span class="recipient-bank--iban--list--number">
                            FI16 8000 1400 0502 67
                        </span>
                    </li>
                </ul>
            </span>
            <label class="recipient-bank--bic--label">
                BIC
            </label>
            <span class="recipient-bank--bic">
                <ul class="recipient-bank--bic--list">
                    <li>DABAFIHH</li>
                    <li>NDEAFIHH</li>
                    <li>OKOYFIHH</li>
                    <li>HELSFIHH</li>
                </ul>
            </span>
        </div>
        <div class="recipient">
            <label class="recipient--label">
                <span>{{ trans('messages.Recipient') }}</span>
            </label>
            <span class="recipient--name">
                OY YRITYS AB<br>
                YRITYSKUJA 12, 4. KRS<br>
                12345 KAUPUNKI<br>
            </span>
        </div>
        <div class="payer">
            <label class="payer--personal--label">
                <span>Maksajan nimi ja osoite</span>
            </label>
            <span class="payer--personal">
                {{ $invoice->customer->name . ' ' . $invoice->customer->surname}}, {{ $invoice->customer->company }}<br>
                {{ $invoice->customer->street1 }} {{ $invoice->customer->street2}}<br>
                {{ $invoice->customer->zipcode }} {{ $invoice->customer->city }}<br>
                {{ $invoice->customer->state }} {{ $invoice->customer->country }}
            </span>
            <label class="payer--signature--label">
                <span>
                    Allekirjoitus
                </span>
            </label>
            <span class="payer--signature"></span>
            <label class="payer--from-account--label">
                <span>Tililtä nro</span>
            </label>
            <span class="payer--from-account">123456-7890</span>
        </div>
        <div class="payment">
            <span class="payment--info">
                Osuuteni itsenäisyyspäivän lounaasta. t. Maijа
            </span>
            <label class="payment--reference--label">
                <span>Viitenro </span>
            </label>
            <span class="payment--reference">
                {{ $invoice->id }}
            </span>
            <label class="payment--due--label">
                Eräpäivä
            </label>
            <span class="payment--due">
                30.11.2010
            </span>
            <label class="payment--amount--label">
                Euro
            </label>
            <span class="payment--amount">
                &euro;{{ $invoice->total }}
            </span>
        </div>
        <div class="barcode">
        </div>
    </div>
</div> -->
</body>
<script type="text/javascript" src="/assets/js/jquery-2.1.1.min.js">
</script>
<script type="text/javascript">

$("body").click(function() {


    var invoiceID = localStorage.getItem("invoiceID");

    window.location = "/api/v3/invoices/" + invoiceID + "/generatePDF";

})
</script>

</html>
