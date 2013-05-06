<?php
namespace BF13\Bundle\BusinessApplicationBundle\Controller;

use Symfony\Component\Form\Exception\NotValidException;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\Form\Form;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as baseController;

use BF13\Bundle\BusinessApplicationBundle\Model\ValidateAction;

/**
 *
 * @author FYAMANI
 *
 */
class Controller extends baseController
{
    /**
     * retourne le service de gestion du méta modèle
     */
    protected function getDomainRepository()
    {
        return $this->get('bf13.dom.repository');
    }

    protected function validateForm(Form $form, $request_data)
    {
        $form->bind($request_data);

        if (!$form->isValid()) {

            $msg = $form->getErrors();

            if(sizeOf($msg)) {

                $msg = array_map(function($message) {

                    return $message->getMessage();
                }, $msg);

                $msg = "\n- " . implode("\n- ", $msg);

            } else {

                $msg = "\n- " . $form->getErrorsAsString();
//                 $msg = "";
            }

//             $this->get('logger')->err('Erreur formulaire: ' . $msg);

            throw new NotValidException($msg);
        }
    }

    protected function isGranted($role)
    {
        return $this->get('security.context')->isGranted($role);
    }

    protected function validateAccess($resource)
    {
        $user = $this->getuser();

        $resource_type = get_class($resource);

        switch($resource_type)
        {
            case 'Rff\DomainBundle\Entity\Root\Incident':
                if($user->getDirectionRegionale() != $resource->getDirectionRegionale()) {

                    throw new AccessDeniedException();
                }
            break;

            default:

                die(sprintf('Type "%s" inconnu !', $resource_type));
        }
    }

    protected function generateForm($model, $data = null, $options = array())
    {
        $generator = $this->get('bf13.app.form_generator');
        
        $file = $this->locateResource($model, 'form.yml');

        $form = $generator->buildForm($file, $data, $options);

        return $form;
    }

    protected function generateDatagrid($model, $data = null)
    {
        $generator = $this->get('bf13.app.datagrid_generator');

        $datagrid = $generator->buildDatagrid($model);

        if($data)
        {
            $datagrid->loadData($data);
        }

        return $datagrid;
    }

    protected function addSuccessMessage($msg)
    {
        $this->get('session')->setFlash('success', $msg);
    }

    protected function addWarningMessage($msg)
    {
        $this->get('session')->setFlash('warning', $msg);
    }

    protected function addErrorMessage($msg)
    {
        $this->get('session')->setFlash('error', $msg);
    }

    protected function getValidateActionForm()
    {
        $ValidateAction = new ValidateAction;

        $form = $this->generateForm('BF13BusinessApplicationBundle:form:ValidateAction', $ValidateAction);

        return $form;
    }

    protected function locateResource($serialName, $ext = 'yml')
    {
        list($bundle, $dir, $filename) = explode(':', $serialName);
    
        $res = sprintf('@%s/Resources/config/%s/%s.%s', $bundle, $dir, $filename, $ext);
    
        $path = $this->get('kernel')->locateResource($res);
    
        return $path;
    }
}
