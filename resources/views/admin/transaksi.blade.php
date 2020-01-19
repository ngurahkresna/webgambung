@extends('layouts.dashboard-layout')

@section('page', 'Mengelola Transaksi')

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
                                <th>Nomor Transaksi</th>
                                <th>Nama Pembeli</th>
                                <th>Checkout</th>
                                <th>Bukti Bayar</th>
                                <th>Aksi</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transaction as $transaksi)
                                <tr>
                                    <td>{{ $loop->iteration }} </td>
                                    <td>{{ $transaksi->code }}</td>
                                    <td>{{ $transaksi->users->name }}</td>
                                    <td><a href="#" class="btn btn-outline-primary detail-checkout"
                                           onclick="getDetail('{{ $transaksi->code }}')">Detail</a></td>
                                    @if($transaksi->payment['proof_image'] != null)
                                        <td><a href="#" class="btn btn-outline-primary"
                                               onclick="getProof('{{ $transaksi->code }}')">Lihat</a></td>
                                        @if($transaksi->payment['verified_status'] == 'OPTNO')
                                            <td>
                                                <form action="{{ route('transaction.verification') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="transaction_code"
                                                           value="{{ $transaksi->code }}">
                                                    <button type="button" class="btn btn-outline-primary" name="button"
                                                            onclick="confirmation($(this))">Confirm
                                                    </button>
                                                </form>
                                            </td>
                                        @else
                                            <td> -</td>
                                        @endif
                                    @else
                                        @if($transaksi->isoverdue == 'OPTYS')
                                            <td>Tidak Melakukan Pembayaran</td>
                                        @else
                                            <td>Belum Bayar</td>
                                        @endif

                                        @if($transaksi->isoverdue == 'OPTYS' and $transaksi->isCancelledAdmin != 'OPTYS')
                                            <td>
                                                <form action="{{ route('transaction.cancel') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="transaction_code"
                                                           value="{{ $transaksi->code }}">
                                                    <button type="button" class="btn btn-danger" name="button"
                                                            onclick="confirmation($(this))">Cancel
                                                    </button>
                                                </form>
                                            </td>
                                        @else
                                            <td> -</td>
                                        @endif
                                    @endif
                                    <td>
                                        @if($transaksi->isCancelledBuyer == 'OPTYS')
                                            Cancalled
                                        @elseif($transaksi->isCancelledAdmin == 'OPTYS')
                                            Tidak Dibayar
                                        @elseif($transaksi->isoverdue == 'OPTYS' and $transaksi->payment['proof_image'] == null)
                                            Pending
                                        @elseif($transaksi->iscancelled == 'OPTYS')
                                            Cancelled
                                        @elseif($transaksi->payment['verified_status'] == 'OPTNO')
                                            @if($transaksi->payment['proof_image'] == null)
                                                Pending
                                            @else
                                                Belum Verifikasi
                                            @endif
                                        @elseif($transaksi->payment['verified_status'] == 'OPTYS')
                                            Verifikasi
                                        @else
                                            Pending
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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

    <div class="modal fade" id="modal-bukti" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bukti Pembayaran</h5>
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

@endsection


@push('script')
    <script src="{{ asset('assets/js/page/bootstrap-modal.js') }}"></script>
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

                        newRow = newRow.concat('<tr style="border-bottom: 1px solid grey">');
                        newRow = newRow.concat('<td colspan="5" class="text-left">Pesan: ' + data.detail[i].message + '</td>');
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
                    $('#modal-bukti .modal-body').empty();

                    let proofimg
                    if (data.proof_image != null) {
                        proofimg = '<img src="' + proofImgDir + '/' + data.proof_image + '" alt="Bukti Bayar" id="buktibayar-img" style="height: 600px; width: auto;">';
                    }

                    $('#modal-bukti .modal-body').append(proofimg);
                    $('#modal-bukti').modal('show');
                }
            });
        }
    </script>
@endpush
