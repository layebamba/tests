<?php

namespace App\Controller;

use App\Entity\Partenaire;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * @Route("/api")
 */
class PartenaireController extends AbstractController
{
    /**
     * @Route("/partenaire", name="partenaire",methods={"POST"})
     * 
     */
    public function ajouter(Request $request,EntityManagerInterface $entityManager,
    ValidatorInterface $validator,SerializerInterface $serializer)
    {
        $values=json_decode($request->getContent());
       
        if (isset($values->nom,$values->ninea,$values->registrecommerce,
        $values->adresse,$values->telephone,$values->email,$values->isActive)) {
            $partenaire= new Partenaire();
            $partenaire->setNom($values->nom);
           
            $partenaire->setNinea($values->ninea);
            
            
            $partenaire->setRegistrecommerce($values->registrecommerce);
            $partenaire->setAdresse($values->adresse);
            $partenaire->setTelephone($values->telephone);
            $partenaire->setEmail($values->email);
           
                $partenaire->setIsActive($values->isActive);
            
            
           
           
            $errors=$validator->validate($partenaire);
            if(count($errors))
            {
                $errors=$serializer->serialize($errors,'json');
                return new Response($errors,500,['Content-Type'=>'Application/json']);

            }
            $entityManager->persist($partenaire);
            $entityManager->flush();
            
            $data=['status'=>201,'message'=>'partenaire enregistrer avec succee'];
            return new JsonResponse($data,201);

        }
        $data=['status'=>500,'message'=>'verifier bien les champs'];
        return new JsonResponse($data,500);
        
    }
   
}
