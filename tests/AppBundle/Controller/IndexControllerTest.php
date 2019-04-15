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
        
        $form->setValues($this->getContactTestExemple());
        
        $crawler = $client->submit($form);
        
        $this->assertTrue($client->getResponse()->isRedirect("/"));
        $this->assertEquals(0,$crawler->filter(".form-error-message")->count());
        
    }
    
    public function testEdit()
    {
        $client = static::createClient();
        
        $crawler = $client->request("GET", "/edit/1");
        
        if(!$client->getResponse()->isNotFound())
        {
            $form = $crawler->selectButton("save_contact")->form();
            
            $form->setValues($this->getContactTestExemple());
            
            $crawler = $client->submit($form);
            
            $this->assertTrue($client->getResponse()->isRedirection());
            
        }
        
    }
    
    public function testDelete()
    {
        
    }
    
    private function getContactTestExemple()
    {
        return [
            "appbundle_person[firstName]"=> "first name",
            
            "appbundle_person[lastName]"=> "last name",
            
            "appbundle_person[address]"=> "adresse fake",
            
            "appbundle_person[zip]"=> "45789",
            
            "appbundle_person[city]"=> "FES",
            
            "appbundle_person[country]"=> "MA",
            
            "appbundle_person[phoneNumber]"=>"0612345678",
            
            "appbundle_person[birthday][month]"=> "1",
            
            "appbundle_person[birthday][day]"=>"6",
            
            "appbundle_person[birthday][year]"=>"1988",
            
            "appbundle_person[email]"=> md5(mt_rand(110,10000))."@gmail.com"
        ];
    }
}
