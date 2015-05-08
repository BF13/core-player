<?php
namespace BF13\Bundle\BusinessApplicationBundle\Controller;

use Symfony\Component\Form\Exception\InvalidArgumentException;

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

            if(is_array($msg)) {

                $msg = array_map(function($message) {

                    return $message->getMessage();
                }, $msg);

                $msg = "\n- " . implode("\n- ", $msg);

            } else {

                $msg = "\n- " . $form->getErrorsAsString();
            }

            $this->get('logger')->err('Erreur formulaire: ' . $msg);

            throw new InvalidArgumentException($msg);
        }
    }

    protected function isGranted($role)
    {
        return $this->get('security.context')->isGranted($role);
    }

    protected function generateForm($model, $data = null, $options = array())
    {
        $generator = $this->get('bf13.app.form_generator');

        $file = $this->locateResource($model, 'form.yml');

        $form = $generator->buildForm($file, $data, $options);

        return $form;
    }

    protected function generateDatagrid($model, $data = null, $pager = null, $config = array())
    {
        $generator = $this->get('bf13.app.datagrid_generator');

        $datagrid = $generator->buildDatagrid($model);

        if(sizeOf($config))
        {
            $datagrid->updateConfig($config);
        }

        if($data)
        {
            $datagrid->loadData($data, $pager, $config);
        }

        return $datagrid;
    }

    protected function addSuccessMessage($msg)
    {
        $this->get('session')->getFlashBag()->add('success', $msg);
    }

    protected function addWarningMessage($msg)
    {
        $this->get('session')->getFlashBag()->add('warning', $msg);
    }

    protected function addErrorMessage($msg)
    {
        $this->get('session')->getFlashBag()->add('error', $msg);
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
