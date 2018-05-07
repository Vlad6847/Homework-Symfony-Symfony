<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class CategoryController
 *
 * @Route(name="category.")
 * @package App\Controller
 */
class CategoryController extends AbstractController
{
    /**
     * Shows all jobs by specific category
     * @Route("category/{slug}", name="allJobs")
     *
     * @param $slug
     *
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \LogicException
     */
        public function allJobsByCategory($slug): Response
        {
            $category = $this->getDoctrine()
                         ->getRepository(Category::class)
                         ->findBySlugWithActiveJobsNotExpired($slug);

            $jobs = $category->getJobs();
            return $this->render('category/allJobs.html.twig', compact('jobs'));
        }
}
