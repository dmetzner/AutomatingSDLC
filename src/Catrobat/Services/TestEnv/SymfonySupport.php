<?php

namespace App\Catrobat\Services\TestEnv;

use App\Catrobat\RecommenderSystem\RecommenderManager;
use App\Catrobat\Services\CatrobatFileCompressor;
use App\Catrobat\Services\ExtractedFileRepository;
use App\Catrobat\Services\MediaPackageFileRepository;
use App\Catrobat\Services\ProgramFileRepository;
use App\Entity\Extension;
use App\Entity\FeaturedProgram;
use App\Entity\GameJam;
use App\Entity\Notification;
use App\Entity\Program;
use App\Entity\ProgramDownloads;
use App\Entity\ProgramInappropriateReport;
use App\Entity\ProgramLike;
use App\Entity\ProgramManager;
use App\Entity\ProgramRemixBackwardRelation;
use App\Entity\ProgramRemixRelation;
use App\Entity\ScratchProgramRemixRelation;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\UserComment;
use App\Entity\UserLikeSimilarityRelation;
use App\Entity\UserManager;
use App\Entity\UserRemixSimilarityRelation;
use App\Repository\CatroNotificationRepository;
use App\Repository\ExtensionRepository;
use App\Repository\ProgramRemixBackwardRepository;
use App\Repository\ProgramRemixRepository;
use App\Repository\ScratchProgramRemixRepository;
use App\Repository\ScratchProgramRepository;
use App\Repository\TagRepository;
use App\Repository\UserLikeSimilarityRelationRepository;
use App\Repository\UserRemixSimilarityRelationRepository;
use App\Utils\TimeUtils;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Symfony2Extension\Context\KernelDictionary;
use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use PHPUnit\Framework\Assert;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Router;

/**
 * Trait SymfonySupport.
 *
 * Php only supports single inheritance, therefore we can't extend all our Context Classes from the same BaseContext.
 * Some Context Classes must extend the (Mink)BrowserContext. That's why we use this trait in all our Context
 * files to provide them with the same basic functionality.
 *
 * A trait is basically just a copy & paste and therefore every context uses its own instances.
 * Since some variables must exist only once, we have to set them to static members. (E.g. kernel_browser)
 */
trait SymfonySupport
{
  use KernelDictionary;

  /**
   * @var string
   */
  private $ERROR_DIR = 'tests/testreports/behat/';

  /**
   * @var string
   */
  private $FIXTURES_DIR = './tests/testdata/DataFixtures/';

  /**
   * @var string
   */
  private $SCREENSHOT_DIR = 'tests/testreports/screens/';

  /**
   * @var string
   */
  private $MEDIA_PACKAGE_DIR = './tests/testdata/DataFixtures/MediaPackage/';

  /**
   * @var string
   */
  private $EXTRACT_RESOURCES_DIR = './public/resources_test/extract/';

  /**
   * @var Kernel
   */
  private $kernel;

  /**
   * Initialize the kernel and all members that must be initialized with the kernel.
   */
  public function setKernel(KernelInterface $kernel)
  {
    $this->kernel = $kernel;
  }

  public function getUserManager(): UserManager
  {
    return $this->kernel->getContainer()->get(UserManager::class);
  }

  public function getUserDataFixtures(): UserDataFixtures
  {
    return $this->kernel->getContainer()->get(UserDataFixtures::class);
  }

  public function getProgramManager(): ProgramManager
  {
    return $this->kernel->getContainer()->get(ProgramManager::class);
  }

  public function getProjectDataFixtures(): ProjectDataFixtures
  {
    return $this->kernel->getContainer()->get(ProjectDataFixtures::class);
  }

  public function getJwtManager(): JWTManager
  {
    return $this->kernel->getContainer()->get('lexik_jwt_authentication.jwt_manager');
  }

  public function getJwtEncoder(): JWTEncoderInterface
  {
    return $this->kernel->getContainer()->get('lexik_jwt_authentication.encoder');
  }

  public function getTagRepository(): TagRepository
  {
    return $this->kernel->getContainer()->get(TagRepository::class);
  }

