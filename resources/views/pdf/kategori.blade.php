<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kategori {{ $data->kode }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        @page {
            margin: 80px 40px;
        }
        header {
            position: fixed;
            top: -50px;
            left: 0;
            right: 0;
            height: 50px;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
        }
        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #0056b3;
            margin: 0;
        }
        footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 11px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
        .info-table {
            width: 100%;
            margin-top: 20px;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .info-table th {
            text-align: left;
            padding: 6px 0;
            font-weight: bold;
            width: 25%;
        }
        .info-table td {
            padding: 6px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .items-table th {
            background-color: #0056b3;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            font-size: 13px;
            border: 1px solid #0056b3;
        }
        .items-table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 13px;
        }
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

    <header>
        <div class="header-title">Laporan Data Kategori Barang</div>
    </header>

    <footer>
        Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}
    </footer>

    <main style="margin-top: 20px;">
        <table class="info-table">
            <tr>
                <th>Nama Kategori</th>
                <td>: {{ $data->nama }}</td>
            </tr>
            <tr>
                <th>Kode Kategori</th>
                <td>: {{ $data->kode }}</td>
            </tr>
        </table>

        <h3>Daftar Item yang Memiliki Kategori Ini</h3>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%">Kode Item</th>
                    <th width="35%">Nama Item</th>
                    <th width="15%">Jenis</th>
                    <th width="15%">Supplier</th>
                    <th width="15%" class="text-right">Harga Jual</th>
                </tr>
            </thead>
            <tbody>
                @if($data->masterItems && $data->masterItems->count() > 0)
                    @php $no = 1; @endphp
                    @foreach($data->masterItems as $item)
                        @php
                            $harga_jual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);
                        @endphp
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $item->kode }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jenis }}</td>
                            <td>{{ $item->supplier }}</td>
                            <td class="text-right">{{ number_format(round($harga_jual), 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 15px; color: #777;">Tidak ada item dalam kategori ini.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </main>

</body>
</html>
