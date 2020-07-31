<?php

namespace App\Tests;

use JWTAuth;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Entity\FootballLeague;
use App\Entity\FootballTeam;

class FootballTeamTest extends WebTestCase
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

        /** Get Latest League Id **/
        $league = $this->entityManager->getRepository(FootballLeague::class)->findAll();
        
        $this->leagueid = end($league)->id;
        $this->request = [
            "name" => substr(str_shuffle($this->chars), 0, 8),
            "strip" => substr(str_shuffle($this->chars), 0, 8)
        ];
    }

    public function login($user)
    {
        $this->client->request('POST', '/api/login_check', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(["username" => $user->username, "password" => "Admin@123"]));
        return $this->client->getResponse()->getContent();
    }

    /** @test*/
    public function createTeam()
    {
        $res = $this->client->request('POST', '/'.$this->leagueid.'/teams', $this->request, [], ["HTTP_Authorization" => "Bearer ".$this->token]);
        

        $this->assertEquals(200, $this->getStatusCode($this->client->getResponse()));
    }

    /** @test*/
    public function updateTeam()
    {
        /** Create Team First */
        $this->createTeam();

        /** Get Latest Team Id **/
        $team = $this->entityManager->getRepository(FootballTeam::class)->findAll();

        $this->client->request('PUT', '/'.$this->leagueid.'/teams/'.end($team)->id, [], [], ["HTTP_Authorization" => "Bearer ".$this->token], json_encode($this->request));

        $this->assertEquals(200, $this->getStatusCode($this->client->getResponse()));
    }

    /** @test*/
    public function teamList()
    {
    	$this->client->request('GET', '/'.$this->leagueid.'/teams', [], [], ["HTTP_Authorization" => "Bearer ".$this->token]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }


    /** @test*/
    public function deleteTeam()
    {
        /** Create Team First */
        $this->createTeam();

        /** Get Latest Team Id **/
        $team = $this->entityManager->getRepository(FootballTeam::class)->findAll();

        $this->client->request('DELETE', '/teams/'.end($team)->id, [], [], ["HTTP_Authorization" => "Bearer ".$this->token]);

        $this->assertEquals(200, $this->getStatusCode($this->client->getResponse()));
    }

    /** @test*/
    public function createleague_without_name()
    {

        $this->request["name"] = "";

        $this->client->request('POST', '/'.$this->leagueid.'/teams', $this->request, [], ["HTTP_Authorization" => "Bearer ".$this->token]);
        
        $this->assertEquals(422, $this->getStatusCode($this->client->getResponse()));
    }

    /** @test*/
    public function createleague_without_strip()
    {

        $this->request["strip"] = "";

        $this->client->request('POST', '/'.$this->leagueid.'/teams', $this->request, [], ["HTTP_Authorization" => "Bearer ".$this->token]);
        
        $this->assertEquals(422, $this->getStatusCode($this->client->getResponse()));
    }


    /** @test*/
    public function createleague_without_name_and_strip()
    {
        $this->request["name"] = "";
        $this->request["strip"] = "";

        $this->client->request('POST', '/'.$this->leagueid.'/teams', $this->request, [], ["HTTP_Authorization" => "Bearer ".$this->token]);
        
        $this->assertEquals(422, $this->getStatusCode($this->client->getResponse()));
    }


    /** @test*/
    public function createleague_with_min_length_name()
    {
        $this->request["name"] = "aa";

        $this->client->request('POST', '/'.$this->leagueid.'/teams', $this->request, [], ["HTTP_Authorization" => "Bearer ".$this->token]);
        
        $this->assertEquals(422, $this->getStatusCode($this->client->getResponse()));
    }

    /** @test*/
    public function createleague_with_min_length_strip()
    {
        $this->request["strip"] = "aa";

        $this->client->request('POST', '/'.$this->leagueid.'/teams', $this->request, [], ["HTTP_Authorization" => "Bearer ".$this->token]);
        
        $this->assertEquals(422, $this->getStatusCode($this->client->getResponse()));
    }

    public function getStatusCode($response){

        $data = json_decode($response->getContent());
        return $data->code;
    }
}
