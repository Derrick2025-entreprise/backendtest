<?php

namespace App\Http\Controllers;

use App\Models\EC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class ECController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/ec",
     *      operationId="getECList",
     *      tags={"ECs"},
     *      summary="Obtenir la liste des EC",
     *      description="Retourne la liste de toutes les EC avec leur UE associée",
     *      @OA\Response(
     *          response=200,
     *          description="Opération réussie",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="code_ec", type="integer"),
     *                  @OA\Property(property="label_ec", type="string"),
     *                  @OA\Property(property="description_ec", type="string"),
     *                  @OA\Property(property="nb_heures_ec", type="integer"),
     *                  @OA\Property(property="nb_credits_ec", type="integer"),
     *                  @OA\Property(property="code_ue", type="string"),
     *                  @OA\Property(property="support_cours", type="string"),
     *                  @OA\Property(property="support_cours_url", type="string"),
     *                  @OA\Property(property="created_at", type="string", format="date-time"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time"),
     *              )
     *          )
     *      )
     * )
     */
    public function index()
    {
        $user = auth()->user();
        $ip = request()->ip();

        Log::channel('daily')->info('📖 CONSULTATION - Liste des EC', [
            'action' => 'INDEX',
            'resource' => 'EC',
            'user_id' => $user->code_personnel ?? 'Non authentifié',
            'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
            'ip' => $ip,
            'timestamp' => now()->toDateTimeString()
        ]);

        $ecs = EC::orderBy('created_at', 'desc')
                ->paginate(10);

        Log::channel('daily')->info('✅ Liste EC récupérée', [
            'total_records' => $ecs->total(),
            'current_page' => $ecs->currentPage(),
            'per_page' => $ecs->perPage()
        ]);

        return response()->json($ecs, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/ec",
     *      operationId="storeEC",
     *      tags={"ECs"},
     *      summary="Créer un nouvel EC",
     *      description="Crée un EC et retourne un message de confirmation",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"label_ec","description_ec","nb_heures_ec","nb_credits_ec","code_ue"},
     *                  @OA\Property(property="label_ec", type="string", minLength=3),
     *                  @OA\Property(property="description_ec", type="string"),
     *                  @OA\Property(property="nb_heures_ec", type="integer"),
     *                  @OA\Property(property="nb_credits_ec", type="integer"),
     *                  @OA\Property(property="code_ue", type="string"),
     *                  @OA\Property(property="support_cours", type="string", format="binary", description="Fichier PDF du support de cours")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="EC créé avec succès",
     *          @OA\JsonContent(@OA\Property(property="message", type="string"))
     *      )
     * )
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $ip = $request->ip();

        Log::channel('daily')->info('➕ CRÉATION - Nouvel EC', [
            'action' => 'STORE',
            'resource' => 'EC',
            'user_id' => $user->code_personnel ?? 'Non authentifié',
            'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
            'ip' => $ip,
            'input_data' => $request->except(['support_cours']),
            'has_file' => $request->hasFile('support_cours'),
            'timestamp' => now()->toDateTimeString()
        ]);

        try {
            $validated = $request->validate([
                'label_ec'         => 'required|string|min:3',
                'description_ec'   => 'required|string',
                'nb_heures_ec'     => 'required|integer|min:1',
                'nb_credits_ec'    => 'required|integer|min:1',
                'code_ue'          => 'required|string|exists:ue,code_ue',
                'support_cours'    => 'nullable|file|mimes:pdf|max:10240'
            ]);

            if ($request->hasFile('support_cours')) {
                $labelSlug = \Illuminate\Support\Str::slug($validated['label_ec']);
                $timestamp = time();
                $filename = "support-{$labelSlug}-{$timestamp}.pdf";

                $path = $request->file('support_cours')->storeAs('supports_cours', $filename, 'public');
                $validated['support_cours'] = $path;
                $validated['support_cours_url'] = config('app.url') . '/storage/' . $path;

                Log::channel('daily')->info('📎 Fichier uploadé', [
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $request->file('support_cours')->getSize() . ' bytes'
                ]);
            } else {
                $validated['support_cours'] = null;
                $validated['support_cours_url'] = null;
            }

            $ec = EC::create($validated);

            Log::channel('daily')->info('✅ EC CRÉÉ AVEC SUCCÈS', [
                'code_ec' => $ec->code_ec,
                'label_ec' => $ec->label_ec,
                'code_ue' => $ec->code_ue,
                'nb_heures' => $ec->nb_heures_ec,
                'nb_credits' => $ec->nb_credits_ec,
                'has_support' => $ec->support_cours !== null,
                'created_by' => $user->code_personnel ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                "message" => "EC créé avec succès",
                "ec" => $ec
            ], 201);

        } catch (Throwable $th) {
            Log::channel('daily')->error('❌ ERREUR CRÉATION EC', [
                'user_id' => $user->code_personnel ?? 'N/A',
                'ip' => $ip,
                'error_message' => $th->getMessage(),
                'error_file' => $th->getFile(),
                'error_line' => $th->getLine(),
                'input_data' => $request->except(['support_cours']),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(["message" => $th->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/ec/{codeEC}",
     *      operationId="showEC",
     *      tags={"ECs"},
     *      summary="Obtenir un EC",
     *      description="Retourne les détails d'un EC",
     *      @OA\Parameter(
     *          name="codeEC",
     *          in="path",
     *          required=true,
     *          description="Code de l'EC",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Opération réussie",
     *          @OA\JsonContent(type="object")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="EC non trouvé"
     *      )
     * )
     */
    public function show(string $codeEC)
    {
        $user = auth()->user();
        $ip = request()->ip();

        Log::channel('daily')->info('👁️ CONSULTATION - Détail EC', [
            'action' => 'SHOW',
            'resource' => 'EC',
            'code_ec' => $codeEC,
            'user_id' => $user->code_personnel ?? 'Non authentifié',
            'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
            'ip' => $ip,
            'timestamp' => now()->toDateTimeString()
        ]);

        try {
            $ec = EC::findOrFail($codeEC);

            Log::channel('daily')->info('✅ EC trouvé', [
                'code_ec' => $ec->code_ec,
                'label_ec' => $ec->label_ec,
                'code_ue' => $ec->code_ue
            ]);

            return response()->json($ec, 200);

        } catch (Throwable $th) {
            Log::channel('daily')->warning('⚠️ EC NON TROUVÉ', [
                'code_ec' => $codeEC,
                'user_id' => $user->code_personnel ?? 'N/A',
                'ip' => $ip,
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(["message" => "EC non trouvé"], 404);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/ec/{codeEC}",
     *      operationId="updateEC",
     *      tags={"ECs"},
     *      summary="Modifier un EC",
     *      description="Modifie un EC existant (utilisez POST avec _method=PUT pour l'upload de fichiers)",
     *      @OA\Parameter(
     *          name="codeEC",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="label_ec", type="string"),
     *                  @OA\Property(property="description_ec", type="string"),
     *                  @OA\Property(property="nb_heures_ec", type="integer"),
     *                  @OA\Property(property="nb_credits_ec", type="integer"),
     *                  @OA\Property(property="code_ue", type="string"),
     *                  @OA\Property(property="support_cours", type="string", format="binary"),
     *                  @OA\Property(property="_method", type="string", enum={"PUT"})
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="EC mis à jour"),
     *      @OA\Response(response=404, description="EC non trouvé")
     * )
     */
    public function update(Request $request, string $codeEC)
    {
        $user = auth()->user();
        $ip = $request->ip();

        try {
            $ec = EC::findOrFail($codeEC);

            // Sauvegarde des données avant modification
            $oldData = $ec->toArray();

            Log::channel('daily')->info('✏️ MODIFICATION - EC', [
                'action' => 'UPDATE',
                'resource' => 'EC',
                'code_ec' => $codeEC,
                'user_id' => $user->code_personnel ?? 'Non authentifié',
                'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
                'ip' => $ip,
                'data_before' => $oldData,
                'input_data' => $request->except(['support_cours', '_method']),
                'has_new_file' => $request->hasFile('support_cours'),
                'timestamp' => now()->toDateTimeString()
            ]);

            $validated = $request->validate([
                'label_ec'         => 'sometimes|string|min:3',
                'description_ec'   => 'sometimes|string',
                'nb_heures_ec'     => 'sometimes|integer|min:1',
                'nb_credits_ec'    => 'sometimes|integer|min:1',
                'code_ue'          => 'sometimes|string|exists:ue,code_ue',
                'support_cours'    => 'nullable|file|mimes:pdf|max:10240'
            ]);

            if ($request->hasFile('support_cours')) {
                // Supprimer l'ancien fichier s'il existe
                if ($ec->support_cours && Storage::disk('public')->exists($ec->support_cours)) {
                    Storage::disk('public')->delete($ec->support_cours);

                    Log::channel('daily')->info('🗑️ Ancien fichier supprimé', [
                        'old_file' => $ec->support_cours
                    ]);
                }

                $labelSlug = \Illuminate\Support\Str::slug($validated['label_ec'] ?? $ec->label_ec);
                $timestamp = time();
                $filename = "support-{$labelSlug}-{$timestamp}.pdf";

                $path = $request->file('support_cours')->storeAs('supports_cours', $filename, 'public');
                $validated['support_cours'] = $path;
                $validated['support_cours_url'] = config('app.url') . '/storage/' . $path;

                Log::channel('daily')->info('📎 Nouveau fichier uploadé', [
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $request->file('support_cours')->getSize() . ' bytes'
                ]);
            }

            $ec->update($validated);

            // Données après modification
            $newData = $ec->fresh()->toArray();

            Log::channel('daily')->info('✅ EC MODIFIÉ AVEC SUCCÈS', [
                'code_ec' => $ec->code_ec,
                'label_ec' => $ec->label_ec,
                'data_after' => $newData,
                'changes' => array_diff_assoc($newData, $oldData),
                'modified_by' => $user->code_personnel ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                "message" => "EC mis à jour",
                "ec" => $ec
            ], 200);

        } catch (Throwable $th) {
            Log::channel('daily')->error('❌ ERREUR MODIFICATION EC', [
                'code_ec' => $codeEC,
                'user_id' => $user->code_personnel ?? 'N/A',
                'ip' => $ip,
                'error_message' => $th->getMessage(),
                'error_file' => $th->getFile(),
                'error_line' => $th->getLine(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(["message" => $th->getMessage()], 404);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/ec/{codeEC}",
     *      operationId="deleteEC",
     *      tags={"ECs"},
     *      summary="Supprimer un EC",
     *      description="Supprime un EC existant",
     *      @OA\Parameter(
     *          name="codeEC",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(response=200, description="EC supprimé"),
     *      @OA\Response(response=404, description="EC non trouvé")
     * )
     */
    public function destroy(string $codeEC)
    {
        $user = auth()->user();
        $ip = request()->ip();

        try {
            $ec = EC::findOrFail($codeEC);

            // Sauvegarde des données avant suppression
            $deletedData = $ec->toArray();

            Log::channel('daily')->warning('🗑️ SUPPRESSION - EC', [
                'action' => 'DELETE',
                'resource' => 'EC',
                'code_ec' => $codeEC,
                'user_id' => $user->code_personnel ?? 'Non authentifié',
                'user_name' => ($user->nom_personnel ?? '') . ' ' . ($user->prenom_personnel ?? ''),
                'ip' => $ip,
                'deleted_data' => $deletedData,
                'timestamp' => now()->toDateTimeString()
            ]);

            // Supprimer le fichier PDF s'il existe
            if ($ec->support_cours) {
                if (Storage::disk('public')->exists($ec->support_cours)) {
                    Storage::disk('public')->delete($ec->support_cours);

                    Log::channel('daily')->info('📎 Fichier supprimé', [
                        'file_path' => $ec->support_cours
                    ]);
                }
            }

            $ec->delete();

            Log::channel('daily')->warning('✅ EC SUPPRIMÉ DÉFINITIVEMENT', [
                'code_ec' => $codeEC,
                'label_ec' => $deletedData['label_ec'],
                'deleted_by' => $user->code_personnel ?? 'N/A',
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(["message" => "EC supprimé"], 200);

        } catch (Throwable $th) {
            Log::channel('daily')->error('❌ ERREUR SUPPRESSION EC', [
                'code_ec' => $codeEC,
                'user_id' => $user->code_personnel ?? 'N/A',
                'ip' => $ip,
                'error_message' => $th->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(["message" => "EC non trouvé"], 404);
        }
    }
}
