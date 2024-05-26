<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SeniorController extends BaseController
{
    // get /senior/dashboard
    public function dashboard()
    {
        return response()->json(["role" => "basic"], 200); //edit to return session as well
    }

    // get /senior/{$senior_username}/show/{$client}
    public function read($client, $senior_username)
    {
        $fields = '*';
        $extraClause = '';

        switch($client){
            case 'senior':
                $fields = 'senior.id, senior.osca_id, senior.fname, senior.mname, senior.lname, barangay.name as barangay_name, town.name as town_name, senior.birthdate, senior.contact_number, senior.username, senior.profile_image, senior.qr_image';
                $extraClause = 'LEFT JOIN barangay 
                                ON senior.barangay_id = barangay.id
                                LEFT JOIN town 
                                ON barangay.town_id = town.id
                                WHERE senior.username = :senior_username';

                break;
            case 'transaction':
                $fields = 'senior.id, senior.osca_id, senior.fname, senior.mname, senior.lname, 
                            barangay.name as barangay_name, town.name as town_name, senior.birthdate, 
                            senior.contact_number, senior.username, senior.profile_image, senior.qr_image, 
                            transaction.date as transaction_date, 
                            GROUP_CONCAT(products.name SEPARATOR \', \') as product_names,
                            GROUP_CONCAT(products.quantity SEPARATOR \', \') as product_quantities,
                            GROUP_CONCAT(products.price SEPARATOR \', \') as product_prices';
                $extraClause = 'LEFT JOIN barangay ON senior.barangay_id = barangay.id
                            LEFT JOIN town ON barangay.town_id = town.id
                            LEFT JOIN transaction ON senior.id = transaction.senior_id
                            LEFT JOIN product_transaction ON transaction.id = product_transaction.transaction_id
                            LEFT JOIN products ON product_transaction.products_id = products.id
                            WHERE senior.username = :senior_username
                            GROUP BY transaction.id';
                break;
            default:
                return response()->json(['error' => 'Unknown client type'], 404);
        }

        if (is_null($bID)) {
            return response()->json(['error' => 'bID parameter is required'], 400);
        }

        return $this->generateReadResponse($fields, $extraClause, $client, ['senior_username' => $senior_username]);
    }

    // for test 
    // get /senior/{senior_username}/show/qr
    public function getQR($senior_username)
    {
        $senior = Senior::where('username', $senior_username)->first();

        if (!$senior) {
            return response()->json(['error' => 'Senior not found'], 404);
        }

        if (!$senior->qr_image) {
            return response()->json(['error' => 'QR image not found for the senior'], 404);
        }

        return response($senior->qr_image, 200)->header('Content-Type', 'image/jpeg');
    }

    // get /senior/{id}
    public function getSelf($id)
    {
        $senior = DB::table('senior')->find($id);

        return response()->json($senior, 200);
    }

    // post /senior/retrieve
    public function getDetailFromQR(Request $request)
    {
        // process the scanned qr here and return the details
    }

    // updates a the phone number and profile 
    // post /senior/update
    public function update(Request $request)
    {
        $validation = $this->checkRequest($request, $this->getStrictScope());

        if ($validation !== null) {
            return $validation;
        }

        $type = $request->input('type');
        $contents = $request->input('contents');

        try {
            $this->updateEntity($type, $contents);
            return response()->json(['message' => 'Update successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed', 'message' => $e->getMessage()], 400);
        }
    }

    private function updateEntity($table, $contents)
    {
        $rules = $this->getRules($table);

        if (empty($rules)) {
            throw new \Exception('Invalid table name or missing validation rules.');
        }

        $rules = $this->transformRulesForUpdate($rules, $contents);
        $validator = Validator::make($contents, $rules);

        if ($validator->fails()) {
            throw new \Exception($this->generateErrorMessage($validator));
        }

        if (!isset($contents['id'])) {
            throw new \Exception('ID not provided for update');
        }

        $id = $contents['id'];
        unset($contents['id']); // remove id from content to avoid update of id

        $contents = array_filter($contents, function ($value) {
            return $value !== "" && $value !== null;
        });

        $affected = DB::table($table)->where('id', $id)->update($contents);

        if ($affected === 0) {
            throw new \Exception('No record found to update or no valid changes made');
        }
    }

    private function getScope()
    {
        return "required|string|in:senior, transaction, products";
    }

    private function getStrictScope()
    {
        return "required|string|in: senior";
    }

    private function getUpdateScope()
    {
        return "";
    }
}