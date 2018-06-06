<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use App\Form\JobType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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
     * @return Response
     * @throws \LogicException
     */
    public function list(): Response
    {
        $categoriesAndActiveNotExpiredJobs = $this->getDoctrine()
                                                  ->getRepository(
                                                      Category::class
                                                  )
                                                  ->findAllWithJobsActiveNotExpiredWithOrderByField();

        return $this->render(
            'job/list.html.twig',
            compact('categoriesAndActiveNotExpiredJobs')
        );
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
     * @param Request                $request
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     * @throws \Symfony\Component\Form\Exception\LogicException
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $job  = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute(
                'job.preview',
                ['token' => $job->getToken()]
            );
        }

        return $this->render(
            'job/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Edit existing job entity
     *
     * @Route("/job/{token}/edit", name="edit", requirements={"token" = "\w+"})
     * @Method({"GET", "POST"})
     *
     * @param Request                $request
     * @param Job                    $job
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function edit(
        Request $request,
        Job $job,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute(
                'job.preview',
                ['token' => $job->getToken()]
            );
        }

        return $this->render(
            'job/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays the preview page for a job entity.
     *
     * @Route("job/{token}", name="preview", requirements={"token" = "\w+"})
     * @Method("GET")
     *
     * @param Job $job
     *
     * @return Response
     */
    public function preview(Job $job): Response
    {
        $deleteForm  = $this->createDeleteForm($job);
        $publishForm = $this->createPublishForm($job);

        return $this->render(
            'job/show.html.twig',
            [
                'job'              => $job,
                'hasControlAccess' => true,
                'deleteForm'       => $deleteForm->createView(),
                'publishForm'      => $publishForm->createView(),
            ]
        );
    }

    /**
     * Delete a job entity.
     *
     * @Route("job/{token}/delete", name="delete", requirements={"token" = "\w+"})
     * @Method("DELETE")
     *
     * @param Request                $request
     * @param Job                    $job
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(
        Request $request,
        Job $job,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createDeleteForm($job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($job);
            $em->flush();
        }

        return $this->redirectToRoute('job.list');
    }

    /**
     * Publish a job entity.
     *
     * @Route("job/{token}/publish", name="publish", requirements={"token" = "\w+"})
     * @Method("POST")
     *
     * @param Request                $request
     * @param Job                    $job
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function publish(
        Request $request,
        Job $job,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createPublishForm($job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $job->setActivated(true);
            $em->flush();
            $this->addFlash('notice', 'Your job was published');
        }

        return $this->redirectToRoute(
            'job.preview',
            [
                'token' => $job->getToken(),
            ]
        );
    }

    /**
     * Creates a form to delete a job entity.
     *
     * @param Job $job
     *
     * @return FormInterface
     */
    private function createDeleteForm(Job $job): FormInterface
    {
        return $this->createFormBuilder()
                    ->setAction(
                        $this->generateUrl(
                            'job.delete',
                            ['token' => $job->getToken()]
                        )
                    )
                    ->setMethod('DELETE')
                    ->getForm();
    }

    /**
     * Creates a form to publish a job entity.
     *
     * @param Job $job
     *
     * @return FormInterface
     */
    private function createPublishForm(Job $job): FormInterface
    {
        return $this->createFormBuilder(['token' => $job->getToken()])
                    ->setAction(
                        $this->generateUrl(
                            'job.publish',
                            ['token' => $job->getToken()]
                        )
                    )
                    ->setMethod('POST')
                    ->getForm();
    }
}
