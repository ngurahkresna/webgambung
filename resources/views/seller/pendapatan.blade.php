@extends('layouts.dashboard-layout')

@section('page', 'Kelola Pendapatan')

@push('css')
<link rel="stylesheet" href="{{ asset('node_modules/prismjs/themes/prism.css') }}">
@endpush

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <div class="col-9">
          Saldo Anda : &nbsp;
          <h4 style="display:inline-block;"> Rp 3.000.000</h4>
          <form style="display:inline-block;">
            <div class="input-group">
              <div class="input-group-btn">
                <button class="btn btn-primary">Tarik Saldo</button>
              </div>
            </div>
          </form>
        </div>
        <div class="card-header-action col-3">
          <form>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search" style="border-radius:50px 0px 0px 50px">
              <div class="input-group-btn">
                <button class="btn btn-primary" style="border-radius:0px 50px 50px 0px" ><i class="fas fa-search"></i></button>
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
                <th>Nama Transaksi</th>
                <th>Waktu Transaksi</th>
                <th>Nominal</th>
                <th>Saldo</th>
              </tr>
            </thead>
            <tbody>
              @for($i=0;$i<100;$i++)
              <tr>
                <td>
                  1
                </td>
                <td>Transaksi Beli Kunci Jawaban</td>
                <td>28 Februari 2019</td>
                <td>Rp 30.000.000</td>
                <td>Rp 50.000.000</td>
              </tr>
              @endfor
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('add-modal')

<div class="modal fade" id="modal-buktibayar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bukti Bayar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="" alt="Bukti Bayar" id="buktibayar-img" width="100%" height="100%">
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
@endpush
