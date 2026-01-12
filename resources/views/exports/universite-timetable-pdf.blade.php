<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ders Programı - {{ $subeType }} Şubesi</title>
    <style>
        @page {
            margin: 10mm;
            size: A4 landscape;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 6px;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 10px;
            font-weight: bold;
            margin: 3px 0;
            line-height: 1.1;
        }

        .header h2 {
            font-size: 8px;
            font-weight: bold;
            margin: 2px 0;
            line-height: 1.1;
        }

        .date-info {
            position: absolute;
            top: 5px;
            right: 15px;
            font-size: 7px;
        }

        .timetable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            table-layout: fixed;
        }

        .timetable th,
        .timetable td {
            border: 0.5px solid #000;
            padding: 1px;
            text-align: center;
            vertical-align: middle;
            font-size: 5px;
            line-height: 1;
            overflow: hidden;
            word-wrap: break-word;
        }

        .timetable th {
            background-color: #E6E6FA;
            font-weight: bold;
            font-size: 5.5px;
        }

        .header-row th {
            background-color: #E6E6FA;
            font-weight: bold;
            padding: 2px 1px;
        }

        .day-col {
            width: 4%;
            font-weight: bold;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            font-size: 5px;
        }

        .time-col {
            width: 6%;
            font-weight: bold;
            font-size: 4.5px;
        }

        .class-header {
            background-color: #E6E6FA !important;
            font-weight: bold;
            font-size: 6px;
        }

        /* Her sınıf için 4 sütun = 22.5% / 4 = 5.625% her sütun */
        .ders-kodu-col {
            width: 4.5%;
            font-size: 4px;
            padding: 0.5px;
        }

        .ders-adi-col {
            width: 8%;
            font-size: 4px;
            padding: 0.5px;
        }

        .derslik-col {
            width: 4%;
            font-size: 4px;
            padding: 0.5px;
        }

        .ogretim-elemani-col {
            width: 6%;
            font-size: 4px;
            padding: 0.5px;
        }

        .empty-cell {
            background-color: #f9f9f9;
        }

        .ders-kodu-text {
            font-weight: bold;
            color: #000080;
            font-size: 4px;
        }

        .ders-adi-text {
            color: #800000;
            font-weight: bold;
            font-size: 4px;
        }

        .derslik-text {
            color: #008000;
            font-size: 4px;
        }

        .ogretim-elemani-text {
            color: #4B0082;
            font-style: italic;
            font-size: 4px;
        }

        /* Responsive adjustments */
        @media print {
            body { font-size: 5px; }
            .timetable th, .timetable td { font-size: 4px; }
        }
    </style>
</head>
<body>
    <div class="date-info">
        {{ $tarih }}
    </div>

    <div class="header">
        <h1>KIRŞEHİR AHİ EVRAN ÜNİVERSİTESİ MÜHENDİSLİK-MİMARLIK FAKÜLTESİ</h1>
        <h2>BİLGİSAYAR MÜHENDİSLİĞİ BÖLÜMÜ {{ $akademikYil }} {{ $donem }} DÖNEMİ DERS PROGRAMI ({{ $subeType }} ŞUBESİ)</h2>
    </div>

    <table class="timetable">
        <thead>
            <tr class="header-row">
                <th rowspan="2" class="day-col">GÜN</th>
                <th rowspan="2" class="time-col">SAAT</th>
                <th colspan="4" class="class-header">I. SINIF</th>
                <th colspan="4" class="class-header">II. SINIF</th>
                <th colspan="4" class="class-header">III. SINIF</th>
                <th colspan="4" class="class-header">IV. SINIF</th>
            </tr>
            <tr class="header-row">
                <th class="ders-kodu-col">KOD</th>
                <th class="ders-adi-col">DERS</th>
                <th class="derslik-col">YER</th>
                <th class="ogretim-elemani-col">HOCA</th>
                <th class="ders-kodu-col">KOD</th>
                <th class="ders-adi-col">DERS</th>
                <th class="derslik-col">YER</th>
                <th class="ogretim-elemani-col">HOCA</th>
                <th class="ders-kodu-col">KOD</th>
                <th class="ders-adi-col">DERS</th>
                <th class="derslik-col">YER</th>
                <th class="ogretim-elemani-col">HOCA</th>
                <th class="ders-kodu-col">KOD</th>
                <th class="ders-adi-col">DERS</th>
                <th class="derslik-col">YER</th>
                <th class="ogretim-elemani-col">HOCA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($programData as $row)
            <tr>
                @if($row['gun_rowspan'] > 0)
                    <td class="day-col" rowspan="{{ $row['gun_rowspan'] }}">{{ $row['gun'] }}</td>
                @endif
                <td class="time-col">{{ $row['saat'] }}</td>

                @for($sinif = 1; $sinif <= 4; $sinif++)
                    @php
                        $sinifData = $row['siniflar'][$sinif] ?? [
                            'ders_kodu' => '',
                            'ders_adi' => '',
                            'derslik' => '',
                            'ogretim_elemani' => ''
                        ];

                        // Metinleri kısalt
                        $dersKodu = strlen($sinifData['ders_kodu']) > 8 ? substr($sinifData['ders_kodu'], 0, 8) : $sinifData['ders_kodu'];
                        $dersAdi = strlen($sinifData['ders_adi']) > 15 ? substr($sinifData['ders_adi'], 0, 15) . '.' : $sinifData['ders_adi'];
                        $derslik = strlen($sinifData['derslik']) > 6 ? substr($sinifData['derslik'], 0, 6) : $sinifData['derslik'];
                        $hoca = strlen($sinifData['ogretim_elemani']) > 12 ? substr($sinifData['ogretim_elemani'], 0, 12) . '.' : $sinifData['ogretim_elemani'];
                    @endphp

                    <td class="ders-kodu-col {{ empty($sinifData['ders_kodu']) ? 'empty-cell' : '' }}">
                        <span class="ders-kodu-text">{{ $dersKodu }}</span>
                    </td>
                    <td class="ders-adi-col {{ empty($sinifData['ders_adi']) ? 'empty-cell' : '' }}">
                        <span class="ders-adi-text">{{ $dersAdi }}</span>
                    </td>
                    <td class="derslik-col {{ empty($sinifData['derslik']) ? 'empty-cell' : '' }}">
                        <span class="derslik-text">{{ $derslik }}</span>
                    </td>
                    <td class="ogretim-elemani-col {{ empty($sinifData['ogretim_elemani']) ? 'empty-cell' : '' }}">
                        <span class="ogretim-elemani-text">{{ $hoca }}</span>
                    </td>
                @endfor
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
