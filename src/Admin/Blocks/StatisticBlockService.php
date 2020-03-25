<?php

namespace App\Admin\Blocks;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class StatisticBlockService.
 */
class StatisticBlockService extends AbstractBlockService
{
  /**
   * @var
   */
  private $extraced_path;

  /**
   * @var
   */
  private $apk_path;

  /**
   * StatisticBlockService constructor.
   *
   * @param $name
   * @param $templating
   * @param $extraced_path
   * @param $apk_path
   */
  public function __construct($name, $templating, $extraced_path, $apk_path)
  {
    parent::__construct($name, $templating);
    $this->extraced_path = $extraced_path;
    $this->apk_path = $apk_path;
  }

  /**
   * {@inheritdoc}
   */
  public function execute(BlockContextInterface $block, Response $response = null)
  {
    $settings = $block->getSettings();

    $wholeSpace = disk_total_space('/');
    $freeSpaceRaw = disk_free_space('/');
    $wholeSpaceRaw = $wholeSpace;
    $usedSpace = $wholeSpaceRaw - $freeSpaceRaw;

    return $this->renderResponse($block->getTemplate(), [
      'block' => $block->getBlock(),
      'settings' => $settings,
      'wholeSpace' => $this->getSymbolByQuantity($wholeSpace),
      'wholeSpace_raw' => $wholeSpaceRaw,
      'freeSpace_raw' => $freeSpaceRaw,
      'freeSpace' => $this->getSymbolByQuantity($freeSpaceRaw),
      'usedSpace' => $this->getSymbolByQuantity($usedSpace),
      'ram' => shell_exec("free | grep Mem | awk '{print $3/$2 * 100.0}'"),
    ], $response);
  }

  /**
   * {@inheritdoc}
   */
  public function getName()
  {
    return 'Cleanup Server';
  }

  /**
   * {@inheritdoc}
   */
  public function configureSettings(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'url' => false,
      'title' => 'Server Information',
      'template' => 'Admin/block_statistic.html.twig',
    ]);
  }

  /**
   * @param $bytes
   *
   * @return string
   */
  public function getSymbolByQuantity($bytes)
  {
    $symbol = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
    $exp = (int) floor(log($bytes) / log(1024));

    return sprintf('%.2f '.$symbol[$exp], ($bytes / pow(1024, floor($exp))));
  }
}
