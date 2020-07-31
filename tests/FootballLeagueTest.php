<?php

namespace App\Tests;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Entity\FootballLeague;

class FootballLeagueTest extends WebTestCase
{
	private $entityManager;	
	private $client = null;
    
    public function setUp(): void
    {
    	parent::setUp();

        $this->client = static::createClient();
    	$kernel = self::bootKernel();
	    $this->entityManager = $kernel
	        ->getContainer()
	        ->get('doctrine')
	        ->getManager();

        $user = $this->entityManager->getRepository(User::class)->find(1);
        $data = $this->login($user);
        $this->token = json_decode($data)->token;

        $this->chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }

    public function login($user)
    {
        $this->client->request('POST', '/api/login_check', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(["username" => $user->username, "password" => "Admin@123"]));
        return $this->client->getResponse()->getContent();
    }

    /** @test*/
    public function createleague()
    {
    	$this->client->request('POST', '/leagues', ["name" => substr(str_shuffle($this->chars), 0, 8)], [], ["HTTP_Authorization" => "Bearer ".$this->token]);

        $this->assertEquals(200, $this->getStatusCode($this->client->getResponse()));
    }


    /** @test*/
    public function updateleague()
    {
        /** First create Football League **/
        $this->createleague();

        /** Get Latest League Id **/
        $league = $this->entityManager->getRepository(FootballLeague::class)->findAll();

        $this->client->request('PUT', '/leagues/'.end($league)->id, [], [], ["HTTP_Authorization" => "Bearer ".$this->token], json_encode(["name" => substr(str_shuffle($this->chars), 0, 8)]));
        
        $this->assertEquals(200, $this->getStatusCode($this->client->getResponse()));
    }


    /** @test*/
    public function leagueList()
    {

        $this->client->request('GET', '/leagues', [], [], ["HTTP_Authorization" => "Bearer ".$this->token]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }


    /** @test*/
    public function deleteLeague()
    {
        /** First create Football League **/
        $this->createleague();

        /** Get Latest League Id **/
        $league = $this->entityManager->getRepository(FootballLeague::class)->findAll();
        
        $this->client->request('DELETE', '/leagues/'.end($league)->id, [], [], ["HTTP_Authorization" => "Bearer ".$this->token]);
        $this->assertEquals(200, $this->getStatusCode($this->client->getResponse()));
    }


    /** @test*/
    public function createleague_without_name()
    {
        $this->client->request('POST', '/leagues', ["name" => ""], [], ["HTTP_Authorization" => "Bearer ".$this->token]);
        
        $this->assertEquals(422, $this->getStatusCode($this->client->getResponse()));
    }

    /** @test*/
    public function createleague_with_name_less_3_chars()
    {
        $this->client->request('POST', '/leagues', ["name" => "aa"], [], ["HTTP_Authorization" => "Bearer ".$this->token]);

        $this->assertEquals(422, $this->getStatusCode($this->client->getResponse()));
    }

    public function getStatusCode($response){

        $data = json_decode($response->getContent());
        return $data->code;
    }
}
