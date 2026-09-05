
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>PALAUTUS</title>
    <link rel="stylesheet" href="/templates/assets/css/invoice.css">
    <link rel="license" href="http://www.opensource.org/licenses/mit-license/">
</head>

<body>
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
</header>
<article>
@if (! $return)
    {{-- Nothing to preview yet. Rendering the body without a return is what
         produced a page of blank fields on the old stack. --}}
    <div>
        <p>{{ trans('messages.no_returns_to_preview') }}</p>
    </div>
@else
    <div>
        <h1>Recipient</h1>
        <address>
            <p>{{ $return->customer->name . ' ' . $return->customer->surname }}
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
        <table class="info receiver" style="border-style:solid; border-color: black; border-width: 1px; float: right; width: 40%">
            <tbody>
            <tr>
                <td>Palautus numero: <span>{{ $return->id }}</span></td>
                <td>Palautettu: <span>{{ $return->total }}€</span></td>
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
        <table class="inventory">
            <thead>
            <tr>

                <th  style="width: 4%">{{ trans('messages.SKU') }}</th>
                <th style="width: 30%; " >{{ trans('messages.Product Name') }}</th>
                <th>{{ trans('messages.Qty Per Pack') }}</th>
                <th>{{ trans('messages.Qty') }}</th>
                <th>{{ trans('messages.Total Qty') }}</th>
                <th>{{ trans('messages.Unit Price without taxes') }}</th>
                <th>{{ trans('messages.Unit Price with taxes') }}</th>
                <th>{{ trans('messages.Tax Percent') }}</th>
                <th>{{ trans('messages.Total without taxes') }}</th>
                <th>{{ trans('messages.Total Taxes') }}</th>
                <th>{{ trans('messages.Total Price') }}</th>
            </tr>
            </thead>
            <tbody>
                @foreach($return->products as $index => $product)
                    <tr>
                        <td>{{  $product->id}}</td>
                        <td><span style="float:left">{{ $product->name }}</span></td>
                        <td><span>{{ $product->qtyPerPack }}</span></td>
                        <td><span>{{ round($product->pivot->quantity / $product->qtyPerPack) }}</span></td>
                        <td><span>{{ number_format($product->pivot->quantity, 2, ',', '') }}</span></td>
                        <td><span>{{ number_format($product->pivot->priceEach, 2, ',', '') }}</span></td>
                        <td><span>{{ number_format($product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100, 2, ',', '') }}</span></td>
                        <td><span>{{ number_format($product->pivot->taxPercent, 2, ',', '') }}%</span></td>
                        <td><span>{{ number_format($product->pivot->priceEach * $product->pivot->quantity, 2, ',', '') }}</span></td>
                        <td><span>{{ number_format($product->pivot->priceEach * $product->pivot->taxPercent * $product->pivot->quantity / 100, 2, ',', '') }}</span></td>
                        <td><span>{{ number_format($product->pivot->priceEach * (100 + $product->pivot->taxPercent) * $product->pivot->quantity / 100, 2, ',', '') }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <table class="balance">
        <tr>
            <th><span>Yhteensä</span></th>
            <td><span data-prefix>€</span><span>{{ $total }}</span></td>
        </tr>
        <tr>
            <th><span>Maksettu</span></th>
            <td><span data-prefix>€</span><span contenteditable>0.00</span></td>
        </tr>
        <tr>
            <th><span>Takaisin</span></th>
            <td><span data-prefix>€</span><span contenteditable>{{ $total }}</span></td>
        </tr>
    </table>
@endif
</article>
<table class="receiver" style="border-width: 1px;border-top-style: solid;border-top-color: black">
    <tbody>
        <tr>
            <td>{{ Setting::get('store_name') }}</td>
            <td>Ytunnus: {{ Setting::get('store_taxid') }}</td>
            <td>Sähköposti: {{ Setting::get('store_email') }}</td>
            <td>Tilinumero:</td>
            <td>Viitenumero:</td>
        </tr>
        <tr>
            <td>{{ Setting::get('store_address_2') }}</td>
            <td>Kotipaikka: {{ Setting::get('store_city') }}</td>
            <td>Puhelin:{{ Setting::get('store_telephone') }} </td>
            <td>IBAN:{{ Setting::get('store_iban') }}</td>
            <td>VAT:{{ Setting::get('store_vatid') }}</td>
        </tr>
        <tr>
            <td>{{ Setting::get('store_postal_code') }} {{ Setting::get('store_state') }}</td>
            <td>{{ Setting::get('store_link') }}</td>
            <td>Puhelin:{{ Setting::get('store_mobile') }}</td>
            <td>BIC:{{ Setting::get('store_bic') }}</td>
            <td></td>
        </tr>
    </tbody>
</table>
</body>

  <script type="text/javascript" src="/assets/js/jquery-2.1.1.min.js">
    </script>
<script type="text/javascript" src="/templates/return.js"></script>


</html>
