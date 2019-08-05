<?php

namespace App\Controller;
use App\Entity\User;

use App\Entity\Partenaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\VarExporter\Internal\Values;

use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Form\FormTypeInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="register",methods={"POST"})
     * 
     */
    public function register(Request $request,ValidatorInterface $validator,EntityManagerInterface $entityManager,
     UserPasswordEncoderInterface $passwordEncoder,SerializerInterface $serializer)
    {
        $values=json_decode($request->getContent());
        if (isset($values->username,$values->password,$values->nom,
        $values->prenom,$values->adresse,$values->telephone,$values->email,$values->partenaire_id,$values->status))
         {

            $user= new User();
            $user->setUsername($values->username);
            
            
            $user->setPassword($passwordEncoder->encodePassword($user,$values->password));
            
            
            if($values->roles==1 )
            {
            $user->setRoles(['ROLE_PARTENAIRE']);
        }
            elseif ($values->roles==2) {
                $user->setRoles(['ROLE_ADMIN']);
            }
            elseif($values->roles==3){
                $user->setRoles(['ROLE_CAISSIER']);
            }
            else{
                $user->setRoles($user->getRoles());
            }
            
            $user->setNom($values->nom);
            $user->setPrenom($values->prenom);
            $user->setAdresse($values->adresse);
            $user->setTelephone($values->telephone);
            $user->setEmail($values->email);
            $part=$this->getDoctrine()->getRepository(Partenaire::class)->find($values->partenaire_id);
            $user->setPartenaire($part);
            $user->setStatus($values->status);
            $errors=$validator->validate($user);
          
            if(count($errors)){
            $errors=$serializer->serialize($errors,'json');
           
            return new Response($errors,500,['Content-Type'=>'Application/json']);
           
            }
        
           
            $entityManager->persist($user);
            $entityManager->flush();
            $data=['status'=>201,'sms'=>'creation reussi'];

            return new JsonResponse($data,201);
        }

        $data=['status'=>500,'message'=>'veuillez remplir les champs correctements'];
        return new JsonResponse($data,500);
    }
    /**
     * @Route("/login", name="login",methods={"GET"})
     */
    public function login(Request $request)
    {
        $user=$this->getUser();
        return $this->json([
        'username'=>$user->getUsername(),
        'roles'=>$user->getRoles()]);

    }


    /**
 * @Route("/show/{id}", name="user_show",methods={"POST","GET"})
 */
public function show($id)
{
    $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($id);

    if (!$user) {
        throw $this->createNotFoundException(
            'No user found for id '.$id
        );
    }

    return new Response('Check out this great user: '.$user->getUsername().$user->getRoles().$user->getPassword().$user->getNom().$user->getPrenom().
    $user->getAdresse().$user->getTelephone().$user->getStatus().$user->getEmail());

  
}

    /**
     * 
     * @Route("/update/edit/{id}",methods={"PUT"})
     */
    public function updateUser($id,Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
       $entityManager=$this->getDoctrine()->getManager();
       $user=$entityManager->getRepository(User::class)->find($id);
       $values=json_decode($request->getContent());
       if(!$user)
       {
           throw $this->createNotFoundException('no user found for id'.$id);
       }
       $user->setUsername($values->username);
       $user->setRoles($values->roles);
       $user->setPassword($passwordEncoder,encodepassword($user,$values->password));
       $user->setNom($values->nom);
       $user->setPrenom($values->prenom);
       $user->setAdresse($values->adresse);
       $user->setTelephone($values->telephone);
       $user->setStatus($values->status);
       $user->setEmail($values->email);
       $entityManager->flush();
       return $this->redirectToRoute('user_show',['id'=>$user->getId()
       ]);

       
    }

   
   
}
