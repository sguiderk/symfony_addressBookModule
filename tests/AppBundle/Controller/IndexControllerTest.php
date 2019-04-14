<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Constraints\Length;

class IndexControllerTest extends WebTestCase
{
    
    public function testIndex()
    {
        $client = static::createClient();
                
        $crawler = $client->request("GET", "/");
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $this->assertEquals(1,$crawler->filter("p.count")->count());
        
        
        
    }
    
    public function testAdd()
    {
        $client = static::createClient();
        
        $crawler = $client->request("GET", "/new");
        
        $form = $crawler->selectButton("submit_add")->form();
        
        $form->setValues([
            "appbundle_person[firstName]"=>"firstname"
        ]);
        
        $crawler = $client->submit($form);
        
        $this->assertGreaterThan(0, $crawler->filter(".form-error-message")->count());
        
        $form->setValues([
            "appbundle_person[lastName]"=> "first name",
            
            "appbundle_person[address]"=> "adresse fake",
            
            "appbundle_person[zip]"=> "45789",
            
            "appbundle_person[city]"=> "FES",
            
            "appbundle_person[country]"=> "MA",
            
            "appbundle_person[phoneNumber]"=>"0644484254",
            
            "appbundle_person[birthday][month]"=> "01",
            
            "appbundle_person[birthday][day]"=>"06",
            
            "appbundle_person[birthday][year]"=>"1988",
            
            "appbundle_person[email]"=> "adil@gmail.com"
        ]);
        
        $crawler = $client->submit($form);
    }
    
    public function testEdit()
    {
        
    }
}
