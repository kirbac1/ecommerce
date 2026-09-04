
<?php echo $header; ?>
<div class="carta-container"><div id="container" class="container j-container success-page">
  <ul class="breadcrumb">
        <li><a href="/">Home</a></li>
        <li><a href="/cart">Shopping Cart</a></li>
        <li><a href="/checkout">{{ trans('messages.Checkout') }}</a></li>
        <li><a href="#">Success</a></li>
      </ul>
  <div class="row">                <div id="content" class="col-sm-12">      <h1 class="heading-title">Your order has been placed!</h1>
      <p>Your order has been successfully processed!</p><p>You can view your order history by going to the <a href="/account">my account</a> page and by clicking on <a href="/account/orders">history</a>.</p><p>If your purchase has an associated download, you can go to the account <a href="#">downloads</a> page to view them.</p><p>Please direct any questions you have to the <a href="/contact">store owner</a>.</p><p>Thanks for shopping with us online!</p>      <div class="buttons">
        <div class="pull-right"><a href="/" class="btn btn-primary button">Continue</a></div>
      </div>
      </div>
    </div>
</div>
</div>

<?php echo $footer; ?>