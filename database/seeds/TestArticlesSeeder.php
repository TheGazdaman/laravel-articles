<?php

use Illuminate\Database\Seeder;
use App\Article;

class TestArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('articles')->truncate();

        $data = json_decode(file_get_contents(storage_path('articles.json')), true);

        foreach ($data as $article_data) {
            $article = new Article;
            $article->title         = $article_data['title'];
            $article->image_file    = $article_data['image_file'];
            $article->author        = $article_data['author'];
            $article->text          = $article_data['text'];
            $article->publish_at    = $article_data['publish_at'];

            $article->save();
        }
    }
}
