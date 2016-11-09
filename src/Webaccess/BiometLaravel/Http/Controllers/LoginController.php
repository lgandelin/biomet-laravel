<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Webaccess\BiometLaravel\Models\User;

class LoginController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function login()
    {
        return view('biomet::pages.auth.login', [
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
        ]);
    }

    /**
     * @param 
     * @return mixed
     */
    public function authenticate()
    {
        if (Auth::attempt([
            'email' => $this->request->input('email'),
            'password' => $this->request->input('password'),
        ])) {
            return redirect()->intended('/');
        }

        return redirect()->route('login')->with([
            'error' => trans('biomet::login.login_or_password_error'),
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
     * @param 
     * @return mixed
     */
    public function forgotten_password()
    {
        return view('biomet::pages.auth.forgotten_password', [
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'message' => ($this->request->session()->has('message')) ? $this->request->session()->get('message') : null,
        ]);
    }

    /**
     * @param 
     * @return mixed
     */
    public function forgotten_password_handler()
    {
        $userEmail = $this->request->input('email');

        try {
            if ($user = User::where('email', '=', $userEmail)->first()) {
                $newPassword = self::generate(8);
                $user->password = bcrypt($newPassword);
                $user->save();
                $this->sendNewPasswordToUser($newPassword, $userEmail);
                $this->request->session()->flash('message', trans('biomet::login.forgotten_password_email_success'));
            } else {
                $this->request->session()->flash('error', trans('biomet::login.forgotten_password_email_not_found_error'));
            }
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::login.forgotten_password_generic_error'));
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