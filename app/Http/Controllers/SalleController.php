<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\Request;
use Throwable;

class SalleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/salles",
     *     operationId="getSallesList",
     *     tags={"Salles"},
     *     summary="Obtenir la liste des salles",
     *     description="Retourne la liste de toutes les salles",
     *     @OA\Response(
     *         response=200,
     *         description="Liste récupérée avec succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="num_salle", type="integer", example=101),
     *                 @OA\Property(property="contenance", type="integer", example=50),
     *                 @OA\Property(property="status", type="string", example="LIBRE"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $salles = Salle::all();
        return response()->json($salles, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/salles",
     *     operationId="storeSalle",
     *     tags={"Salles"},
     *     summary="Créer une nouvelle salle",
     *     description="Crée une salle et retourne un message de confirmation",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"num_salle","contenance","status"},
     *             @OA\Property(property="num_salle", type="integer", example=101),
     *             @OA\Property(property="contenance", type="integer", example=50),
     *             @OA\Property(property="status", type="string", enum={"OCCUPEE","LIBRE"}, example="LIBRE")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Salle créée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Salle créée avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'num_salle' => 'required|integer|unique:salles,num_salle',
                'contenance' => 'required|integer|min:1',
                'status' => 'required|in:OCCUPEE,LIBRE'
            ]);

            Salle::create($validatedData);

            return response()->json(["message" => "Salle créée avec succès"], 201);
        } catch (Throwable $th) {
            return response()->json([
                "message" => "Erreur lors de la création: " . $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/salles/{num_salle}",
     *     operationId="getSalleByNum",
     *     tags={"Salles"},
     *     summary="Afficher une salle spécifique",
     *     description="Retourne les détails d'une salle",
     *     @OA\Parameter(
     *         name="num_salle",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Salle récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="num_salle", type="integer", example=101),
     *             @OA\Property(property="contenance", type="integer", example=50),
     *             @OA\Property(property="status", type="string", example="LIBRE"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Salle non trouvée")
     * )
     */
    public function show(int $numSalle)
    {
        try {
            $salle = Salle::findOrFail($numSalle);
            return response()->json($salle, 200);

        } catch (Throwable $th) {
            return response()->json(["message" => "Salle non trouvée"], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/salles/{num_salle}",
     *     operationId="updateSalle",
     *     tags={"Salles"},
     *     summary="Mettre à jour une salle",
     *     description="Met à jour les informations d'une salle",
     *     @OA\Parameter(
     *         name="num_salle",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="contenance", type="integer", example=60),
     *             @OA\Property(property="status", type="string", enum={"OCCUPEE","LIBRE"}, example="OCCUPEE")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Salle mise à jour avec succès"),
     *     @OA\Response(response=404, description="Salle non trouvée"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function update(Request $request, int $numSalle)
    {
        try {

            $salle  = Salle::findOrFail($numSalle);

            $validatedData = $request->validate([
                'contenance' => 'sometimes|integer|min:1',
                'status' => 'sometimes|in:OCCUPEE,LIBRE'
            ]);

            $salle->update($validatedData);

            return response()->json(["message" => "Salle mise à jour avec succès"], 200);
        } catch (Throwable $th) {
            return response()->json([
                "message" => "Erreur lors de la mise à jour: " . $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/salles/{num_salle}",
     *     operationId="deleteSalle",
     *     tags={"Salles"},
     *     summary="Supprimer une salle",
     *     description="Supprime une salle par son numéro",
     *     @OA\Parameter(
     *         name="num_salle",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\Response(response=200, description="Salle supprimée avec succès"),
     *     @OA\Response(response=404, description="Salle non trouvée"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function destroy(int $numSalle)
    {
        try {
            $salle = Salle::findOrFail($numSalle);

            $salle->delete();
            return response()->json(["message" => "Salle supprimée avec succès"], 200);
        } catch (Throwable $th) {
            return response()->json([
                "message" => "Erreur lors de la suppression: " . $th->getMessage()
            ], 500);
        }
    }
}
