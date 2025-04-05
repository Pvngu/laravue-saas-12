<?php


namespace App\Models;

use Sqids\Sqids;
use Examyou\RestAPI\ApiModel;
use Illuminate\Support\Facades\Log;

class BaseModel extends ApiModel
{

    function __call($method, $arguments)
    {
        if (isset($this->hashableGetterFunctions) && isset($this->hashableGetterFunctions[$method])) {

            $sqids = new Sqids();
            $value = $this->{$this->hashableGetterFunctions[$method]};

            if ($value) {
                $value = $sqids->encode($value);
            }

            return $value;
        }

        if (isset($this->hashableGetterArrayFunctions) && isset($this->hashableGetterArrayFunctions[$method])) {

            $sqids = new Sqids();
            $value = $this->{$this->hashableGetterArrayFunctions[$method]};

            if (count($value) > 0) {
                $valueArray = [];

                foreach ($value as $productId) {
                    $valueArray[] = $sqids->encode($productId);
                }

                $value = $valueArray;
            }

            return $value;
        }

        return parent::__call($method, $arguments);
    }

    public function getXIDAttribute()
    {
        $sqids = new Sqids();
        return $sqids->encode([$this->id]);
    }
}
