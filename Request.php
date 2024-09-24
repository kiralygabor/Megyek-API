<?php

namespace App\Html;

use App\Repositories\CountyRepository;
use App\Html\Response;

class Request {

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
                    $entity = $db->find($resourceId);
                    Response::response($entity, $code);
                    break;
                }

                $enteties = $db->getAll();
                if(empty($enteties)){
                    $code = 404;
                }
                Response::response($enteties, $code);
                break;

            default: 
                Response::response([], 404,  $_SERVER['REQUEST_URI'] . " not found");
        }
    }

    private static function deleteRequest(): void 
    {
        $id = self::getResourceId();
        if(!$id){
            Response::response([], 400, Response::STATUSES[400]);
            return;
        }

        $resourceName = self::getResourceName();
        switch ($resourceName){
            case 'counties':
                $db = new CountyRepository();
                $result = $db->delete($id);
                $code = 404;
                if($result){
                    $code = 204;
                }
                Response::response([], $code);
                break;
            default: 
                Response::response([], 404,  $_SERVER['REQUEST_URI'] . " not found");
            }
    }

    private static function postRequest(): void
    {
        $resource = self::getResourceName();
        switch ($resource){
            case 'counties':
                $data = self::getRequestData();
                if(isset($data['name'])) {
                    $db = new CountyRepository();
                    $newId = $db->create($data);
                    $code = 201;
                    if(!$newId){
                        $code = 400; // Bad request
                    }
                }
                Response::response(['id' => $newId], $code);

            default: 
                Response::response([], 404,  $_SERVER['REQUEST_URI'] . " not found");
        }
    }

    private static function putRequest(): void
    {
        $resource = self::getResourceName();
        switch ($resource){
            case 'counties':
                $data = self::getRequestData();
                $id = $data['id'];
                $db = new CountyRepository();
                $entity = $db->find($id);
                $code = 404;
                if($entity) {
                    $result = $db->update($id, ['name' => $data['name']]);
                    if($result){
                        $code = 201;
                    }
                }
                Response::response([], $code);
                break;

            default: 
                Response::response([], 404,  $_SERVER['REQUEST_URI'] . " not found");
        }
    }

    private static function getArrUri(string $requestUri): ?array
    {
        return explode("/",$requestUri) ?? null;
    }


    private static function getResourceName(): string
    {
        $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);
        $result = $arrUri[count($arrUri) - 1];
        if(is_numeric($result)){
            $result = $arrUri[count($arrUri) - 2];
        }

        return $result;
    }

    private static function getResourceId(): int
    {
        $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);
        $result = 0;
        if(is_numeric($arrUri[count($arrUri) - 1])){
            $result = $arrUri[count($arrUri) - 1];
        }

        return $result;
    }

    private static function getFilterData(): array
    {

    }

    private static function getRequestData(): ?array
    {
        return json_decode(file_get_contens("php://input"), true);
    }
}

?>