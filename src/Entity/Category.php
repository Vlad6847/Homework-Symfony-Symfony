<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Affiliate", mappedBy="categories")
     */
    private $affiliates;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Job", mappedBy="category")
     */
    private $jobs;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param $slug
     *
     * @return void
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->affiliates = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    /**
     * @param Job $job
     * @return Category
     */
    public function addJob(Job $job): Category
    {
        $this->jobs->add($job);

        return $this;
    }

    /**
     * @param Job $job
     * @return Category
     */
    public function removeJob(Job $job): Category
    {
        $this->jobs->removeElement($job);

        return $this;
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param Affiliate|null $affiliate
     *
     * @return Category
     */
    public function addAffiliate(Affiliate $affiliate): Category
    {
        if (!$this->affiliates->contains($affiliate)) {
            $this->affiliates->add($affiliate);
        }

        return $this;
    }

    public function removeAffiliate(Affiliate $affiliate): Category
    {
        $this->affiliates->removeElement($affiliate);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getAffiliates(): Collection
    {
        return $this->affiliates;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Category
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
