<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Génération de code qr</title>
</head>
<body>
    <h1>Générateur de QR Code</h1>

    <form method="POST" action="{{ route('codeqr') }}">
        @csrf
        <button type="submit">Générer un QR Code</button>
    </form>

    @if(session('qr'))
        <div style="margin-top: 20px;">
            <p><strong>Contenu du QR :</strong> {{ session('code') }}</p>
            {!! session('qr') !!}
        </div>
    @endif

    {{-- <h1>Générateur de QR Code</h1>

    <form method="POST" action="{{ route('codeqr') }}">
        @csrf
        <button type="submit">Générer un QR Code</button>

    </form>

    @isset($qr)
        <div style="margin-top: 20px;">
            <p><strong>Contenu du QR :</strong> {{ $code }}</p>
            {!! $qr !!}
        </div>
    @endisset --}}

</body>
</html>