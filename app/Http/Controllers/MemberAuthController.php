<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MemberAuthController extends Controller
{
    public function showSignup(): View
    {
        return view('member.auth.signup');
    }

    public function signup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $normalizedPhone = $this->normalizePhone($validated['phone']);

        if (User::where('phone', $normalizedPhone)->exists()) {
            return back()
                ->withErrors([
                    'phone' => 'This phone number is already registered.',
                ])
                ->onlyInput('name', 'phone');
        }

        $member = User::create([
            'name' => $validated['name'],
            'email' => $this->buildMemberEmail($normalizedPhone),
            'phone' => $normalizedPhone,
            'country' => $this->resolveCountry($request),
            'password' => $validated['password'],
            'is_admin' => false,
            'is_member' => true,
        ]);

        Auth::login($member);
        $request->session()->regenerate();

        return redirect()
            ->route('home')
            ->with('status', 'Welcome! Your member account has been created successfully.');
    }

    public function showSignin(): View
    {
        return view('member.auth.signin');
    }

    public function signin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string'],
        ]);

        $normalizedPhone = $this->normalizePhone($validated['phone']);

        if (! Auth::attempt(['phone' => $normalizedPhone, 'password' => $validated['password']], $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'phone' => 'The provided phone number or password is incorrect.',
                ])
                ->onlyInput('phone');
        }

        $request->session()->regenerate();

        if ($request->user()?->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors([
                    'phone' => 'This account is reserved for the admin portal. Please use admin sign in.',
                ])
                ->onlyInput('phone');
        }

        return redirect()
            ->route('home')
            ->with('status', 'Welcome back, member.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('status', 'You have been signed out.');
    }

    private function normalizePhone(string $phone): string
    {
        $phone = trim($phone);
        $hasPlus = str_starts_with($phone, '+');
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        return $hasPlus ? '+' . $digits : $digits;
    }

    private function buildMemberEmail(string $phone): string
    {
        $emailLocalPart = preg_replace('/\D+/', '', $phone) ?? $phone;

        return 'member.' . $emailLocalPart . '@members.praythroughwithgodsword.local';
    }

    private function resolveCountry(Request $request): string
    {
        $country = $request->server('HTTP_CF_IPCOUNTRY')
            ?? $request->server('GEOIP_COUNTRY_NAME')
            ?? $request->server('HTTP_X_COUNTRY_NAME')
            ?? null;

        if (filled($country)) {
            return (string) $country;
        }

        return in_array($request->ip(), ['127.0.0.1', '::1'], true) ? 'Localhost' : 'Unknown';
    }
}
