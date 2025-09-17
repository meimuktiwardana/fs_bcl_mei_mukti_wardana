{{-- resources/views/orders/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Data Pemesanan</h2>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Buat Pesanan
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if(isset($orders) && $orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Telepon</th>
                            <th>Jenis Kendaraan</th>
                            <th>Tanggal Pesan</th>
                            <th>Status</th>
                            <th>Armada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->customer_phone }}</td>
                                <td>{{ ucfirst($order->vehicle_type) }}</td>
                                <td>{{ $order->order_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'confirmed' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->fleet)
                                        <a href="{{ route('fleets.show', $order->fleet) }}">
                                            {{ $order->fleet->fleet_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('orders.destroy', $order) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        @else
            <div class="text-center py-4">
                <i class="bi bi-cart display-1 text-muted"></i>
                <p class="mt-2">Belum ada data pesanan.</p>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">Buat Pesanan Pertama</a>
            </div>
        @endif
    </div>
</div>
@endsection