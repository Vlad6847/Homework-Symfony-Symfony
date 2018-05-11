<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(name="category.")
 */
class CategoryController extends Controller
{
    /**
     * Shows all jobs by specific category
     *
     * @Entity("category", expr="repository.findBySlugWithActiveJobsNotExpired(slug)")
     * @Route("category/{slug}/{page}", name="allJobs", defaults={"page": 1}, requirements={"page": "\d+"})
     * @Method("GET")
     *
     * @param Category $category
     * @param int $page
     *
     * @param PaginatorInterface $paginator
     *
     * @return Response
     * @throws \LogicException
     */
    public function allJobsByCategory(
        Category $category,
        int $page,
        PaginatorInterface $paginator
    ): Response {
        $activeJobs = $paginator->paginate(
            $this->getDoctrine()->getRepository(Job::class)
                                ->getPaginatedActiveJobsByCategoryQuery($category),
            $page,
            $this->getParameter('max_jobs_on_category')
        );

        return $this->render('category/allJobs.html.twig', [
            'category' => $category,
            'activeJobs' => $activeJobs,
        ]);
    }
}
