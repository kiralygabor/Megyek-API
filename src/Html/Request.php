<?php

namespace App\Html;

use App\Repositories\CountyRepository;

class Request{

    static function handle(): void
    {
        switch ($_SERVER["REQUEST_METHOD"])
        {
            case "POST":
                self::postRequest();
                break;
            case "PUT":
                self::putRequest();
                break;
            case "GET":
                self::getRequest();
                break;
            case "DELETE":
                self::deleteRequest();
                break; 
            default:
                echo 'Unknown request type';
                break;

        }
    }

    private static function getRequest(): void
    {
        $resourceName = self::getResourceName();
        switch ($resourceName){
            case 'counties':
                $db = new CountyRepository();
                $resourceId = self::getResourceId();
                $code = 200;
                if($resourceId){
                    $entity = $db->find(id: $resourceId);
                    Response::response(data: $entity, code: $code);
                    break;
                }

                $enteties = $db->getAll();
                if(empty($enteties)){
                    $code = 404;
                }
                Response::response(data: $enteties, code: $code);
                break;

            default: 
                Response::response(data: [], code: 404, message: $_SERVER['REQUEST_URI'] . " not found");
        }
    }

    private static function getArrUri(string $requestUri): ?array
    {
        return explode(separator: "/", string: $requestUri) ?? null;
    }
    private static function getResourceName(): string
    {
        $arrUri = self::getArrUri(requestUri: $_SERVER['REQUEST_URI']);
        $result = $arrUri[count(value: $arrUri) - 1];
        if(is_numeric(value: $result)){
            $result = $arrUri[count(value: $arrUri) - 2];
        }
    }

    

}

?>