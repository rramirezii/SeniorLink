<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    public static function createRecord($data)
    {
        return self::create($data);
    }

    public function updateRecord($data)
    {
        return $this->update($data);
    }

    public function deleteRecord($id)
    {
        return $this->destroy($id);
    }

    public function getAll()
    {
        return $this->all();
    }

    public function getById($id)
    {
        return $this->find($id);
    }
}
