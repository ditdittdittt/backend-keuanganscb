<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionController extends Controller
{
    private $role_model_name = "Role";
    private $permission_model_name = "Permission";
    public function getAllRolesAndPermissions()
    {
        try {
            $permissions = Permission::all();
            $roles = Role::all();
            $data = array(
                'roles' => $roles,
                'permissions' => $permissions,
            );
            // return $data;
            return ReturnGoodWay::multipleReturn(
                $data,
                "List roles and permissions",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->role_model_name);
        }
    }

    /**
     * Get All Roles
     */
    public function getAllRoles()
    {
        try {
            $roles = Role::all();
            return ReturnGoodWay::successReturn(
                $roles,
                $this->role_model_name,
                "List of all roles",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->role_model_name);
        }
    }

    /**
     * Get All Permissions
     */
    public function getAllPermissions()
    {
        try {
            $permissions = Permission::all();
            return ReturnGoodWay::successReturn(
                $permissions,
                $this->role_model_name,
                "List of all permissions",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->permission_model_name);
        }
    }

    public function storeRole(Request $request)
    {
        try {
            $role = Role::create([
                'name' => $request['role_name']
            ]);
            return ReturnGoodWay::successReturn(
                $role,
                "Role",
                "Role has successfully stored",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->role_model_name);
        }
    }

    public function storePermission(Request $request)
    {
        try {
            $permission = Permission::create([
                'name' => $request['permission_name']
            ]);
            return ReturnGoodWay::successReturn(
                $permission,
                $this->permission_model_name,
                $this->permission_model_name . " has successfully stored",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->permission_model_name);
        }
    }

    public function assignPermissionToRole(Request $request)
    {
        try {
            $role = Role::findByName($request['role_name']);
            $role->givePermissionTo($request['permission_name']);
            return ReturnGoodWay::successReturn(
                $role,
                'Role',
                "The " . $role->name . " role has been given permission for " . $request['permission'],
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->role_model_name);
        }
    }

    public function revokePermissionFromRole(Request $request)
    {
        try {
            $role = Role::findByName($request['role_name']);
            $role->revokePermissionTo($request['permission_name']);
            return ReturnGoodWay::successReturn(
                $role,
                'Role',
                "The " . $role->name . " role has been revoked permission to " . $request['permission'],
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->role_model_name);
        }
    }

    public function getPermissionsByRole(Request $request)
    {
        try {
            $role = Role::findByName($request['role_name']);
            $permissions = $role->permissions;
            return ReturnGoodWay::successReturn(
                $permissions,
                $this->permission_model_name,
                "List Permissions that Role " . $role->name . " has",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->role_model_name);
        }
    }
}
