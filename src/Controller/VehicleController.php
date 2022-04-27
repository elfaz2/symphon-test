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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\SerializerInterface;

class VehicleController extends AbstractController
{

    private $vehicleRepository;
    private SerializerInterface $serializer;

    public function __construct(VehicleRepository $vehicleRepository, SerializerInterface $serializer) {
        $this->vehicleRepository = $vehicleRepository;
        $this->serializer = $serializer;
    }

    
    /**
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns all vehicle",
     *     @Model(type=Vehicle::class)
     * )
     * 
     */ 
    #[Route('/api/vehicles', name: 'get_vehicles', methods: ['GET'])]
    public function get_vehicles(Request $request): Response
    {
        $filter_data = $request->query->all();
        return new JsonResponse($this->vehicleRepository->findAllAsArray($filter_data), Response::HTTP_OK);
    }
    
    /**
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns vehicle with given ID",
     *     @Model(type=Vehicle::class)
     * )
     * 
     * @OA\Response(
     *     response=204,
     *     description="HTTP_NO_CONTENT - no vehicle exist with given ID",
     * )
     * 
     */ 
    #[Route('/api/vehicle/{id}', name: 'get_vehicle', methods: ['GET'])]
    public function get_vehicle($id, VehicleRepository $vehicleRepository,): JsonResponse
    {
        $vehicle = $this->vehicleRepository->find($id);

        if($vehicle)
            return new JsonResponse($vehicle->toArray(), Response::HTTP_OK);
        else
            return new JsonResponse(['status' => 'vehicle not found!'], Response::HTTP_NO_CONTENT);
    }

    /**
     * 
     * @OA\RequestBody(
     *   description="Add new vehicule",     
     *   @OA\JsonContent(
     *      @OA\Property(type="datetime", property="date_added"),
     *      @OA\Property(type="string", property="vehicule_type"),
     *      @OA\Property(type="integer", property="msrp"),
     *      @OA\Property(type="integer", property="year"),
     *      @OA\Property(type="string", property="make"),
     *      @OA\Property(type="string", property="model"),
     *      @OA\Property(type="string", property="vin"),
     *      @OA\Property(type="boolean", property="deleted")
     *  )
     * )
     * 
     * @OA\Response(
     *  response=200,
     *  description="New vehicle added message",
     *  @OA\JsonContent(
     *      @OA\Property(type="string", property="status")
     *  )
     * )
     * 
     */
    #[Route('/api/vehicle/new', name: 'add_vehicle', methods: ['POST'])]
    public function add_vehicle(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $vehicle = new Vehicle;
        $vehicle->dataDefaults();
        
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->submit($request->request->all());

        
        $errors = $validator->validate($vehicle);
        
        if(count($errors) == 0) {
            $this->vehicleRepository->add($vehicle);
            return new JsonResponse(['status' => 'New  vehicle added!'], Response::HTTP_CREATED);
        }
        else {
            $errorsString = (string) $errors;
            return new JsonResponse(['error' => $this->serializer->serialize($errors,'json') ], Response::HTTP_NOT_IMPLEMENTED);
        }
    }

    /**
     * 
     * @OA\RequestBody(
     *   description="Edit vehicule",     
     *   @OA\JsonContent(
     *      @OA\Property(type="datetime", property="date_added"),
     *      @OA\Property(type="string", property="vehicule_type"),
     *      @OA\Property(type="integer", property="msrp"),
     *      @OA\Property(type="integer", property="year"),
     *      @OA\Property(type="string", property="make"),
     *      @OA\Property(type="string", property="model"),
     *      @OA\Property(type="string", property="vin"),
     *      @OA\Property(type="boolean", property="deleted")
     *  )
     * )
     * 
     * @OA\Response(
     *  response=200,
     *  description="Vehicle edit successful message",
     *  @OA\JsonContent(
     *      @OA\Property(type="string", property="status")
     *  )
     * )
     * 
     * @OA\Response(
     *     response=204,
     *     description="HTTP_NO_CONTENT - no vehicle exist with given ID",
     * )
     * 
     */ 
    #[Route('/api/vehicle/{id}', name: 'edit_vehicle', methods: ['PATCH'])]
    public function edit_vehicle($id, Request $request, ValidatorInterface $validator):  JsonResponse
    {
        $vehicle = $this->vehicleRepository->find($id);

        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->submit($request->request->all());

        
        $errors = $validator->validate($vehicle);
        
        if(count($errors) == 0) {
            $this->vehicleRepository->add($vehicle);
            return new JsonResponse(['status' => 'Vehicle edit successful!'], Response::HTTP_CREATED);
        }
        else {
            $errorsString = (string) $errors;
            return new JsonResponse(['error' => $this->serializer->serialize($errors,'json') ], Response::HTTP_NOT_IMPLEMENTED);
        }
    }
    
    /**
     * 
     * @OA\Response(
     *  response=200,
     *  description="Vehicle deleted message",
     *  @OA\JsonContent(
     *      @OA\Property(type="string", property="status")
     *  )
     * )
     * 
     * @OA\Response(
     *     response=204,
     *     description="HTTP_NO_CONTENT - no vehicle exist with given ID",
     * )
     * 
     */ 
    #[Route('/api/vehicle/{id}', name: 'delete_vehicle', methods: ['DELETE'])]
    public function delete_vehicle($id, VehicleRepository $vehicleRepository): JsonResponse
    {        
        $vehicle = $this->vehicleRepository->find($id);

        if($vehicle)
        {
            $this->vehicleRepository->remove($vehicle);
            return new JsonResponse(['status' => 'vehicle deleted!'], Response::HTTP_OK);
        }
        else
            return new JsonResponse(['status' => 'vehicle not found!'], Response::HTTP_NO_CONTENT);
    }
}
