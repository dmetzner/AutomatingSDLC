<?php

namespace App\Admin;

use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ExampleProgramAdmin.
 */
class ExampleProgramAdmin extends AbstractAdmin
{
  /**
   * @var string
   */
  protected $baseRouteName = 'adminexample_program';

  /**
   * @var string
   */
  protected $baseRoutePattern = 'example_program';

  /**
   * @var array
   */
  protected $datagridValues = [
    '_sort_order' => 'DESC',
    '_sort_by' => 'example',
  ];

  /**
   * @var ParameterBagInterface
   */
  private $parameter_bag;

  public function __construct($code, $class, $baseControllerName,
                              ParameterBagInterface $parameter_bag)
  {
    parent::__construct($code, $class, $baseControllerName);
    $this->parameter_bag = $parameter_bag;
  }

  /**
   * @param string $context
   *
   * @return QueryBuilder|\Sonata\AdminBundle\Datagrid\ProxyQueryInterface
   */
  public function createQuery($context = 'list')
  {
    /**
     * @var QueryBuilder
     */
    $query = parent::createQuery();
    $query->getAllAliases();

    return $query;
  }

  /**
   * @param $object
   */
  public function prePersist($object)
  {
    $this->checkFlavor();
  }

  /**
   * @param DatagridMapper $datagridMapper
   *
   * Fields to be shown on filter forms
   */
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('id')
      ->add('name')
      ->add('example')
    ;
  }

  /**
   * @param ListMapper $listMapper
   *
   * Fields to be shown on lists
   */
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('name')
      ->add('flavor', null, ['editable' => true])
      ->add('example', null, ['editable' => true])
    ;
  }

  protected function configureRoutes(RouteCollection $collection)
  {
    $collection->remove('delete')->remove('create');
  }

  private function checkFlavor()
  {
    $flavor = $this->getForm()->get('flavor')->getData();

    if (!$flavor)
    {
      return; // There was no required flavor form field in this Action, so no check is needed!
    }

    $flavor_options = $this->parameter_bag->get('themes');

    if (!in_array($flavor, $flavor_options, true))
    {
      throw new NotFoundHttpException('"'.$flavor.'"Flavor is unknown! Choose either '.implode(',', $flavor_options));
    }
  }
}
