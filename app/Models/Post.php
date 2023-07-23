<?php

namespace App\Models;
use Illuminate\Support\Facades\File;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Post {
    public $title;
    public $excerpt;
    public $date;
    public $body;
    public $slug;
    public function __construct($t, $e, $d, $b, $s) {
        $this->title = $t;
        $this->excerpt = $e;
        $this->date = $d;
        $this->body = $b;
        $this->slug = $s;
    }
    public static function find($slug) {
        $path = resource_path("posts/{$slug}.html");
        if (! file_exists($path)) {
            throw new ModelNotFoundException();
        }

        $post = cache()->remember("posts.{$slug}", now()->addMinutes(20), function () use ($path) { 
            return file_get_contents($path);
        });

        return $post;
    }

    public static function all() {
        $files = File::files(resource_path("posts"));
        return array_map(function ($file) { //Applies a specified function to each element in an array and returns an array containing the results
            $document = YamlFrontMatter::parseFile($file);
            $front_matter = $document->matter();
            $body = $document->body();
            $post = new Post($front_matter["title"], $front_matter["excerpt"], $front_matter["date"], $body, $front_matter["slug"]);
            return $post;
        }, $files);
    }

    public static function all_alt() {
        //cache()->forget('posts.all');
        return cache()->rememberForever('posts.all', function () {
            return collect(File::files(resource_path("posts"))) //Collect all the files in this directory into a collection
                ->map(fn($file) => YamlFrontMatter::parseFile($file)) //Map to an array of parsed documents
                ->map(fn($document) => new Post($document->title,$document->excerpt,$document->date,$document->body(),$document->slug)) //Map to an array of posts
                ->sortByDesc('date');
        });
    }

    public static function find_alt($slug) {
        $posts = static::all_alt();
        $post = $posts->firstWhere('slug', $slug);
        return $post;
    }
}