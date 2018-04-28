<?php

namespace App\Controller;

use App\Entity\Job;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    /**
     * List of all jobs
     *
     * @Route("/", name="job.list")
     * @Method("GET")
     * @return Response
     * @throws \LogicException
     */
    public function listAction(): Response
    {
        $jobs = $this->getDoctrine()->getRepository(Job::class)->findAll();

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * @Route("/orderby/{slug}", name="orderBy")
     * @param string $slug
     *
     * @return Response
     * @throws \LogicException
     */
    public function orderBy(string $slug): Response
    {
        $jobs = $this->getDoctrine()->getRepository(Job::class)
                     ->findAllSortedBy($slug);


        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }
}
