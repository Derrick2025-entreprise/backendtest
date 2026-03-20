<?php

namespace App\Http\Controllers;

use App\Models\UE;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class UEController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/ue",
     *      operationId="getUEList",
     *      tags={"UEs"},
     *      summary="Obtenir la liste des UEs",
     *      description="Retourne la liste de toutes les UEs avec leurs EC associées",
     *      @OA\Response(
     *          response=200,
     *          description="Opération réussie",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="code_ue", type="string", example="UE101"),
     *                  @OA\Property(property="label_ue", type="string", example="Algorithmes"),
     *                  @OA\Property(property="description_ue", type="string", example="Fondamentaux des algorithmes"),
     *                  @OA\Property(property="code_niveau", type="integer", example=1),
     *                  @OA\Property(property="created_at", type="string", format="date-time"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time"),
     *                  @OA\Property(
     *                      property="ec",
     *                      type="array",
     *                      description="Liste des EC associées à cette UE",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="code_ec", type="integer", example="1"),
     *                          @OA\Property(property="label_ec", type="string", example="Programmation"),
     *                          @OA\Property(property="description_ec", type="string", example="Introduction à la programmation"),
     *                          @OA\Property(property="nb_heures_ec", type="integer", example=30),
     *                          @OA\Property(property="nb_credits_ec", type="integer", example=2),
     *                          @OA\Property(property="code_ue", type="string", example="UE101"),
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

        Log::channel('daily')->info('📖 CONSULTATION - Liste des UE', [
            'action' => 'INDEX',
            'resource' => 'UE',
            'user_id' => $user->code_personnel ?? 'Non authentifié',
            'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
            'ip' => $ip,
            'timestamp' => now()->toDateTimeString()
        ]);

        $ue = UE::with('ec')->get();

        Log::channel('daily')->info('✅ Liste UE récupérée', [
            'total_records' => $ue->count(),
            'with_relations' => 'ec (Éléments Constitutifs)'
        ]);

        return response()->json($ue, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/ue",
     *      operationId="createUE",
     *      tags={"UEs"},
     *      summary="Créer une nouvelle UE",
     *      description="Crée une nouvelle UE dans la base de données",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"code_ue", "label_ue", "description_ue", "code_niveau"},
     *              @OA\Property(property="code_ue", type="string", example="UE101"),
     *              @OA\Property(property="label_ue", type="string", example="Mathématiques"),
     *              @OA\Property(property="description_ue", type="string", example="Analyse et algèbre"),
     *              @OA\Property(property="code_niveau", type="integer", example=1)
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="UE créée avec succès"
     *      )
     * )
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $ip = $request->ip();

        Log::channel('daily')->info('➕ CRÉATION - Nouvelle UE', [
            'action' => 'STORE',
            'resource' => 'UE',
            'user_id' => $user->code_personnel ?? 'Non authentifié',
            'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
            'ip' => $ip,
            'input_data' => $request->all(),
            'timestamp' => now()->toDateTimeString()
        ]);

        try {
            $validatedData = $request->validate([
                'code_ue' => 'required|string|unique:ue,code_ue',
                'label_ue' => 'required|min:5|string',
                'description_ue' => 'required|string',
                'code_niveau' => 'required|integer'
            ]);

            $ue = UE::create($validatedData);

            Log::channel('daily')->info('✅ UE CRÉÉE AVEC SUCCÈS', [
                'code_ue' => $ue->code_ue,
                'label_ue' => $ue->label_ue,
                'description_ue' => $ue->description_ue,
                'code_niveau' => $ue->code_niveau,
                'created_by' => $user->code_personnel ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(["message" => "UE créée avec succès"], 201);

        } catch(Throwable $th) {
            Log::channel('daily')->error('❌ ERREUR CRÉATION UE', [
                'user_id' => $user->code_personnel ?? 'N/A',
                'ip' => $ip,
                'error_message' => $th->getMessage(),
                'error_file' => $th->getFile(),
                'error_line' => $th->getLine(),
                'input_data' => $request->all(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/ue/{codeUE}",
     *      operationId="getUEShow",
     *      tags={"UEs"},
     *      summary="Afficher une UE spécifique",
     *      description="Retourne une UE par son code",
     *      @OA\Parameter(
     *          name="codeUE",
     *          in="path",
     *          required=true,
     *          description="Code de l'UE",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Détails de l'UE"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="UE non trouvée"
     *      )
     * )
     */
    public function show(string $codeUE)
    {
        $user = auth()->user();
        $ip = request()->ip();

        Log::channel('daily')->info('👁️ CONSULTATION - Détail UE', [
            'action' => 'SHOW',
            'resource' => 'UE',
            'code_ue' => $codeUE,
            'user_id' => $user->code_personnel ?? 'Non authentifié',
            'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
            'ip' => $ip,
            'timestamp' => now()->toDateTimeString()
        ]);

        $ue = UE::with('ec')->find($codeUE);

        if (!$ue) {
            Log::channel('daily')->warning('⚠️ UE NON TROUVÉE', [
                'code_ue' => $codeUE,
                'user_id' => $user->code_personnel ?? 'N/A',
                'ip' => $ip,
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(["message" => "UE non trouvée"], 404);
        }

        Log::channel('daily')->info('✅ UE trouvée', [
            'code_ue' => $ue->code_ue,
            'label_ue' => $ue->label_ue,
            'ec_count' => $ue->ec->count()
        ]);

        return response()->json($ue, 200);
    }

    /**
     * @OA\Put(
     *      path="/api/ue/{codeUE}",
     *      operationId="updateUE",
     *      tags={"UEs"},
     *      summary="Mettre à jour une UE",
     *      description="Modifie une UE existante",
     *      @OA\Parameter(
     *          name="codeUE",
     *          in="path",
     *          required=true,
     *          description="Code de l'UE",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="code_ue", type="string", example="MA"),
     *              @OA\Property(property="label_ue", type="string", example="Maths avancées"),
     *              @OA\Property(property="description_ue", type="string", example="Analyse avancée"),
     *              @OA\Property(property="code_niveau", type="integer", example=2)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="UE mise à jour avec succès"
     *      )
     * )
     */
    public function update(Request $request, string $codeUE)
    {
        $user = auth()->user();
        $ip = $request->ip();

        try {
            $ue = UE::findOrFail($codeUE);

            // Sauvegarde des données avant modification
            $oldData = $ue->toArray();

            Log::channel('daily')->info('✏️ MODIFICATION - UE', [
                'action' => 'UPDATE',
                'resource' => 'UE',
                'code_ue' => $codeUE,
                'user_id' => $user->code_personnel ?? 'Non authentifié',
                'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
                'ip' => $ip,
                'data_before' => $oldData,
                'input_data' => $request->all(),
                'timestamp' => now()->toDateTimeString()
            ]);

            $validatedData = $request->validate([
                'code_ue' => 'sometimes|string|unique:ue,code_ue,' . $codeUE . ',code_ue',
                'label_ue' => 'sometimes|string|min:5',
                'description_ue' => 'sometimes|string',
                'code_niveau' => 'sometimes|integer'
            ]);

            $ue->update($validatedData);

            // Données après modification
            $newData = $ue->fresh()->toArray();

            Log::channel('daily')->info('✅ UE MODIFIÉE AVEC SUCCÈS', [
                'code_ue' => $ue->code_ue,
                'label_ue' => $ue->label_ue,
                'data_after' => $newData,
                'changes' => array_diff_assoc($newData, $oldData),
                'modified_by' => $user->code_personnel ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(["message" => "UE mise à jour avec succès"], 200);

        } catch(Throwable $th) {
            Log::channel('daily')->error('❌ ERREUR MODIFICATION UE', [
                'code_ue' => $codeUE,
                'user_id' => $user->code_personnel ?? 'N/A',
                'ip' => $ip,
                'error_message' => $th->getMessage(),
                'error_file' => $th->getFile(),
                'error_line' => $th->getLine(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/ue/{codeUE}",
     *      operationId="deleteUE",
     *      tags={"UEs"},
     *      summary="Supprimer une UE",
     *      description="Supprime une UE grâce à son code",
     *      @OA\Parameter(
     *          name="codeUE",
     *          in="path",
     *          required=true,
     *          description="Code de l'UE",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="UE supprimée avec succès"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="UE non trouvée"
     *      )
     * )
     */
    public function destroy(string $codeUE)
    {
        $user = auth()->user();
        $ip = request()->ip();

        try {
            $ue = UE::findOrFail($codeUE);

            // Sauvegarde des données avant suppression
            $deletedData = $ue->toArray();

            Log::channel('daily')->warning('🗑️ SUPPRESSION - UE', [
                'action' => 'DELETE',
                'resource' => 'UE',
                'code_ue' => $codeUE,
                'user_id' => $user->code_personnel ?? 'Non authentifié',
                'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
                'ip' => $ip,
                'deleted_data' => $deletedData,
                'timestamp' => now()->toDateTimeString()
            ]);

            $ue->delete();

            Log::channel('daily')->warning('✅ UE SUPPRIMÉE DÉFINITIVEMENT', [
                'code_ue' => $codeUE,
                'label_ue' => $deletedData['label_ue'],
                'deleted_by' => $user->code_personnel ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                "message" => "UE supprimée avec succès"
            ], 200);

        } catch(Throwable $th) {
            Log::channel('daily')->error('❌ ERREUR SUPPRESSION UE', [
                'code_ue' => $codeUE,
                'user_id' => $user->code_personnel ?? 'N/A',
                'ip' => $ip,
                'error_message' => $th->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
