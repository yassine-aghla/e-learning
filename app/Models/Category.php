<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table='categories';

    protected $fillable=['name','categorie_id'];

    public function subCategory()
    {
        return $this->hasMany(Category::class, 'categorie_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }


}
