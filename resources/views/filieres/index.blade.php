<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Filières</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-warning {
            background: #ffc107;
            color: black;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .actions {
            display: flex;
            gap: 10px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 12px;
            padding: 0;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s;
            overflow: hidden;
        }

        .modal-header {
            background: #dc3545;
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 20px;
        }

        .modal-icon {
            font-size: 32px;
        }

        .modal-body {
            padding: 30px;
        }

        .modal-body p {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 16px;
            line-height: 1.5;
        }

        .filiere-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border-left: 4px solid #dc3545;
        }

        .filiere-info strong {
            color: #dc3545;
        }

        .modal-footer {
            padding: 20px 30px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Liste des Filières</h1>

        <!-- Messages flash -->
        @if(session('success'))
            <div class="alert alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                ❌ {{ session('error') }}
            </div>
        @endif

        <!-- Bouton Créer -->
        <a href="{{ route('filieres-resources.create') }}" class="btn btn-primary">
            ➕ Créer une nouvelle filière
        </a>

        <!-- Tableau des filières -->
        @if($filieres->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Libellé</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($filieres as $filiere)
                        <tr>
                            <td><strong>{{ $filiere->code_filiere }}</strong></td>
                            <td>{{ $filiere->label_filiere }}</td>
                            <td>{{ Str::limit($filiere->description_filiere, 50) }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('filieres-resources.show', ['codeFiliere' => $filiere->code_filiere]) }}" class="btn btn-success">
                                        👁️ Voir
                                    </a>
                                    <a href="{{ route('filieres-resources.edit', ['codeFiliere' => $filiere->code_filiere]) }}" class="btn btn-warning">
                                        ✏️ Modifier
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-danger"
                                        onclick="openDeleteModal('{{ $filiere->code_filiere }}', '{{ $filiere->label_filiere }}')"
                                    >
                                        🗑️ Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="margin-top: 20px; color: #6c757d;">
                Aucune filière trouvée. Commencez par en créer une !
            </p>
        @endif
    </div>

    <!-- Modal de confirmation de suppression -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-icon">⚠️</span>
                <h2>Confirmation de suppression</h2>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette filière ?</p>
                <div class="filiere-info">
                    <p><strong>Code :</strong> <span id="filiereCode"></span></p>
                    <p style="margin: 5px 0 0 0;"><strong>Libellé :</strong> <span id="filiereLabel"></span></p>
                </div>
                <p style="margin-top: 15px; color: #dc3545; font-weight: bold;">
                    ⚠️ Cette action est irréversible !
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                    ↩️ Annuler
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        🗑️ Oui, supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Ouvrir le modal
        function openDeleteModal(codeFiliere, labelFiliere) {
            // Mettre à jour les informations de la filière
            document.getElementById('filiereCode').textContent = codeFiliere;
            document.getElementById('filiereLabel').textContent = labelFiliere;

            // Mettre à jour l'action du formulaire
            const form = document.getElementById('deleteForm');
            form.action = `/filieres/${codeFiliere}`;

            // Afficher le modal
            document.getElementById('deleteModal').classList.add('show');
        }

        // Fermer le modal
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }

        // Fermer le modal en cliquant à l'extérieur
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeDeleteModal();
            }
        }

        // Fermer avec la touche Échap
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
