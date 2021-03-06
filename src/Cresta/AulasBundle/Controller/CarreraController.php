<?php

namespace Cresta\AulasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cresta\AulasBundle\Entity\Carrera;
use Cresta\AulasBundle\Form\CarreraType;
use Exception;
/**
 * Carrera controller.
 *
 */
class CarreraController extends Controller
{

    /**
     * Lists all Carrera entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CrestaAulasBundle:Carrera')->findAll();

        return $this->render('CrestaAulasBundle:Carrera:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Carrera entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Carrera();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($this::existeCarrera($entity)) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('aulas_carrera_show', array('id' => $entity->getId())));
            }

            return $this->render('CrestaAulasBundle:Carrera:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        }else{
            throw new Exception("Ya existe una Carrera con ese nombre modifique e intente nuevamente");
        }
    }

    /**
     * Creates a form to create a Carrera entity.
     *
     * @param Carrera $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Carrera $entity)
    {
        $form = $this->createForm(new CarreraType(), $entity, array(
            'action' => $this->generateUrl('aulas_carrera_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear','attr'=>array('class'=>'btn btn-default botonTabla')));
        $form->add('button', 'submit', array('label' => 'Volver a la lista','attr'=>array('formaction'=>'../carrera','formnovalidate'=>'formnovalidate','class'=>'btn btn-default botonTabla')));

        
        return $form;
    }

    /**
     * Displays a form to create a new Carrera entity.
     *
     */
    public function newAction()
    {
        $entity = new Carrera();
        $form   = $this->createCreateForm($entity);

        return $this->render('CrestaAulasBundle:Carrera:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Carrera entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CrestaAulasBundle:Carrera')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No pudimos encontrar la carrera :/ intenta recargar la pagina.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CrestaAulasBundle:Carrera:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Carrera entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CrestaAulasBundle:Carrera')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No pudimos encontrar la carrera :/ intenta recargar la pagina.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CrestaAulasBundle:Carrera:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Carrera entity.
    *
    * @param Carrera $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Carrera $entity)
    {
        $form = $this->createForm(new CarreraType(), $entity, array(
            'action' => $this->generateUrl('aulas_carrera_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar','attr'=>array('class'=>'btn btn-default botonTabla')));
        $form->add('button', 'submit', array('label' => 'Volver a la lista','attr'=>array('formaction'=>'../../carrera','formnovalidate'=>'formnovalidate','class'=>'btn btn-default botonTabla')));


        return $form;
    }
    /**
     * Edits an existing Carrera entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CrestaAulasBundle:Carrera')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No pudimos encontrar la carrera :/ intenta recargar la pagina.');
        }
        if ($this::existeCarrera($entity)) {
            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createEditForm($entity);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $em->flush();

                return $this->redirect($this->generateUrl('aulas_carrera_edit', array('id' => $id)));
            }

            return $this->render('CrestaAulasBundle:Carrera:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else{
             throw new Exception("Ya existe una Carrera con ese nombre modifique e intente nuevamente");
        }
        
    }
    /**
     * Deletes a Carrera entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        //if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CrestaAulasBundle:Carrera')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('No pudimos encontrar la carrera :/ intenta recargar la pagina.');
            }

            $em->remove($entity);
            $em->flush();
        //}

        return $this->redirect($this->generateUrl('aulas_carrera'));
    }

    /**
     * Creates a form to delete a Carrera entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aulas_carrera_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    private function existeCarrera ($entity){
        $em = $this->getDoctrine()->getManager();
        $nombre = $entity->getNombre();

        $query = $em->createQuery('SELECT c FROM CrestaAulasBundle:carrera c WHERE c.nombre = :nombre ')->setParameter('nombre',$nombre);
        $carrera = $query->getResult();
        
        if (empty($carrera)) {
            $compara = null;
        }else{
            $compara = $carrera[0]->getNombre();
        }
        
        if (strtolower($compara) != strtolower($entity->getNombre())){
            return true;
        }else{
            return false;
        }
     }
}
