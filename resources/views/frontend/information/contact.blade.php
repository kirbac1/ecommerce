<?php echo $header; ?>
<div class="carta-container">
    <div id="container" class="container j-container">
        <ul class="breadcrumb">
            <li><a href="http://journal.digital-atelier.com/3/index.php?route=common/home">Home</a></li>
            <li><a href="http://journal.digital-atelier.com/3/index.php?route=information/contact">Contact Us</a></li>
        </ul>
        <div class="row">
            <div id="content" class="col-sm-12 contact-page">
                <h1 class="heading-title">Contact Us</h1>
                <div id="journal-cms-block-1342819094" class="box cms-blocks  " style="">
                    <div class="blocks">
                        <div class="cms-block xs-100 sm-100 md-100 lg-100 xl-100">
                            <span class="block-content" style="height: 208px;">
                        <div class="editor-content" style="text-align: left"> <p><iframe frameborder="0" height="200" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.745754504347!2d2.3350847999999917!3d48.863058399999964!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e25967267e3%3A0x72b2a53b90685549!2s174+Rue+de+Rivoli!5e0!3m2!1sen!2s!4v1400359491114" style="border:0" width="100%"></iframe></p></div>
        </span>
                        </div>
                    </div>
                </div>
                <script>
                Journal.equalHeight($('#journal-cms-block-1342819094 .cms-block'), '.block-content');
                </script>
                <h2 class="secondary-title">Our Location</h2>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-3 col"><strong>Journal</strong>
                                <br>
                                <address>
                                    Address 1 </address>
                            </div>
                            <div class="col-sm-3 col"><strong>Telephone</strong>
                                <br> 123456789
                                <br>
                                <br>
                            </div>
                            <div class="col-sm-3 col">
                            </div>
                        </div>
                    </div>
                </div>
                <form action="http://journal.digital-atelier.com/3/index.php?route=information/contact" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <fieldset>
                        <h2 class="secondary-title">Contact Form</h2>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name">Your Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="" id="input-name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-email">E-Mail Address</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" value="" id="input-email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-enquiry">Enquiry</label>
                            <div class="col-sm-10">
                                <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control"></textarea>
                            </div>
                        </div>
                    </fieldset>
                    <div class="buttons">
                        <div class="pull-right">
                            <input class="btn btn-primary button" type="submit" value="Submit">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
