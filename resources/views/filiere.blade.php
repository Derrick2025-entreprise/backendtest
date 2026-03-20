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
        .btn-success:hover {
            background: #218838;
        }
        .btn-warning {
            background: #ffc107;
            color: black;
        }
        .btn-warning:hover {
            background: #e0a800;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
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
            overflow-y: auto;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background-color: white;
            border-radius: 12px;
            padding: 0;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s;
            overflow: hidden;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 20px 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid #dee2e6;
            flex-shrink: 0;
        }

        .modal-header.create {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .modal-header.view {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .modal-header.edit {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .modal-header.delete {
            background: #dc3545;
            color: white;
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
            overflow-y: auto;
            flex: 1;
        }

        .modal-footer {
            padding: 20px 30px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
            flex-shrink: 0;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            transition: all 0.3s;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
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

        /* Detail View Styles */
        .detail-group {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            color: #212529;
            font-size: 16px;
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

        .close-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 28px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            line-height: 1;
            transition: transform 0.2s;
        }

        .close-btn:hover {
            transform: rotate(90deg);
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
        <button type="button" class="btn btn-primary" onclick="openCreateModal()">
            ➕ Créer une nouvelle filière
        </button>

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
                                    <button
                                        type="button"
                                        class="btn btn-success"
                                        onclick='openViewModal(@json($filiere))'
                                    >
                                        👁️ Voir
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-warning"
                                        onclick='openEditModal(@json($filiere))'
                                    >
                                        ✏️ Modifier
                                    </button>
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

    <!-- Modal Créer -->
    <div id="createModal" class="modal">
        <div class="modal-content">
            <div class="modal-header create">
                <span class="modal-icon">➕</span>
                <h2>Créer une nouvelle filière</h2>
                <button class="close-btn" onclick="closeCreateModal()">&times;</button>
            </div>
            <form action="{{ route('filieres-resources.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="create_code_filiere">Code Filière *</label>
                        <input
                            type="text"
                            id="create_code_filiere"
                            name="code_filiere"
                            placeholder="Ex: INFO-001"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="create_label_filiere">Libellé *</label>
                        <input
                            type="text"
                            id="create_label_filiere"
                            name="label_filiere"
                            placeholder="Ex: Informatique"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="create_description_filiere">Description *</label>
                        <textarea
                            id="create_description_filiere"
                            name="description_filiere"
                            placeholder="Décrivez la filière..."
                            required
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeCreateModal()">
                        ↩️ Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        💾 Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Voir -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header view">
                <span class="modal-icon">👁️</span>
                <h2>Détails de la filière</h2>
                <button class="close-btn" onclick="closeViewModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="detail-group">
                    <div class="detail-label">Code Filière</div>
                    <div class="detail-value" id="view_code_filiere"></div>
                </div>

                <div class="detail-group">
                    <div class="detail-label">Libellé</div>
                    <div class="detail-value" id="view_label_filiere"></div>
                </div>

                <div class="detail-group">
                    <div class="detail-label">Description</div>
                    <div class="detail-value" id="view_description_filiere"></div>
                </div>

                <div class="detail-group">
                    <div class="detail-label">Créé le</div>
                    <div class="detail-value" id="view_created_at"></div>
                </div>

                <div class="detail-group">
                    <div class="detail-label">Mis à jour le</div>
                    <div class="detail-value" id="view_updated_at"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeViewModal()">
                    ↩️ Fermer
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Modifier -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header edit">
                <span class="modal-icon">✏️</span>
                <h2>Modifier la filière</h2>
                <button class="close-btn" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_code_filiere">Code Filière *</label>
                        <input
                            type="text"
                            id="edit_code_filiere"
                            name="code_filiere"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="edit_label_filiere">Libellé *</label>
                        <input
                            type="text"
                            id="edit_label_filiere"
                            name="label_filiere"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="edit_description_filiere">Description *</label>
                        <textarea
                            id="edit_description_filiere"
                            name="description_filiere"
                            required
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                        ↩️ Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        💾 Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Supprimer -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header delete">
                <span class="modal-icon">⚠️</span>
                <h2>Confirmation de suppression</h2>
                <button class="close-btn" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette filière ?</p>
                <div class="filiere-info">
                    <p><strong>Code :</strong> <span id="delete_code"></span></p>
                    <p style="margin: 5px 0 0 0;"><strong>Libellé :</strong> <span id="delete_label"></span></p>
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
        // ===== Modal Créer =====
        function openCreateModal() {
            document.getElementById('createModal').classList.add('show');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.remove('show');
            // Réinitialiser le formulaire
            document.getElementById('createModal').querySelector('form').reset();
        }

        // ===== Modal Voir =====
        function openViewModal(filiere) {
            document.getElementById('view_code_filiere').textContent = filiere.code_filiere;
            document.getElementById('view_label_filiere').textContent = filiere.label_filiere;
            document.getElementById('view_description_filiere').textContent = filiere.description_filiere;

            // Formater les dates
            const createdDate = new Date(filiere.created_at);
            const updatedDate = new Date(filiere.updated_at);

            document.getElementById('view_created_at').textContent = createdDate.toLocaleString('fr-FR');
            document.getElementById('view_updated_at').textContent = updatedDate.toLocaleString('fr-FR');

            document.getElementById('viewModal').classList.add('show');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.remove('show');
        }

        // ===== Modal Modifier =====
        function openEditModal(filiere) {
            document.getElementById('edit_code_filiere').value = filiere.code_filiere;
            document.getElementById('edit_label_filiere').value = filiere.label_filiere;
            document.getElementById('edit_description_filiere').value = filiere.description_filiere;

            // Mettre à jour l'action du formulaire
            const form = document.getElementById('editForm');
            form.action = `/filieres-resources/${filiere.code_filiere}`;

            document.getElementById('editModal').classList.add('show');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('show');
        }

        // ===== Modal Supprimer =====
        function openDeleteModal(codeFiliere, labelFiliere) {
            document.getElementById('delete_code').textContent = codeFiliere;
            document.getElementById('delete_label').textContent = labelFiliere;

            const form = document.getElementById('deleteForm');
            form.action = `/filieres-resources/${codeFiliere}`;

            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }

        // ===== Fermeture globale =====
        // Fermer en cliquant à l'extérieur
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('show');
            }
        }

        // Fermer avec la touche Échap
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal').forEach(modal => {
                    modal.classList.remove('show');
                });
            }
        });

        // Ouvrir automatiquement le modal de création si erreurs de validation
        @if($errors->any() && old('_method') === null)
            openCreateModal();
            // Pré-remplir avec les anciennes valeurs
            @if(old('code_filiere'))
                document.getElementById('create_code_filiere').value = "{{ old('code_filiere') }}";
            @endif
            @if(old('label_filiere'))
                document.getElementById('create_label_filiere').value = "{{ old('label_filiere') }}";
            @endif
            @if(old('description_filiere'))
                document.getElementById('create_description_filiere').value = "{{ old('description_filiere') }}";
            @endif
        @endif

        // Afficher les erreurs de validation dans les modals
        @if($errors->any())
            const errorHtml = `
                <div class="alert alert-error" style="margin-bottom: 20px;">
                    <strong>❌ Erreurs de validation :</strong>
                    <ul style="margin: 10px 0 0 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            `;

            // Ajouter les erreurs au début du modal body
            const modalBody = document.querySelector('.modal.show .modal-body');
            if (modalBody) {
                modalBody.insertAdjacentHTML('afterbegin', errorHtml);
            }
        @endif
    </script>
</body>
</html>
