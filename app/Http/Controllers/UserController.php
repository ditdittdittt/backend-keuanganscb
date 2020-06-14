<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\Http\Requests\UserRequest;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $modelName = 'User';

    public function update(User $user, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'email|unique:users',
                'username' => 'unique:users|alpha_dash'
            ]);
            if ($validator->fails()) {
                return ReturnGoodWay::failedReturn(
                    $validator->errors()->first(),
                    "bad request"
                );
            };
            $fieldArray = $request->except('password');
            if (auth()->user()->id == $user->id || auth()->user()->hasRole('admin')) {
                if ($request->has('password')) {
                    $hashedPassword = Hash::make($request->password);
                    $fieldArray['password'] = $hashedPassword;
                }
                $user->update($fieldArray);
                return ReturnGoodWay::successReturn(
                    $user,
                    $this->modelName,
                    $this->modelName . " has been updated",
                    "success"
                );
            }
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function destroy(User $user)
    {
        try {
            if (auth()->user()->id == $user->id || auth()->user()->hasRole('admin')) {
                $user->delete();
                return ReturnGoodWay::successReturn(
                    $user,
                    $this->modelName,
                    $this->modelName . " has been deleted",
                    "success"
                );
            }
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function getAllUserWithAllTheirRolesAndPermissions()
    {
        try {
            $users = User::with(['roles', 'permissions'])->get();
            foreach ($users as $user) {
                $user->getPermissionsViaRoles();
            }
            return ReturnGoodWay::successReturn(
                $users,
                "Users",
                "A list of all Users and their Roles and Permissions",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException("Users");
        }
    }

    public function changeRole(Request $request)
    {
        try {
            $user = User::with('roles')->findOrFail($request['user_id']);
            $currentRole = $user->getRoleNames()['0'];
            $user->removeRole($currentRole);
            $user->assignRole($request['role_name']);
            $user->save();
            return ReturnGoodWay::successReturn(
                $user,
                $this->modelName,
                "Role of User " . $user->id . " has successfully changed",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function assignRole(Request $request)
    {
        try {
            $user = User::findOrFail($request['user_id']);
            $user->syncRoles($request['role_name']);
            return ReturnGoodWay::successReturn(
                $user,
                $this->modelName,
                "Role has successfully assigned to User " . $user->id,
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function removeRole(Request $request)
    {
        try {
            $user = User::findOrFail($request['user_id']);
            $user->removeRole($request['role_name']);
            return ReturnGoodWay::successReturn(
                $user,
                $this->modelName,
                "User " . $user->id . " has successfully removed from the role " . $request['role_name'],
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function givePermission(Request $request)
    {
        try {
            $user = User::findOrFail($request['user_id']);
            $user->givePermissionTo($request['permission_name']);
            return ReturnGoodWay::successReturn(
                $user,
                $this->modelName,
                "The User " . $user->id . " has been given permission for " . $request['permission'],
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function revokePermission(Request $request)
    {
        try {
            $user = User::findOrFail($request['user_id']);
            $user->revokePermissionTo($request['permission_name']);
            return ReturnGoodWay::successReturn(
                $user,
                $this->modelName,
                "The User " . $user->id . " has been revoked permission to " . $request['permission'],
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function count()
    {
        try {
            $usersCount = User::all()->count();
            return response()->json([
                'total_users' => $usersCount
            ], 200);
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }
}
