<?php

namespace App\Controller;
use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(CalendarRepository $calendar)
    {
        $events = $calendar->findAll();

        $rdvs = [];
        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'titre' => $event->getTitre(),
                'debut' => $event->getDebut()->format('Y-m-d H:i:s'),
                'fin' => $event->getFin()->format('Y-m-d H:i:s'),
                'description' => $event->getDescription(),
                'allDay' => $event->getAllDay(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),

            ];
        }
        $data = json_encode($rdvs);

        return $this->render('main/index.html.twig', compact('data'));
    }
}
