@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('kategori-items')}}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
            </div>
            <div class="card mb-4">
                <div class="card-header">Detail Kategori</div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Kode Kategori</th>
                            <td>{{$data->kode}}</td>
                        </tr>
                        <tr>
                            <th>Nama Kategori</th>
                            <td>{{$data->nama}}</td>
                        </tr>
                    </table>
                    <a class="btn btn-info" href="{{url('kategori-items/form/edit')}}/{{$data->id}}">Edit</a>
                    <a class="btn btn-danger" href="{{url('kategori-items/delete')}}/{{$data->id}}" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">Delete</a>
                    <a class="btn btn-success" href="{{url('kategori-items/download-pdf')}}/{{$data->kode}}">Download PDF</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Daftar Item dengan Kategori Ini</div>
                <div class="card-body">
                    @if($data->masterItems && $data->masterItems->count() > 0)
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode Item</th>
                                    <th>Nama Item</th>
                                    <th>Jenis</th>
                                    <th>Supplier</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->masterItems as $item)
                                    @php
                                        $harga_jual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);
                                    @endphp
                                    <tr>
                                        <td>{{$item->kode}}</td>
                                        <td>{{$item->nama}}</td>
                                        <td>{{$item->jenis}}</td>
                                        <td>{{$item->supplier}}</td>
                                        <td>{{$item->harga_beli}}</td>
                                        <td>{{round($harga_jual)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted text-center my-3">Tidak ada item yang terhubung dengan kategori ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection
