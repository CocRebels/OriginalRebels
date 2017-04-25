<?php

namespace AppBundle\Command;
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 4/17/17
 * Time: 2:16 PM
 */

use AppBundle\Entity\Champion;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class ImportChampionsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:insert-champions')
        ;
    }

// ...
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $website = 'http://marvel-contestofchampions.wikia.com/';
        $source = $this->getWebsiteSourceCode(
            $website.'wiki/List_of_Champions'
        );
        $links = $this->getChampionLinks($source, $website);
        $number = count($links);
        foreach ($links as $link)
        {
            $championData = $this->getChampionData($link);
//            if ($championData === null)
//            {
//                continue;
//            }
//            $this->saveChampionData($championData);
            $output->writeln('Uploaded:'.$championData['name']);
        }

        // retrieve the argument value using getArgument()
        $output->writeln('Champions exist - '.$number);
    }


    /**
     * @param string $source
     * @param string $website
     * @return array
     */
    public function getChampionLinks(string $source, string $website)
    {
        $crawler = new Crawler($source, 'https');

        $links = $crawler->filter('div.tabbertab > table > tr > th > a')->links();
        $championLinks = array();
        foreach ($links as $link)
        {
            $formatUrl = substr($link->getUri(), 6);
            if (strpos($formatUrl, '?action=edit&redlink=1') === false )
            {
                $championLinks[] = $website . $formatUrl;
            }
        }
        return $championLinks;
    }

    /**
     * @param string $url
     * @return string
     */
    public function getWebsiteSourceCode(string $url)
    {
        return file_get_contents($url);
    }

    public function getChampionData(string $link)
    {
        $source = $this->getWebsiteSourceCode($link);
        $crawler = new Crawler($source, 'https');
        if ($crawler->filter('table.infobox')->count() === 0)
        {
            return $championData = null;
        }
        else {
            $championData['name'] = $crawler->filter('table.infobox > tr > th')->eq(0)->text();
            $championData['description'] = $crawler->filter('div.mw-content-ltr > p')->eq(1)->text();
            $championData['class'] = $crawler->filter('table.infobox > tr > td > a')->eq(0)->text();
            $championData['image'] = $crawler->filter('table.infobox.bordered > tbody > tr > td > center > div.tabberlive > div.tabbertab > p > a.image.image-thumbnail > img')->attr('src');
            dump($championData['image']);
            return $championData;
        }
    }

    public function saveChampionData(array $championData)
    {
        $em = $this->getContainer()->get('doctrine')
            ->getManager();
        $champion = new Champion();
        $champion->setName($championData['name']);
        $champion->setDescription($championData['description']);
        $champion->setClass($championData['class']);
        $champion->setImage($championData['image']);
        $em->persist($champion);
        $em->flush();
    }

}