<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category
 *
 * @package App\Entity
 * @author Boris MALEZYK <contact@borismalezyk.com>
 *
 * @ORM\Entity(repositoryClass="\App\Repository\CategoryRepository")
 * @ORM\Table(name="symfony_demo_category")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Category name must be at least {{ limit }} characters long",
     *      maxMessage = "Category name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Post[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Post",
     *      mappedBy="category",
     *      orphanRemoval=true,
     *      cascade={"persist"}
     * )
     */
    private $posts;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->posts     = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Category self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Category self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return Category self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \App\Entity\Post[]|\Doctrine\Common\Collections\ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param \App\Entity\Post[]|\Doctrine\Common\Collections\ArrayCollection $posts
     *
     * @return Category self
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }
}
