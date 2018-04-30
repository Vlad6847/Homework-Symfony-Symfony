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

        if (null !== $request->query->get('0')
            && null !== $request->query->get('1')
        ) {
            $sort_by = $request->query->get('0');
            $order = $request->query->get('1');
        }
        $jobs = $this->getDoctrine()->getRepository(Job::class)
                     ->findAllOrderBy($sort_by, $order);

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    public function search(Request $request): Response
    {
        $jobs = $this->getDoctrine()->getRepository(Job::class)
                     ->findAll();

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }
}
