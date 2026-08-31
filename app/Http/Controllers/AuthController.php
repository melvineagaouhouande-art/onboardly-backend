<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stagiaire;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur.
     * Le compte est créé avec le statut 'en_attente' et nécessite une validation OTP par email.
     */
    public function register(Request $request)
    {
        // 1. Validation des données d'entrée
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6|confirmed', // Le champ password_confirmation doit être fourni
            'role' => 'required|in:employe,manager,admin_rh',
            'statut_contrat' => 'nullable|string|max:50',
            'code_invitation' => 'nullable|string|max:50',
            'date_arrivee' => 'nullable|date',
            'departement_id' => 'nullable|exists:departements,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation des données.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Génération d'un code OTP à 6 chiffres
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // 3. Création de l'utilisateur en base de données
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'statut_contrat' => $request->statut_contrat,
            'code_invitation' => $request->code_invitation,
            'date_arrivee' => $request->date_arrivee,
            'departement_id' => $request->departement_id,
            'statut_compte' => 'en_attente', // Toujours en attente par défaut
            'otp_code' => $otpCode,
            'otp_expires_at' => Carbon::now()->addMinutes(10), // Expire après 10 minutes
        ]);

        // 4. Si c'est un employé, on lui crée automatiquement une fiche Stagiaire correspondante
        if ($user->role === 'employe') {
            Stagiaire::create([
                'user_id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'poste' => $user->statut_contrat ?? 'Stagiaire',
                'date_debut' => $user->date_arrivee,
                'points' => 0
            ]);
        }

        // 5. Envoi du code OTP par email (configurer le SMTP dans le fichier .env)
        try {
            Mail::to($user->email)->send(new OtpMail($otpCode, $user->prenom));
        } catch (\Exception $e) {
            // On loggue l'erreur mais on ne bloque pas l'inscription
            \Log::error("Erreur d'envoi du mail OTP : " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Inscription réussie. Un code de vérification vous a été envoyé par email.',
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'role' => $user->role,
                'statut_compte' => $user->statut_compte
            ]
        ], 201);
    }

    /**
     * Validation du code OTP envoyé par email.
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'otp_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Recherche de l'utilisateur par son email
        $user = User::where('email', strtolower($request->email))->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé.'
            ], 444);
        }

        // Vérifier si l'email est déjà vérifié
        if ($user->email_verified_at) {
            return response()->json([
                'success' => true,
                'message' => 'Email déjà vérifié.'
            ], 200);
        }

        // Vérification de la validité et de l'expiration du code OTP
        if ($user->otp_code !== $request->otp_code) {
            return response()->json([
                'success' => false,
                'message' => 'Code de vérification incorrect.'
            ], 400);
        }

        if (Carbon::now()->isAfter($user->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Le code de vérification a expiré.'
            ], 400);
        }

        // Validation réussie
        $user->email_verified_at = Carbon::now();
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Email vérifié avec succès. Votre compte est en attente d\'activation par le service RH.'
        ], 200);
    }

    /**
     * Connexion de l'utilisateur. Renvoie le token JWT.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données de connexion invalides.',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = [
            'email' => strtolower($request->email),
            'password' => $request->password
        ];

        // Tentative de génération du token JWT avec les identifiants
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email ou mot de passe incorrect.'
            ], 401);
        }

        // Récupérer l'utilisateur connecté
        $user = auth()->user();

        // Vérifier si son e-mail a bien été validé par l'OTP
        if (!$user->email_verified_at) {
            // Renvoyer un nouveau code OTP si non vérifié
            $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->otp_code = $otpCode;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            try {
                Mail::to($user->email)->send(new OtpMail($otpCode, $user->prenom));
            } catch (\Exception $e) {
                \Log::error("Erreur envoi OTP lors du login: " . $e->getMessage());
            }

            return response()->json([
                'success' => false,
                'email_not_verified' => true,
                'message' => 'Votre adresse e-mail doit être vérifiée d\'abord. Un nouveau code OTP vous a été envoyé.'
            ], 403);
        }

        // On renvoie le token et les infos de l'utilisateur
        return response()->json([
            'success' => true,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60, // Durée de validité en secondes
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'role' => $user->role,
                'statut_compte' => $user->statut_compte,
                'departement_id' => $user->departement_id,
            ]
        ], 200);
    }

    /**
     * Déconnexion de l'utilisateur. Invalide le token JWT.
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie.'
        ], 200);
    }

    /**
     * Récupération des informations de l'utilisateur connecté.
     */
    public function me()
    {
        return response()->json([
            'success' => true,
            'user' => auth()->user()
        ], 200);
    }

    /**
     * Rafraîchir le token d'accès JWT expiré.
     */
    public function refresh()
    {
        try {
            $newToken = auth()->refresh();
            return response()->json([
                'success' => true,
                'token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de rafraîchir le token.'
            ], 401);
        }
    }

    /**
     * Endpoint RH : Activer ou suspendre le compte d'un collaborateur.
     */
    public function changerStatutCompte(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'statut_compte' => 'required|in:actif,suspendu,en_attente',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($id);
        $user->statut_compte = $request->statut_compte;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => "Le statut du compte de l'utilisateur a été mis à jour avec succès en : " . $request->statut_compte,
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'statut_compte' => $user->statut_compte
            ]
        ], 200);
    }
}
