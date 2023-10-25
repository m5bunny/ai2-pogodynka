<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MeasurementRepository;
use App\Repository\LocationRepository;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{country?}', name: 'app_weather', requirements: ['id' => '\d+'])]
    public function city(string $city, ?string $country, MeasurementRepository $measurementRepo,
                         LocationRepository $locationRepo): Response
    {

        if (!$country) {
            $country = 'PL';
        }
        $location = $locationRepo->findByCityCountry($city, $country);

        if (!$location) {
            throw $this->createNotFoundException('Location not found!');
        }
        $measurements = $measurementRepo->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
