@extends('layouts.dashboard-layout')

@section('page', 'Dashboard')

@section('content')
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="card">
      <div class="card-header">
        <h4>Grafik Penjualan Perbulan</h4>
      </div>
      <div class="card-body p-0">
        <div class="card-wrap">
          <div id="chart-container" class="card-body m-5">
            <canvas id="myChart2" height="150"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script type="text/javascript">
$(document).ready(function(){

  var ctx = $('#myChart2');
  var chart1 = new Chart(ctx,config);

  var config = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober','November','Desember'],
      datasets: [{
        label: ['Penjualan Perbulan'],
        data: [
          @for($i = 1; $i <= 12;$i++)
          {{ $data[$i] }},
          @endfor
        ],
        backgroundColor: [
          @for($i = 0; $i < 12;$i++)
          'rgba(75, 192, 192, 0.2)',
          @endfor
        ],
        borderColor: [
          @for($i = 0; $i < 12;$i++)
          'rgba(75, 192, 192, 1)',
          @endfor
        ],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true
          }
        }]
      }
    }
  });

});
</script>
@endpush
