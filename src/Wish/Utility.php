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
        $this->cache = $cache;
    }

    public function loadCacheAndGetData()
    {
        return $this->cache->get("mesboules", function (ItemInterface $item) {
            $client = HttpClient::create();
            $data = $client->request("GET", "http://auction-api-eu.worldofwarcraft.com/auction-data/5a9683e91c242d14a070162757b4b546/auctions.json")->getContent();

            return json_decode($data)->auctions;
        });
    }

    public function getAuctionsForItem(int $id)
    {
        $auctions = array_filter($this->loadCacheAndGetData(), function ($auction) use ($id) {
            return $auction->item == $id;
        });

        $bestBid = INF;
        $bestAuction = null;

        foreach ($auctions as $auction) {
            if ($auction->bid < $bestBid) {
                $bestBid = $auction->bid;
                $bestAuction = $auction;
            }
        }

        $averageBid = array_reduce($auctions, function ($total, $auction) {
            return $total + $auction->bid;
        }, 0) / count($auctions);

        return [
            'item' => $id,
            'bestAuction' => $bestAuction,
            'averageBid' => $averageBid
        ];
    }
}
