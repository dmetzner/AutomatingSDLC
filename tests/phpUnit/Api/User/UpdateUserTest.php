<?php

namespace Tests\phpUnit\Api\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @covers \App\Api\UserApi
 */
class UpdateUserTest extends WebTestCase
{
  /**
   * {@inheritdoc}
   */
  public function setUp(): void
  {
    static::$kernel = static::createKernel();
    static::$kernel->boot();
  }

  /**
   * {@inheritdoc}
   */
  protected function tearDown(): void
  {
    parent::tearDown();
  }

  public function testUser(): void
  {
    $client = static::createClient();
    $client->request('POST', '/api/authentication', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'], '{"username": "Catrobat", "password":"123456"}');
    $this->assertResponseStatusCodeSame(200);
    $response = $client->getResponse();
    $data = json_decode($response->getContent(), true);
    /** @var string $token */
    $token = $data['token'] ?? null;
    $client->request('PUT', '/api/user', [], [], ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json', 'HTTP_authorization' => 'Bearer '.$token], '{"dry-run": false, "email": "test@test.lan", "username": "Testuser", "password": "password"}');
    $this->assertResponseStatusCodeSame(501);
  }
}
