<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Webaccess\BiometLaravel\Models\User;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        return view('biomet::pages.auth.login', [
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function authenticate(Request $request)
    {
        if (Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ])) {
            return redirect()->intended('/');
        }

        return redirect()->route('login')->with([
            'error' => trans('biomet::login.error_login_or_password'),
        ]);
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function forgotten_password(Request $request)
    {
        return view('biomet::pages.auth.forgotten_password', [
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'message' => ($request->session()->has('message')) ? $request->session()->get('message') : null,
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function forgotten_password_handler(Request $request)
    {
        $userEmail = $request->input('email');

        try {
            if ($user = User::where('email', '=', $userEmail)->first()) {
                $newPassword = self::generate(8);
                $user->password = bcrypt($newPassword);
                $user->save();
                $this->sendNewPasswordToUser($newPassword, $userEmail);
                $request->session()->flash('message', 'Un email contenant votre nouveau mot de passe vous a été envoyé sur votre adresse.');
            } else {
                throw new \Exception('Aucun compte trouvé avec cette adresse');
            }
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('forgotten_password');
    }

    /**
     * @param $newPassword
     * @param $userEmail
     */
    private function sendNewPasswordToUser($newPassword, $userEmail)
    {
        Mail::send('biomet::emails.password', array('password' => $newPassword), function ($message) use ($userEmail) {

            $message->to($userEmail)
                ->from('no-reply@biomet.com')
                ->subject('[Biomet] Votre nouveau mot de passe pour accéder à votre compte');
        });
    }

    /**
     * @param int $length
     * @return string
     */
    private static function generate($length = 8)
    {
        $chars = 'abcdefghkmnpqrstuvwxyz23456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; ++$i) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
}