  public function getExtensionRepository(): ExtensionRepository
  {
    return $this->kernel->getContainer()->get(ExtensionRepository::class);
  }

  public function getProgramRemixForwardRepository(): ProgramRemixRepository
  {
    return $this->kernel->getContainer()->get(ProgramRemixRepository::class);
  }

  public function getProgramRemixBackwardRepository(): ProgramRemixBackwardRepository
  {
    return $this->kernel->getContainer()->get(ProgramRemixBackwardRepository::class);
  }

  public function getScratchProgramRepository(): ScratchProgramRepository
  {
    return $this->kernel->getContainer()->get(ScratchProgramRepository::class);
  }

  public function getScratchProgramRemixRepository(): ScratchProgramRemixRepository
  {
    return $this->kernel->getContainer()->get(ScratchProgramRemixRepository::class);
  }

  public function getFileRepository(): ProgramFileRepository
  {
    return $this->kernel->getContainer()->get(ProgramFileRepository::class);
  }

  public function getExtractedFileRepository(): ExtractedFileRepository
  {
    return $this->kernel->getContainer()->get(ExtractedFileRepository::class);
  }

  public function getMediaPackageFileRepository(): MediaPackageFileRepository
  {
    return $this->kernel->getContainer()->get(MediaPackageFileRepository::class);
  }

  public function getRecommenderManager(): RecommenderManager
  {
    return $this->kernel->getContainer()->get(RecommenderManager::class);
  }

  public function getUserLikeSimilarityRelationRepository(): UserLikeSimilarityRelationRepository
  {
    return $this->kernel->getContainer()->get(UserLikeSimilarityRelationRepository::class);
  }

  public function getUserRemixSimilarityRelationRepository(): UserRemixSimilarityRelationRepository
  {
    return $this->kernel->getContainer()->get(UserRemixSimilarityRelationRepository::class);
  }

  public function getCatroNotificationRepository(): CatroNotificationRepository
  {
    return $this->kernel->getContainer()->get(CatroNotificationRepository::class);
  }

  public function getManager(): ObjectManager
  {
    return $this->kernel->getContainer()->get('doctrine')->getManager();
  }

  /**
   * @param $service_name
   *
   * @return object|null
   */
  public function getService($service_name)
  {
    return $this->kernel->getContainer()->get($service_name);
  }

  public function getRouter(): Router
  {
    return $this->kernel->getContainer()->get('router');
  }

  /**
   * @param $param
   *
   * @return mixed
   */
  public function getSymfonyParameter($param)
  {
    return $this->kernel->getContainer()->getParameter($param);
  }

  /**
   * @param $param
   *
   * @return object
   */
  public function getSymfonyService($param)
  {
    return $this->kernel->getContainer()->get($param);
  }

  /**
   * @return string
   */
  public function getDefaultProgramFile()
  {
    $file = $this->FIXTURES_DIR.'/test.catrobat';
    Assert::assertTrue(is_file($file));

    return $file;
  }

  /**
   * @param $directory
   */
  public function emptyDirectory($directory)
  {
    if (!is_dir($directory))
    {
      return;
    }
    $filesystem = new Filesystem();

    $finder = new Finder();
    $finder->in($directory)->depth(0);
    foreach ($finder as $file)
    {
      $filesystem->remove($file);
    }
  }

  /**
   * @param array $config
   *
   * @throws Exception
   *
   * @return GameJam
   */
  public function insertDefaultGameJam($config = [])
  {
    $game_jam = new GameJam();
    $game_jam->setName(isset($config['name']) ? $config['name'] : 'pocketalice');
    $game_jam->setHashtag(isset($config['hashtag']) ? $config['hashtag'] : null);

    if (isset($config['flavor']) && 'no-flavor' !== $config['flavor'])
    {
      $game_jam->setFlavor($config['flavor']);
    }
    elseif (!isset($config['flavor']))
    {
      $game_jam->setFlavor('pocketalice');
    }

    $start_date = TimeUtils::getDateTime();
    $start_date->sub(new DateInterval('P10D'));
    $end_date = TimeUtils::getDateTime();
    $end_date->add(new DateInterval('P10D'));

    $game_jam->setStart(isset($config['start']) ? $config['start'] : $start_date);
    $game_jam->setEnd(isset($config['end']) ? $config['end'] : $end_date);

    $game_jam->setFormUrl(isset($config['formurl']) ? $config['formurl'] : 'https://catrob.at/url/to/form');

    $this->getManager()->persist($game_jam);
    $this->getManager()->flush();

    return $game_jam;
  }

