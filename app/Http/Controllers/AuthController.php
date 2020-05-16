<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\Http\Requests\UserRequest;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $modelName = "User";
    public $successStatus = 200;

    public function register(UserRequest $request)
    {
        try {
            $hashedPassword = Hash::make($request['password']);
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = $hashedPassword;
            $user->division = $request->division;
            $user->email_verified_at = Carbon::now();
            $user->assignRole($request->role);
            $user->save();
            $user->access_token =  $user->createToken($user->email)->accessToken;
            return ReturnGoodWay::successReturn(
                $user,
                $this->modelName,
                $this->modelName . " telah berhasil dibuat",
                'created'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function login(Request $request)
    {
        $credentialsWithEmail = $request->only('email', 'password');
        $credentialsWithUsername = $request->only(['username', 'password']);
        if (
            Auth::attempt($credentialsWithEmail) ||
            Auth::attempt($credentialsWithUsername)
        ) {
            $user = Auth::user();
            if ($user->email_verified_at != NULL) {
                $success['token'] =  $user->createToken($user->email)->accessToken;
                return response()->json(['success' => $success], 200);
            } else {
                return response()->json(['messages' => 'Please verify your email first'], 401);
            }
        } else {
            return response()->json(['error' => "The email or password you entered don't match our records. Please check and try again."], 401);
        }
    }

    public function getUser()
    {
        $user = auth()->user();
        $user->roles;
        //        $roles = User::find(auth()->user()->id)->getRoleNames();
        //        $user->roles = $roles;
        // $user->roles;
        // $user->permissions;
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->revoke();
        });

        return response()->json('Logged out', $this->successStatus);
    }
}
