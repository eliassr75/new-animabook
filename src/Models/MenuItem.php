<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use PDO;

class MenuItem extends Model
{
    protected $table = "menu_items";

    protected $fillable = [
        'id',
        'title',
        'link',
        'category',
        'is_visible',
        'type',
        'staff',
        'method',
        'action',
        'controller'
    ];

    protected $guarded = ['id'];

    public $allowed_keys = [
        'title',
        'link',
        'category',
        'is_visible',
        'type',
        'staff',
        'method',
        'action',
        'controller'
    ];

    public static function getByCategory( $category)
    {
        return MenuItem::where('category', $category)
            ->where('is_visible', 'true')
            ->orderBy('title', 'asc')
            ->get();
    }

    public static function getAll()
    {
        return MenuItem::all();
    }
}
