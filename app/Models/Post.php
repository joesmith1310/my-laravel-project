<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory; //Has access to factory() method

    //Avoiding mass assignment vulnerabilities
    //You should avoid passing user input i.e. from forms to the mass assignment create method, but if you do, use the properties below in the eloquent model to set which attributes can be assigned by the user
    protected $fillable = ['title','excerpt','body']; //Any attributes in here are allowed to be mass assigned. e.g. Post::create(['title'=>'My first post', ...])
    //protected $guarded = []; //Any attributes in here are specifically not allowed to be mass assigned, you use one or the other

    protected $with = ["category", "author"]; //This makes eager loading the default. You don't have to include eager loading in the routes. Sometimes thios can cause relationships to be loaded even when you don't need them. You can disable the default using the withouf in the route.
    
    public function scopeFilter($query, array $filters) //Laravel query scope
    {
        /*if ($filters['search'] ?? false) { //Null safe operator
            $query
            ->where('title', 'like', '%' . request('search') . '%')
            ->orWhere('body', 'like', '%' . request('search') . '%');
        }*/

        $query->when($filters['search'] ?? false, fn ($query, $search) => //$filters['search'] is the search term it gets passed to the callback if it exists
            $query->where(fn($query) =>
                $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('body', 'like', '%' . $search . '%')
            )
        );
        $query->when($filters['category'] ?? false, fn ($query, $category) =>
            $query->whereHas('category', fn ($query) =>
                $query->where('slug', $category) //Where slug matched category
            ) 
        );
        $query->when($filters['author'] ?? false, fn ($query, $author) => 
            $query->whereHas('author', fn ($query) => //Represents relationship (In this case as custom relationship defined below)
                $query->where('username', $author)
            ) 
        );
    }
    public function category()
    {
        return $this->belongsTo(Category::class); //This is how we define an eloquent relationship
    }

    /*public function user() //Laravel assumes this name corresponds to a foreign key
    {
        return $this->belongsTo(User::class); //This is how we define an eloquent relationship
    }*/

    public function author() //Laravel can't find a foreign key with this name. We have to provide one
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
