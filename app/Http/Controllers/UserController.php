<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('nom')->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'       => 'required|string',
            'prenom'    => 'required|string',
            'email'     => 'required|email|unique:users,email',
            'telephone' => 'nullable|string',
            'role'      => 'required|in:admin,caissier,superviseur,caissierHaut,secretaire,magasinier',
            'password'  => 'required|min:6|confirmed',
        ]);

        User::create([
            'nom'       => $request->nom,
            'prenom'    => $request->prenom,
            'email'     => $request->email,
            'telephone' => $request->telephone,
            'role'      => $request->role,
            'password'  => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'prenom'    => 'required|string|max:100',
            'nom'       => 'required|string|max:100',
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'telephone' => 'nullable|string|max:20',
            'role'      => 'required|in:admin,caissier,superviseur,caissierHaut,secretaire,magasinier',
            'password'  => 'nullable|string|min:6|confirmed',
        ], [
            'prenom.required'   => 'Le prénom est obligatoire.',
            'nom.required'      => 'Le nom est obligatoire.',
            'email.required'    => 'L\'email est obligatoire.',
            'email.email'       => 'L\'email n\'est pas valide.',
            'email.unique'      => 'Cet email est déjà utilisé par un autre compte.',
            'role.required'     => 'Le rôle est obligatoire.',
            'role.in'           => 'Le rôle sélectionné est invalide.',
            'password.min'      => 'Le mot de passe doit avoir au moins 6 caractères.',
            'password.confirmed'=> 'La confirmation ne correspond pas.',
        ]);
 
        $data = [
            'prenom'    => $request->prenom,
            'nom'       => $request->nom,
            'email'     => $request->email,
            'telephone' => $request->telephone,
            'role'      => $request->role,
        ];
 
        // Ne mettre à jour l'actif que si ce n'est pas l'utilisateur connecté
        if ($user->id !== auth()->id()) {
            $data['actif'] = $request->boolean('actif');
        }
 
        // Mot de passe : seulement si fourni
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
 
        $user->update($data);
 
        return redirect()
            ->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }
 
    public function toggle(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->update(['actif' => !$user->actif]);
        $msg = $user->actif ? 'activé' : 'désactivé';

        return back()->with('success', "Utilisateur {$msg} avec succès.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé.');
    }
}
