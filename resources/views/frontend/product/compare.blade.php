<?php echo $header; ?>
<div class="carta-container">
    <div id="container" class="container j-container">
        <ul class="breadcrumb">
            <li><a href="http://journal.digital-atelier.com/3/index.php?route=common/home">Home</a></li>
            <li><a href="http://journal.digital-atelier.com/3/index.php?route=product/compare">Product Comparison</a></li>
        </ul>
        <div class="row">
            <div id="content" class="col-sm-12 compare">
                <h1 class="heading-title">Product Comparison</h1>
                <table class="table table-bordered compare-info">
                    <thead>
                        <tr>
                            <td colspan="3" class="compare-product"><strong>Product Details</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Product</td>
                            <td class="name"><a href="http://journal.digital-atelier.com/3/index.php?route=product/product&amp;product_id=159"><strong>White Leather Belt</strong></a></td>
                            <td class="name"><a href="http://journal.digital-atelier.com/3/index.php?route=product/product&amp;product_id=69"><strong>Big Modern Necklace</strong></a></td>
                        </tr>
                        <tr>
                            <td>Image</td>
                            <td class="text-center"> <img src="/assets/img/no-image.png" alt="White Leather Belt" title="White Leather Belt" class="img-thumbnail">
                            </td>
                            <td class="text-center"> <img src="/assets/img/no-image.png" alt="Big Modern Necklace" title="Big Modern Necklace" class="img-thumbnail">
                            </td>
                        </tr>
                        <tr>
                            <td>Price</td>
                            <td> <span class="price-old">$280.00 </span> <span class="price-new"> $199.00 </span>
                            </td>
                            <td> $79.00 </td>
                        </tr>
                        <tr>
                            <td>Model</td>
                            <td>Model 180</td>
                            <td>Model 63</td>
                        </tr>
                        <tr>
                            <td>Brand</td>
                            <td>Hipster</td>
                            <td>Chic D'or</td>
                        </tr>
                        <tr>
                            <td>Availability</td>
                            <td>In Stock</td>
                            <td>In Stock</td>
                        </tr>
                        <tr>
                            <td>Rating</td>
                            <td class="rating"> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                <br> Based on 0 reviews.</td>
                            <td class="rating"> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                <br> Based on 0 reviews.</td>
                        </tr>
                        <tr>
                            <td>Summary</td>
                            <td class="description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type a..</td>
                            <td class="description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type a..</td>
                        </tr>
                        <tr>
                            <td>Weight</td>
                            <td>0.00kg</td>
                            <td>0.00kg</td>
                        </tr>
                        <tr>
                            <td>Dimensions (L x W x H)</td>
                            <td>0.00cm x 0.00cm x 0.00cm</td>
                            <td>0.00cm x 0.00cm x 0.00cm</td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td></td>
                            <td>
                                <div class="cart ">
                                    <a onclick="addToCart('159');" class="button hint--top compare-add-to-cart" data-hint="Add to Cart"><i class="button-left-icon"></i><span class="button-cart-text">Add to Cart</span><i class="button-right-icon"></i></a>
                                    <a href="http://journal.digital-atelier.com/3/index.php?route=product/compare&amp;remove=159" class="btn btn-danger btn-block button compare-remove">Remove</a>
                                </div>
                            </td>
                            <td>
                                <div class="cart ">
                                    <a onclick="addToCart('69');" class="button hint--top compare-add-to-cart" data-hint="Add to Cart"><i class="button-left-icon"></i><span class="button-cart-text">Add to Cart</span><i class="button-right-icon"></i></a>
                                    <a href="http://journal.digital-atelier.com/3/index.php?route=product/compare&amp;remove=69" class="btn btn-danger btn-block button compare-remove">Remove</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
