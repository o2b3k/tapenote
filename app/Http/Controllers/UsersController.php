<?php

namespace App\Http\Controllers;

use App\Constants\UserRoles;
use App\Mail\RegistrationMail;
use App\Models\RegistrationToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Validator;

class UsersController extends Controller
{
    const REGISTRATION_TOKEN_LIVE_TIME = 3 * 24 * 60 * 60; // 4 days


    public function __construct()
    {
        $except = ['except' => ['registerForm', 'register']];
        $this->middleware('auth', $except);
        $this->middleware(
            'role:' . UserRoles::ROLE_SUPER_USER,
            array_merge_recursive($except, ['except' => [
                'editForm', 'edit'
            ]])
        );
    }


    public function index()
    {
        return view('users.index', ['users' => User::all()]);
    }


    public function addUserForm()
    {
        return view('users.add');
    }


    public function registerForm(Request $request, $token)
    {
        $registrationToken = $this->getValidatedToken($token);

        return view('users.register', [
            'email' => $registrationToken->email,
            'token' => $registrationToken->token
        ]);
    }


    public function register(Request $request, $token)
    {
        $registrationToken = $this->getValidatedToken($token);

        if (User::where('email', $registrationToken->email)->count() > 0) {
            $registrationToken->delete();
            abort(400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'password' => 'required|min:6|max:255|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $user = new User();
        $user->fill($request->all());
        $user->email = $registrationToken->email;
        $user->role = UserRoles::ROLE_ADMIN;
        $user->password = bcrypt($request->input('password'));
        $user->save();

        // Login user after registration
        $credentials = [
            'email' => $user->email,
            'password' => $user->password
        ];

        // Remove registration token
        $registrationToken->delete();

        if (Auth::attempt($credentials)) {
            $request->session()->flash('status', [
                'type' => 'success',
                'message' => 'Welcome ' . $request->input('name') . ' ' . $request->input('surname') . '!',
            ]);

            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login');
        }
    }


    public function invite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|max:40',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $registrationToken = RegistrationToken::where('email', $request->input('email'))->first();
        if (!$registrationToken) {
            $registrationToken = new RegistrationToken();
        }

        $registrationToken->email = $request->input('email');
        $registrationToken->token = base64_encode(str_random(40));
        $registrationToken->save();

        Mail::to($registrationToken->email)->send(new RegistrationMail($registrationToken->token));

        return redirect()->route('users.index');
    }


    public function editForm(Request $request, $id)
    {
        $authUser = Auth::user();
        if ($authUser->id != $id) {
            abort(404);
        }

        $user = $this->getValidatedUser($id);
        return view('users.edit', ['user' => $user]);
    }


    public function edit(Request $request, $id) {
        $authUser = Auth::user();
        if ($authUser->id != $id) {
            abort(404);
        }

        $user = $this->getValidatedUser($id);

        $validator = Validator::make($request->all(), [
            'name' => 'present|max:255',
            'surname' => 'present|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->save();

        $request->session()->flash('status', [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'User information successfully updated'
        ]);

        return redirect()->route('users.index');
    }


    public function deleteUser(Request $request)
    {
        $validator = Validator::make($request->all(), ['user_id' => 'present|exists:users,id']);

        if (!$validator->fails()) {
            $authUser = Auth::user();
            $user = User::find($request->input('user_id'));
            if ($authUser->id != $user->id) {
                $user->delete();

                $request->session()->flash('status', [
                    'type' => 'success',
                    'title' => 'Success',
                    'message' => 'User has been deleted'
                ]);
            }
            else {
                $request->session()->flash('status', [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Delete own user is not allowed'
                ]);
            }
        }
        else {
            $request->session()->flash('status', [
                'type' => 'error',
                'title' => 'Error. Unable delete user',
                'message' => 'User not found in a database'
            ]);
        }

        return redirect()->route('users.index');
    }


    private function getValidatedToken($token)
    {
        if (!$token) {
            abort(404);
        }

        $registrationToken = RegistrationToken::where('token', $token)->first();
        if (!$registrationToken) {
            abort(404);
        }

        if ($registrationToken->updated_at->timestamp < time() - self::REGISTRATION_TOKEN_LIVE_TIME) {
            $registrationToken->delete();
            abort(404);
        }

        return $registrationToken;
    }


    private function getValidatedUser($id) {
        if (!$id) {
            abort(404);
        }

        $user = User::find($id);
        if (!$user) {
            abort(404);
        }

        return $user;
    }
}