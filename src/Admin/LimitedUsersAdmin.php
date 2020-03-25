<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class LimitedUsersAdmin.
 */
class LimitedUsersAdmin extends AbstractAdmin
{
  /**
   * @var string
   */
  protected $baseRouteName = 'admin_limited_users';

  /**
   * @var string
   */
  protected $baseRoutePattern = 'limited_users';

  /**
   * @param ListMapper $listMapper
   *
   * Fields to be shown on lists
   */
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper->addIdentifier('id')
      ->add('username')
      ->add('email')
      ->add('limited', 'boolean', [
        'editable' => true,
      ])
    ;
  }

  /**
   * @param DatagridMapper $datagridMapper
   *
   * Fields to be shown on filter forms
   */
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper->add('username', null, [
      'show_filter' => true,
    ])
      ->add('email')
      ->add('limited')
    ;
  }

  protected function configureRoutes(RouteCollection $collection)
  {
    $collection->clearExcept([
      'list',
      'edit',
    ]);
  }
}
