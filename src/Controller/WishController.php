<?php

namespace App\Controller;

use App\Repository\WishRepository;
use App\Wish\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/api/wishes", name="wish")
     */
    public function index(WishRepository $wishRepository, Utility $wishUtility)
    {
        $data = [];

        foreach ($wishRepository->findAll() as $wish) {
            $data[] = [
                'wish' => [
                    'id' => $wish->getId(),
                    'wantedPrice' => $wish->getWantedPrice(),
                    'item' => $wish->getItem()
                ],
                'stats' => $wishUtility->getAuctionsForItem($wish->getItem())
            ];
        }

        return new JsonResponse($data);
    }
}
