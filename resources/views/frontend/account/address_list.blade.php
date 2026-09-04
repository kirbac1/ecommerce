<?php echo $header; ?>
<div class="carta-container"><div id="container" class="container j-container">
  <ul class="breadcrumb">
        <li><a href="/">Home</a></li>
        <li><a href="/account">Account</a></li>
        <li><a href="/account/address_edit">Address Book</a></li>
      </ul>
      <div class="row">                <div id="content" class="col-sm-12 address-entry">      <h2 class="secondary-title">Address Book Entries</h2>
            <div class="content">
      <table class="table table-bordered table-hover">
                <tbody><tr>
          <td class="text-left">Example Customer<br>Example Oy<br>Esimerkkikatu 1<br>Tampere 33210<br>Pirkanmaa<br>Finland</td>
          <td class="text-right"><a href="#" class="btn btn-info button">Edit</a> &nbsp; <a href="#" class="btn btn-danger button">Delete</a></td>
        </tr>
              </tbody></table>
      </div>
            <div class="buttons">
        <div class="pull-left"><a href="/account" class="btn btn-default button">Back</a></div>
        <div class="pull-right"><a href="#" class="btn btn-primary button">New Address</a></div>
      </div>
      </div>
    </div>
</div>
</div>
<?php echo $footer; ?>