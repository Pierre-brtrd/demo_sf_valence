<?php

namespace App\Data;

/**
 * Class for the data of search posts.
 */
class SearchData
{
    /**
     * The number of the page of search.
     *
     * @var int|null
     */
    private ?int $page = 1;

    /**
     * The content of the query for title posts.
     *
     * @var string|null
     */
    private ?string $query = '';

    /**
     * Array of tag for the search posts.
     *
     * @var array|null
     */
    private ?array $categories = [];

    /**
     * Array of user for the search posts.
     *
     * @var array|null
     */
    private ?array $auteur = [];

    /**
     * Array of visibility for the search post.
     *
     * @var array|null
     */
    private ?array $active = [];

    /**
     * Get the value of query.
     *
     * @return ?string
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * Set the value of query.
     *
     * @param ?string $query
     *
     * @return self
     */
    public function setQuery(?string $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get the value of categories.
     *
     * @return ?array
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }

    /**
     * Set the value of categories.
     *
     * @param ?array $categories
     *
     * @return self
     */
    public function setCategories(?array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get the value of auteur.
     *
     * @return ?array
     */
    public function getAuteur(): ?array
    {
        return $this->auteur;
    }

    /**
     * Set the value of auteur.
     *
     * @param ?array $auteur
     *
     * @return self
     */
    public function setAuteur(?array $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get the value of page.
     *
     * @return ?int
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * Set the value of page.
     *
     * @param ?int $page
     *
     * @return self
     */
    public function setPage(?int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the value of active.
     *
     * @return ?array
     */
    public function getActive(): ?array
    {
        return $this->active;
    }

    /**
     * Set the value of active.
     *
     * @param ?array $active
     *
     * @return self
     */
    public function setActive(?array $active): self
    {
        $this->active = $active;

        return $this;
    }
}
