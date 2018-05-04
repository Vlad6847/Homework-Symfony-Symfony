<?php

namespace App\Controller;

use App\Entity\Job;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    /**
     * List of all jobs
     *
     * @Route("/", name="job.list")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return Response
     * @throws \LogicException
     */
    public function listAction(Request $request): Response
    {
        $sort_by = 'createdAt';
        $order = 'ASC';

        if (null !== $request->query->get('field')
            && null !== $request->query->get('direction')
        ) {
            $sort_by = $request->query->get('field');
            $order = $request->query->get('direction');
        }
        $jobs = $this->getDoctrine()->getRepository(Job::class)
                     ->findAllOrderByNotExpired($sort_by, $order);

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Shows a job entity
     *
     * @Route("/show/{id}", name="job.show", requirements={"id": "\d+"})
     * @Method("GET")
     * @param Job $job
     *
     * @return Response
     */
    public function showAction(Job $job): Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }
}
