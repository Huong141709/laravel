<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['createdAt','name','avatar','quatity,id_type,description'];
    protected $table = "products";

    public function type_products(){
        return $this ->belongsTo("App\Type_product");
        //Belongsto: ngược lại bảng type_product thuộc về products
    }
    public function bill_details(){
        return $this ->belongsTo("App\bill_detail");
    }
}
