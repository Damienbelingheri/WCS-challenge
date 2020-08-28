<?php

namespace App\Controllers;


use App\Models\Argonaute;
use Respect\Validation\Validator as v;
use Respect\Validation\Rules;

class ApiController extends CoreController
{


    /**
     * Méthod for api 
     * @Route = /api/
     *
     * @return void
     */
    public function read()
    {


        $argonautes  = Argonaute::findAll();
        //dd($argonautes);
        $code=200;
       
       
        foreach ($argonautes as $argonaute){

            $data[]= $argonaute->getName();
        }
        //dd($data);


        echo $this->json_response($code, array(
            'data' => $data

            ));
    }



    public function insert()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->name)) {
            if (!empty($data->name)) {

                $argonaute = new Argonaute;
                $validator = v::alnum()->notBlank()->length(1, 20);

                $name = strip_tags($data->name);
                $argonaute->setName($name);

                $validator = new Rules\AllOf(
                    new Rules\Alnum(),
                    new Rules\NotBlank(),
                    new Rules\Length(1, 30)
                );

                $code = [];
                if ($validator->validate($name) === true) {
                    if ($argonaute->insert()) {

                        $msg['msg'] = ('working') ;
                        $code = 200;
                    }
                } else {
                    $msg['msg'] = ('Il y\'a un problème !');
                    $code = 400;
                }
            } else {
                $msg['msg'] = ('C\'est vide');
                $code = 400;
            }
        }

        echo $this->json_response($code, array(
            'msg' =>  [$msg],
            'data' => $data
            ));
    }


}
