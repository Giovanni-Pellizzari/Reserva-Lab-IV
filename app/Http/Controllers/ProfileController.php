<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile.
     */
    public function index(Request $request): View
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
{
    // Rellenamos los campos con los datos validados
    $user = $request->user();
    $user->fill($request->validated());

    // Si el correo electrónico ha cambiado, lo marcamos como no verificado
    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    // Guardamos los cambios en el usuario
    $user->save();

    // Redirigimos al formulario de edición con un mensaje de éxito
    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}

public function updatePassword(Request $request)
{
    // Validar las contraseñas
    $validated = $request->validate([
        'password' => 'required|confirmed|min:8', // Valida que las contraseñas coincidan y tenga al menos 8 caracteres
    ]);

    // Obtener al usuario actual
    $user = Auth::user();
    
    // Cambiar la contraseña
    $user->password = Hash::make($request->password);
    $user->save();

    // Redirigir con mensaje de éxito
    return redirect()->route('profile')->with('status', 'Contraseña actualizada exitosamente.');
}


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'Tu cuenta ha sido eliminada exitosamente.');
    }
}
