<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ders Programı</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            padding: 10px;
        }

        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }

        h2 {
            font-size: 12px;
            margin: 15px 0 10px 0;
            padding: 8px;
            background-color: #4F81BD;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        th {
            background-color: #4F81BD;
            color: white;
            padding: 8px 5px;
            text-align: center;
            font-size: 8px;
            border: 1px solid #2E5C8A;
        }

        td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            font-size: 7px;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .group-name {
            font-weight: bold;
            font-size: 10px;
        }

        .ders-cell {
            background-color: #E8F4FF;
            font-weight: 600;
            color: #2E5C8A;
        }

        .empty-cell {
            color: #999;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 7px;
            color: #666;
        }

        @page {
            margin: 15mm;
        }
    </style>
</head>
<body>
    <h1>Ders Programı</h1>
    <p style="text-align: center; margin-bottom: 20px; font-size: 8px; color: #666;">
        Oluşturulma Tarihi: {{ date('d.m.Y H:i') }}
    </p>

    @php
        $gunler = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'];
    @endphp

    @foreach($program_tablosu as $grupId => $item)
        <h2 class="group-name">{{ $item['grup']->isim }} - {{ $item['grup']->yil }}. Yıl</h2>

        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Gün</th>
                    <th style="width: 12%;">Saat</th>
                    <th style="width: 30%;">Ders</th>
                    <th style="width: 26%;">Öğretmen</th>
                    <th style="width: 20%;">Mekan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($zaman_dilimleri as $zamanDilim)
                    @php
                        $ders = $item['dersler'][$zamanDilim->id] ?? null;
                    @endphp
                    <tr>
                        <td>{{ $gunler[$zamanDilim->gun_sirasi] }}</td>
                        <td>{{ $zamanDilim->baslangic_saat }} - {{ $zamanDilim->bitis_saat }}</td>
                        <td class="{{ $ders ? 'ders-cell' : 'empty-cell' }}">
                            {{ $ders ? $ders->ders->isim : '-' }}
                        </td>
                        <td>{{ $ders ? $ders->ogretmen->isim : '-' }}</td>
                        <td>{{ $ders ? $ders->mekan->isim : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="footer">
        <p>Bu program Genetik Algoritma kullanılarak otomatik olarak oluşturulmuştur.</p>
        <p style="margin-top: 5px;">{{ config('app.name') }} - Akıllı Ders Programı Sistemi</p>
    </div>
</body>
</html>
