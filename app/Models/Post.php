<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    //Avoiding mass assignment vulnerabilities
    //You should avoid passing user input i.e. from forms to the mass assignment create method, but if you do, use the properties below in the eloquent model to set which attributes can be assigned by the user
    protected $fillable = ['title','excerpt','body']; //Any attributes in here are allowed to be mass assigned. e.g. Post::create(['title'=>'My first post', ...])
    //protected $guarded = []; //Any attributes in here are specifically not allowed to be mass assigned, you use one or the other
    
    public function category()
    {
        return $this->belongsTo((Category::class)); //This is how we define an eloquent relationship
    }
}
