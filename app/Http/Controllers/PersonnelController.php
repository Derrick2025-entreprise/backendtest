<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Illuminate\Http\Request;
use Throwable;

class PersonnelController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/personnels",
     *     operationId="getPersonnelsList",
     *     tags={"Personnel"},
     *     summary="Obtenir la liste des personnels",
     *     description="Retourne la liste de tous les personnels",
     *     @OA\Response(
     *         response=200,
     *         description="Liste récupérée avec succès",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="code_personnel", type="string", example="PER-001"),
                *     @OA\Property(property="nom_personnel", type="string", example="Doe"),
                *     @OA\Property(property="prenom_personnel", type="string", example="John"),
                *     @OA\Property(property="sexe_personnel", type="string", enum={"M","F"}, example="M"),
                *     @OA\Property(property="phone_personnel", type="string", example="699123456"),
                *     @OA\Property(property="login_personnel", type="string", example="jdoe"),
                *     @OA\Property(property="type_personnel", type="string", enum={"ENSEIGNANT","RESPONSABLE_ACADEMIQUE","RESPONSABLE_DISCIPLINE"}, example="ENSEIGNANT"),
                *     @OA\Property(property="created_at", type="string", format="date-time"),
                *     @OA\Property(property="updated_at", type="string", format="date-time")
     *              )
     *        )
     *     )
     * )
     */
    public function index() {
        $personnels = Personnel::all();
        return response()->json($personnels, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/personnels",
     *     operationId="storePersonnel",
     *     tags={"Personnel"},
     *     summary="Créer un personnel",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *                @OA\Property(property="code_personnel", type="string", example="PER-001"),
                *     @OA\Property(property="nom_personnel", type="string", example="Doe"),
                *     @OA\Property(property="prenom_personnel", type="string", example="John"),
                *     @OA\Property(property="sexe_personnel", type="string", enum={"M","F"}, example="M"),
                *     @OA\Property(property="phone_personnel", type="string", example="699123456"),
                *     @OA\Property(property="login_personnel", type="string", example="jdoe"),
                *     @OA\Property(property="password_personnel", type="string", example="888rdqdf"),
                *     @OA\Property(property="type_personnel", type="string", enum={"ENSEIGNANT","RESPONSABLE_ACADEMIQUE","RESPONSABLE_DISCIPLINE"}, example="ENSEIGNANT"),
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Personnel créé avec succès",
     *         @OA\JsonContent(@OA\Property(property="message", type="string")
     *        )
     *     )
     * )
     */
    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'code_personnel' => 'required|string|unique:personnel,code_personnel',
                'nom_personnel' => 'required|string|max:100',
                'prenom_personnel' => 'nullable|string|max:100',
                'sexe_personnel' => 'required|in:M,F',
                'phone_personnel' => 'required|string|max:20',
                'login_personnel' => 'required|string|max:50|unique:personnel,login_personnel',
                'password_personnel' => 'required|string|min:6',
                'type_personnel' => 'required|in:ENSEIGNANT,RESPONSABLE_ACADEMIQUE,RESPONSABLE_DISCIPLINE',
            ]);

            $validatedData['password_personnel'] = bcrypt($validatedData['password_personnel']);

            $personnel = Personnel::create($validatedData);

            return response()->json(["message" => "Personnel créé avec succès"], 201);
        } catch (Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/personnels/{code_personnel}",
     *     operationId="showPersonnel",
     *     tags={"Personnel"},
     *     summary="Afficher un personnel",
     *     @OA\Parameter(
     *         name="code_personnel",
     *         in="path",
     *         required=true,
     *         description="Code du personnel",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Personnel trouvé",
     *         @OA\JsonContent(
      *          @OA\Property(property="code_personnel", type="string", example="PER-001"),
                *     @OA\Property(property="nom_personnel", type="string", example="Doe"),
                *     @OA\Property(property="prenom_personnel", type="string", example="John"),
                *     @OA\Property(property="sexe_personnel", type="string", enum={"M","F"}, example="M"),
                *     @OA\Property(property="phone_personnel", type="string", example="699123456"),
                *     @OA\Property(property="login_personnel", type="string", example="jdoe"),
                *     @OA\Property(property="type_personnel", type="string", enum={"ENSEIGNANT","RESPONSABLE_ACADEMIQUE","RESPONSABLE_DISCIPLINE"}, example="ENSEIGNANT"),
                *     @OA\Property(property="created_at", type="string", format="date-time"),
                *     @OA\Property(property="updated_at", type="string", format="date-time")
     *
     *          )
     *     )
     * )
     */
    public function show(string $codePersonnel) {
        try {
            $personnel = Personnel::findOrFail($codePersonnel);
            return response()->json($personnel, 200);
        } catch (Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/personnels/{code_personnel}",
     *     operationId="updatePersonnel",
     *     tags={"Personnel"},
     *     summary="Modifier un personnel",
     *     @OA\Parameter(
     *         name="code_personnel",
     *         in="path",
     *         required=true,
     *         description="Code du personnel",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *                       @OA\Property(property="code_personnel", type="string", example="PER-001"),
                *     @OA\Property(property="nom_personnel", type="string", example="Doe"),
                *     @OA\Property(property="prenom_personnel", type="string", example="John"),
                *     @OA\Property(property="sexe_personnel", type="string", enum={"M","F"}, example="M"),
                *     @OA\Property(property="phone_personnel", type="string", example="699123456"),
                *     @OA\Property(property="login_personnel", type="string", example="jdoe"),
                *     @OA\Property(property="password_personnel", type="string", example="888rdqdf"),
                *     @OA\Property(property="type_personnel", type="string", enum={"ENSEIGNANT","RESPONSABLE_ACADEMIQUE","RESPONSABLE_DISCIPLINE"}, example="ENSEIGNANT"),

     *          )
     *     ),
     *     @OA\Response(response=200, description="Personnel mis à jour"),
     *     @OA\Response(response=404, description="Personnel non trouvé")
     * )
     */
    public function update(Request $request, string $code_personnel)
    {
        try {
            $personnel = Personnel::findOrFail($code_personnel);

            $validatedData = $request->validate([
                'code_personnel' => 'sometimes|string|unique:personnel,code_personnel',
                'nom_personnel' => 'sometimes|required|string|max:100',
                'prenom_personnel' => 'sometimes|nullable|string|max:100',
                'sexe_personnel' => 'sometimes|required|in:M,F',
                'phone_personnel' => 'sometimes|required|string|max:20',
                'login_personnel' => 'sometimes|required|string|max:50|unique:personnel,login_personnel,' . $personnel->code_personnel . ',code_personnel',
                'password_personnel' => 'sometimes|required|string|min:6',
                'type_personnel' => 'sometimes|required|in:ENSEIGNANT,RESPONSABLE_ACADEMIQUE,RESPONSABLE_DISCIPLINE',
            ]);

            if (isset($validatedData['password_personnel'])) {
                $validatedData['password_personnel'] = bcrypt($validatedData['password_personnel']);
            }

            $personnel->update($validatedData);

            return response()->json(["message" => "Personnel mis à jour avec succès"], 200);
        } catch (Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/personnels/{code_personnel}",
     *     operationId="deletePersonnel",
     *     tags={"Personnel"},
     *     summary="Supprimer un personnel",
     *     @OA\Parameter(
     *         name="code_personnel",
     *         in="path",
     *         required=true,
     *         description="Code du personnel",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Supprimé avec succès"
     *     )
     * )
     */
   public function destroy(string $code_personnel)
    {
        try {
            $personnel = Personnel::findOrFail($code_personnel);
            $personnel->delete();

            return response()->json(['message' => 'Personnel supprimé'], 200);
        } catch (Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
