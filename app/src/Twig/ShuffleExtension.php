<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ShuffleExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('shuffle', [$this, 'formatShuffle']),
        ];
    }


    public function formatShuffle(array $table)
    {
        shuffle($table);
        return $table;
    }

}