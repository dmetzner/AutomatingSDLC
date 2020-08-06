<?php

namespace Tests\phpUnit\Commands;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class ClearExtractedProjectsTest.
 *
 * @internal
 * @covers \App\Commands\Maintenance\CleanLogsCommand
 */
class CleanLogsTest extends KernelTestCase
{
  private CommandTester $command_tester;

  private string $logs_dir;

  protected function setUp(): void
  {
    $kernel = static::createKernel();
    $application = new Application($kernel);
    $command = $application->find('catrobat:clean:logs');
    $this->command_tester = new CommandTester($command);
    $this->logs_dir = $kernel->getContainer()->getParameter('catrobat.logs.dir');
    fopen('/tmp/phpUnitTestCleanLogs', 'w');
    $file = new File('/tmp/phpUnitTestCleanLogs');
    $file->move($this->logs_dir, 'test');
  }

  /**
   * @test
   */
  public function clearLogsData(): void
  {
    $logsSize = filesize($this->logs_dir);
    $return = $this->command_tester->execute([]);
    $this->assertEquals(0, $return);
    $logsSizeAfter = filesize($this->logs_dir);
    $this->assertGreaterThan($logsSizeAfter, $logsSize);
  }
}
