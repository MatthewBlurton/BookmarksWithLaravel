<?php

use Illuminate\Database\Seeder;

class BookmarksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Bookmark', 10)->create()
            ->each(function (App\Bookmark $bookmark) {
                for ($i = 0; $i < rand(2, 5); $i++) {
                    $tag = factory('App\Tag')->make();
                    $bookmark->attachTag($tag->name);
                }
        });
    }
}
