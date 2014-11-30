<?php
namespace BF13\Component\Breadcrumb\TwigExtension;

class BreadcrumbExtension extends \Twig_Extension
{
    public function __construct($breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;
    }

    public function getFunctions()
    {
        return array(
            'titleBreadcrumb' => new \Twig_Function_Method($this, 'titleBreadcrumbFunction'),
            'rootBreadcrumb' => new \Twig_Function_Method($this, 'rootBreadcrumbFunction'),
            'childsBreadcrumb' => new \Twig_Function_Method($this, 'childsBreadcrumbFunction'),
            'activePathBreadcrumb' => new \Twig_Function_Method($this, 'activePathBreadcrumbFunction'),
        );
    }

    /**
     * afficher le titre
     */
    public function titleBreadcrumbFunction()
    {
    	return $this->breadcrumb->title;
    }
    
    /**
     * retourner la liste des principales catégories
     */
    public function rootBreadcrumbFunction()
    {
        return $this->breadcrumb->getRoots();
    }

    /**
     * retourner le fil d'ariane
     *
     */
    public function activePathBreadcrumbFunction()
    {
        return $this->breadcrumb->getActivePath();
    }

    /**
     * retourner la liste des actions pour un RootNode donné
     * Le RootNode actif sera utilisé par défaut
     *
     * @param unknown_type $root
     */
    public function childsBreadcrumbFunction($rootNode = null)
    {
        return $this->breadcrumb->getChilds($rootNode);
    }

    public function getName()
    {
        return 'breadcrumb_extension';
    }
}