<?php

namespace App\Http\Controllers;

use App\Models\Programmation;
use Illuminate\Http\Request;
use Throwable;

class ProgrammationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/programmations",
     *     summary="Obtenir toutes les programmations",
     *     tags={"Programmation"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des programmations",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="num_salle", type="integer", example=101),
     *                 @OA\Property(property="code_ec", type="integer", example=12),
     *                 @OA\Property(property="code_personnel", type="string", example="P001"),
     *                 @OA\Property(property="nbre_heures", type="integer", example=4),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-02-20"),
     *                 @OA\Property(property="heure_debut", type="string", format="time", example="08:30"),
     *                 @OA\Property(property="heure_fin", type="string", format="time", example="12:30"),
     *                 @OA\Property(property="status", type="string", example="PLANIFIÉ"),
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Programmation::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/programmations",
     *     summary="Créer une programmation",
     *     tags={"Programmation"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="num_salle", type="integer", example=101),
     *             @OA\Property(property="code_ec", type="integer", example=12),
     *             @OA\Property(property="code_personnel", type="string", example="P001"),
     *             @OA\Property(property="nbre_heures", type="integer", example=4),
     *             @OA\Property(property="date", type="string", format="date", example="2025-02-20"),
     *             @OA\Property(property="heure_debut", type="string", format="time", example="08:00"),
     *             @OA\Property(property="heure_fin", type="string", format="time", example="12:00"),
     *             @OA\Property(property="status", type="string", example="PLANIFIÉ"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Programmation créée")
     * )
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                "num_salle" => "required|integer|exists:salles,num_salle",
                "code_ec" => "required|integer|exists:ec,code_ec",
                "code_personnel" => "required|string|exists:personnel,code_personnel",
                "nbre_heures" => "required|integer|min:1",
                "date" => "required|date",
                "heure_debut" => "required",
                "heure_fin" => "required",
                "status" => "required|in:PLANIFIÉ,TERMINÉ,ANNULÉ,REPORTÉ"
            ]);

            $programmation = Programmation::create($validated);

            return response()->json(["message" => "Programmtion créée avec succès"], 201);
        } catch(Throwable $th) {

            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/programmations/{id}",
     *     summary="Afficher une programmation",
     *     tags={"Programmation"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la programmation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la programmation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="num_salle", type="integer", example=101),
     *             @OA\Property(property="code_ec", type="integer", example=12),
     *             @OA\Property(property="code_personnel", type="string", example="P001"),
     *             @OA\Property(property="nbre_heures", type="integer", example=4),
     *             @OA\Property(property="date", type="string", format="date", example="2025-02-20"),
     *             @OA\Property(property="heure_debut", type="string", example="08:00"),
     *             @OA\Property(property="heure_fin", type="string", example="12:00"),
     *             @OA\Property(property="status", type="string", example="PLANIFIÉ"),
     *         )
     *     ),
     *     @OA\Response(response=404, description="Programmation non trouvée")
     * )
     */
    public function show(int $idProgrammation)
    {
        $programmation = Programmation::find($idProgrammation);

        if (!$programmation) {
            return response()->json(["message" => "Programmation non trouvée"], 404);
        }

        return response()->json($programmation, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/programmations/{id}",
     *     summary="Mettre à jour une programmation",
     *     tags={"Programmation"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la programmation"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="num_salle", type="integer", example=101),
     *             @OA\Property(property="code_ec", type="integer", example=12),
     *             @OA\Property(property="code_personnel", type="string", example="P001"),
     *             @OA\Property(property="nbre_heures", type="integer", example=4),
     *             @OA\Property(property="date", type="string", example="2025-02-20"),
     *             @OA\Property(property="heure_debut", type="string", example="09:00"),
     *             @OA\Property(property="heure_fin", type="string", example="13:00"),
     *             @OA\Property(property="status", type="string", example="REPORTÉ"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Programmation mise à jour"),
     *     @OA\Response(response=404, description="Non trouvée")
     * )
     */
    public function update(Request $request, int $idProgrammation)
    {
        try {
            $programmation = Programmation::findOrFail($idProgrammation);

            $validated = $request->validate([
                "num_salle" => "sometimes|integer|exists:salles,num_salle",
                "code_ec" => "sometimes|integer|exists:ec,code_ec",
                "code_personnel" => "sometimes|string|exists:personnel,code_personnel",
                "nbre_heures" => "sometimes|integer|min:1",
                "date" => "sometimes|date",
                "heure_debut" => "sometimes",
                "heure_fin" => "sometimes",
                "status" => "sometimes|in:PLANIFIÉ,TERMINÉ,ANNULÉ,REPORTÉ"
            ]);

            $programmation->update($validated);

            return response()->json(["message" => "Programmation mise à jour avec succès"], 200);
        } catch(Throwable $th) {

            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/programmations/{id}",
     *     summary="Supprimer une programmation",
     *     tags={"Programmation"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la programmation"
     *     ),
     *     @OA\Response(response=200, description="Programmation supprimée"),
     *     @OA\Response(response=404, description="Non trouvée")
     * )
     */
    public function destroy(int $idProgrammation)
    {
        $programmation = Programmation::find($idProgrammation);

        if (!$programmation) {
            return response()->json(["message" => "Programmation non trouvée"], 404);
        }

        $programmation->delete();

        return response()->json(["message" => "Programmation supprimée avec succès"], 200);
    }
}
