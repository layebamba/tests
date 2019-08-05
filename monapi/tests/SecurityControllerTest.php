<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
     public function testparte()
     {
         $client = static::createClient();
         $crawler = $client->request('POST','/api/partenaire',[],[],['CONTENT-TYPE'=>"Application/json"],
     '{"nom":"mysend","ninea":"1250lmk","registrecommerce":"1250mkj","adresse":"dakar",
         "telephone":778409645,"email":"mysend@gmail.com","isActive":0}');
     $rep=$client->getResponse();
     var_dump($rep);
     $this->assertSame(201,$client->getResponse()->getStatuscode());
     }
     
     public function testecompte()
     {
         $client=static::createClient();
         $crawler=$client->request('POST','/api/compte',[],[],['CONTENT-TYPE'=>'Application/json'],
     '{"montant":500000,"partenaire_id":2}');
     $rep=$client->getResponse();
     var_dump($rep);
     $this->assertSame(201,$client->getResponse()->getStatuscode());
     }
     public function testedepot()
     {
         $client=static::createClient();
         $crawler=$client->request('POST','/api/depot',[],[],['CONTENT-TYPE'=>'Application'],
         '{"date":"2019-08-04","montant":85000,"user_id":8,"compte_id":2}');
         $rep=$client->getResponse();
         var_dump($rep);
         $this->assertSame(201,$client->getResponse()->getStatuscode());
     }
    public function testeUser()
    {
$client=static::createClient();
$crawler=$client->request('POST','/api/register',[],[],['CONTENT-TYPE'=>'Application/json'],
'{"username":"mamy","roles":2,"password":"0001","nom":"gaye","prenom":"saly",
"adresse":"dakar","telephone":771450231,"email":"saly@gmail.com",
"partenaire_id":2,"status":"activer"}');
$rep=$client->getResponse();
var_dump($rep);
$this->assertSame(201,$client->getResponse()->getStatusCode());
    }
}
