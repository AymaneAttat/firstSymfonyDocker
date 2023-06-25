<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Profile;
use App\Entity\Course;
use App\Entity\User;
use App\Entity\Tag;
use \Datetime;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher){

    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
/**/
        $categories = ["Frontend", "Backend", "FullStack"];
        foreach ($categories as $category) {
            $myCategory = new Category();
            $myCategory->setLabel($category);
            $manager->persist($myCategory, true);
        }

        $tags = ["Javascript", "PHP", "Python", "Web"];
        foreach ($tags as $tag) {
            $myTag = new Tag();
            $myTag->setLabel($tag);
            $manager->persist($myTag, true);
        }

        $user1 = new User();
        $user1->setEmail("aymanetestapp@gmail.com");
        $user1->setRoles(["ROLE_CREATOR", "ROLE_EDITOR"]);
        $user1->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user1, "test123"
            )
        );
        $profile1 = new Profile();
        $profile1->setBio("Teacher");
        $profile1->setName("Aymane Attat");
        $profile1->setBirthday(new DateTime());
        $profile1->setUser($user1);
        $manager->persist($profile1, true);

        $user2 = new User();
        $user2->setEmail("aynana08@gmail.com");
        $user2->setRoles(["ROLE_EDITOR"]);
        $user2->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user2, "aymane123"
            )
        );
        $profile2 = new Profile();
        $profile2->setBio("Doctor");
        $profile2->setName("Amine Hosni");
        $profile2->setBirthday(new DateTime());
        $profile2->setUser($user2);
        $manager->persist($profile2, true);


        $user3 = new User();
        $user3->setEmail("hicham@gmail.com");
        $user3->setRoles(["ROLE_CREATOR"]);
        $user3->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user3, "hicham123"
            )
        );
        $profile3 = new Profile();
        $profile3->setBio("Enginer");
        $profile3->setName("Hicham Mohtadi");
        $profile3->setBirthday(new DateTime());
        $profile3->setUser($user3);
        $manager->persist($profile3, true);

        /**/$course1 = new Course();
        $course1->setTitle("Learn Laravel");
        $course1->setDescription("Learn Laravel from scratch");
        $manager->persist($course1);

        $course2 = new Course();
        $course2->setTitle("Learn WordPress");
        $course2->setDescription("Learn WordPress from scratch");
        $manager->persist($course2);

        $manager->flush();
    }
}
