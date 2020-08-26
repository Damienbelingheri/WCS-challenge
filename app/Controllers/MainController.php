<?php

namespace App\Controllers;

use App\Models\Argonaute;
use App\Utils\Scrapping;
use Respect\Validation\Validator as v;
use Respect\Validation\Rules;

// Si j'ai besoin du Model Category
// use App\Models\Category;

class MainController extends CoreController {

   
   
    public function scrap()
    {
        
        Scrapping::GetTableWiki();
    }


    /**
     * Méthode s'occupant de la page d'accueil
     *
     * @return void
     */
    public function home()
    {
         $argonautes = Argonaute::findAll();
         
         if (isset($_POST['submit'])){
            $argonaute = new Argonaute;
            $name = strip_tags(filter_input(INPUT_POST, 'name'));

            $validator = v::alnum()->notBlank()->length(1, 20);
            $argonaute->setName($name);
            if ($validator->validate($name) === true){               
                if($argonaute->insert()){
                    
                    $this->redirectToRoute("main-home");
                }
            } else {
                //...
            }
         }

        $this->show('main/home',['argonautes' => $argonautes]);
    }

     /**
     * Méthod for api 
     * @Route = /api/sub_category
     *
     * @return void
     */
    public function apiList()
    {

        $data = Argonaute::findAll();
        header('Content-Type: application/json');
        $myJson = json_encode($data);
        echo $myJson;
    }
    public function apiInsert()
    {

        
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->name)){
            if(!empty($data->name)){
               
                $argonaute = new Argonaute;
                $validator = v::alnum()->notBlank()->length(1, 20);

                $name = strip_tags($data->name);
                $argonaute->setName($name);

                $validator = new Rules\AllOf(
                    new Rules\Alnum(),
                    new Rules\NoWhitespace(),
                    new Rules\NotBlank(),
                    new Rules\Length(1, 15)
                );

                if ($validator->validate($name) === true){               
                    if($argonaute->insert()){

                        echo  $this->json_response(200, 'working');
                    }
                } else {
                        echo $this->json_response(400, 'pas bon');
                }

            } 
             else {
                       echo $this->json_response(400, 'pas bon !');
                }
        } 

    }
}