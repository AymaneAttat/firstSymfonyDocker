<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProfileRepository;
use App\Repository\CourseRepository;
use App\Repository\UserRepository;
use App\Form\CourseType;
use App\Entity\Profile;
use App\Entity\Course;
use App\Entity\User;

class HomeController extends AbstractController
{
    private $articles = [
        1 => ['title' => 'Learn Symfony', 'image' => 'https://process.fs.teachablecdn.com/ADNupMnWyR7kCWRvm76Laz/resize=width:705/https://cdn.filestackcontent.com/otcfFBbQSsuFqhl5W2mF'],
        2 => ['title' => 'Learn Javascript', 'image' => 'https://process.fs.teachablecdn.com/ADNupMnWyR7kCWRvm76Laz/resize=width:705/https://cdn.filestackcontent.com/7zQdVPcjSnGj7TCSIF5P'],
        3 => ['title' => 'Learn NodeJS', 'image' => 'https://process.fs.teachablecdn.com/ADNupMnWyR7kCWRvm76Laz/resize=width:705/https://cdn.filestackcontent.com/nSksEHOHQLSteJPjCg9t'],
    ];

    //public function index(): JsonResponse
    #[Route('/home/{limit<\d+>?10}', name: 'home_index')]
    #[IsGranted("ROLE_USER")]
    public function index($limit, CourseRepository $course): Response
    {
        //dd($course->findAll());
        //dd($course->find(2));
        //dd($course->findBy(["title" => "learn angular"])); //return une liste de résultat
        //dd($course->findOneBy(["title" => "learn angular"]));//return une seule instance
        //dd($this->getUser());
        return $this->render("home/index.html.twig", [
            'about' => "List of courses",
            'articles' => $this->articles,
            'courses' => $course->findAll(),
            'limit' => $limit
        ]);
        //return new Response("<h1>Bright Coding</h1>");
        /*return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/HomeController.php',
        ]);*/
    }

    //#[Route('/articles/{id}', name: 'app_show', requirements: ['id' => '[0-9]+'])]//'slug' => '[a-zA-Z0-9]*'
    //#[Route('/articles/{id<\d+>}', name: 'home_show')] public function show($id, CourseRepository $course): Response
    #[Route('/articles/{course}', name: 'home_show')]
    #[IsGranted("ROLE_USER")]
    public function show(Course $course): Response
    {
        return $this->render("home/show.html.twig", [
            //'article' => $this->articles[$id],
            //'course' => $course->find($id)
            'course' => $course
        ]);
        //return new Response("<h1>Article ID : $id </h1>");
    }

    #[Route('/articles/{slug<[a-zA-Z0-9-]*>}', name: 'app_slug')]
    public function slug($slug): Response
    {
        return new Response("<h1>Article SLUG: $slug </h1>");
    }

    #[Route('/articles/list', name: 'app_list', priority: 2)]
    public function list(): Response
    {
        return new Response("<h1>Article LIST </h1>");
    }

    //    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/articles/create', name: 'home_create', priority: 2)]
    #[IsGranted("ROLE_CREATOR")]
    public function create(Request $request, CourseRepository $courseRepo): Response
    {
        //$this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        //dd($this->getUser()->getProfile());
        $myCourse = new Course();
        //$myCourse->setTitle("Learn React");
        //$myCourse->setDescription("Learn React from scratch");
        //$course->save($myCourse, true);   return new Response("<h1>Course Create </h1>");

        //$form = $this->createFormBuilder($myCourse)
        //->add("title")
        //->add("description")
        //->add("Save", SubmitType::class)
        //->getForm();

        $form = $this->createForm(CourseType::class, $myCourse);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $course = $form->getData();
            $course->setProfile($this->getUser()->getProfile());
            $courseRepo->save($course, true);
            //Flash message
            $this->addFlash("success", "Course added to database");
            //redirect to list of courses
            return $this->redirectToRoute("home_index");//$this->redirect("/home/10")
        }
        return $this->renderForm("home/create.html.twig", [
            'formCourse' => $form
        ]);
    }

    #[Route('/articles/{course<\d+>}/update', name: 'home_update')]
    #[IsGranted("ROLE_EDITOR")]
    public function update(Request $request, Course $course, CourseRepository $courseRepo): Response
    {
        //$myCourse = $course->find($id);
        //$myCourse->setTitle("Learn Spring Boot");
        //$myCourse->setDescription("Learn spring boot from scratch");
        //$course->save($myCourse, true);
        //return new Response("<h1>Course UPDATED </h1>");

        //$form = $this->createFormBuilder($course)
        //->add("title")
        //->add("description")
        //->add("Save", SubmitType::class)
        //->getForm();

        $form = $this->createForm(CourseType::class, $course);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $course = $form->getData();
            $courseRepo->save($course, true);
            //Flash message
            $this->addFlash("success", "Course updated");
            //redirect to list of courses
            return $this->redirectToRoute("home_index");//$this->redirect("/home/10")
        }
        return $this->renderForm("home/edit.html.twig", [
            'formCourse' => $form
        ]);
    }

    #[Route('/articles/{id<\d+>}/delete', name: 'home_delete')]
    public function delete($id, CourseRepository $course): Response
    {
        $myCourse = $course->find($id);
        $course->remove($myCourse, true);
        return new Response("<h1>Course DELETED </h1>");
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(ProfileRepository $profileRepo, UserRepository $userRepo): Response
    {
        //Pour la création avec la relation one to one
        //$user = new User();
        //$user->setEmail("aymane@gmail.com");
        //$user->setPassword("aymane123");

        //$myProfile = new Profile();
        //$myProfile->setUser($user);
        //$myProfile->setBio("hello every one");
        //$myProfile->setName("Aymane");
        //$profileRepo->save($myProfile, true);

        //Pour la suppression avec la relation one to one
        //$myProfile = $profileRepo->find(1);
        //$profileRepo->remove($myProfile, true);

        $myUser = $userRepo->find(3);
        $userRepo->remove($myUser, true);

        return new Response("<h1>Profile is deleted</h1>");
    }

    #[Route('/users', name: 'profile_home')]
    public function user(UserRepository $userRepo): Response
    {
        return $this->render("home/users.html.twig", [
            'users' => $userRepo->findAll()
        ]);
    }

}
