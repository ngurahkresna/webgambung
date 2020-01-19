@extends('layouts.client-layout')

@section('page', 'Cart Produk')

@section('content')
    <div class="container-fluid page">
        <div class="row">
            <div class="col-12 page-title">
                <h2>SHOPPING CART</h2>
            </div>
        </div>
        <div class="row container-cart">
            <div class="col-xs-12 col-lg-8">
                @isset($carts)
                    @foreach($carts as $cart)
                        <div class="section-cart">
                            <div class="row">
                                <div class="col-sm-11">
                                    <h2>{{ $cart->product->store->name }}</h2>
                                    <p>{{ $cart->product->store->address_1 }} <span class="fa fa-map-marker"></span></p>
                                </div>
                                <div class="col-sm-1 text-right">
                                    <input type="checkbox" value="{{ $cart->id }}" class="form-control cart-checkbox" checked>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-lg-2 text-center">
                                    @if (isset($cart->product->images[0]->image_name))
                                        <img src="{{ asset('assets/img/products') . $cart->product->images[0]->image_name }}" alt="Image 1" height="100px" width="100px">
                                    @else
                                        <img src="{{ asset('assets/img/products/product-1.jpg') }}" alt="Produk" height="100px" width="100px">
                                    @endisset
                                </div>
                                <div class="col-xs-12 col-lg-6">
                                    <h1>{{ $cart->product->name }}</h1>
                                    <p>
                                      @isset($cart->product->color)
                                      <span class="badge badge-light">{{ $cart->product->color }} </span>
                                      @endisset
                                      @isset($cart->cart_product_status)
                                      <span class="badge badge-success">{{ $cart->cart_product_status->value }}</span>
                                      @endisset
                                    </p>
                                    <input type="hidden" value="{{ $cart->product->price }}" class="product-price">
                                    <p><span>Rp {{ number_format($cart->product->price) }}</span>/pcs</p>
                                </div>
                                <div class="col-xs-12 col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a class="btn btn-number" data-type="minus" data-field="quant{{ $loop->iteration }}">
                                                <span class="fa fa-minus"></span>
                                            </a>
                                        </span>
                                        <input type="text" name="quant{{ $loop->iteration }}" class="form-control text-center input-number"
                                               value="{{ $cart->quantity }}" min="1" max="{{ $cart->product->stock }}">
                                        <span class="input-group-btn">
                                            <a class="btn btn-number" data-type="plus" data-field="quant{{ $loop->iteration }}">
                                                <span class="fa fa-plus"></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-lg-1">
                                    <form action="{{ route('cart.destroy', $cart->id) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-light"><span class="fa fa-trash"></span>
                                        </button>
                                    </form>
                                </div>
                                <div class="col-12">
                                    <input type="text" name="pesan" value=""
                                           placeholder="Masukan pesan kepada penjual (optional)" class="form-control pesan">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endisset;
            </div>
            <div class="col-xs-12 col-lg-4 cart-harga">
                <div class="section-harga">
                    <h2>Total Belanja</h2>
                    <form class="text-center" action="{{ url('cart/1') }}" method="POST" id="form-checkout">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="message" id="message" value="tes">
                        <input type="hidden" name="cart" value="">
                        <input type="hidden" name="quantity" value="">
                        <div class="harga">
                            <h1><b>Rp {{ number_format($shopping_charges) }},-</b></h1>
                        </div>
                        <button type="button" class="btn btn-success" name="button" id="btnCheckout"
                                onclick="check_cart()">Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

    <script type="text/javascript">
        fillCart();

        $('.cart-checkbox, .input-number').change(function () {
            fillCart();
        });

        function fillCart() {
            var cart = [];
            var quantity = [];
            var message =[];
            var total = 0;
            $('.cart-checkbox:checked').each(function () {
                var qty = $(this).closest('.section-cart').find('.input-number').val();
                var price = $(this).closest('.section-cart').find('.product-price').val();
                var msg = $(this).closest('.section-cart').find('.pesan').val();

                cart.push(parseInt($(this).val()));
                quantity.push(parseInt(qty));
                message.push(msg);

                total += (qty * price);
            });

            $('.harga h1 b').html('Rp ' + total.toLocaleString() + ',-');

            $('input[name="cart"]').val(JSON.stringify(cart));
            $('input[name="quantity"]').val(JSON.stringify(quantity));
            $('input[name="message"]').val(JSON.stringify(message));
        }

        function check_cart() {
            fillCart();

            if ($('.cart-checkbox:checked').length < 1) {
                alert('Pilih Produk yang Ingin Anda Checkout!');
                return false;
            }

            $('#form-checkout').submit();
        }
    </script>

    <script type="text/javascript">
        $('.btn-number').click(function (e) {
            e.preventDefault();

            fieldName = $(this).attr('data-field');
            type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());
            if (!isNaN(currentVal)) {
                if (type == 'minus') {

                    if (currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if (type == 'plus') {

                    if (currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('max')) {
                        $(this).attr('disabled', true);
                    }

                }
            } else {
                input.val(0);
            }
        });
        $('.input-number').focusin(function () {
            $(this).data('oldValue', $(this).val());
        });
        $('.input-number').change(function () {

            minValue = parseInt($(this).attr('min'));
            maxValue = parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());

            name = $(this).attr('name');
            if (valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                alert('Maaf, anda melebihi minimum pembelian');
                $(this).val($(this).data('oldValue'));
            }
            if (valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                alert('Maaf, stock tidak cukup');
                $(this).val($(this).data('oldValue'));
            }


        });
        $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    </script>
@endpush
