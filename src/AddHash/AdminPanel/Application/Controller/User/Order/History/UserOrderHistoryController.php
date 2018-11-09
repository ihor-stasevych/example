<?php

namespace App\AddHash\AdminPanel\Application\Controller\User\Order\History;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;
use App\AddHash\AdminPanel\Domain\Store\Order\Exceptions\StoreOrderNoOrderErrorException;
use App\AddHash\AdminPanel\Application\Command\User\Order\History\UserOrderHistoryGetCommand;
use App\AddHash\AdminPanel\Application\Command\User\Order\History\UserOrderHistoryListCommand;
use App\AddHash\AdminPanel\Domain\User\Services\Order\History\UserOrderHistoryGetServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Order\History\UserOrderHistoryListServiceInterface;

class UserOrderHistoryController extends BaseServiceController
{
    private $listService;

    private $getService;

    public function __construct(UserOrderHistoryListServiceInterface $listService, UserOrderHistoryGetServiceInterface $getService)
    {
        $this->listService = $listService;
        $this->getService = $getService;
    }

    /**
     * Get user orders history
     *
     * @SWG\Parameter(
     *     in="formData",
     *     name="sort",
     *     type="string",
     *     required=false,
     *     description="Sort orders (createdAt)",
     * )
     *
     * @SWG\Parameter(
     *     in="formData",
     *     name="order",
     *     type="string",
     *     required=false,
     *     description="Order (asc or desc)",
     * )
     *
     * @SWG\Parameter(
     *     in="formData",
     *     name="state",
     *     type="integer",
     *     required=false,
     *     description="Filter State (1 or 2 or 3)",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the user orders",
     *     @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="id", type="integer"),
     *                 @SWG\Property(property="createdAt", type="string"),
     *                 @SWG\Property(property="state", type="string"),
     *                 @SWG\Property(property="paymentMethodName", type="string"),
     *                 @SWG\Property(property="currency", type="string"),
     *                 @SWG\Property(property="itemsPriceTotal", type="string"),
     *                 @SWG\Property(
     *                      property="items",
     *                      type="array",
     *                      @SWG\Items(
     *                          type="object",
     *                          @SWG\Property(property="quantity", type="integer"),
     *                          @SWG\Property(property="price", type="string"),
     *                          @SWG\Property(property="totalPrice", type="string"),
     *                          @SWG\Property(property="productTitle", type="string"),
     *                     ),
     *               ),
     *            )
     *     ),
     * )
     * @param Request $request
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function index(Request $request)
    {
        $command = new UserOrderHistoryListCommand(
            $request->get('sort'),
            $request->get('order'),
            $request->get('state')
        );

        return $this->json($this->listService->execute($command));
    }


    /**
     * Get user order history
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the user order",
     *     @SWG\Items(
     *          type="object",
     *          @SWG\Property(property="id", type="integer"),
     *          @SWG\Property(property="createdAt", type="string"),
     *          @SWG\Property(property="state", type="string"),
     *          @SWG\Property(property="paymentMethodName", type="string"),
     *          @SWG\Property(property="currency", type="string"),
     *          @SWG\Property(property="itemsPriceTotal", type="string"),
     *          @SWG\Property(
     *              property="items",
     *              type="array",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="quantity", type="integer"),
     *                  @SWG\Property(property="price", type="string"),
     *                  @SWG\Property(property="totalPrice", type="string"),
     *                  @SWG\Property(property="productTitle", type="string"),
     *              ),
     *          ),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Returns validation errors"
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @SWG\Tag(name="User")
     */
    public function get(int $id)
    {
        $command = new UserOrderHistoryGetCommand($id);

        try {
            $order = $this->getService->execute($command);
        } catch (\Exception $e) {
            return $this->json([
                'errors' => $e->getMessage(),
            ], $e->getCode());
        }

        return $this->json($order);
    }
}