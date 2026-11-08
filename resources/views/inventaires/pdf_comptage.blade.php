<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body{
    font-family: DejaVu Sans, sans-serif;
    font-size:12px;
}

h2{
    text-align:center;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    border:1px solid #ccc;
    padding:8px;
}

th{
    background:#f5f5f5;
}

</style>
</head>

<body>

<h2>Inventaire : {{ $inventaire->titre }}</h2>

<table>
<thead>
<tr>
<th>#</th>
<th>Produit</th>
<th>Catégorie</th>
<th>Stock physique</th>
</tr>
</thead>

<tbody>
@foreach($lignes as $i => $ligne)
<tr>
<td>{{ $i+1 }}</td>
<td>{{ $ligne->produit->libelle }}</td>
<td>{{ $ligne->produit->categorie->nom }}</td>
<td></td>
</tr>
@endforeach
</tbody>

</table>

</body>
</html>