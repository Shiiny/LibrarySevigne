<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class BookSearch
{
    /**
     * @var mixed|null
     * @Assert\NotBlank(message="Ne peut être vide")
     */
    private $search;

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @param mixed|null $search
     * @return BookSearch
     */
    public function setSearch(string $search): BookSearch
    {
        $this->search = $search;
        return $this;
    }

}