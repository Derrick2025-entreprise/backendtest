<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Throwable;

class FiliereController extends Controller
{
    /**
 * @OA\Get(
 *      path="/api/filieres",
 *      operationId="getFilieresList",
 *      tags={"Filières"},
 *      summary="Obtenir la liste des filières",
 *      description="Retourne la liste de toutes les filières avec leurs niveaux associés",
 *      @OA\Response(
 *          response=200,
 *          description="Opération réussie",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(property="code_filiere", type="string", example="INFO"),
 *                  @OA\Property(property="label_filiere", type="string", example="Informatique"),
 *                  @OA\Property(property="description_filiere", type="string", example="Filière Informatique"),
 *                  @OA\Property(property="created_at", type="string", format="date-time"),
 *                  @OA\Property(property="updated_at", type="string", format="date-time"),
 *                  @OA\Property(
 *                      property="niveaux",
 *                      type="array",
 *                      description="Liste des niveaux associés à la filière",
 *                      @OA\Items(
 *                          @OA\Property(property="code_niveau", type="integer", example=1),
 *                          @OA\Property(property="label_niveau", type="string", example="Niveau 1"),
 *                          @OA\Property(property="description_niveau", type="string", example="Première année"),
 *                          @OA\Property(property="code_filiere", type="string", example="INFO"),
 *                          @OA\Property(property="created_at", type="string", format="date-time"),
 *                          @OA\Property(property="updated_at", type="string", format="date-time")
 *                      )
 *                  )
 *              )
 *          )
 *      )
 * )
 */
    public function index()
    {
        $user = auth()->user();
        $ip = request()->ip();

        Log::channel('daily')->info('📖 CONSULTATION - Liste des Filières', [
            'action' => 'INDEX',
            'resource' => 'FILIERE',
            'user_id' => $user->code_personnel ?? 'Non authentifié',
            'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
            'ip' => $ip,
            'timestamp' => now()->toDateTimeString()
        ]);

        $filieres = Filiere::with('niveaux')->paginate(10);

        Log::channel('daily')->info('✅ Liste Filières récupérée', [
            'total_records' => $filieres->total(),
            'current_page' => $filieres->currentPage(),
            'per_page' => $filieres->perPage(),
            'with_relations' => 'niveaux'
        ]);

        return response()->json($filieres, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/filieres",
     *      operationId="storeFiliere",
     *      tags={"Filières"},
     *      summary="Créer une nouvelle filière",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"code_filiere","label_filiere","description_filiere"},
     *              @OA\Property(property="code_filiere", type="string"),
     *              @OA\Property(property="label_filiere", type="string"),
     *              @OA\Property(property="description_filiere", type="string")
     *          )
     *      ),
     *      @OA\Response(response=201, description="Filière créée"),
     *      @OA\Response(response=422, description="Erreur validation"),
     *      @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $ip = $request->ip();

        Log::channel('daily')->info('➕ CRÉATION - Nouvelle Filière', [
            'action' => 'STORE',
            'resource' => 'FILIERE',
            'user_id' => $user->code_personnel ?? 'Non authentifié',
            'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
            'ip' => $ip,
            'input_data' => $request->all(),
            'timestamp' => now()->toDateTimeString()
        ]);

        try {
            $validatedData = $request->validate([
                'code_filiere' => 'required|min:5|string|unique:filiere,code_filiere',
                'label_filiere' => 'required|min:5|string',
                'description_filiere' => 'required'
            ]);

            $filiere = Filiere::create($validatedData);

            Log::channel('daily')->info('✅ FILIÈRE CRÉÉE AVEC SUCCÈS', [
                'code_filiere' => $filiere->code_filiere,
                'label_filiere' => $filiere->label_filiere,
                'description_filiere' => $filiere->description_filiere,
                'created_by' => $user->code_personnel ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(["message" => "Filière créée avec succès"], 201);

        } catch(Throwable $th) {
            Log::channel('daily')->error('❌ ERREUR CRÉATION FILIÈRE', [
                'user_id' => $user->code_personnel ?? 'N/A',
                'ip' => $ip,
                'error_message' => $th->getMessage(),
                'error_file' => $th->getFile(),
                'error_line' => $th->getLine(),
                'input_data' => $request->all(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/filieres/{codeFiliere}",
     *      operationId="showFiliere",
     *      tags={"Filières"},
     *      summary="Obtenir une filière par son code",
     *      description="Retourne les informations d'une filière spécifique",
     *      @OA\Parameter(
     *          name="codeFiliere",
     *          in="path",
     *          description="Code unique de la filière",
     *          required=true,
     *          @OA\Schema(type="string", example="INFO-001")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Filière trouvée",
     *          @OA\JsonContent(
     *              @OA\Property(property="code_filiere", type="string"),
     *              @OA\Property(property="label_filiere", type="string"),
     *              @OA\Property(property="description_filiere", type="string"),
     *              @OA\Property(property="created_at", type="string", format="date-time"),
     *              @OA\Property(property="updated_at", type="string", format="date-time"),
     *              @OA\Property(
 *                      property="niveaux",
 *                      type="array",
 *                      description="Liste des niveaux associés à la filière",
 *                      @OA\Items(
 *                          @OA\Property(property="code_niveau", type="integer", example=1),
 *                          @OA\Property(property="label_niveau", type="string", example="Niveau 1"),
 *                          @OA\Property(property="description_niveau", type="string", example="Première année"),
 *                          @OA\Property(property="code_filiere", type="string", example="INFO"),
 *                          @OA\Property(property="created_at", type="string", format="date-time"),
 *                          @OA\Property(property="updated_at", type="string", format="date-time")
 *                      )
 *                  )
     *          )
     *      ),
     *      @OA\Response(response=404, description="Filière non trouvée")
     * )
     */
    public function show(string $codeFiliere)
    {
        $user = auth()->user();
        $ip = request()->ip();

        Log::channel('daily')->info('👁️ CONSULTATION - Détail Filière', [
            'action' => 'SHOW',
            'resource' => 'FILIERE',
            'code_filiere' => $codeFiliere,
            'user_id' => $user->code_personnel ?? 'Non authentifié',
            'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
            'ip' => $ip,
            'timestamp' => now()->toDateTimeString()
        ]);

        try {
            $filiere = Filiere::findOrFail($codeFiliere);

            Log::channel('daily')->info('✅ Filière trouvée', [
                'code_filiere' => $filiere->code_filiere,
                'label_filiere' => $filiere->label_filiere
            ]);

            return response()->json($filiere, 200);

        } catch(Throwable $th) {
            Log::channel('daily')->warning('⚠️ FILIÈRE NON TROUVÉE', [
                'code_filiere' => $codeFiliere,
                'user_id' => $user->code_personnel ?? 'N/A',
                'ip' => $ip,
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(['message' => 'Filière non trouvée'], 404);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/filieres/{codeFiliere}",
     *      operationId="updateFiliere",
     *      tags={"Filières"},
     *      summary="Mettre à jour une filière",
     *      description="Met à jour les informations d'une filière existante",
     *      @OA\Parameter(
     *          name="codeFiliere",
     *          in="path",
     *          description="Code unique de la filière",
     *          required=true,
     *          @OA\Schema(type="string", example="INFO-001")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"code_filiere","label_filiere","description_filiere"},
     *              @OA\Property(property="code_filiere", type="string"),
     *              @OA\Property(property="label_filiere", type="string"),
     *              @OA\Property(property="description_filiere", type="string")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Filière mise à jour"),
     *      @OA\Response(response=404, description="Filière non trouvée"),
     *      @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function update(Request $request, string $codeFiliere)
    {
        $user = auth()->user();
        $ip = $request->ip();

        try {
            $filiere = Filiere::findOrFail($codeFiliere);

            // Sauvegarde des données avant modification
            $oldData = $filiere->toArray();

            Log::channel('daily')->info('✏️ MODIFICATION - Filière', [
                'action' => 'UPDATE',
                'resource' => 'FILIERE',
                'code_filiere' => $codeFiliere,
                'user_id' => $user->code_personnel ?? 'Non authentifié',
                'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
                'ip' => $ip,
                'data_before' => $oldData,
                'input_data' => $request->all(),
                'timestamp' => now()->toDateTimeString()
            ]);

            $validatedData = $request->validate([
                'code_filiere' => 'sometimes|min:5|string|unique:filiere,code_filiere,' . $codeFiliere . ',code_filiere',
                'label_filiere' => 'sometimes|min:5|string',
                'description_filiere' => 'sometimes'
            ]);

            $filiere->update($validatedData);

            // Données après modification
            $newData = $filiere->fresh()->toArray();

            Log::channel('daily')->info('✅ FILIÈRE MODIFIÉE AVEC SUCCÈS', [
                'code_filiere' => $filiere->code_filiere,
                'label_filiere' => $filiere->label_filiere,
                'data_after' => $newData,
                'changes' => array_diff_assoc($newData, $oldData),
                'modified_by' => $user->code_personnel ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                "message" => "Filière mise à jour avec succès"
            ], 200);

        } catch(Throwable $th) {
            Log::channel('daily')->error('❌ ERREUR MODIFICATION FILIÈRE', [
                'code_filiere' => $codeFiliere,
                'user_id' => $user->code_personnel ?? 'N/A',
                'ip' => $ip,
                'error_message' => $th->getMessage(),
                'error_file' => $th->getFile(),
                'error_line' => $th->getLine(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'message' => 'Erreur lors de la mise à jour : ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/filieres/{codeFiliere}",
     *      operationId="deleteFiliere",
     *      tags={"Filières"},
     *      summary="Supprimer une filière",
     *      @OA\Parameter(
     *          name="codeFiliere",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="string", example="INFO-001")
     *      ),
     *      @OA\Response(response=200, description="Filière supprimée"),
     *      @OA\Response(response=404, description="Filière non trouvée"),
     *      @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function destroy(string $codeFiliere)
    {
        $user = auth()->user();
        $ip = request()->ip();

        try {
            $filiere = Filiere::findOrFail($codeFiliere);

            // Sauvegarde des données avant suppression
            $deletedData = $filiere->toArray();

            Log::channel('daily')->warning('🗑️ SUPPRESSION - Filière', [
                'action' => 'DELETE',
                'resource' => 'FILIERE',
                'code_filiere' => $codeFiliere,
                'user_id' => $user->code_personnel ?? 'Non authentifié',
                'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
                'ip' => $ip,
                'deleted_data' => $deletedData,
                'timestamp' => now()->toDateTimeString()
            ]);

            $filiere->delete();

            Log::channel('daily')->warning('✅ FILIÈRE SUPPRIMÉE DÉFINITIVEMENT', [
                'code_filiere' => $codeFiliere,
                'label_filiere' => $deletedData['label_filiere'],
                'deleted_by' => $user->code_personnel ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(["message" => "Filière supprimée"], 200);

        } catch(Throwable $th) {
            Log::channel('daily')->error('❌ ERREUR SUPPRESSION FILIÈRE', [
                'code_filiere' => $codeFiliere,
                'user_id' => $user->code_personnel ?? 'N/A',
                'ip' => $ip,
                'error_message' => $th->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }

    public function exportPdf()
    {
        $user = auth()->user();
        $ip = request()->ip();

        Log::channel('daily')->info('📄 EXPORT PDF - Liste des Filières', [
            'action' => 'EXPORT_PDF',
            'resource' => 'FILIERE',
            'user_id' => $user->code_personnel ?? 'Non authentifié',
            'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
            'ip' => $ip,
            'timestamp' => now()->toDateTimeString()
        ]);

        try {
            $filieres = Filiere::all();

            Log::channel('daily')->info('PDF en cours de génération', [
                'total_filieres' => $filieres->count()
            ]);

            $pdf = FacadePdf::loadView('filieres.pdf', compact('filieres'));

            Log::channel('daily')->info('✅ PDF GÉNÉRÉ ET TÉLÉCHARGÉ', [
                'filename' => 'filieres.pdf',
                'records_count' => $filieres->count(),
                'generated_by' => $user->code_personnel ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
            ]);

            return $pdf->download('filieres.pdf');

        } catch(Throwable $th) {
            Log::channel('daily')->error('❌ ERREUR GÉNÉRATION PDF', [
                'user_id' => $user->code_personnel ?? 'N/A',
                'ip' => $ip,
                'error_message' => $th->getMessage(),
                'error_file' => $th->getFile(),
                'error_line' => $th->getLine(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'message' => 'Erreur lors de la génération du PDF'
            ], 500);
        }
    }

    public function exportExcel()
{
    $user = auth()->user();
    $ip = request()->ip();

    Log::channel('daily')->info('📊 EXPORT EXCEL/CSV - Liste des Filières', [
        'action' => 'EXPORT_EXCEL',
        'resource' => 'FILIERE',
        'user_id' => $user->code_personnel ?? 'Non authentifié',
        'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
        'ip' => $ip,
        'timestamp' => now()->toDateTimeString()
    ]);

    try {
        $filieres = Filiere::all();

        $filename = 'filieres_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($filieres) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8
            fwrite($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // En-têtes
            fputcsv($file, [
                'CODE FILIÈRE',
                'LABEL FILIÈRE',
                'DESCRIPTION',
                'DATE DE CRÉATION',
                'DATE DE MODIFICATION'
            ], ';');

            // Données avec FORMAT DE DATE MODIFIÉ
            foreach ($filieres as $filiere) {
                fputcsv($file, [
                    $filiere->code_filiere,
                    $filiere->label_filiere,
                    $filiere->description_filiere,
                    // FORMAT ISO (reconnu par Excel)
                    $filiere->created_at ? "'" . $filiere->created_at->format('d/m/Y H:i') : '-',
                    $filiere->updated_at ? "'" . $filiere->updated_at->format('d/m/Y H:i') : '-'
                ], ';');
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);

    } catch(Throwable $th) {
        Log::channel('daily')->error('❌ ERREUR GÉNÉRATION EXCEL/CSV', [
            'user_id' => $user->code_personnel ?? 'N/A',
            'ip' => $ip,
            'error_message' => $th->getMessage(),
            'error_file' => $th->getFile(),
            'error_line' => $th->getLine(),
            'timestamp' => now()->toDateTimeString()
        ]);

        return response()->json([
            'message' => 'Erreur lors de la génération du fichier Excel/CSV'
        ], 500);
    }
}
}
