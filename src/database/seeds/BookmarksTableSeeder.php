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
        factory('App\Bookmark', 30)->create()
            ->each(function (App\Bookmark $bookmark) {
                for ($i = 0; $i < rand(2, 5); $i++) {
                    $tag = factory('App\Tag')->make();
                    $bookmark->attachTag($tag->name);
                }
        });

        // for ($i = 0; $i < 20; $i++) {
        //     $bookmark = App\Bookmark::create([
        //         'title'         => "Google $i",
        //         'url'           => 'https://www.google.com',
        //         'description'   => 'Search engine with no privacy',
        //         'user_id'       => 1,
        //         'is_public'     => true,
        //     ]);
        //     $bookmark->attachTag('Web Search');
        // }
    }
}
