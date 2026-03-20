<?php

namespace App\Http\Controllers;

use App\Models\Enseignement;
use Illuminate\Http\Request;
use Throwable;

class EnseignementController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/enseignements",
     *      operationId="getEnseignementsList",
     *      tags={"Enseignements"},
     *      summary="Obtenir la liste des enseignements",
     *      description="Retourne la liste de tous les enseignements avec leurs relations",
     *      @OA\Response(
     *          response=200,
     *          description="Opération réussie",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="code_personnel", type="string", example="PERS-001"),
     *                  @OA\Property(property="code_ec", type="integer", example=1),
     *                  @OA\Property(property="created_at", type="string", format="date-time"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time"),
     *                  @OA\Property(
     *                      property="personnel",
     *                      type="object",
     *                      description="Informations du personnel"
     *                  ),
     *                  @OA\Property(
     *                      property="ec",
     *                      type="object",
     *                      description="Informations de l'élément constitutif"
     *                  )
     *              )
     *          )
     *      )
     * )
     */
    public function index()
    {
        $enseignements = Enseignement::with(['personnel', 'ec'])->paginate(10);
        return response()->json($enseignements, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/enseignements",
     *      operationId="storeEnseignement",
     *      tags={"Enseignements"},
     *      summary="Créer un nouveau enseignement",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"code_personnel","code_ec"},
     *              @OA\Property(property="code_personnel", type="string", example="PERS-001"),
     *              @OA\Property(property="code_ec", type="integer", example=1)
     *          )
     *      ),
     *      @OA\Response(response=201, description="Enseignement créé"),
     *      @OA\Response(response=422, description="Erreur validation"),
     *      @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code_personnel' => 'required|string|exists:personnel,code_personnel',
                'code_ec' => 'required|integer|exists:ec,code_ec'
            ]);

            $enseignement = Enseignement::create($validatedData);

            return response()->json([
                "message" => "Enseignement créé avec succès",
                "data" => $enseignement
            ], 201);

        } catch(Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/enseignements/{id}",
     *      operationId="showEnseignement",
     *      tags={"Enseignements"},
     *      summary="Obtenir un enseignement par son ID",
     *      description="Retourne les informations d'un enseignement spécifique",
     *
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID de l'enseignement",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Enseignement trouvé",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="code_personnel", type="string"),
     *              @OA\Property(property="code_ec", type="integer"),
     *              @OA\Property(property="created_at", type="string", format="date-time"),
     *              @OA\Property(property="updated_at", type="string", format="date-time"),
     *              @OA\Property(property="personnel", type="object"),
     *              @OA\Property(property="ec", type="object")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=404,
     *          description="Enseignement non trouvé"
     *      )
     * )
     */
    public function show(int $id)
    {
        try {
            $enseignement = Enseignement::with(['personnel', 'ec'])->findOrFail($id);
            return response()->json($enseignement, 200);

        } catch(Throwable $th) {
            return response()->json([
                'message' => 'Enseignement non trouvé'
            ], 404);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/enseignements/{id}",
     *      operationId="updateEnseignement",
     *      tags={"Enseignements"},
     *      summary="Mettre à jour un enseignement",
     *      description="Met à jour les informations d'un enseignement existant",
     *
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID de l'enseignement",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="code_personnel", type="string", example="PERS-001"),
     *              @OA\Property(property="code_ec", type="integer", example=1)
     *          )
     *      ),
     *
     *      @OA\Response(response=200, description="Enseignement mis à jour"),
     *      @OA\Response(response=404, description="Enseignement non trouvé"),
     *      @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function update(Request $request, int $id)
    {
        try {
            $enseignement = Enseignement::findOrFail($id);

            $validatedData = $request->validate([
                'code_personnel' => 'sometimes|string|exists:personnel,code_personnel',
                'code_ec' => 'sometimes|integer|exists:ec,code_ec'
            ]);

            $enseignement->update($validatedData);

            return response()->json([
                "message" => "Enseignement mis à jour avec succès",
                "data" => $enseignement
            ], 200);

        } catch(Throwable $th) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour : ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/enseignements/{id}",
     *      operationId="deleteEnseignement",
     *      tags={"Enseignements"},
     *      summary="Supprimer un enseignement",
     *
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *
     *      @OA\Response(response=200, description="Enseignement supprimé"),
     *      @OA\Response(response=404, description="Enseignement non trouvé"),
     *      @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function destroy(int $id)
    {
        try {
            $enseignement = Enseignement::findOrFail($id);
            $enseignement->delete();

            return response()->json(["message" => "Enseignement supprimé avec succès"], 200);

        } catch(Throwable $th) {
            return response()->json([
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/enseignements/personnel/{codePersonnel}",
     *      operationId="getEnseignementsByPersonnel",
     *      tags={"Enseignements"},
     *      summary="Obtenir les enseignements d'un personnel",
     *
     *      @OA\Parameter(
     *          name="codePersonnel",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="string", example="PERS-001")
     *      ),
     *
     *      @OA\Response(response=200, description="Liste des enseignements"),
     *      @OA\Response(response=404, description="Aucun enseignement trouvé")
     * )
     */
    public function getByPersonnel(string $codePersonnel)
    {
        try {
            $enseignements = Enseignement::with(['ec'])
                ->where('code_personnel', $codePersonnel)
                ->get();

            return response()->json($enseignements, 200);

        } catch(Throwable $th) {
            return response()->json([
                'message' => 'Erreur lors de la récupération'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/enseignements/ec/{codeEc}",
     *      operationId="getEnseignementsByEc",
     *      tags={"Enseignements"},
     *      summary="Obtenir les enseignements d'un élément constitutif",
     *
     *      @OA\Parameter(
     *          name="codeEc",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *
     *      @OA\Response(response=200, description="Liste des enseignements"),
     *      @OA\Response(response=404, description="Aucun enseignement trouvé")
     * )
     */
    public function getByEc(string $codeEc)
    {
        try {
            $enseignements = Enseignement::with(['personnel'])
                ->where('code_ec', $codeEc)
                ->get();

            return response()->json($enseignements, 200);

        } catch(Throwable $th) {
            return response()->json([
                'message' => 'Erreur lors de la récupération'
            ], 500);
        }
    }
}
