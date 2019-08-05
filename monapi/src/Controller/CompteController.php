<?php

namespace App\Controller;
use App\Entity\Compte;
use App\Entity\Partenaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * @Route("/api")
 */
class CompteController extends AbstractController
{
    /**
     * @Route("/compte", name="compte")
     * 
     */
    public function compt(Request $request,EntityManagerInterface $entityManager,SerializerInterface $serializer
    ,ValidatorInterface $validator)
    {
       
       $values=json_decode($request->getContent());
       $jour = date('d');
        $mois = date('m');
        $annee = date('Y');

        $heure = date('H');
        $minute = date('i');
        $seconde=date('s');
        $num= $jour.$mois.$annee.$heure.$minute.$seconde;
       
       if(isset($num,$values->montant,$values->partenaire_id))
       {
        
           $compte =new Compte();
    
           $compte->setNumcompte($num);
           $compte->setMontant($values->montant);
           $part=$this->getDoctrine()->getRepository(Partenaire::class)->find($values->partenaire_id);
            $compte->setPartenaire($part);
           $errors=$validator->validate($compte);
           if(count($errors))
           {
               $errors=$serializer->serialize($errors,'json');
               return new Response($errors,500,['Content-Type'=>'Application/json']);
           }
           $entityManager->persist($compte);
           $entityManager->flush();

           $data=['status'=>201,'message'=>'compte ajouter avec succe'];
           return new JsonResponse($data,201);
       }
       $data=['status'=>500,'message'=>'erreur '];
       return new JsonResponse($data,500);
    }



   














}
