<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\Http\Requests\ValidateUser;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $modelName = "User";

    public function register(ValidateUser $request)
    {
        try {
            $hashedPassword = Hash::make($request['password']);
            $user = new User();
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->password = $hashedPassword;
            $user->division = $request['division'];
            $user->email_verified_at = Carbon::now();
            $user->assignRole($request['role']);
            $user->save();
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
        $credentials = $request->only('email', 'password');
        if (Auth::guard('web')->attempt($credentials)) {
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
}