  public function insertUser(array $config = [], bool $andFlush = true): User
  {
    return $this->getUserDataFixtures()->insertUser($config, $andFlush);
  }

  /**
   * @param array $config
   */
  public function assertUser(array $config = [])
  {
    $this->getUserDataFixtures()->assertUser($config);
  }


  public function computeAllLikeSimilaritiesBetweenUsers()
  {
    $catroweb_dir = $this->kernel->getProjectDir().'/..';
    $similarity_computation_service = $catroweb_dir.'/bin/recsys-similarity-computation-service.jar';
    $output_dir = $catroweb_dir;
    $sqlite_db_path = '';

    shell_exec("{$catroweb_dir}/bin/console catrobat:recommender:export --env=test");
    shell_exec("/usr/bin/env java -jar {$similarity_computation_service} catroweb user_like_similarity_relation {$catroweb_dir} {$output_dir}");
    shell_exec("/usr/bin/env printf \"with open('{$catroweb_dir}/import_likes.sql') as file:\\n  for line in file:".
      "\\n    print line.replace('use catroweb;', '').replace('NOW()', '\\\"\\\"')\\n\" | ".
      "/usr/bin/env python2 > {$catroweb_dir}/import_likes_output.sql");
    shell_exec("/usr/bin/env cat {$catroweb_dir}/import_likes_output.sql | /usr/bin/env sqlite3 {$sqlite_db_path}");
    @unlink("{$catroweb_dir}/data_likes");
    @unlink("{$catroweb_dir}/data_remixes");
    @unlink("{$catroweb_dir}/import_likes.sql");
    @unlink("{$catroweb_dir}/import_likes_output.sql");
  }

  public function computeAllRemixSimilaritiesBetweenUsers()
  {
    //$this->getRecommenderManager()->computeUserRemixSimilarities(null);
    $catroweb_dir = $this->kernel->getProjectDir().'/..';
    $similarity_computation_service = $catroweb_dir.'/bin/recsys-similarity-computation-service.jar';
    $output_dir = $catroweb_dir;
    $sqlite_db_path = '';

    shell_exec("{$catroweb_dir}/bin/console catrobat:recommender:export --env=test");
    shell_exec("/usr/bin/env java -jar {$similarity_computation_service} catroweb user_remix_similarity_relation {$catroweb_dir} {$output_dir}");
    shell_exec("/usr/bin/env printf \"with open('{$catroweb_dir}/import_remixes.sql') as file:\\n  for line in file:".
      "\\n    print line.replace('use catroweb;', '').replace('NOW()', '\\\"\\\"')\\n\" | ".
      "/usr/bin/env python2 > {$catroweb_dir}/import_remixes_output.sql");
    shell_exec("/usr/bin/env cat {$catroweb_dir}/import_remixes_output.sql | /usr/bin/env sqlite3 {$sqlite_db_path}");
    @unlink("{$catroweb_dir}/data_likes");
    @unlink("{$catroweb_dir}/data_remixes");
    @unlink("{$catroweb_dir}/import_remixes.sql");
    @unlink("{$catroweb_dir}/import_remixes_output.sql");
  }

  public function insertUserLikeSimilarity(array $config = [], bool $andFlush = true): UserLikeSimilarityRelation
  {
    $user_manager = $this->getUserManager();

    /** @var User $first_user */
    $first_user = $user_manager->find($config['first_user_id']);

    /** @var User $second_user */
    $second_user = $user_manager->find($config['second_user_id']);

    $relation = new UserLikeSimilarityRelation($first_user, $second_user, $config['similarity']);

    $this->getManager()->persist($relation);
    if ($andFlush)
    {
      $this->getManager()->flush();
    }

    return $relation;
  }

