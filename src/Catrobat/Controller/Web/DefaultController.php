<?php

namespace App\Catrobat\Controller\Web;

use App\Catrobat\Services\FeaturedImageRepository;
use App\Catrobat\Services\StatisticsService;
use App\Entity\FeaturedProgram;
use App\Repository\FeaturedRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
  private StatisticsService $statistics;

  public function __construct(StatisticsService $statistics_service)
  {
    $this->statistics = $statistics_service;
  }

  /**
   * @Route("/", name="index", methods={"GET"})
   */
  public function indexAction(Request $request, FeaturedImageRepository $image_repository, FeaturedRepository $repository): Response
  {
    $flavor = $request->get('flavor');

    if ('phirocode' === $flavor)
    {
      $featured_items = $repository->getFeaturedItems('pocketcode', 10, 0);
    }
    else
    {
      $featured_items = $repository->getFeaturedItems($flavor, 10, 0);
    }

    $featured = [];
    foreach ($featured_items as $item)
    {
      /** @var FeaturedProgram $item */
      $info = [];
      if (null !== $item->getProgram())
      {
        if ($flavor)
        {
          $info['url'] = $this->generateUrl('program',
          ['id' => $item->getProgram()->getId(), 'flavor' => $flavor]);
        }
        else
        {
          $info['url'] = $this->generateUrl('program', ['id' => $item->getProgram()->getId()]);
        }
      }
      else
      {
        $info['url'] = $item->getUrl();
      }
      $info['image'] = $image_repository->getWebPath($item->getId(), $item->getImageType());

      $featured[] = $info;
    }

    return $this->render('Index/index.html.twig', [
      'featured' => $featured,
    ]);
  }

  /**
   * @Route("/termsOfUse", name="termsOfUse", methods={"GET"})
   */
  public function termsOfUseAction(): Response
  {
    return $this->render('PrivacyAndTerms/termsOfUse.html.twig');
  }

  /**
   * @Route("/privacypolicy", name="privacypolicy", methods={"GET"})
   */
  public function privacypolicyAction(): Response
  {
    return $this->render('PrivacyAndTerms/policy.html.twig');
  }

  /**
   * @Route("/licenseToPlay", name="licenseToPlay", methods={"GET"})
   */
  public function licenseToPlayAction(): Response
  {
    return $this->render('PrivacyAndTerms/licenseToPlay.html.twig');
  }

  public function comparePriorities($current, $next): int
  {
    if ($current['priority'] == $next['priority'])
    {
      return 0;
    }

    return ($current['priority'] > $next['priority']) ? -1 : 1;
  }

  /**
   * @Route("/click-statistic", name="click_stats", methods={"POST"})
   *
   * @throws Exception
   */
  public function makeClickStatisticAction(Request $request): Response
  {
    $type = $_POST['type'];
    $referrer = $request->headers->get('referer');
    $locale = strtolower($request->getLocale());

    if (in_array($type, ['project', 'rec_homepage', 'rec_remix_graph',
      'rec_remix_notification', 'rec_specific_programs', ], true))
    {
      $rec_from_id = $_POST['recFromID'];
      $rec_program_id = $_POST['recID'];
      $is_user_specific_recommendation = isset($_POST['recIsUserSpecific'])
      ? (bool) $_POST['recIsUserSpecific'] : false;
      $is_recommended_program_a_scratch_program = (('rec_remix_graph' == $type)
      && isset($_POST['isScratchProgram'])) ? (bool) $_POST['isScratchProgram'] : false;

      $this->statistics->createClickStatistics($request, $type, $rec_from_id, $rec_program_id, null, null,
        $referrer, $locale, $is_recommended_program_a_scratch_program, $is_user_specific_recommendation);

      return new Response('ok');
    }

    if ('tags' == $type)
    {
      $tag_id = $_POST['recID'];
      $this->statistics->createClickStatistics($request, $type, null, null, $tag_id, null, $referrer, $locale);

      return new Response('ok');
    }

    if ('extensions' == $type)
    {
      $extension_name = $_POST['recID'];
      $this->statistics->createClickStatistics($request, $type, null, null, null, $extension_name, $referrer, $locale);

      return new Response('ok');
    }

    return new Response('error');
  }

  /**
   * @Route("/homepage-click-statistic", name="homepage_click_stats", methods={"POST"})
   *
   * @throws Exception
   */
  public function makeNonRecommendedProgramClickStatisticAction(Request $request)
  {
    $type = $_POST['type'];
    $referrer = $request->headers->get('referer');

    $locale = strtolower($request->getLocale());

    if (in_array($type, ['featured', 'newest', 'mostDownloaded', 'scratchRemixes', 'mostViewed', 'random'], true))
    {
      $program_id = $_POST['programID'];
      $this->statistics->createHomepageProgramClickStatistics($request, $type, $program_id, $referrer, $locale);

      return new Response('ok');
    }

    return new Response('error');
  }
}
