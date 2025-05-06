<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture - Poissonnerie</title>
    <style>
        body {
            background-color: white;
            color: black;
            padding: 1rem;
            font-family: sans-serif;
            font-size: 0.875rem; /* équivalent à text-sm */
        }

        @media print {
            body {
                width: 105mm;
                height: 148mm;
                margin: 0;
            }
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .text-lg {
            font-size: 1.125rem;
        }

        .font-bold {
            font-weight: bold;
        }

        .font-semibold {
            font-weight: 600;
        }

        .uppercase {
            text-transform: uppercase;
        }

        table {
            width: 100%;
            text-align: left;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }

        th, td {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .border-b {
            border-bottom: 1px solid black;
        }

        .italic {
            font-style: italic;
        }
    </style>
</head>

<body>
    <!-- En-tête -->
    <div class="text-center mb-4">
        <h1 class="text-lg font-bold uppercase">Poissonnerie La Bonne Marée</h1>
        <p>Rue des Pêcheurs, Cotonou</p>
        <p>Tel : +229 90 00 00 00</p>
    </div>

    <!-- Infos facture -->
    <div class="mb-4">
        <p><span class="font-semibold">Facture N°:</span> {{ $vente->id }}</p>
        <p><span class="font-semibold">Date:</span> {{ $vente->created_at }}</p>
        <p><span class="font-semibold">Client:</span> {{ $vente->buyer_infos == null ? "__" : $vente->buyer_infos->nom }}</p>
    </div>

    <!-- Tableau des produits -->
    <table>
        <thead>
            <tr class="border-b">
                <th>Produit</th>
                <th>Qté</th>
                <th>PU</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vente->selledProducts as $selled_product)
                <tr>
                    <td>{{$selled_product->product->name}}</td>
                    <td>{{$selled_product->quantity}} {{ $selled_product->type == 'gros' ? 'carton(s)' : 'kg' }}</td>
                    <td>{{$selled_product->type == 'gros' ? $selled_product->product->price_carton : $selled_product->product->price_kilo}} F</td>
                    <td>{{ $selled_product->total_price }} F</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total -->
    <div class="text-right mb-4">
        <p class="font-bold">Total à payer : {{$vente->total_price}} F CFA</p>
    </div>

    <!-- Remerciement -->
    <div class="text-center">
        <p class="italic">Merci pour votre achat et à bientôt !</p>
    </div>
</body>

</html>