  public function insertUserRemixSimilarity(array $config = [], bool $andFlush = true): UserRemixSimilarityRelation
  {
    $user_manager = $this->getUserManager();

    /** @var User $first_user */
    $first_user = $user_manager->find($config['first_user_id']);

    /** @var User $second_user */
    $second_user = $user_manager->find($config['second_user_id']);

    $relation = new UserRemixSimilarityRelation($first_user, $second_user, $config['similarity']);

    $this->getManager()->persist($relation);
    if ($andFlush)
    {
      $this->getManager()->flush();
    }

    return $relation;
  }

  /**
   * @throws Exception
   */
  public function insertProgramLike(array $config = [], bool $andFlush = true): ProgramLike
  {
    $user_manager = $this->getUserManager();
    $program_manager = $this->getProgramManager();

    /** @var User $user */
    $user = $user_manager->findUserByUsername($config['username']);

    /** @var Program $program */
    $program = $program_manager->find($config['program_id']);

    $program_like = new ProgramLike($program, $user, $config['type']);
    $program_like->setCreatedAt(new DateTime($config['created at'], new DateTimeZone('UTC')));

    $this->getManager()->persist($program_like);
    if ($andFlush)
    {
      $this->getManager()->flush();
    }

    return $program_like;
  }

  /**
   * @param $config
   */
  public function insertTag(array $config = [], bool $andFlush = true): Tag
  {
    $tag = new Tag();
    $tag->setEn($config['en']);
    $tag->setDe(isset($config['de']) ? $config['de'] : null);

    $this->getManager()->persist($tag);
    if ($andFlush)
    {
      $this->getManager()->flush();
    }

    return $tag;
  }

  /**
   * @param $config
   */
  public function insertExtension(array $config = [], bool $andFlush = true): Extension
  {
    $extension = new Extension();
    $extension->setName($config['name']);
    $extension->setPrefix($config['prefix']);

    $this->getManager()->persist($extension);
    if ($andFlush)
    {
      $this->getManager()->flush();
    }

    return $extension;
  }

  public function insertForwardRemixRelation(array $config = [], bool $andFlush = true): ProgramRemixRelation
  {
    /** @var Program $ancestor */
    $ancestor = $this->getProgramManager()->find($config['ancestor_id']);

    /** @var Program $descendant */
    $descendant = $this->getProgramManager()->find($config['descendant_id']);

    $forward_relation = new ProgramRemixRelation($ancestor, $descendant, (int) $config['depth']);

    $this->getManager()->persist($forward_relation);
    if ($andFlush)
    {
      $this->getManager()->flush();
    }

    return $forward_relation;
  }

  /**
   * @param $config
   */
  public function insertBackwardRemixRelation(array $config = [], bool $andFlush = true): ProgramRemixBackwardRelation
  {
    /** @var Program $parent */
    $parent = $this->getProgramManager()->find($config['parent_id']);

    /** @var Program $child */
    $child = $this->getProgramManager()->find($config['child_id']);

    $backward_relation = new ProgramRemixBackwardRelation($parent, $child);

    $this->getManager()->persist($backward_relation);
    if ($andFlush)
    {
      $this->getManager()->flush();
    }

    return $backward_relation;
  }

  /**
   * @param $config
   */
  public function insertScratchRemixRelation(array $config = [], bool $andFlush = true): ScratchProgramRemixRelation
  {
    /** @var Program $catrobat_child */
    $catrobat_child = $this->getProgramManager()->find($config['catrobat_child_id']);

    $scratch_relation = new ScratchProgramRemixRelation(
      $config['scratch_parent_id'],
      $catrobat_child
    );

    $this->getManager()->persist($scratch_relation);
    if ($andFlush)
    {
      $this->getManager()->flush();
    }

    return $scratch_relation;
  }

  /**
   * @throws Exception
   */
  public function insertProject(array $config, bool $andFlush = true): Program
  {
    return $this->getProjectDataFixtures()->insertProject($config, $andFlush);
  }

