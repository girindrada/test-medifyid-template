<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Item {{ $kategori->kode }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #2d2d2d;
            padding: 30px 40px;
        }

        /* ─── HEADER ─── */
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 0.5px;
        }

        .header p {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* ─── INFO KATEGORI ─── */
        .info-box {
            background-color: #eff6ff;
            border-left: 5px solid #2563eb;
            padding: 14px 18px;
            border-radius: 4px;
            margin-bottom: 24px;
        }

        .info-box .label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 2px;
        }

        .info-box .value {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a8a;
        }

        .info-row {
            display: flex; /* DomPDF: pakai table trick di bawah */
        }

        .info-col {
            width: 50%;
            display: inline-block;
        }

        /* ─── TABLE ─── */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 1px solid #bfdbfe;
        }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table.items-table thead tr {
            background-color: #1e3a8a;
            color: #ffffff;
        }

        table.items-table thead th {
            padding: 9px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.3px;
        }

        table.items-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        table.items-table tbody tr:nth-child(even) {
            background-color: #f0f7ff;
        }

        table.items-table tbody td {
            padding: 8px 10px;
            font-size: 11px;
            vertical-align: top;
        }

        .badge {
            display: inline-block;
            background-color: #dbeafe;
            color: #1e40af;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .empty-state {
            text-align: center;
            color: #9ca3af;
            font-style: italic;
            padding: 20px;
        }

        /* ─── FOOTER ─── */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 40px;
            right: 40px;
            border-top: 1px solid #d1d5db;
            padding-top: 8px;
        }

        .footer-inner {
            width: 100%;
        }

        .footer-left {
            float: left;
            font-size: 10px;
            color: #6b7280;
        }

        .footer-right {
            float: right;
            font-size: 10px;
            color: #6b7280;
        }

        .clearfix::after {
            content: '';
            display: table;
            clear: both;
        }
    </style>
</head>
<body>

    {{-- ══ HEADER ══ --}}
    <div class="header">
        <h1>Laporan Detail Kategori</h1>
        <p>Sistem Manajemen Master Item</p>
    </div>

    {{-- ══ INFO KATEGORI (pakai table agar DomPDF render float dengan benar) ══ --}}
    <div class="info-box">
        <table width="100%">
            <tr>
                <td width="50%">
                    <div class="label">Nama Kategori</div>
                    <div class="value">{{ $kategori->nama }}</div>
                </td>
                <td width="50%">
                    <div class="label">Kode Kategori</div>
                    <div class="value">
                        <span class="badge">{{ $kategori->kode }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ══ TABEL ITEM ══ --}}
    <div class="section-title">
        Daftar Item dalam Kategori Ini
        ({{ $kategori->items->count() }} item)
    </div>

    @if($kategori->items->isEmpty())
        <p class="empty-state">Belum ada item yang terdaftar dalam kategori ini.</p>
    @else
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama Item</th>
                    <th width="15%">Harga Beli</th>
                    <th width="10%">Laba (%)</th>
                    <th width="18%">Harga Jual</th>
                    <th width="15%">Supplier</th>
                    <th width="12%">Jenis</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategori->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td class="text-right">
                        Rp {{ number_format($item->harga_beli, 0, ',', '.') }}
                    </td>
                    <td class="text-right">{{ $item->laba }}%</td>
                    <td class="text-right">
                        Rp {{ number_format($item->harga_beli + $item->harga_beli * $item->laba / 100, 0, ',', '.') }}
                    </td>
                    <td>{{ $item->supplier ?? '-' }}</td>
                    <td>{{ $item->jenis ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- ══ FOOTER ══ --}}
    <div class="footer">
        <div class="footer-inner clearfix">
            <span class="footer-left">
                Kategori: <strong>{{ $kategori->nama }}</strong> ({{ $kategori->kode }})
            </span>
            <span class="footer-right">
                Dicetak pada: {{ $printed_at }}
            </span>
        </div>
    </div>

</body>
</html>