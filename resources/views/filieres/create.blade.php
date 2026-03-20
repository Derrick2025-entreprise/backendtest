<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Filière</title>
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
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #495057;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>➕ Créer une nouvelle Filière</h1>

        @if(session('error'))
            <div class="alert-error">
                ❌ {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('filieres-resources.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="code_filiere">Code Filière *</label>
                <input
                    type="text"
                    id="code_filiere"
                    name="code_filiere"
                    value="{{ old('code_filiere') }}"
                    placeholder="Ex: INFO-001"
                    required
                >
                @error('code_filiere')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="label_filiere">Libellé *</label>
                <input
                    type="text"
                    id="label_filiere"
                    name="label_filiere"
                    value="{{ old('label_filiere') }}"
                    placeholder="Ex: Informatique"
                    required
                >
                @error('label_filiere')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description_filiere">Description *</label>
                <textarea
                    id="description_filiere"
                    name="description_filiere"
                    placeholder="Décrivez la filière..."
                    required
                >{{ old('description_filiere') }}</textarea>
                @error('description_filiere')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                💾 Enregistrer
            </button>
            <a href="{{ route('filieres.index') }}" class="btn btn-secondary">
                ↩️ Retour
            </a>
        </form>
    </div>
</body>
</html>
