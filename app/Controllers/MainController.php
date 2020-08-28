<?php

namespace App\Controllers;

use App\Models\Argonaute;
use App\Utils\Scrapping;

// Si j'ai besoin du Model Category
// use App\Models\Category;

class MainController extends CoreController
{


    //    // Pour activer le scrapping

    //     public function scrap()
    //     {

    //         Scrapping::GetTableWiki();
    //     }


    /**
     * Méthode s'occupant de la page d'accueil
     *
     * @return void
     */
    public function home()
    {

        //REMPLACER PAR API

        // $argonautes = Argonaute::findAll();

        // if (isset($_POST['submit'])) {
        //     $argonaute = new Argonaute;
        //     $name = strip_tags(filter_input(INPUT_POST, 'name'));

        //     $validator = v::alnum()->notBlank()->length(1, 20);
        //     $argonaute->setName($name);
        //     if ($validator->validate($name) === true) {
        //         if ($argonaute->insert()) {

        //             $this->redirectToRoute("main-home");
        //         }
        //     } else {
        //         //...
        //     }
        // }


        $this->show('main/home');
    }

}
