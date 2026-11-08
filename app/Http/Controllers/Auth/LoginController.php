<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(): View
    {
        $prenoms = User::query()
            ->where('actif', true)
            ->orderBy('prenom')
            ->pluck('prenom')
            ->unique()
            ->values();

        return view('auth.login', compact('prenoms'));
    }

    public function username(): string
    {
        return 'prenom';
    }

    protected function validateLogin(Request $request): void
    {
        $request->validate([
            'prenom' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'prenom.required' => 'Le prenom est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);
    }

    protected function credentials(Request $request): array
    {
        return [
            'prenom' => trim((string) $request->input('prenom')),
            'password' => $request->input('password'),
            'actif' => true,
        ];
    }

    protected function attemptLogin(Request $request): bool
    {
        $prenom = trim((string) $request->input('prenom'));

        $user = User::query()
            ->where('actif', true)
            ->get()
            ->first(fn (User $user) => Str::lower($user->prenom) === Str::lower($prenom));

        if (! $user) {
            return false;
        }

        return $this->guard()->attempt([
            'prenom' => $user->prenom,
            'password' => $request->input('password'),
            'actif' => true,
        ], $request->boolean('remember'));
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $prenom = trim((string) $request->input('prenom'));

        $inactiveUser = User::query()
            ->get()
            ->first(fn (User $user) => Str::lower($user->prenom) === Str::lower($prenom) && ! $user->actif);

        $message = $inactiveUser
            ? 'Ce compte est desactive. Contactez l administrateur.'
            : 'Prenom ou mot de passe incorrect.';

        throw ValidationException::withMessages([
            $this->username() => [$message],
        ]);
    }
}
