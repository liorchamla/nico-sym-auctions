<?php

namespace App\Wish;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class Utility
{

    protected $cache;

    public function __construct(CacheInterface $cache)
    {
        // On aura besoin du cache de symfony
        $this->cache = $cache;
    }

    /**
     * Permet de charger les données soit déjà présentes dans le cache, soit venant de l'api JSON
     * On ne retourne pas du JSON mais un véritable tableau d'auctions
     * 
     * @return array
     */
    public function loadCacheAndGetData()
    {
        // On demande si les données sont déjà en cache, sinon, on les stockes
        return $this->cache->get("mesboules", function (ItemInterface $item) {
            // On créé un client HTTP
            $client = HttpClient::create();
            // On fait appel au JSON
            $data = $client->request("GET", "http://auction-api-eu.worldofwarcraft.com/auction-data/5a9683e91c242d14a070162757b4b546/auctions.json")->getContent();

            // On transforme la chaine JSON en un tableau et on stocke les auctions
            return json_decode($data)->auctions;
        });
    }

    /**
     * Retourne des stats intéressantes sur les auctions concernant un item
     *
     * @param integer $item L'identifiant blizzard d'un item
     *
     * @return array
     */
    public function getAuctionsForItem(int $item)
    {
        // On filtre parmis toutes les auctions uniquement celles qui parlent de l'item qui nous intéresse
        $auctions = array_filter($this->loadCacheAndGetData(), function ($auction) use ($item) {
            return $auction->item == $item;
        });

        // On va calculer le bid minimum et choper l'auction en question
        $bestBid = INF;
        $bestAuction = null;
        foreach ($auctions as $auction) {
            if ($auction->bid < $bestBid) {
                $bestBid = $auction->bid;
                $bestAuction = $auction;
            }
        }

        // On va calculer la moyenne des bids pour l'item parmis toutes les auctions qui parlent de cet item
        $averageBid = array_reduce($auctions, function ($total, $auction) {
            return $total + $auction->bid;
        }, 0) / count($auctions);

        // On retourne un tableau associatif contenant l'id blizzard de l'item, les données de la meilleure offre, le bid moyen
        return [
            'item' => $item,
            'bestAuction' => $bestAuction,
            'averageBid' => $averageBid
        ];
    }
}
