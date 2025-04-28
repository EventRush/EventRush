
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Billet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-xl shadow-lg text-center">
        <h1 class="text-2xl font-bold mb-4">Votre Billet</h1>
        <div class="mb-4">
            {!! $qrCode !!}
        </div>
        <div class="text-lg">
            <p><strong>Nom :</strong> {{ $billet->utilisateur->nom }}</p>
            <p><strong>Email :</strong> {{ $billet->utilisateur->email }}</p>
        </div>
    </div>

</body>
</html>
