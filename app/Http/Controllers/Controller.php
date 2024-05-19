<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

abstract class Controller
{
    protected function checkRequest($request, $scope)
    {
        
        $validator = Validator::make($request->all(), [
            'type' => $scope,
            'contents' => 'required|array'
        ]);
    
        if ($validator->fails()) {
            return [
                'status' => 400,
                'data' => ['error' => 'Invalid request', 'messages' => $validator->errors()]
            ];
        }
    
        return [
            'status' => 200,
            'data' => []
        ];
    }

    protected function generateReadResponse($fields, $extraClause, $table)
    {
        $result = DB::select("SELECT $fields FROM $table $extraClause");

        if(empty($result)){
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

    // move this out soon for other controllers to use
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
            default:
                return [];
        }
    }

    protected function transformRulesForUpdate($rules, $contents)
    {
        $updatedRules = [];

        // add the id field
        if (!isset($updatedRules['id'])) {
            $updatedRules['id'] = 'required|integer';
        }

        foreach ($rules as $field => $rule) {
            $updatedRules[$field] = $rule;

            // unique fields must add validation 
            if (strpos($rule, 'unique:') !== false) {
                list($validator, $uniqueConstraint) = explode(':', $rule);
                $tableColumn = explode(',', $uniqueConstraint)[0];

                $updatedRules[$field] = "$validator:{$tableColumn}," . $contents['id'];
            }
        }

        return $updatedRules;
    }
}
