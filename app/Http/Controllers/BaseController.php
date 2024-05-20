<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BaseController extends Controller
{   
    protected function checkRequest(Request $request, $scope)
    {
        $validator = Validator::make($request->all(), [
            'type' => $scope,
            'contents' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid request',
                'messages' => $validator->errors()
            ], 400);
        }

        return null;
    }

    protected function generateReadResponse($fields, $extraClause, $table, $bindings = [])
    {
        $query = "SELECT $fields FROM $table $extraClause";
        $result = DB::select($query, $bindings);

        if (empty($result)) {
            return response()->json(['error' => 'No data found'], 404);
        }

        return response()->json($result, 200);
    }

    protected function generateErrorMessage($originator)
    {
        $messages = $originator->errors()->all();
        return implode(', ', $messages);
    }

    protected function makeRulesRequired($rules)
    {
        $requiredRules = [];

        foreach ($rules as $key => $value) {
            $requiredRules[$key] = 'required|' . $value;
        }

        return $requiredRules;
    }

    protected function removeRequiredFromRules($rules)
    {
        $modifiedRules = [];

        foreach ($rules as $key => $value) {
            $modifiedRules[$key] = preg_replace('/^required\|/', '', $value);
        }

        return $modifiedRules;
    }

    protected function getRules($table)
    {
        switch ($table) {
            case 'town':
                return [
                    'name' => 'string|max:255',
                    'zip_code' => 'integer|unique:town',
                    'username' => 'string|unique:town|max:255',
                    'password' => 'string|max:255',
                ];
                break;
            case 'establishment':
                return [
                    'name' => 'string|max:255',
                    'code' => 'integer|unique:establishment',
                    'address' => 'string|max:255',
                    'username' => 'string|unique:establishment|max:255',
                    'password' => 'string|max:255',
                ];
                break;
            case 'superadmin':
                return [
                    'name' => 'string|max:255',
                    'username' => 'string|unique:super_admin|max:255',
                    'password' => 'string|max:255',
                ];
                break;
            case 'barangay':
                return [
                    'name' => 'string|max:255',
                    'town_id' => 'integer|exists:towns,id',
                    'username' => 'string|unique:super_admin|max:255',
                    'password' => 'string|max:255',
                ];
                break;
            case 'senior':
                return [
                    'osca_id' => 'required|string|max:255',
                    'fname' => 'required|string|max:255',
                    'mname' => 'nullable|string|max:255',
                    'lname' => 'required|string|max:255',
                    'barangay_id' => 'required|integer|exists:barangay,id',
                    'birthdate' => 'required|date',
                    'contact_number' => 'required|string|size:11',
                    'username' => 'required|string|unique:senior|max:255',
                    'profile_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'qr_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];
                break;
            default:
                return [];
        }
    }

    protected function transformRulesForUpdate($rules, $contents)
    {
        $updatedRules = ['id' => 'required|integer'];

        foreach ($rules as $field => $rule) {
            if (strpos($rule, 'unique:') !== false) {
                list($validator, $uniqueConstraint) = explode(':', $rule);
                $tableColumn = explode(',', $uniqueConstraint)[0];

                $updatedRules[$field] = "$validator:{$tableColumn}," . $contents['id'];
            } else {
                $updatedRules[$field] = $rule;
            }
        }

        return $updatedRules;
    }
}