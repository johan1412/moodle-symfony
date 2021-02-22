<?php

namespace App\Twig;

use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ContainProfessorExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'containProfessor']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('containProfessor', [$this, 'doSomething']),
        ];
    }

    public function doSomething(int $id, Collection $professorCollection): bool
    {
        foreach ($professorCollection->getValues() as $key => $value) {
            if ($value->getId() === $id) {
                return true;
            }
        }
        return false;
    }
}
