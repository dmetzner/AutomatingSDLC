<?php

namespace App\Admin;

use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
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

  private ParameterBagInterface $parameter_bag;

  public function __construct($code, $class, $baseControllerName,
                              ParameterBagInterface $parameter_bag)
  {
    parent::__construct($code, $class, $baseControllerName);
    $this->parameter_bag = $parameter_bag;
  }

  protected function configureQuery(ProxyQueryInterface $query): ProxyQueryInterface
  {
    $query = parent::configureQuery($query);

    if (!$query instanceof ProxyQuery)
    {
      return $query;
    }

    /** @var QueryBuilder $qb */
    $qb = $query->getQueryBuilder();
    $qb->getAllAliases();

    return $query;
  }

  /**
   * @param mixed $object
   */
  public function prePersist($object): void
  {
    $this->checkFlavor();
  }

  /**
   * @param DatagridMapper $datagridMapper
   *
   * Fields to be shown on filter forms
   */
  protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
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
  protected function configureListFields(ListMapper $listMapper): void
  {
    $listMapper
      ->addIdentifier('id')
      ->add('name')
      ->add('flavor', null, ['editable' => true])
      ->add('example', null, ['editable' => true])
    ;
  }

  protected function configureRoutes(RouteCollection $collection): void
  {
    $collection->remove('delete')->remove('create');
  }

  private function checkFlavor(): void
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
