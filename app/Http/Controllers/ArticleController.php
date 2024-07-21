<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Hash;

class ArticleController extends Controller
{
    public function numbers() {
        // count()
        $count = Article::count();
        $count = Article::where('is_published', true)->count();

        // countBy()
        $countBy = Article::pluck('user_id')->countBy();

        // max()
        $max = Article::max('min_to_read');
        $max = Article::where('user_id', '<', 7)->max('min_to_read');

        // min()
        $min = Article::min('min_to_read');
        $min = Article::whereBetween('id', [1, 6])->min('min_to_read');

        // median()
        $median = Article::pluck('id')->median();

        // mode()
        $mode = Article::where('is_published', false)->pluck('min_to_read')->mode();

        // inRandomOrder() -> random 1 data
        $randomData = Article::inRandomOrder()->first();

        // sum
        $sum = Article::sum('min_to_read');

        return $randomData;
    }

    public function whereClauses() {
        $collection = collect([
            ['name' => 'Alex', 'age' => 28, 'gender' => 'male'],
            ['name' => 'Dary', 'age' => 17, 'gender' => 'male'],
            ['name' => 'Lucy', 'age' => 21, 'gender' => 'female'],
            ['name' => 'dary', 'age' => 35, 'gender' => 'female'],
        ]);

        $roles = [
            'admin', 'user'
        ];

        // where
        dump($collection->where('age', '>=', 21)->first());

        // whereStrict()
        dump($collection->whereStrict('name', 'Dary'));

        // whereBetween()
        dump($collection->whereBetween('age', [17, 27]));

        // whereIn()
        dump(User::whereIn('role', $roles)->count());

        // whereNotInt()
        dump(User::whereNotIn('role', ['admin', 'super_admin'])->get());

        // whereNull() -> excerpt column is null
        dump(Article::whereNull('excerpt')->get());

        // whereNotNull() -> the opposite version of whereNull()
        dump(Article::whereNotNull('excerpt')->get());
    }

    public function mapMethod() {
        $collection = collect([
            [
                'name' => 'Ibrohim', 
                'company' => 'Abbibr', 
                'email' => 'aaa@aaa.aaa',
                'role' => 'admin',
                'password' => Hash::make('admin')
            ],
            [
                'name' => 'Bill Gates', 
                'company' => 'Microsoft', 
                'email' => 'bbb@bbb.bbb',
                'role' => 'super_admin',
                'password' => Hash::make('super_admin')
            ],
            [
                'name' => 'Elon Musk', 
                'company' => 'Tesla', 
                'email' => 'ccc@ccc.ccc',
                'role' => 'user',
                'password' => Hash::make('user')
            ]
        ]);

        $filtered = $collection->map(function($value) {
            return Arr::except($value, ['company']);
        });

        User::insert($filtered->toArray());

        return response()->json('success', 200);
    }

    public function conExOn() {
        $collection = collect([
            [
                'name' => 'Ibrohim',
                'surname' => 'Abbosov',
                'age' => 18
            ],
            [
                'name' => 'Dilnoza',
                'surname' => 'Nishanova',
                'age' => 18
            ],
            [
                'name' => 'Dilbar',
                'surname' => 'Doniyorova',
                'age' => 18
            ]
        ]);

        // contains() -> get values of collection(arrays)
        $filtered =  $collection->map(function($value) {
            return $value['name'];
        });

        dump($filtered);
        dump($filtered->contains('Ibrohim') ? 'true' : 'false');


        // except() -> get keys of collections(arrays)
        $except = $collection->map(function($value) {
            return Arr::except($value, ['surname', 'age']);
        });
        dump($except);

        
        $array = collect([
            'name' => 'Ibrohim',
            'surname' => 'Abbosov',
            'age' => 18
        ]);
        
        $filtered = $array->except('name');
        dump($filtered);


        // only() -> only get keys of collections(arrays)
        $filtered = $array->only(['name', 'surname']);
        dump($filtered);

        $model = User::whereBetween('id', [8, 20])->get();
        $eloquentFiltered = $model->collect()->map(function($value) {
            $array = $value->toArray();
            return Arr::only($array, ['name', 'email']);
        });

        dump($eloquentFiltered);
    }

    public function otherMethods() {
        // pluck()
        $articles = Article::pluck('title')->map(function($value) {
            return Str::upper($value);
        });

        // return $articles;

        $collection = collect([
            [
                'name' => 'ibrohim',
                'surname' => 'abbosov'
            ],
            [
                'name' => 'Dilnoza',
                'surname' => 'Nishanova'
            ]
        ]);

        $collection2 = collect([
            [
                'name' => 'samariddin',
                'surname' => 'norboyev'
            ],
            [
                'name' => 'Sevinch',
                'surname' => 'Sayfutdinova'
            ]
        ]);

        // councut()
        $concut = $collection->concat($collection2);

        $final = $concut->map(function($value) {
            $surname = Str::ucfirst($value['surname']);
            $name = Str::ucfirst($value['name']);

            return $name . " " . $surname;
        });

        return $final;
    }
}
