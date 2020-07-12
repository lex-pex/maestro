<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categories
 *
 * @ORM\Table(name="categories", uniqueConstraints={@ORM\UniqueConstraint(name="category_alias_unique", columns={"alias"})})
 * @ORM\Entity
 */
class Categories
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=256, nullable=false)
     */
    private $image = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1024, nullable=false)
     */
    private $description = '';

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint", nullable=false, options={"default"="1"})
     */
    private $status = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=256, nullable=false)
     */
    private $alias;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get Categories ordered by ids, where index equals the id
     * @param $doctrine
     * @param bool $except_main
     * @return array [ id = {id, alias, name} ] where index == id
     * ] where index == id
     */
    public static function getArray($doctrine, bool $except_main = false) {
        if($except_main)
            $cats = self::allExceptMain($doctrine);
        else
            $cats = $doctrine->getRepository(Categories::class)->findBy([], ['id'=>'asc']);
        $categories = [];
        for($i = 0; $i < count($cats); $i ++) {
            $cat = new \stdClass();
            $cat->id = $cats[$i]->getId();
            $cat->alias = $cats[$i]->getAlias();
            $cat->name = $cats[$i]->getName();
            $categories[$cats[$i]->getId()] = $cat;
        }
        return $categories;
    }

    /**
     * Fetch all except main one
     * @param $doctrine
     * @param string $order
     * @return mixed
     */
    public static function allExceptMain($doctrine, $order = 'desc') {
        $repository = $doctrine->getRepository(Categories::class);
        $query = $repository->createQueryBuilder('c')
            ->where('c.id > 1')->addOrderBy('c.id', $order)->getQuery();
        return $query->getResult();
    }

}
