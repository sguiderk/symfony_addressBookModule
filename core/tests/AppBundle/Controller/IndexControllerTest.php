<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Constraints\Length;

class IndexControllerTest extends WebTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * set Up the test 
     */
    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * function to test index page
     */
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request("GET", "/");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1,$crawler->filter("p.count")->count());
        
    }

    /**
     * function to test Add contact
     */
    public function testAdd()
    {
        $client = static::createClient();
        $crawler = $client->request("GET", "/new");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $crawler->selectButton("submit_add")->form();
        $form->setValues([
            "appbundle_person[firstName]"=>"firstname"
        ]);
        $crawler = $client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter(".form-error-message")->count());
        $form->setValues($this->getContactTestExample());
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect("/"));
        $this->assertEquals(0,$crawler->filter(".form-error-message")->count());

    }

    /**
     * function to test Edit contact
     */
    public function testEdit()
    {
        $client = static::createClient();
        $crawler = $client->request("GET", "/edit/".$this->getLastInsertedId());
        
        if (!$client->getResponse()->isNotFound()) {
            $form = $crawler->selectButton("save_contact")->form();
            $form->setValues($this->getContactTestExample());
            $crawler = $client->submit($form);
            $this->assertTrue($client->getResponse()->isRedirection());
        }
    }

    /**
     * function to test Delete contact
     */
    public function testDelete()
    {
        $client = static::createClient();
        $crawler =  $client->request("GET", "/delete/".$this->getLastInsertedId());
        $this->assertTrue($client->getResponse()->isRedirect("/"));
    }

    /**
     * function to set data example of contact
     */
    private function getContactTestExample()
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

    /**
     * @desc get last id inserted
     * @return int
     */
    public function getLastInsertedId(){

        $persons = $this->em
            ->getRepository('AppBundle:Person')->findOneBy([], ['id' => 'desc']);
        return  $persons->getId();
    }

}
