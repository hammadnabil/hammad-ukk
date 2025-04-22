@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Laporan Pendapatan</h2>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Kembali</a>

    
  

    

    <form method="GET" action="{{ route('manager.report') }}" class="mb-4">
        <div class="form-group">
            <label class="form-label">Filter berdasarkan:</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="filter_type" id="filter_day" value="day" 
                {{ request('filter_type') == 'day' ? 'checked' : '' }} onclick="toggleFilter()">
                <label class="form-check-label" for="filter_day">Hari</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="filter_type" id="filter_month" value="month"
                    {{ request('filter_type') == 'month' ? 'checked' : '' }} onclick="toggleFilter()">
                <label class="form-check-label" for="filter_month">Bulan</label>
            </div>
        </div>

        <div class="form-group mt-3" id="dateFilter" style="display: none;">
            <label for="date" class="form-label">Pilih Hari:</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
        </div>

        <div class="form-group mt-3" id="monthFilter" style="display: none;">
            <label for="month" class="form-label">Pilih Bulan:</label>
            <input type="month" name="month" id="month" class="form-control" value="{{ request('month') }}">
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Tampilkan</button>
            <a href="{{ route('manager.report') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
    

   
    @if($transactions->count())
  <a href="{{ route('manager.report.export_excel', request()->all()) }}" class="btn btn-success mt-3">Export Excel</a>

  <div class="mt-4">
      <h4>Total Pendapatan: <strong>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</strong></h4>
  </div>

  <div class="table-responsive mt-4">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Kasir</th>
          <th>Total Transaksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transactions as $transaction)
        <tr>
          <td>{{ $transaction->paid_at->format('d-m-Y') }}</td>
          <td>{{ $transaction->cashier->name ?? 'tidak diketahui' }}</td>
          <td>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="3">Tidak ada transaksi</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endif

</div>


<script>
    function toggleFilter() {
        const filterType = document.querySelector('input[name="filter_type"]:checked');
        if (!filterType) return;

        document.getElementById('dateFilter').style.display = (filterType.value === 'day') ? 'block' : 'none';
        document.getElementById('monthFilter').style.display = (filterType.value === 'month') ? 'block' : 'none';
    }

    document.addEventListener("DOMContentLoaded", toggleFilter);

</script>

@endsection
