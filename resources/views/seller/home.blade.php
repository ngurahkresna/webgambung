@extends('layouts.dashboard-layout')

@section('page', 'Dashboard')

@section('content')
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-12">
    <div class="card card-statistic-2" style="padding:80px;">
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-dollar-sign"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Total Order Bulan Ini</h4>
        </div>
        <div class="card-body">
          {{ $transaksi->count() }}
        </div>
      </div>
    </div>
  </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="card card-statistic-2" style="padding:80px;">
        <div class="card-icon shadow-primary bg-primary">
          <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>Total Penjualan Bulan Ini</h4>
          </div>
          <div class="card-body">
          Rp
          {{ number_format($transaksi->sum('grand_total_amount')) }},-
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection
