<?php


namespace App\Utils;

use App\Models\Argonaute;

// Scraping d'un tableau wikipédia 
class Scrapping
{

    
    public static function GetTableWiki()
    {
    //https://stackoverflow.com/questions/16259641/extract-content-from-wikipedia
         $data = file_get_contents('https://fr.wikipedia.org/w/api.php?action=parse&page=Argonautes&section=2&prop=wikitext&format=json');
         $data = json_decode($data, true);
         $data = $data['parse']['wikitext']['*'];
         //dd($data);

        preg_match_all('/\|\W\[\[(.*?)\]\]/m', $data, $matches, PREG_SET_ORDER);

        $result = [];
       // dd($matches);
        $argonaute = new Argonaute;
        foreach($matches as $match) {
            $argonaute->setName($match[1]);
            $argonaute->insert();
        
        }
      
    }

}
