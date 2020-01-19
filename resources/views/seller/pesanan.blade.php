@extends('layouts.dashboard-layout')

@section('page', 'List Pesanan')

@push('css')
    <link rel="stylesheet" href="{{ asset('node_modules/prismjs/themes/prism.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4></h4>
                    <div class="card-header-action">
                        <form>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive gambung-tables">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>No Transaksi</th>
                                <th>Nama Pembeli</th>
                                <th>Nama Toko</th>
                                <th>Nama Produk</th>
                                <th>Checkout</th>
                                <th>Bukti Bayar</th>
                                <th>Aksi</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @isset($transactions)
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $transaction->transaction->code }}</td>
                                        <td>{{ $transaction->transaction->users->name }}</td>
                                        <td>{{ $transaction->product->store->name }}</td>
                                        <td>{{ $transaction->product->name }}</td>
                                        <td><a href="#" class="btn btn-outline-primary detail-checkout"
                                               onclick="getDetail('{{ $transaction->transaction->code }}')">Detail</a>
                                        </td>
                                        <td><a href="#" class="btn btn-outline-primary"
                                               onclick="getProof('{{ $transaction->transaction->code }}')">Lihat</a>
                                        </td>
                                        </td>
                                        <td>
                                            @if($transaction->shipping_status == 'OPTNO')
                                                <button type="button" class="btn btn-outline-primary"
                                                        onclick="showShipmentForm('{{ $transaction->transaction->code }}')">
                                                    Kirim
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="badge badge-primary">
                                                @if($transaction->shipping_status == 'OPTNO')
                                                    Belum Dikirim
                                                @else
                                                    Dikirim
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-modal')
    <div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pemesanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="gambung-tables">
                        <table class="table table-striped" id="detail-table">
                            <thead>
                            <tr>
                                <th width="100" class="text-left">Nama Produk</th>
                                <th width="200" class="text-center">Nama Toko</th>
                                <th width="50">Qty</th>
                                <th width="100">Harga Satuan</th>
                                <th width="100">Harga</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-buktibayar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bukti Bayar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-shipping" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bukti Bayar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('transaction.verification_delivery') }}"
                      method="POST">
                    @csrf
                    <div class="modal-body text-center">
                        <input type="hidden" name="transaction_code" id="transaction_code" value="">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">No Resi</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="shipping_no" id="shipping_no">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/js/page/bootstrap-modal.js') }}"></script>
    <script type="text/javascript">
        $('#buktibayar').click(function () {
            var link = $('#buktibayar').val();
            $('#buktibayar-img').attr('src', link);

        });
    </script>

    <script>
        function getDetail(code) {
            $.ajax({
                type: 'POST',
                url: '{{ route('transaction.detail') }}',
                data: {code: code},
                dataType: 'json',
                success: function (data) {
                    $('#detail-table tbody').empty();

                    let newRow = '';
                    let expeditions = [];
                    let stores = [];
                    $(data.detail).each(function (i) {
                        newRow = newRow.concat('<tr>');
                        newRow = newRow.concat('<td class="text-left">' + data.detail[i].product.name + '</td>');
                        newRow = newRow.concat('<td class="text-center">' + data.detail[i].product.store.name + '</td>');
                        newRow = newRow.concat('<td>' + parseInt(data.detail[i].quantity) + '</td>');
                        newRow = newRow.concat('<td class="text-right">Rp. ' + (data.detail[i].product.price / 1000).toFixed(3) + '</td>');
                        newRow = newRow.concat('<td class="text-right">Rp. ' + (data.detail[i].price / 1000).toFixed(3) + '</td>');
                        newRow = newRow.concat('</tr>');

                        if (jQuery.inArray(data.detail[i].product.store.name, stores) == -1) {
                            stores.push(data.detail[i].product.store.name);
                            expeditions.push(data.detail[i].expedition);
                        }
                    });

                    newRow = newRow.concat('<tr>');
                    newRow = newRow.concat('<td class="text-left">Biaya Pengiriman</td>');
                    newRow = newRow.concat('<td colspan="4" class="text-right">Rp.' + (data.shipping_charges / 1000).toFixed(3) + '</td>');
                    newRow = newRow.concat('</tr>');

                    newRow = newRow.concat('<tr style="border-top: 1px solid black; border-bottom: 1px solid black">');
                    newRow = newRow.concat('<td class="text-left"><b>Total Harga</b></td>');
                    newRow = newRow.concat('<td colspan="4" class="text-right"><b>Rp.' + (data.grand_total_amount / 1000).toFixed(3) + '</b></td>');
                    newRow = newRow.concat('</tr>');

                    newRow = newRow.concat('<tr>');
                    newRow = newRow.concat('<td colspan="5" class="text-left"><b>Pengiriman</b>');
                    $(stores).each(function (i) {
                        newRow = newRow.concat('<br><span>' + stores[i] + '   -   ' + expeditions[i] + '</span>');
                    });
                    newRow = newRow.concat('</td>');
                    newRow = newRow.concat('</tr>');

                    newRow = newRow.concat('<tr>');
                    newRow = newRow.concat('<td colspan="5" class="text-left"><b>Dikirim Kepada ' + data.users.name + '</b>');
                    newRow = newRow.concat('<br><span><b>' + data.address_1 + '</b></span>');
                    newRow = newRow.concat('<br><span><b>Telp ' + data.users.phone + '</b></span>');
                    newRow = newRow.concat('</td>');
                    newRow = newRow.concat('</tr>');

                    $('#detail-table tbody').append(newRow);

                    $('#modal-detail').modal('show');
                }
            })
        }

        function getProof(code) {
            let proofImgDir = '{{ asset('assets/img/proof') }}';
            $.ajax({
                type: 'POST',
                url: '{{ route('transaction.proof') }}',
                data: {code: code},
                dataType: 'json',
                success: function (data) {
                    $('#modal-buktibayar .modal-body').empty();

                    let proofimg
                    if (data.proof_image != null) {
                        proofimg = '<img src="' + proofImgDir + '/' + data.proof_image + '" alt="Bukti Bayar" id="buktibayar-img" style="height: 600px; width: auto;">';
                    }

                    $('#modal-buktibayar .modal-body').append(proofimg);
                    $('#modal-buktibayar').modal('show');
                }
            });
        }

        function showShipmentForm(code) {
            $('#transaction_code').val(code);

            $('#modal-shipping').modal('show');
        }
    </script>
@endpush
