<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class VehicleController extends AbstractController
{
    // API
    // returning all vehicules
    #[Route('/api/vehicles', name: 'api_vehicle_index', methods: ['GET'])]
    public function api_index(VehicleRepository $vehicleRepository): Response
    {
        return new JsonResponse($vehicleRepository->findAllAsArray(), Response::HTTP_OK);
    }


    // API
    // adding new vehicule
    #[Route('/api/vehicle/new', name: 'api_vehicle_new', methods: ['POST'])]
    public function api_new(Request $request, VehicleRepository $vehicleRepository, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode(html_entity_decode($request->getContent()), true);
        $vehicle = new Vehicle();
        $vehicle->setDateAdded(new \DateTime($data['date_added']));
        $vehicle->setType($data['vehicule_type']);
        $vehicle->setMsrp($data['msrp']);
        $vehicle->setYear($data['year']);
        $vehicle->setMake($data['make']);
        $vehicle->setModel($data['model']);
        $vehicle->setMiles($data['miles']);
        $vehicle->setVin($data['vin']);
        $vehicle->setDeleted($data['deleted']);

        $errors = $validator->validate($vehicle);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse($errorsString, Response::HTTP_NOT_IMPLEMENTED);
        }

        $vehicleRepository->add($vehicle);
        return new JsonResponse(['status' => 'New  vehicule added!'], Response::HTTP_CREATED);
    }


    // API 
    // return vehicule with given id
    #[Route('/api/vehicle/{id}', name: 'api_vehicle_show', methods: ['GET'])]
    public function api_show(Vehicle $vehicle): JsonResponse
    {
        return new JsonResponse($vehicle->toArray(), Response::HTTP_OK);
    }


    // API
    // Edit vehicule
    #[Route('/api/vehicle/{id}/edit', name: 'api_vehicle_edit', methods: ['POST'])]
    public function api_edit(Request $request, Vehicle $vehicle, VehicleRepository $vehicleRepository, ValidatorInterface $validator):  JsonResponse
    {
        $data = json_decode(html_entity_decode($request->getContent()), true);
        $vehicle->setDateAdded(new \DateTime($data['date_added']));
        $vehicle->setType($data['vehicule_type']);
        $vehicle->setMsrp($data['msrp']);
        $vehicle->setYear($data['year']);
        $vehicle->setMake($data['make']);
        $vehicle->setModel($data['model']);
        $vehicle->setMiles($data['miles']);
        $vehicle->setVin($data['vin']);
        $vehicle->setDeleted($data['deleted']);
        
        $errors = $validator->validate($author);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse($errorsString, Response::HTTP_NOT_IMPLEMENTED);
        }
        $vehicleRepository->add($vehicle);
        return new JsonResponse(['status' => 'Vehicule edit successful!'], Response::HTTP_OK);
    }

    // API
    // delete vehicule with given id
    #[Route('/api/vehicle/{id}', name: 'api_vehicle_delete', methods: ['DELETE'])]
    public function api_delete(Request $request, Vehicle $vehicle, VehicleRepository $vehicleRepository): JsonResponse
    {
        $vehicleRepository->remove($vehicle);
        return new JsonResponse(['status' => 'Vehicule deleted!'], Response::HTTP_NO_CONTENT);
    }
}
