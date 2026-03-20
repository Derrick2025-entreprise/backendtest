<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeLoginMail;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request) {
        $ip = $request->ip();
        $loginAttempt = $request->input('login_personnel');

        // Log de la tentative de connexion
        Log::channel('daily')->info('=== TENTATIVE DE CONNEXION ===', [
            'login' => $loginAttempt,
            'ip' => $ip,
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ]);

        $credentials = $request->validate([
            "login_personnel" => "required",
            "password_personnel" => "required|string:min:6"
        ]);

        $personnel = Personnel::where("login_personnel", $credentials["login_personnel"])->first();

        if(!$personnel || !Hash::check($credentials["password_personnel"], $personnel->password_personnel)){
            // Log d'échec de connexion
            Log::channel('daily')->warning('CONNEXION ÉCHOUÉE - Identifiants invalides', [
                'login' => $loginAttempt,
                'ip' => $ip,
                'reason' => !$personnel ? 'Utilisateur inexistant' : 'Mot de passe incorrect',
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(["message" => "Identifiants invalides"], 401);
        }

        $old_token = DB::table('personal_access_tokens')
            ->where('tokenable_id', $personnel->code_personnel)
            ->get();

        if($old_token->count() > 0) {
            DB::table('personal_access_tokens')
                ->delete($old_token->get('id'));

            Log::channel('daily')->info('Ancien token supprimé', [
                'personnel_id' => $personnel->code_personnel,
                'tokens_deleted' => $old_token->count()
            ]);
        }

        $expiration = Carbon::now()->addDay(1);
        $token = $personnel->createToken('user_token', ["*"], $expiration)->plainTextToken;

        // Log de connexion réussie
        Log::channel('daily')->info('✅ CONNEXION RÉUSSIE', [
            'personnel_id' => $personnel->code_personnel,
            'personnel_nom' => $personnel->nom_personnel ?? 'N/A',
            'personnel_prenom' => $personnel->prenom_personnel ?? 'N/A',
            'login' => $personnel->login_personnel,
            'ip' => $ip,
            'token_expiration' => $expiration->toDateTimeString(),
            'timestamp' => now()->toDateTimeString()
        ]);

        // ✉️ ENVOI DE L'EMAIL DE BIENVENUE
        try {
            // Vérifier que login_personnel est un email valide
            if (filter_var($personnel->login_personnel, FILTER_VALIDATE_EMAIL)) {
                Mail::to($personnel->login_personnel)->send(
                    new WelcomeLoginMail(
                        $personnel,
                        now()->format('d/m/Y à H:i:s'),
                        $ip
                    )
                );

                Log::channel('daily')->info('📧 Email de bienvenue envoyé', [
                    'personnel_id' => $personnel->code_personnel,
                    'email' => $personnel->login_personnel,
                    'timestamp' => now()->toDateTimeString()
                ]);
            } else {
                Log::channel('daily')->warning('⚠️ Login personnel n\'est pas un email valide', [
                    'personnel_id' => $personnel->code_personnel,
                    'login' => $personnel->login_personnel
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('daily')->error('❌ ERREUR ENVOI EMAIL', [
                'personnel_id' => $personnel->code_personnel,
                'email' => $personnel->login_personnel,
                'error' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ]);
            // On ne bloque pas la connexion si l'email échoue
        }

        return response()->json([
            "access_token" => $token,
            "token_type" => "Bearer",
            "personnel" => $personnel
        ]);
    }

    public function logout(Request $request) {
        $personnel = $request->user();
        $ip = $request->ip();

        // Log avant déconnexion
        Log::channel('daily')->info('=== DÉCONNEXION ===', [
            'personnel_id' => $personnel->code_personnel ?? 'N/A',
            'personnel_nom' => $personnel->nom_personnel ?? 'N/A',
            'personnel_prenom' => $personnel->prenom_personnel ?? 'N/A',
            'login' => $personnel->login_personnel ?? 'N/A',
            'ip' => $ip,
            'timestamp' => now()->toDateTimeString()
        ]);

        $request->user()->currentAccessToken()->delete();

        // Log après déconnexion
        Log::channel('daily')->info('✅ DÉCONNEXION RÉUSSIE', [
            'personnel_id' => $personnel->code_personnel ?? 'N/A',
            'ip' => $ip,
            'timestamp' => now()->toDateTimeString()
        ]);

        return response()->json(["message" => "Déconnexion réussie"], 200);
    }
}
