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
        dump($collection->whereBetween('age', [20, 27]));

        // whereIn()
        dump("WhereIn method with count(): ".User::whereIn('role', $roles)->count());

        // whereNotInt()
        dump(User::whereNotIn('role', ['admin', 'super_admin'])->get());

        // whereNull() -> excerpt column is null
        dump(Article::whereNull('excerpt')->get());

        // whereNotNull() -> the opposite version of whereNull()
        dump(Article::whereNotNull('excerpt')->get());
    }

    public function mapMethod() {
        $random = rand(1, 1000);

        $collection = collect([
            [
                'name' => "Ibrohim $random", 
                'company' => 'Abbibr', 
                'email' => "aaa@aaa$random.aaa",
                'role' => 'admin',
                'password' => Hash::make("admin")
            ],
            [
                'name' => "Bill Gates $random", 
                'company' => 'Microsoft', 
                'email' => "bbb@bbb$random.bbb",
                'role' => 'super_admin',
                'password' => Hash::make("super_admin")
            ],
            [
                'name' => "Elon Musk $random", 
                'company' => 'Tesla', 
                'email' => "ccc@ccc$random.ccc",
                'role' => 'user',
                'password' => Hash::make("user")
            ]
        ]);

        $filtered = $collection->map(function($value) {
            return Arr::except($value, ['company']);
        });

        if (User::where('email', $filtered[0]['email'])->first()) {
            return "These data exist in database: " . $filtered[0]['email'];
        }
        else {
            User::insert($filtered->toArray());
            return response()->json($collection, 200);
        }
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
        $start_time = microtime();

        // pluck()
        $articles = Article::pluck('title')->map(function($value) {
            return Str::upper($value);
        });

        dump($articles);

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

        // concat()
        $concut = $collection->concat($collection2);

        $final = $concut->map(function($value) {
            $surname = Str::ucfirst($value['surname']);
            $name = Str::ucfirst($value['name']);

            return $name . " " . $surname;
        });

        dump($final);


        $collection = collect([
            [ 
                'id' => 1,
                'items' => [
                    [
                        'name' => 'ibrohim',
                        'surname' => 'abbosov'
                    ],
                    [
                        'name' => 'Dilnoza',
                        'surname' => 'Nishanova'
                    ]
                ]
            ],
            [
                'id' => 2,
                'items' => [
                    [
                        'name' => 'samariddin',
                        'surname' => 'norboyev'
                    ],
                    [
                        'name' => 'Sevinch',
                        'surname' => 'Sayfutdinova'
                    ]
                ]
            ]
        ]);

        // $articles = Article::all();
        // $users = User::all();

        dump($collection->pluck('items')->collapse());

        $end_time = microtime();

        return number_format((float)$end_time - (float)$start_time, 5);
    }
}
