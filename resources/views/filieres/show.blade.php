<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Filière</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .detail-group {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #212529;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-warning {
            background: #ffc107;
            color: black;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>👁️ Détails de la Filière</h1>

        @if(session('success'))
            <div class="alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="detail-group">
            <div class="detail-label">Code Filière:</div>
            <div class="detail-value">{{ $filiere->code_filiere }}</div>
        </div>

        <div class="detail-group">
            <div class="detail-label">Libellé:</div>
            <div class="detail-value">{{ $filiere->label_filiere }}</div>
        </div>

        <div class="detail-group">
            <div class="detail-label">Description:</div>
            <div class="detail-value">{{ $filiere->description_filiere }}</div>
        </div>

        <div class="detail-group">
            <div class="detail-label">Créé le:</div>
            <div class="detail-value">{{ $filiere->created_at->format('d/m/Y à H:i') }}</div>
        </div>

        <div class="detail-group">
            <div class="detail-label">Mis à jour le:</div>
            <div class="detail-value">{{ $filiere->updated_at->format('d/m/Y à H:i') }}</div>
        </div>

        <a href="{{ route('filieres-resources.edit', ['codeFiliere' => $filiere->code_filiere]) }}" class="btn btn-warning">
            ✏️ Modifier
        </a>

        <form action="{{ route('filieres-resources.destroy', ['codeFiliere' => $filiere->code_filiere]) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr ?')">
                🗑️ Supprimer
            </button>
        </form>

        <a href="{{ route('filieres-resources.index') }}" class="btn btn-secondary">
            ↩️ Retour à la liste
        </a>
    </div>
</body>
</html>
