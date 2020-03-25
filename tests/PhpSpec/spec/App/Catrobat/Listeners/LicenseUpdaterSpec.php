<?php

namespace tests\PhpSpec\spec\App\Catrobat\Listeners;

use App\Catrobat\Services\ExtractedCatrobatFile;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem;

class LicenseUpdaterSpec extends ObjectBehavior
{
  public function it_is_initializable()
  {
    $this->shouldHaveType('App\Catrobat\Listeners\LicenseUpdater');
  }

  public function it_sets_media_license()
  {
    $filesystem = new Filesystem();
    $filesystem->mirror(__SPEC_GENERATED_FIXTURES_DIR__ . '/base/', __SPEC_CACHE_DIR__ . '/base/');
    $xml = simplexml_load_file(__SPEC_CACHE_DIR__ . '/base/code.xml');
    expect($xml->header->mediaLicense)->toBeLike('');
    $file = new ExtractedCatrobatFile(__SPEC_CACHE_DIR__ . '/base/', '/webpath', 'hash');
    $this->update($file);
    $xml = simplexml_load_file(__SPEC_CACHE_DIR__ . '/base/code.xml');
    expect($xml->header->mediaLicense)->toBeLike('https://developer.catrobat.org/ccbysa_v4');
  }

  public function it_sets_program_license()
  {
    $filesystem = new Filesystem();
    $filesystem->mirror(__SPEC_GENERATED_FIXTURES_DIR__ . '/base/', __SPEC_CACHE_DIR__ . '/base/');
    $xml = simplexml_load_file(__SPEC_CACHE_DIR__ . '/base/code.xml');
    expect($xml->header->programLicense)->toBeLike('');
    $file = new ExtractedCatrobatFile(__SPEC_CACHE_DIR__ . '/base/', '/webpath', 'hash');
    $this->update($file);
    $xml = simplexml_load_file(__SPEC_CACHE_DIR__ . '/base/code.xml');
    expect($xml->header->programLicense)->toBeLike('https://developer.catrobat.org/agpl_v3');
  }
}
