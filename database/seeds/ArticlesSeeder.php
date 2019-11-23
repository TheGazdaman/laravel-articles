<?php

use Illuminate\Database\Seeder;
use DiDom\Document;
use App\Article;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url = 'https://thenextweb.com/artificial-intelligence/2019/11/18/ibms-code-and-response-documentary-proves-developers-can-be-superheroes-too/';  // here we expect cache file

        $cache_file = storage_path('scraping_cache/'.Str::slug($url));  // rules for creating it if it does or doesnt exist

        if (!file_exists($cache_file)) {
            
            $curl = curl_init();
            //  curl_setopt($curl, CURLOPT_COOKIEFILE, CACHE_DIR . DIRECTORY_SEPARATOR . 'cookies.txt');
            //  curl_setopt($curl, CURLOPT_COOKIEJAR, CACHE_DIR . DIRECTORY_SEPARATOR . 'cookies.txt');
            //  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                curl_setopt($curl, CURLOPT_URL, $url);
                $response = curl_exec($curl);     
                
                file_put_contents($cache_file, $response); // 
        }

        $html = file_get_contents($cache_file);

        $document = new Document($html);

        $h1 = $document->first('h1');
        if ($h1) {
            $title = $h1->text();
            //var_dump($title);


        $author = $document->first('.post-authorName');

        if ($author) {
            $nome = $author->text();
            //var_dump($nome);
        }
        }

        $body_element = $document->first('.post-body');
        if ($body_element) {
            $body = $body_element->innerHtml();
            //var_dump($body);
        }

        $image_element = $document->first('.post-featuredImage img');
        if ($image_element) {
            $image_url = $image_element->attr('src');
            //var_dump($image_url);
        }

        $image_file = public_path('images/'.Str::slug($title).'.jpg'); // saving image from scrapping

        if (!file_exists($image_file)) {
            file_put_contents(
                $image_file,
                file_get_contents($image_url)
            );
        }

        

        $article = new Article;
        $article->title = $title;
        $article->author = $author;
        $article->text = $body;
        $article->image_file = Str::slug($title).'.jpg';

        $article->save();

        sleep(rand(1, 5));
    }
    
}
