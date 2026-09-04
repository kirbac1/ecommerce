<?php echo $header; ?>
<div class="carta-container"><div id="container" class="container j-container oc-newsletter">
  <ul class="breadcrumb">
        <li><a href="http://journal.digital-atelier.com/3/index.php?route=common/home">Home</a></li>
        <li><a href="http://journal.digital-atelier.com/3/index.php?route=account/account">Account</a></li>
        <li><a href="http://journal.digital-atelier.com/3/index.php?route=account/newsletter">Newsletter</a></li>
      </ul>
  <div class="row">                <div id="content" class="col-sm-12">
      <h1 class="heading-title">Newsletter Subscription</h1>
            <form action="http://journal.digital-atelier.com/3/index.php?route=account/newsletter" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <div class="form-group">
            <label class="col-sm-2 control-label">Subscribe</label>
            <div class="col-sm-10">
                            <label class="radio-inline">
                <input type="radio" name="newsletter" value="1">
                Yes </label>
              <label class="radio-inline">
                <input type="radio" name="newsletter" value="0" checked="checked">
                No</label>
                          </div>
          </div>
        </fieldset>
        <div class="buttons">
          <div class="pull-left"><a href="http://journal.digital-atelier.com/3/index.php?route=account/account" class="btn btn-default button">Back</a></div>
          <div class="pull-right">
            <input type="submit" value="Continue" class="btn btn-primary button">
          </div>
        </div>
      </form>
      </div>
    </div>
</div>
</div>
<?php echo $footer; ?>