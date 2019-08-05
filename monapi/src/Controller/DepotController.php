<?php

namespace App\Controller;
use App\Entity\Compte;
use App\Entity\Depot;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;

/**
 * @Route("/api")
 */
class DepotController extends AbstractController
{
    /**
     * @Route("/depot", name="depot")
     * 
     */
    public function depot(Request $request,EntityManagerInterface $entityManager,ValidatorInterface $validator,
    SerializerInterface $serializer)
    {
        $values= json_decode($request->getContent());
        if(isset($values->date,$values->montant,$values->user_id,$values->compte_id))
        {
            $depot= new Depot();
            $depot->setDate(new\DateTime());
            if($values->montant>=75000)
            {
                $depot->setMontant($values->montant);
            }
            elseif($values->montant<75000)
            {
                $data=['statu'=>500,'sms'=>'la somme est insuffisante'];
                return new JsonResponse($data,500);
            }
            
            $use = $this->getDoctrine()->getRepository(User::class)->find($values->user_id);
            $depot->setUser($use);
            $compt = $this->getDoctrine()->getRepository(Compte::class)->find($values->compte_id);
            $depot->setCompte($compt);
            
            $errors=$validator->validate($depot);
            if(count($errors))
            {
                $errors=$serializer->serialize($errors,'json');
                return new Response($errors,500,['Content-Type'=>'Application/json']);
            }
            $entityManager->persist($depot);
            $entityManager->flush();

            $data=['status'=>201,'message'=>'depot effectué avec succee'];
            return new JsonResponse($data,201);
        }
        $data=['status'=>500,'message'=>'erreur'];
        return new JsonResponse($data,500);
    }
}
