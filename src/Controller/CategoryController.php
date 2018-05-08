<?php

namespace App\Controller;

use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(name="category.")
 */
class CategoryController extends AbstractController
{
    /**
     * Shows all jobs by specific category
     *
     * @Entity("category", expr="repository.findBySlugWithActiveJobsNotExpired(slug)")
     * @Route("category/{slug}", name="allJobs")
     * @Method("GET")
     * @param Category $category
     *
     * @return Response
     */
    public function allJobsByCategory(Category $category): Response
    {
        return $this->render('category/allJobs.html.twig', [
            'category' => $category,
        ]);
    }
}
