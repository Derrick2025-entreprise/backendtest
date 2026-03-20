<?php

namespace App\Http\Controllers;

use App\Models\Niveau;
use Illuminate\Http\Request;
use Throwable;

class NiveauController extends Controller
{
    /**
 * @OA\Get(
 *      path="/api/niveaux",
 *      operationId="getNiveauxList",
 *      tags={"Niveaux"},
 *      summary="Obtenir la liste des niveaux",
 *      description="Retourne la liste de tous les niveaux avec leurs UE associées",
 *      @OA\Response(
 *          response=200,
 *          description="Opération réussie",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(property="code_niveau", type="integer", example=1),
 *                  @OA\Property(property="label_niveau", type="string", example="Niveau 1"),
 *                  @OA\Property(property="description_niveau", type="string", example="Premier niveau d'étude"),
 *                  @OA\Property(property="code_filiere", type="string", example="INFO-001"),
 *                  @OA\Property(property="created_at", type="string", format="date-time"),
 *                  @OA\Property(property="updated_at", type="string", format="date-time"),
 *
 *                  @OA\Property(
 *                      property="ue",
 *                      type="array",
 *                      description="Liste des UE associées à ce niveau",
 *                      @OA\Items(
 *                          type="object",
 *                          @OA\Property(property="code_ue", type="string", example="UE-101"),
 *                          @OA\Property(property="label_ue", type="string", example="Programmation"),
 *                          @OA\Property(property="description_ue", type="string", example="Introduction à la programmation"),
 *                          @OA\Property(property="code_niveau", type="int", example="1"),
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
        $niveaux = Niveau::with('ue')->get();
        return response()->json($niveaux, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/niveaux",
     *      operationId="storeNiveau",
     *      tags={"Niveaux"},
     *      summary="Créer un niveau",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"code_filiere","label_niveau","description_niveau"},
     *              @OA\Property(property="code_filiere", type="string"),
     *              @OA\Property(property="label_niveau", type="string"),
     *              @OA\Property(property="description_niveau", type="string")
     *          )
     *      ),
     *      @OA\Response(response=201, description="Niveau créé"),
     *      @OA\Response(response=422, description="Erreur validation"),
     *      @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code_filiere' => 'required|string',
                'label_niveau' => 'required|min:5|string',
                'description_niveau' => 'required|string'
            ]);

            $res = Niveau::create($validatedData);

            return response()->json(["message" => "Niveau crée avec succès"], 201);

        } catch(Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/niveaux/{codeNiveau}",
     *      operationId="showNiveau",
     *      tags={"Niveaux"},
     *      summary="Obtenir une niveau par son code",
     *      description="Retourne les informations d'une niveau spécifique",
     *
     *      @OA\Parameter(
     *          name="codeNiveau",
     *          in="path",
     *          description="Code unique de la niveau",
     *          required=true,
     *          @OA\Schema(type="int", example="1")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Niveau trouvé",
     *          @OA\JsonContent(
     *              @OA\Property(property="code_niveau", type="int"),
     *              @OA\Property(property="label_niveau", type="string"),
     *              @OA\Property(property="description_niveau", type="string"),
     *              @OA\Property(property="code_filiere", type="string"),
     *              @OA\Property(property="created_at", type="string", format="date-time"),
     *              @OA\Property(property="updated_at", type="string", format="date-time"),
     *
     *              @OA\Property(
 *                      property="ue",
 *                      type="array",
 *                      description="Liste des UE associées à ce niveau",
 *                      @OA\Items(
 *                          type="object",
 *                          @OA\Property(property="code_ue", type="string", example="UE-101"),
 *                          @OA\Property(property="label_ue", type="string", example="Programmation"),
 *                          @OA\Property(property="description_ue", type="string", example="Introduction à la programmation"),
 *                          @OA\Property(property="code_niveau", type="int", example="1"),
 *                          @OA\Property(property="created_at", type="string", format="date-time"),
 *                          @OA\Property(property="updated_at", type="string", format="date-time")
 *                      )
 *                  )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=404,
     *          description="Niveau non trouvée"
     *      )
     * )
     */
    public function show(int $codeNiveau)
    {
        try {
            $niveau = Niveau::with('ue')->findOrFail($codeNiveau);
            return response()->json($niveau, 200);

        } catch(Throwable $th) {
            return response()->json([
                'message' => 'Niveau non trouvé'
            ], 404);
        }
    }
/**
     * @OA\Put(
     *      path="/api/niveaux/{codeNiveau}",
     *      operationId="updateNiveau",
     *      tags={"Niveaux"},
     *      summary="Mettre à jour un niveau",
     *      description="Met à jour les informations d'une niveau existant",
     *
     *      @OA\Parameter(
     *          name="codeNiveau",
     *          in="path",
     *          description="Code unique du niveau",
     *          required=true,
     *          @OA\Schema(type="int", example="1")
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"code_filiere","label_filiere","description_filiere"},
     *              @OA\Property(property="code_filiere", type="string"),
     *              @OA\Property(property="label_niveau", type="string"),
     *              @OA\Property(property="description_niveau", type="string")
     *          )
     *      ),
     *
     *      @OA\Response(response=200, description="Niveau mise à jour"),
     *      @OA\Response(response=404, description="Niveau non trouvé"),
     *      @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function update(Request $request, int $codeNiveau)
    {
        try {
            $niveau = Niveau::findOrFail($codeNiveau);

            $validatedData = $request->validate([
                'code_filiere' => 'required|min:5|string',
                'label_niveau' => 'required|min:5|string',
                'description_niveau' => 'required'
            ]);

            $niveau->update($validatedData);

            return response()->json([
                "message" => "Niveau mis à jour avec succès"
            ], 200);

        } catch(Throwable $th) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour : ' . $th->getMessage()
            ], 500);
        }
    }

     /**
     * @OA\Delete(
     *      path="/api/niveaux/{codeNiveau}",
     *      operationId="deleteNiveau",
     *      tags={"Niveaux"},
     *      summary="Supprimer un niveau",
     *
     *      @OA\Parameter(
     *          name="codeNiveau",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="int", example="1")
     *      ),
     *
     *      @OA\Response(response=200, description="Niveau supprimée"),
     *      @OA\Response(response=404, description="Niveau non trouvé"),
     *      @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function destroy(int $codeNiveau)
    {
        try {
            $niveau = Niveau::findOrFail($codeNiveau);
            $niveau->delete();

            return response()->json([
                "message" => "Niveau supprimée"
            ], 200);

        } catch(Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
