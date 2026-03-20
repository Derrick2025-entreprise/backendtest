<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Filières</title>
    <style>
        @page {
            margin: 100px 50px 120px 50px;
            /* Initialisation des compteurs de page */
            counter-increment: page;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            /* Initialisation du compteur principal */
            counter-reset: page-total;
        }

        /* En-tête fixe */
        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 70px;
            text-align: center;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }

        header h1 {
            margin: 0;
            padding: 10px 0;
            color: #4CAF50;
            font-size: 24px;
        }

        header .subtitle {
            color: #666;
            font-size: 12px;
        }

        /* Pied de page fixe avec pagination */
        footer {
            position: fixed;
            bottom: -100px;
            left: 0;
            right: 0;
            height: 80px;
            border-top: 2px solid #4CAF50;
            padding-top: 10px;
            font-size: 10px;
            color: #666;
        }

        footer .footer-content {
            display: table;
            width: 100%;
        }

        footer .footer-left,
        footer .footer-center,
        footer .footer-right {
            display: table-cell;
            width: 33.33%;
            vertical-align: middle;
        }

        footer .footer-left {
            text-align: left;
        }

        footer .footer-center {
            text-align: center;
            font-weight: bold;
            color: #4CAF50;
        }

        footer .footer-right {
            text-align: right;
        }

        /* Correction de la pagination */
        footer .page-number:before {
            content: counter(page);
        }

        /* Contenu principal */
        main {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            font-size: 11px;
        }

        /* Compteur de filières */
        .stats {
            margin-top: 20px;
            padding: 10px;
            background-color: #e8f5e9;
            border-left: 4px solid #4CAF50;
            font-size: 12px;
        }

        .stats strong {
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <!-- En-tête sur chaque page -->
    <header>
        <h1>📚 Liste des Filières</h1>
        <div class="subtitle">Document généré le {{ date('d/m/Y à H:i') }}</div>
    </header>

    <!-- Pied de page sur chaque page -->
    <footer>
        <div class="footer-content">
            <div class="footer-left">
                {{ config('app.name', 'Application') }}<br>
                © {{ date('Y') }} - Tous droits réservés
            </div>
            <div class="footer-center">
                <span class="page-number"></span>
            </div>
            <div class="footer-right">
                Généré le {{ date('d/m/Y') }}<br>
                à {{ date('H:i:s') }}
            </div>
        </div>
    </footer>

    <!-- Contenu principal -->
    <main>
        <div class="stats">
            <strong>Nombre total de filières :</strong> {{ count($filieres) }}
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Code</th>
                    <th style="width: 30%;">Label</th>
                    <th style="width: 55%;">Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach($filieres as $filiere)
                <tr>
                    <td><strong>{{ $filiere->code_filiere }}</strong></td>
                    <td>{{ $filiere->label_filiere }}</td>
                    <td>{{ $filiere->description_filiere }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>
</html>
