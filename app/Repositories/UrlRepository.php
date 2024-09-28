<?php

namespace App\Repositories;

use App\Models\Url;
use App\Interfaces\UrlRepositoryInterface;

class UrlRepository extends BaseRepository implements UrlRepositoryInterface
{
    public function __construct(Url $url)
    {
        parent::__construct($url);
    }

    // You can add additional methods specific to the Url model here
}
