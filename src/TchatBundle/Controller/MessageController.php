<?php

namespace TchatBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TchatBundle\Entity\Message;
use TchatBundle\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Message controller.
 *
 * @Route("/message")
 */
class MessageController extends Controller
{
    /**
     * Lists all Message entities.
     * @Security("has_role('ROLE_USER')")
     * @Route("/", name="message_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $messages = $em->getRepository('TchatBundle:Message')->findAll();

        return $this->render('TchatBundle:Message:index.html.twig', array(
            'messages' => $messages,
        ));
    }

    /**
     * Creates a new Message entity.
     * @Security("has_role('ROLE_USER')")
     * @Route("/new/{room_id}", name="message_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $room_id)
    {
        $message = new Message();
        $form = $this->createForm('TchatBundle\Form\MessageType', $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $message->setUser($this->getUser());
            $room = $em->getRepository('TchatBundle:Room')->find($room_id);
            $message->setRoom($room);
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('room_show', array('id' => $room_id));
        }

        return $this->render('TchatBundle:Message:new.html.twig', array(
            'message' => $message,
            'form' => $form->createView(),
            'room_id' => $room_id
        ));
    }

    /**
     * Finds and displays a Message entity.
     * @Security("has_role('ROLE_USER')")
     * @Route("/{id}", name="message_show")
     * @Method("GET")
     */
    public function showAction(Message $message)
    {
        $deleteForm = $this->createDeleteForm($message);

        return $this->render('TchatBundle:Message:show.html.twig', array(
            'message' => $message,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Message entity.
     * @Security("has_role('ROLE_USER')")
     * @Route("/{id}/edit", name="message_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Message $message)
    {
        $deleteForm = $this->createDeleteForm($message);
        $editForm = $this->createForm('TchatBundle\Form\MessageType', $message);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('message_edit', array('id' => $message->getId()));
        }

        return $this->render('TchatBundle:Message:edit.html.twig', array(
            'message' => $message,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Message entity.
     * @Security("has_role('ROLE_USER')")
     * @Route("/{id}", name="message_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Message $message)
    {
        $form = $this->createDeleteForm($message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();
        }

        return $this->redirectToRoute('message_index');
    }

    /**
     * Creates a form to delete a Message entity.
     * @Security("has_role('ROLE_USER')")
     * @param Message $message The Message entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Message $message)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('message_delete', array('id' => $message->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
