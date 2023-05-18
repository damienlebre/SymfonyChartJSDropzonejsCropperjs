<?php

namespace App\Controller;

use App\Repository\DailyResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartjsController extends AbstractController
{
    #[Route('/chartjs', name: 'app_chartjs')]
    public function index(DailyResultRepository $dailyResultRepository, ChartBuilderInterface $chartBuilder): Response
    {

        $dailyResults= $dailyResultRepository->findAll();

        $labels = [];
        $datas = [];

        foreach ($dailyResults as $dailyResult) {
            $labels[] = $dailyResult->getDate()->format('d/m/Y');
            $datas[] = $dailyResult->getValue();
        }
        
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'valeurs par date',
                    'backgroundColor' => 'red',
                    'borderColor' => 'blue',
                    'data' => $datas,
                ],
            ],
        ]);

        $chart->setOptions([
            'plugins' => [
                'zoom' => [
                    'zoom' => [
                        'wheel' => ['enabled' => true],
                        'pinch' => ['enabled' => true],
                        'mode' => 'yx',
                        'sensitivity' => 0,
                        'speed' => 0.1,
                        'threshold' => 10,
                    ],
                ],
            ],
        ]);
        return $this->render('chartjs/index.html.twig', [
            'controller_name' => 'ChartjsController',
            'chart' => $chart,
        ]);
    }
}
