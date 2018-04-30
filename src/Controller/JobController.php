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
     * @return Response
     * @throws \LogicException
     */
    public function listAction(): Response
    {
        $request   = new Request($_GET);
        $sort_by = 'expiresAt';
        $order     = 'ASC';

        if (null !== $request->query->get('sort_by')
            && null !== $request->query->get('order')
        ) {
            $sort_by = $request->query->get('sort_by');
            $order     = $request->query->get('order');
        }
        $jobs = $this->getDoctrine()->getRepository(Job::class)
                     ->findAllOrderBy($sort_by, $order);

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }
}
