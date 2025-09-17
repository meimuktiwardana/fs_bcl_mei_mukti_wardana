{{-- resources/views/orders/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-cart"></i> Detail Pesanan</h4>
                <div>
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('orders.destroy', $order) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('Yakin ingin menghapus pesanan ini?')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Pelanggan</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>{{ $order->customer_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Telepon:</strong></td>
                                <td>{{ $order->customer_phone }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Pesan:</strong></td>
                                <td>{{ $order->order_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'confirmed' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Detail Pesanan</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Jenis Kendaraan:</strong></td>
                                <td>{{ ucfirst($order->vehicle_type) }}</td>
                            </tr>
                            @if($order->fleet)
                                <tr>
                                    <td><strong>Armada Assigned:</strong></td>
                                    <td>
                                        <a href="{{ route('fleets.show', $order->fleet) }}">
                                            {{ $order->fleet->fleet_number }}
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <h6>Detail Barang</h6>
                <p>{{ $order->item_details }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-3 text-center">
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesanan
    </a>
</div>
@endsection