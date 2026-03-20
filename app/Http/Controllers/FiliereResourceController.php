<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use Illuminate\Http\Request;
use Throwable;

class FiliereResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /filieres
     */
    public function index()
    {
        $filieres = Filiere::all();

        // Retourne une vue avec la liste des filières
        return view('filieres.index', compact('filieres'));
    }

    /**
     * Show the form for creating a new resource.
     * GET /filieres/create
     */
    public function create()
    {
        // Retourne la vue avec le formulaire de création
        return view('filieres.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /filieres
     */
    public function store(Request $request)
    {
        try {
            // Validation des données
            $validatedData = $request->validate([
                'code_filiere' => 'required|min:5|string|unique:filiere,code_filiere',
                'label_filiere' => 'required|min:5|string',
                'description_filiere' => 'required'
            ]);

            // Création de la filière
            Filiere::create($validatedData);

            // Redirection avec message de succès
            return redirect()
                ->route('filieres-resources.index')
                ->with('success', 'Filière créée avec succès !');

        } catch(Throwable $th) {
            // En cas d'erreur, retour au formulaire avec les anciennes valeurs
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     * GET /filieres/{codeFiliere}
     */
    public function show(string $codeFiliere)
    {
        try {
            // Récupère la filière ou lance une exception 404
            $filiere = Filiere::findOrFail($codeFiliere);

            // Retourne la vue de détail
            return view('filieres.show', compact('filiere'));

        } catch(Throwable $th) {
            return redirect()
                ->route('filieres-resources.index')
                ->with('error', 'Filière non trouvée');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * GET /filieres/{codeFiliere}/edit
     */
    public function edit(string $codeFiliere)
    {
        try {
            // Récupère la filière à modifier
            $filiere = Filiere::findOrFail($codeFiliere);

            // Retourne la vue avec le formulaire d'édition pré-rempli
            return view('filieres.edit', compact('filiere'));

        } catch(Throwable $th) {
            return redirect()
                ->route('filieres.index')
                ->with('error', 'Filière non trouvée');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /filieres/{codeFiliere}
     */
    public function update(Request $request, string $codeFiliere)
    {
        try {
            // Récupère la filière
            $filiere = Filiere::findOrFail($codeFiliere);

            // Validation (exclut le code actuel de la validation unique)
            $validatedData = $request->validate([
                'code_filiere' => 'required|min:5|string|unique:filiere,code_filiere,' . $codeFiliere . ',code_filiere',
                'label_filiere' => 'required|min:5|string',
                'description_filiere' => 'required'
            ]);

            // Mise à jour
            $filiere->update($validatedData);

            // Redirection avec message de succès
            return redirect()
                ->route('filieres-resources.index', ['codeFiliere' => $filiere->code_filiere])
                ->with('success', 'Filière mise à jour avec succès !');

        } catch(Throwable $th) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /filieres/{codeFiliere}
     */
    public function destroy(string $codeFiliere)
    {
        try {
            $filiere = Filiere::findOrFail($codeFiliere);
            $filiere->delete();

            return redirect()
                ->route('filieres-resources.index')
                ->with('success', 'Filière supprimée avec succès !');

        } catch(Throwable $th) {
            return redirect()
                ->route('filieres-resources.index')
                ->with('error', 'Erreur lors de la suppression : ' . $th->getMessage());
        }
    }
}
