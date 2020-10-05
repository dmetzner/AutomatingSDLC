<?php

namespace App\Admin\Blocks;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatisticBlockService extends AbstractBlockService
{
  private $extraced_path;

  private $apk_path;

  /**
   * StatisticBlockService constructor.
   *
   * @param mixed $name
   * @param mixed $templating
   * @param mixed $extraced_path
   * @param mixed $apk_path
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
  public function execute(BlockContextInterface $blockContext, Response $response = null)
  {
    $settings = $blockContext->getSettings();

    $wholeSpace = disk_total_space('/');
    $freeSpaceRaw = disk_free_space('/');
    $wholeSpaceRaw = $wholeSpace;
    $usedSpace = $wholeSpaceRaw - $freeSpaceRaw;

    return $this->renderResponse($blockContext->getTemplate(), [
      'block' => $blockContext->getBlock(),
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
  public function configureSettings(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'url' => false,
      'title' => 'Server Information',
      'template' => 'Admin/block_statistic.html.twig',
    ]);
  }

  /**
   * @param mixed $bytes
   *
   * @return string
   */
  public function getSymbolByQuantity($bytes)
  {
    $symbol = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
    $exp = (int) floor(log($bytes) / log(1_024));

    return sprintf('%.2f '.$symbol[$exp], ($bytes / 1_024 ** floor($exp)));
  }
}
