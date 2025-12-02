<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
    }

    /**
     * Показывает страницу генерации 2FA
     */
    public function show(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->google2fa_enabled) {
            return redirect()->route('dashboard')->with('info', '2FA уже активирована');
        }

        // Генерируем новый секрет
        $secret = $this->google2fa->generateSecretKey();
        
        // QR код
        $qrCode = $this->google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('auth.two-factor', [
            'qrCode' => $qrCode,
            'secret' => $secret,
        ]);
    }

    /**
     * Активирует 2FA для пользователя
     */
    public function enable(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
            'code' => 'required|numeric|digits:6',
        ]);

        $user = auth()->user();
        
        // Проверяем код
        if (!$this->google2fa->verifyKey($request->secret, $request->code)) {
            return back()->withErrors(['code' => 'Неверный код. Пожалуйста, попробуйте еще раз.']);
        }

        // Сохраняем секрет и активируем 2FA
        $user->update([
            'google2fa_secret' => $request->secret,
            'google2fa_enabled' => true,
        ]);

        return redirect()->route('dashboard')->with('success', '2FA успешно активирована');
    }

    /**
     * Отключает 2FA для пользователя
     */
    public function disable(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $user = auth()->user();
        
        if (!$user->google2fa_secret) {
            return back()->withErrors(['code' => '2FA не активирована']);
        }

        // Проверяем код
        if (!$this->google2fa->verifyKey($user->google2fa_secret, $request->code)) {
            return back()->withErrors(['code' => 'Неверный код']);
        }

        // Отключаем 2FA
        $user->update([
            'google2fa_secret' => null,
            'google2fa_enabled' => false,
        ]);

        return back()->with('success', '2FA отключена');
    }

    /**
     * Верифицирует код при логине
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $user = auth()->user();
        
        if (!$user || !$user->google2fa_enabled) {
            return redirect()->route('login');
        }

        if (!$this->google2fa->verifyKey($user->google2fa_secret, $request->code)) {
            return back()->withErrors(['code' => 'Неверный код']);
        }

        // Отмечаем сессию как верифицированную
        session(['auth.2fa_verified' => true]);
        
        return redirect()->route('dashboard');
    }
}
