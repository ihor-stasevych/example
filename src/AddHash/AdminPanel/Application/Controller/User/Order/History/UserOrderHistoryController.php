<?php

namespace App\AddHash\AdminPanel\Application\Controller\User\Order\History;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Application\Command\User\Order\History\UserOrderHistoryGetCommand;

class UserOrderHistoryController extends BaseServiceController
{
    public function index()
    {

    }

    public function get(int $id)
    {
        $command = new UserOrderHistoryGetCommand($id);
    }
}