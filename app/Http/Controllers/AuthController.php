<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
class AuthController extends Controller
{
    public function showLogin(): View { return view('auth.login'); }
    public function showRegister(): View { return view('auth.register'); }
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:100'],
            'email' => ['required','email','max:150','unique:users,email'],
            'password' => ['required','confirmed', Password::min(8)],
        ]);
        $user = User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>$data['password'],'language'=>'en']);
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('dashboard')->with('success', 'Account created successfully.');
    }
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email'=>['required','email'],'password'=>['required','string']]);
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }
        return back()->withErrors(['email'=>'The provided credentials do not match our records.'])->onlyInput('email');
    }
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
