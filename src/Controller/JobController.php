<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use App\Form\JobType;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @Route("/job/new", name="new")
     * @Method({"GET", "POST"})
     * @param Request $request
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     * @throws \Symfony\Component\Form\Exception\LogicException
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $newJob = new Job();
        $form = $this->createForm(JobType::class, $newJob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newJob);
            $em->flush();

            return $this->redirectToRoute('job.list');
        }

        return $this->render('job/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
