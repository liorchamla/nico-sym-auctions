<?php

namespace App\Controller;

use App\Wish\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

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
        return new JsonResponse($this->wishUtility->loadCacheAndGetData());
    }

    /**
     * @Route("/api/auctions/{id}", name="stats")
     */
    public function stats($id)
    {
        return new JsonResponse($this->wishUtility->getAuctionsForItem($id));
    }
}
