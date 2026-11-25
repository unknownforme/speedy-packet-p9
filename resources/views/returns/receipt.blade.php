<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #2d1b18; }
        .header { text-align: center; border-bottom: 2px solid #a04000; padding-bottom: 10px; margin-bottom: 20px; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; text-transform: uppercase; font-size: 14px; color: #a04000; margin-bottom: 5px; }
        .details { font-size: 16px; }
        .footer { margin-top: 40px; font-size: 12px; text-align: center; color: #666; border-top: 1px solid #ccc; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Speedy Packet</h1>
        <p>Retourbewijs</p>
    </div>

    <div class="section">
        <div class="section-title">Pakket Informatie</div>
        <div class="details">
            <strong>Tracking Nummer:</strong> {{ $package->tracking_number }}<br>
            <strong>Status:</strong> {{ $package->current_status }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Retour Details</div>
        <div class="details">
            <strong>Datum aanvraag:</strong> {{ $return->requested_at->format('d M Y, H:i') }}<br>
            <strong>Reden:</strong> {{ $return->reason }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Ontvanger Adres</div>
        <div class="details">
            {{ $package->receiver_street }} {{ $package->receiver_house_number }}{{ $package->receiver_house_number_suffix }}<br>
            {{ $package->receiver_city }}, {{ $package->destination_province }}
        </div>
    </div>

    <div class="footer">
        <p>Bedankt voor het gebruik van Speedy Packet.</p>
        <p>Bewaar dit bewijs bij het versturen van uw retour.</p>
    </div>
</body>
</html>
