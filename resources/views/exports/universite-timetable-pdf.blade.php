<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ders Programı - {{ $subeType }} Şubesi</title>
    <style>
        @page {
            margin: 15mm;
            size: A4 landscape;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 12px;
            font-weight: bold;
            margin: 5px 0;
            line-height: 1.2;
        }

        .header h2 {
            font-size: 10px;
            font-weight: bold;
            margin: 3px 0;
            line-height: 1.2;
        }

        .date-info {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 8px;
        }

        .timetable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .timetable th,
        .timetable td {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            vertical-align: middle;
            font-size: 7px;
            line-height: 1.1;
        }

        .timetable th {
            background-color: #E6E6FA;
            font-weight: bold;
            font-size: 8px;
        }

        .header-row th {
            background-color: #E6E6FA;
            font-weight: bold;
            padding: 4px 2px;
        }

        .time-col {
            width: 60px;
            font-weight: bold;
        }

        .day-col {
            width: 60px;
            font-weight: bold;
            writing-mode: vertical-rl;
            text-orientation: mixed;
        }

        .class-header {
            background-color: #E6E6FA !important;
            font-weight: bold;
            font-size: 9px;
        }

        .ders-col {
            width: 120px;
            font-size: 6px;
            padding: 1px;
        }

        .hoca-col {
            width: 80px;
            font-size: 6px;
            padding: 1px;
        }

        .yer-col {
            width: 40px;
            font-size: 6px;
            padding: 1px;
        }

        .signature-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .signature-left,
        .signature-right {
            text-align: center;
            font-weight: bold;
            font-size: 9px;
        }

        .signature-left {
            margin-left: 50px;
        }

        .signature-right {
            margin-right: 50px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 150px;
            height: 40px;
            margin: 10px auto;
        }

        .empty-cell {
            background-color: #f9f9f9;
        }

        .ders-text {
            font-weight: bold;
            color: #000080;
        }

        .hoca-text {
            color: #800000;
            font-style: italic;
        }

        .yer-text {
            color: #008000;
            font-size: 6px;
        }
    </style>
</head>
<body>
    <div class="date-info">
        Tarih: {{ $tarih }}
    </div>

    <div class="header">
        <h1>KIRŞEHİR AHİ EVRAN ÜNİVERSİTESİ MÜHENDİSLİK-MİMARLIK FAKÜLTESİ</h1>
        <h2>BİLGİSAYAR MÜHENDİSLİĞİ BÖLÜMÜ (TÜRKÇE) {{ $akademikYil }} EĞİTİM-ÖĞRETİM YILI {{ $donem }} DÖNEMİ HAFTALIK DERS PROGRAMI ({{ $subeType }} ŞUBESİ)</h2>
    </div>

    <table class="timetable">
        <thead>
            <tr class="header-row">
                <th rowspan="2" class="time-col">SAAT</th>
                <th rowspan="2" class="day-col">GÜN</th>
                <th colspan="3" class="class-header">1-{{ $subeType }} SINIFI</th>
                <th colspan="3" class="class-header">2-{{ $subeType }} SINIFI</th>
                <th colspan="3" class="class-header">3-{{ $subeType }} SINIFI</th>
                <th colspan="3" class="class-header">4-{{ $subeType }} SINIFI</th>
            </tr>
            <tr class="header-row">
                <th class="ders-col">DERS</th>
                <th class="hoca-col">HOCA</th>
                <th class="yer-col">YER</th>
                <th class="ders-col">DERS</th>
                <th class="hoca-col">HOCA</th>
                <th class="yer-col">YER</th>
                <th class="ders-col">DERS</th>
                <th class="hoca-col">HOCA</th>
                <th class="yer-col">YER</th>
                <th class="ders-col">DERS</th>
                <th class="hoca-col">HOCA</th>
                <th class="yer-col">YER</th>
            </tr>
        </thead>
        <tbody>
            @foreach($programData as $row)
            <tr>
                <td class="time-col">{{ $row['saat'] }}</td>
                @if($row['gun_rowspan'] > 0)
                    <td class="day-col" rowspan="{{ $row['gun_rowspan'] }}">{{ $row['gun'] }}</td>
                @endif

                @for($sinif = 1; $sinif <= 4; $sinif++)
                    @php $sinifData = $row['siniflar'][$sinif] ?? ['ders' => '', 'hoca' => '', 'yer' => ''] @endphp
                    <td class="ders-col {{ empty($sinifData['ders']) ? 'empty-cell' : '' }}">
                        <span class="ders-text">{{ $sinifData['ders'] }}</span>
                    </td>
                    <td class="hoca-col {{ empty($sinifData['hoca']) ? 'empty-cell' : '' }}">
                        <span class="hoca-text">{{ $sinifData['hoca'] }}</span>
                    </td>
                    <td class="yer-col {{ empty($sinifData['yer']) ? 'empty-cell' : '' }}">
                        <span class="yer-text">{{ $sinifData['yer'] }}</span>
                    </td>
                @endfor
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-left">
            <div>BÖLÜM BAŞKANI</div>
            <div class="signature-line"></div>
            <div>Doç. Dr. Öğr. Üyesi Ahmet BİLİR</div>
        </div>

        <div class="signature-right">
            <div>DEKAN</div>
            <div class="signature-line"></div>
            <div>Prof. Dr. Cengiz ELDEM</div>
        </div>
    </div>
</body>
</html>
