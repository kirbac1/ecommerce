@extends('layouts.default') @section('content')
<div class="carta-container">
    <div id="container" class="container j-container">
        <ul class="breadcrumb">
            <li><a href="/webstore/home">Home</a></li>
            <li><a href="/webstore/account">Account</a></li>
            <li><a href="/webstore/wishlist">My Wish List</a></li>
        </ul>
        <div class="row">
            <div id="content" class="col-sm-12">
                <h1 class="heading-title">My Wish List</h1>
                <div class="content wishlist-info">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <td class="text-center image">Image</td>
                                <td class="text-left name">Product Name</td>
                                <td class="text-left model">QtyPerPack</td>
                                <td class="text-right stock">Stock</td>
                                <td class="text-right price">Unit Price</td>
                                <td class="text-right action">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in wishlist.products">
                                <td class="text-center image">
                                    <a href="/product?id=@{{product.id}}"><img src="/catalog/@{{product.image}}" alt="@{{product.name}}" title="B@{{product.name}}"></a>
                                </td>
                                <td class="text-left name"><a href="http://journal.digital-atelier.com/3/index.php?route=product/product&amp;product_id=57">@{{product.name}}</a></td>
                                <td class="text-left model">@{{product.qtyPerPack}}</td>
                                <td class="text-right stock">In Stock</td>
                                <td class="text-right price">
                                    <div class="price">
                                        @{{product.priceEach}} </div>
                                </td>
                                <td class="text-right action "><a v-on:click="add2Cart(product.id)"  data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Add to Cart"><i class="fa fa-shopping-cart"></i></a>
                                    <a  v-on:click="removeFromWishlist(product.id)" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="Remove"><i class="fa fa-times"></i></a></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="buttons">
                    <div class="pull-right"><a href="/webstore/account" class="btn btn-primary button">Continue</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- template for the modal component -->
<script type="x/template" id="modal-template">
    <div class="modal-mask" @click="show = false" v-show="show" transition="modal">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-header">
                    <slot name="header">
                    </slot>
                </div>
                <div class="modal-body">
                    <slot name="body">
                        <img style="max-width:300px; max-height:400px" src="@{{img}}">
                    </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="modal-default-button" @click="show = false">
                            CLOSE
                        </button>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</script>
<!-- use the modal component, pass in the prop -->
<modal :img="img" :show.sync="showModal">
    <!--
      you can use custom content here to overwrite
      default content
    -->
</modal>
@stop @section('footer')