  public function insertFeaturedProject(array $config, bool $andFlush = true): FeaturedProgram
  {
    $featured_program = new FeaturedProgram();

    /* @var Program $program */
    if (isset($config['program_id']))
    {
      $program = $this->getProgramManager()->find($config['program_id']);
      $featured_program->setProgram($program);
    }
    else
    {
      $program = $this->getProgramManager()->findOneByName($config['name']);
      $featured_program->setProgram($program);
    }

    $featured_program->setUrl(isset($config['url']) ? $config['url'] : null);
    $featured_program->setImageType(isset($config['imagetype']) ? $config['imagetype'] : 'jpg');
    $featured_program->setActive(isset($config['active']) ? intval($config['active']) : true);
    $featured_program->setFlavor(isset($config['flavor']) ? $config['flavor'] : 'pocketcode');
    $featured_program->setPriority(isset($config['priority']) ? intval($config['priority']) : 1);
    $featured_program->setForIos(isset($config['ios_only']) ? 'yes' === $config['ios_only'] : false);

    $this->getManager()->persist($featured_program);
    if ($andFlush)
    {
      $this->getManager()->flush();
    }

    return $featured_program;
  }

  /**
   * @throws Exception
   */
  public function insertUserComment(array $config, bool $andFlush = true): UserComment
  {
    /** @var Program $project */
    $project = $this->getProgramManager()->find($config['program_id']);

    /** @var User $user */
    $user = $this->getUserManager()->find($config['user_id']);

    $new_comment = new UserComment();
    $new_comment->setUploadDate(new DateTime($config['upload_date'], new DateTimeZone('UTC')));
    $new_comment->setProgram($project);
    $new_comment->setUser($user);
    $new_comment->setUsername($config['user_name']);
    $new_comment->setIsReported($config['reported']);
    $new_comment->setText($config['text']);

    $this->getManager()->persist($new_comment);

    if (isset($comment['id']))
    {
      // overwrite id if desired
      $new_comment->setId($config['id']);
      $this->getManager()->persist($new_comment);
    }

    if ($andFlush)
    {
      $this->getManager()->flush();
    }

    return $new_comment;
  }

  /**
   * @throws Exception
   */
  public function insertProjectReport(array $config, bool $andFlush = true): ProgramInappropriateReport
  {
    /** @var Program $project */
    $project = $this->getProgramManager()->find($config['program_id']);

    /** @var User $user */
    $user = $this->getUserManager()->find($config['user_id']);

    $new_report = new ProgramInappropriateReport();
    $new_report->setCategory($config['category']);
    $new_report->setProgram($project);
    $new_report->setReportingUser($user);
    $new_report->setTime(new DateTime($config['time'], new DateTimeZone('UTC')));
    $new_report->setNote($config['note']);
    $this->getManager()->persist($new_report);

    if ($andFlush)
    {
      $this->getManager()->flush();
    }

    return $new_report;
  }

  /**
   * @throws Exception
   */
  public function insertProgramDownloadStatistics(array $config, bool $andFlush = true): ProgramDownloads
  {
    /** @var Program $project */
    $project = $this->getProgramManager()->find($config['program_id']);

    $program_statistics = new ProgramDownloads();
    $program_statistics->setProgram($project);
    $program_statistics->setDownloadedAt(new DateTime($config['downloaded_at']) ?: TimeUtils::getDateTime());
    $program_statistics->setIp(isset($config['ip']) ? $config['ip'] : '88.116.169.222');
    $program_statistics->setCountryCode(isset($config['country_code']) ? $config['country_code'] : 'AT');
    $program_statistics->setCountryName(isset($config['country_name']) ? $config['country_name'] : 'Austria');
    $program_statistics->setUserAgent(isset($config['user_agent']) ? $config['user_agent'] : 'okhttp');
    $program_statistics->setReferrer(isset($config['referrer']) ? $config['referrer'] : 'Facebook');

    if (isset($config['username']))
    {
      $user_manager = $this->getUserManager();
      $user = $user_manager->findUserByUsername($config['username']);
      if (null === $user)
      {
        $this->insertUser(['name' => $config['username']], false);
      }
      $program_statistics->setUser($user);
    }

    $this->getManager()->persist($program_statistics);
    $project->addProgramDownloads($program_statistics);
    $this->getManager()->persist($project);

    if ($andFlush)
    {
      $this->getManager()->flush();
    }

    return $program_statistics;
  }

