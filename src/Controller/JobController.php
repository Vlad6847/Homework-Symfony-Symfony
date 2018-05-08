<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="job.")
 */
class JobController extends AbstractController
{
    /**
     * List of all jobs
     *
     * @Route("/", name="list")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return Response
     * @throws \LogicException
     */
    public function list(Request $request): Response
    {
        $sortBy = 'createdAt';
        $order  = 'ASC';

        if (null !== $request->query->get('field')
            && null !== $request->query->get('direction')
        ) {
            $sortBy = $request->query->get('field');
            $order  = $request->query->get('direction');
        }

        $categoriesAndActiveNotExpiredJobs = $this->getDoctrine()
                                                  ->getRepository(Category::class)
                                                  ->findAllWithJobsActiveNotExpiredWithOrderByField($sortBy,
                                                      $order);

        return $this->render('job/list.html.twig',
            compact('categoriesAndActiveNotExpiredJobs'));
    }

    /**
     * Shows a job entity
     *
     * @Entity("job", expr="repository.findActiveJob(id)")
     * @Route("/show/{id}", name="show", requirements={"id": "\d+"})
     * @Method("GET")
     * @param Job $job
     *
     * @return Response
     */
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig', compact('job'));
    }
}
