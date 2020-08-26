<?php

namespace App\Controllers;

class CoreController {

    public function __construct()
    {
        //pour savoir sur quelle page on est en ce moment
        global $match;
        //le nom de la route sur laquelle on est
        $currentRouteName = $match['name'];
        
        //la liste des noms de routes, et des rôles qui permettent d'y accéder
        //en clé, on a le nom de la route
        //en valeur, on a un tableau avec tous les rôles qui ont le droit de voir cette page
        $acl = [
            //n'importe qui doit pouvoir accéder à la home, même si pas connecté ! 
            'main-home' => 'anonymous',
            'api-insert' => 'anonymous',
            'api-scrap'=> 'anonymous'
            //'user-logout' => ['admin', 'catalog-manager'],
        ];

        //si la page actuelle n'est pas dans le tableau des acls, on a un problème ! 
        //c'est obligatoire de l'ajouter, pour être sûr de ne pas oublier
        if (!array_key_exists($currentRouteName, $acl)){
            //on prévient le dév d'ajouter sa route
            die('yoooo ! ajoute ta route dans les acls du CoreController.php !');
        }

        //liste des rôles autorisés pour la page actuelle
        $allowedRoles = $acl[$currentRouteName];
        //si le rôle demandé pour la page actuelle est anonymous, alors on ne fait rien
        //sinon...
        if ($allowedRoles !== 'anonymous'){
            //on appelle notre méthode de check
            $this->checkAuthorization($allowedRoles);
        }
    }

    protected function checkAuthorization($allowedRoles = [])
    {
        /*
        //si le user n'est pas connecté ... 
        if (empty($_SESSION['userObject'])){
            //un ptit message qui s'affichera sur le login
            $_SESSION['alert'] = "Veuillez vous connecter d'abord !";

            //redirige gentiment vers le login 
            //$this->redirectToRoute("user-login");
        }
        //sinon, s'il est connecté
        else {
            //récupérer les infos du user connecté
            $user = $_SESSION['userObject'];

            //récupére son role
            $role = $user->getRole();

            //est-ce que le rôle du user fait parti des rôles autorisés pour cette page ?
            //si non (le rôle n'est pas autorisé)


            //if (!in_array("admin", ["admin", "catalog-manager"])){

            //}

            if (!in_array($role, $allowedRoles)){
                //on pète une erreur 403
                $this->err403();

                //on arrête tout ici ! 
                die();
            }
        }
        */
    }


    //accès interdit ! 
    public function err403() {
        header('HTTP/1.0 403 Forbidden');

        $this->show('error/err403');
        die();
    }


    protected function validateCsrfToken()
    {
        //on tchèque le token CSRF avant tout ! 
        $csrfTokenFromForm = filter_input(INPUT_POST, 'csrf_token');
        //si le token envoyé avec le form ne correspond pas à celui de la session
        if ($csrfTokenFromForm !== $_SESSION['csrfToken']){
            //alors c'est méga louche
            $this->err403();
        }
    }

    //https://gist.github.com/james2doyle/33794328675a6c88edd6
    protected function json_response($code = 200, $message = null)
{
    // clear the old headers
    header_remove();
    // set the actual code
    http_response_code($code);
    // set the header to make sure cache is forced
    header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    // treat this as json
    header('Content-Type: application/json');
    $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
        );
    // ok, validation error, or failure
    header('Status: '.$status[$code]);
    // return the encoded json
    return json_encode(array(
        'status' => $code < 300, // success or not?
        'message' => $message
        ));
}


    /**
     * Méthode permettant d'afficher du code HTML en se basant sur les views
     *
     * @param string $viewName Nom du fichier de vue
     * @param array $viewVars Tableau des données à transmettre aux vues
     * @return void
     */
    protected function show(string $viewName, $viewVars = []) {
        // On globalise $router car on ne sait pas faire mieux pour l'instant
        global $router;

        // Comme $viewVars est déclarée comme paramètre de la méthode show()
        // les vues y ont accès
        // ici une valeur dont on a besoin sur TOUTES les vues
        // donc on la définit dans show()
        $viewVars['currentPage'] = $viewName; 

        // définir l'url absolue pour nos assets
        $viewVars['assetsBaseUri'] = $_SERVER['BASE_URI'] . '/assets';
        // définir l'url absolue pour la racine du site
        // /!\ != racine projet, ici on parle du répertoire public/
        $viewVars['baseUri'] = $_SERVER['BASE_URI'];

        //pour se protégrer des attaques CSRF
        //on génère une chaîne aléatoire
        $csrfToken = bin2hex(random_bytes(32));
        //on la stocke dans la session pour pouvoir la retrouver après soumission du form
        $_SESSION['csrfToken'] = $csrfToken;

        // On veut désormais accéder aux données de $viewVars, mais sans accéder au tableau
        // La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
        extract($viewVars);
        // => la variable $currentPage existe désormais, et sa valeur est $viewName
        // => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
        // => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
        // => il en va de même pour chaque élément du tableau

        // $viewVars est disponible dans chaque fichier de vue
        require_once __DIR__.'/../views/layout/header.tpl.php';
        require_once __DIR__.'/../views/'.$viewName.'.tpl.php';
        require_once __DIR__.'/../views/layout/footer.tpl.php';
    }

    
    //permet de rediriger facilement vers une route de notre application
    public function redirectToRoute($route, $urlParams = [])
    {
         //redirection
         global $router;
         header("Location: " . $router->generate($route, $urlParams));
         die();
    }
}
