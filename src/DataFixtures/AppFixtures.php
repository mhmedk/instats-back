<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Result;
use App\Repository\AccountRepository;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // create 20 transactions! Bam!
        for ($i = 1; $i < 30; $i++) {
            $results = new Result;
            $results->setValue(mt_rand(-10000, 10000)/100);

            if ($i >9) {
                $newDate = '2022-01-' . $i;
            } else {
                $newDate = '2022-01-0' . $i;
            }

            $results->setResultAt(new DateTimeImmutable($newDate));
            $results->setAccount($manager->getRepository(Account::class)->find(1));
            $manager->persist($results);
        }

        $manager->flush();
    }
}