  public function insertNotification(array $config, bool $andFlush = true): Notification
  {
    /** @var User $user */
    $user = $this->getUserManager()->findUserByUsername($config['user']);

    $notification = new Notification();
    $notification->setUser($user);
    $notification->setReport($config['report']);
    $notification->setSummary($config['summary']);
    $notification->setUpload($config['upload']);
    $this->getManager()->persist($notification);

    if ($andFlush)
    {
      $this->getManager()->flush();
    }

    return $notification;
  }

  /**
   * @param $parameters
   * @param mixed $is_embroidery
   *
   * @return string
   */
  public function generateProgramFileWith($parameters, $is_embroidery = false)
  {
    $filesystem = new Filesystem();
    $this->emptyDirectory(sys_get_temp_dir().'/program_generated/');
    $new_program_dir = sys_get_temp_dir().'/program_generated/';
    if ($is_embroidery)
    {
      $filesystem->mirror($this->FIXTURES_DIR.'/GeneratedFixtures/embroidery', $new_program_dir);
    }
    else
    {
      $filesystem->mirror($this->FIXTURES_DIR.'/GeneratedFixtures/base', $new_program_dir);
    }
    $properties = simplexml_load_file($new_program_dir.'/code.xml');

    foreach ($parameters as $name => $value)
    {
      switch ($name)
      {
        case 'description':
          $properties->header->description = $value;
          break;
        case 'name':
          $properties->header->programName = $value;
          break;
        case 'platform':
          $properties->header->platform = $value;
          break;
        case 'catrobatLanguageVersion':
          $properties->header->catrobatLanguageVersion = $value;
          break;
        case 'applicationVersion':
          $properties->header->applicationVersion = $value;
          break;
        case 'applicationName':
          $properties->header->applicationName = $value;
          break;
        case 'url':
          $properties->header->url = $value;
          break;
        case 'tags':
          $properties->header->tags = $value;
          break;

        default:
          throw new PendingException('unknown xml field '.$name);
      }
    }

    $properties->asXML($new_program_dir.'/code.xml');
    $compressor = new CatrobatFileCompressor();

    return $compressor->compress($new_program_dir, sys_get_temp_dir().'/', 'program_generated');
  }

  /**
   * @param $path
   *
   * @return bool|string
   */
  protected function getTempCopy($path)
  {
    $temp_path = tempnam(sys_get_temp_dir(), 'apktest');
    copy($path, $temp_path);

    return $temp_path;
  }

  /**
   * @return UploadedFile
   */
  private function getStandardProgramFile()
  {
    $filepath = $this->FIXTURES_DIR.'test.catrobat';
    Assert::assertTrue(file_exists($filepath), 'File not found');

    return new UploadedFile($filepath, 'test.catrobat');
  }

  private function assertJsonRegex($pattern, $json)
  {
    // allows to compare strings using a regex wildcard (.*?)
    $pattern = json_encode(json_decode($pattern)); // reformat string

    // escape chars that should not be used as regex
    $pattern = str_replace('\\', '\\\\', $pattern);
    $pattern = str_replace('[', '\\[', $pattern);
    $pattern = str_replace(']', '\\]', $pattern);
    $pattern = str_replace('?', '\\?', $pattern);
    $pattern = str_replace('*', '\\*', $pattern);
    $pattern = str_replace('(', '\\(', $pattern);
    $pattern = str_replace(')', '\\)', $pattern);
    $pattern = str_replace('+', '\\+', $pattern);

    // define regex wildcards
    $pattern = str_replace('REGEX_STRING_WILDCARD', '(.+?)', $pattern);
    $pattern = str_replace('"REGEX_INT_WILDCARD"', '([0-9]+?)', $pattern);

    $delimter = '#';
    $json = json_encode(json_decode($json));
    Assert::assertRegExp($delimter.$pattern.$delimter, $json);
  }
}
