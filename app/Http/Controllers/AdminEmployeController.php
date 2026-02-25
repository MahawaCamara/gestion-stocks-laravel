<?php

namespace App\Http\Controllers;

use App\Mail\EmployeInscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminEmployeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'employe');

        // Recherche par nom ou email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Tri (asc ou desc) par colonne
        $sortField = $request->input('sort_field', 'name');
        $sortDirection = $request->input('sort_direction', 'asc');

        // Sécurité : autoriser uniquement certains champs au tri
        if (!in_array($sortField, ['name', 'email', 'is_active'])) {
            $sortField = 'name';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $employes = $query->orderBy($sortField, $sortDirection)->paginate(10)->withQueryString();

        return view('administration.pages.employes.liste-employes', compact('employes', 'sortField', 'sortDirection'));
    }


    public function create()
    {
        return view('administration.pages.employes.create-employe');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

      $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public'); 
        }

        // On garde le mot de passe clair pour l'envoyer par mail
        $plainPassword = $request->password;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'role' => 'employe',
            'is_active' => true,
            'photo' => $photoPath,
        ]);

        // Envoie d'un email d'inscription
        Mail::to($user->email)->send(new EmployeInscription($user->name, $user->email, $plainPassword));

        return redirect()->route('employes.index')->with('success', 'Employé créé avec succès et email envoyé.');
    }


    public function update(Request $request, User $employe)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $employe->id,
            'password' => 'nullable|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Préparer les données à mettre à jour
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Si on a une nouvelle photo
        if ($request->hasFile('photo')) {
            // Supprimer l’ancienne photo si elle existe
            if ($employe->photo && Storage::disk('public')->exists($employe->photo)) {
                Storage::disk('public')->delete($employe->photo);
            }

            // Stocker la nouvelle photo
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        // Si on a un nouveau mot de passe
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $employe->update($data);

        return redirect()->route('administration.pages.employes.index')->with('success', 'Employé mis à jour avec succès.');
    }

    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return back();
    }

    public function destroy(User $employe)
    {
        if ($employe->role !== 'employe') {
            return redirect()->back()->with('error', "Vous ne pouvez supprimer que des employés.");
        }

        // Supprimer l'image s'il y en a une
        if ($employe->photo && Storage::disk('public')->exists($employe->photo)) {
            Storage::disk('public')->delete($employe->photo);
        }

        $employe->delete();

        return redirect()->route('employes.index')->with('success', 'Employé supprimé avec succès.');
    }
}
