<?php

namespace App\Controller;

use App\Wish\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AuctionController extends AbstractController
{
    private $wishUtility;

    public function __construct(Utility $wishUtility)
    {
        $this->wishUtility = $wishUtility;
    }

    /**
     * @Route("/api/auctions", name="auction")
     */
    public function index()
    {
        // On retourne toutes les auctions sous la forme d'un JSON
        return new JsonResponse($this->wishUtility->loadCacheAndGetData());
    }
}
