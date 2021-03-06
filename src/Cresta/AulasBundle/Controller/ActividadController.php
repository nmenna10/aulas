<?php

namespace Cresta\AulasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cresta\AulasBundle\Entity\Actividad;
use Cresta\AulasBundle\Form\ActividadType;
use Exception;
/**
 * Actividad controller.
 *
 */
class ActividadController extends Controller
{

    /**
     * Lists all Actividad entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CrestaAulasBundle:Actividad')->findAll();

        return $this->render('CrestaAulasBundle:Actividad:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Actividad entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Actividad();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($this::existeActividad($entity)) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('aulas_actividad_show', array('id' => $entity->getId())));
            }

            return $this->render('CrestaAulasBundle:Actividad:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        }else{
            throw new Exception("Ya existe una Actividad con ese nombre modifique e intente nuevamente");
        }
    }

    /**
     * Creates a form to create a Actividad entity.
     *
     * @param Actividad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Actividad $entity)
    {
        $form = $this->createForm(new ActividadType(), $entity, array(
            'action' => $this->generateUrl('aulas_actividad_create'),
            'method' => 'POST',
        ));

       // $form->add('submit', 'submit', array('label' => 'Create'));
        $form->add('submit', 'submit', array('label' => 'Crear','attr'=>array('class'=>'btn btn-default botonTabla')));
        $form->add('button', 'submit', array('label' => 'Volver a la lista','attr'=>array('formaction'=>'../actividad','formnovalidate'=>'formnovalidate','class'=>'btn btn-default botonTabla')));

        return $form;
    }

    /**
     * Displays a form to create a new Actividad entity.
     *
     */
    public function newAction()
    {
        $entity = new Actividad();
        $form   = $this->createCreateForm($entity);

        return $this->render('CrestaAulasBundle:Actividad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),

        ));
    }

    /**
     * Finds and displays a Actividad entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CrestaAulasBundle:Actividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No pudimos encontrar esta Actividad :/ recarga la pagina e intenta nuevamente.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CrestaAulasBundle:Actividad:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Actividad entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CrestaAulasBundle:Actividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No pudimos encontrar esta Actividad :/ recarga la pagina e intenta nuevamente');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CrestaAulasBundle:Actividad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Actividad entity.
    *
    * @param Actividad $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Actividad $entity)
    {
        $form = $this->createForm(new ActividadType(), $entity, array(
            'action' => $this->generateUrl('aulas_actividad_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Editar','attr'=>array('class'=>'btn btn-default botonTabla')));
        $form->add('button', 'submit', array('label' => 'Volver a la lista','attr'=>array('formaction'=>'../../actividad','formnovalidate'=>'formnovalidate','class'=>'btn btn-default botonTabla')));

        return $form;
    }
    /**
     * Edits an existing Actividad entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CrestaAulasBundle:Actividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No pudimos encontrar esta Actividad :/ recarga la pagina e intenta nuevamente');
        }
        if ($this::existeActividad($entity)) {
            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createEditForm($entity);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $em->flush();

                return $this->redirect($this->generateUrl('aulas_actividad_edit', array('id' => $id)));
            }

            return $this->render('CrestaAulasBundle:Actividad:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else{
            throw new Exception("Ya existe una Actividad con ese nombre modifique e intente nuevamente");
        }
       
    }
    /**
     * Deletes a Actividad entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        //if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CrestaAulasBundle:Actividad')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('No pudimos encontrar esta Actividad :/ recarga la pagina e intenta nuevamente');
            }

            $em->remove($entity);
            $em->flush();
       // }

        return $this->redirect($this->generateUrl('aulas_actividad'));
    }

    /**
     * Creates a form to delete a Actividad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aulas_actividad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    private function existeActividad ($entity){
        $em = $this->getDoctrine()->getManager();
        $nombre = $entity->getNombre();

        $query = $em->createQuery('SELECT a FROM CrestaAulasBundle:actividad a WHERE a.nombre = :nombre ')->setParameter('nombre',$nombre);
        $actividad = $query->getResult();
        
        if (empty($actividad)) {
            $compara = null;
        }else{
            $compara = $actividad[0]->getNombre();
        }
        
        if (strtolower($compara) != strtolower($entity->getNombre())){
            return true;
        }else{
            return false;
        }
     }
}
