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
     * Permet de récupérer la liste des souhaits accompagnés des données statistiques sur les enchères
     * 
     * @Route("/api/wishes", name="wish")
     */
    public function index(WishRepository $wishRepository, Utility $wishUtility)
    {
        // On créé un tableau destiné à contenir les données [wish, stats] pour chaque wish
        $data = [];

        foreach ($wishRepository->findAll() as $wish) {
            // On ajoute un élément à la liste
            $data[] = [
                // Les données du wish (base de données)
                'wish' => [
                    'id' => $wish->getId(),
                    'wantedPrice' => $wish->getWantedPrice(),
                    'item' => $wish->getItem()
                ],
                // Les stats des auctions (tirées du JSON)
                'stats' => $wishUtility->getAuctionsForItem($wish->getItem())
            ];
        }

        return new JsonResponse($data);
    }
}
