<?php

namespace App\Controller;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TagType;use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
* Require ROLE_ADMIN for *every* controller method in this class.
*
* @IsGranted("ROLE_ADMIN")
*/
class TagController extends AbstractController
{
    /**
     * @Route("/tag", name="tag")
     */
    public function index()
    {
        $tags = $this->getDoctrine()->getRepository(Tag::class)->findAll();

        return $this->render('tag/index.html.twig', [
            'controller_name' => 'TagController',
            'tags' => $tags
        ]);
    }

    /**
     * @Route("/tag/new", name="tag_new")
     */
    public function newTag(Request $request)
    {
        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute('tag');
        }

        return $this->render('tag/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("tag/edit/{id}", requirements={"id": "\d+"}, name="tag_edit")
     */
    public function editTag(Request $request, Tag $tag){

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute('tag');
        }

        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tag/delete/{id}", requirements={"id": "\d+"}, name="tag_delete")
     */
    public function deleteTag(Request $request, Tag $tag){
        $em = $this->getDoctrine()->getManager();
        $em->remove($tag);
        $em->flush();

        return $this->redirectToRoute('tag');
    }
}
