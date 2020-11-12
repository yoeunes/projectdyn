<?php

namespace App\DataFixtures;

use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $genders = ['male', 'female'];
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $slug = new Slugify();
        /* All Users role USER */
        for($i = 1; $i <= 50; $i++) {
            $user = new User();
            $gender = $faker->randomElement($this->genders);
            $user->setFirstName($faker->firstName($gender));
            $user->setLastName($faker->lastName);
            $user->setEmail($slug->slugify($user->getFirstName()).'.'.$slug->slugify($user->getLastName()).'@gmail.com');
            $gender = $gender == 'male' ? 'm' : 'f';
            $user->setImage('0'.($i + 10).$gender.'.jpg');
            $user->setPassword($this->encoder->encodePassword($user, 'password'));
            $user->setCreatedAt($faker->dateTimeBetween('-6 months', 'now'));
            $user->setUpdatedAt($faker->dateTimeBetween('now'));
            $user->setLastLogAt($faker->dateTimeBetween('now'));
            $user->setIsDisabled($faker->boolean(10));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }
        /* John Doe role ADMIN */
        $user = new User();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setEmail('john.doe@gmail.com');
        $user->setImage('075m.jpg');
        $user->setPassword($this->encoder->encodePassword($user, 'password'));
        $user->setCreatedAt($faker->dateTimeBetween('-6 months', 'now'));
        $user->setUpdatedAt($faker->dateTimeBetween('now'));
        $user->setLastLogAt($faker->dateTimeBetween('now'));
        $user->setIsDisabled(false);
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $manager->flush();
    }
}
