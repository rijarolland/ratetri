<?php

namespace App\Controller;

use App\Service\JsonTools;
use App\Service\Validator;
use App\Factory\ValidatorFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RateTriController extends AbstractController
{
    public function search(Request $request, KernelInterface $kernel, JsonTools $jsonTools, SerializerInterface $serializer): JsonResponse
    {
        /** @var int */
        $amount = $request->get('amount', 0);

        /** @var int */
        $delay = $request->get('delay', 0);

        /** @var string */
        $name = $request->get('name', '');

        /** @var string */
        $email = $request->get('email', '');

        /** @var string */
        $phone = $request->get('phone', '');

        /** @var mixed[] */
        $results = [];

        /** @var mixed[] */
        $orderedRates = [];

        /** @var int */
        $status = Response::HTTP_OK;

        /**
         * Name, email, phone number validation
         * 
         * @var Validator
         */
        $validator = ValidatorFactory::make($name, $email, $phone);

        if ($validator->isValid()) {

            $results[] = $jsonTools->filter($kernel->getProjectDir() . '/Data/dataone.json', ["montant" => $amount, "duree" => $delay], "taux");

            $results[] = $jsonTools->filter($kernel->getProjectDir() . '/Data/datatwo.json', ["montant_pret" => $amount, "duree_pret" => $delay], "taux_pret");

            $results[] = $jsonTools->filter($kernel->getProjectDir() . '/Data/datathree.json', ["amount" => $amount, "duration" => $delay], "rate");

            // Do tri by rate
            $orderedRates = $jsonTools->tri($results);

        } else {

            $status = Response::HTTP_INTERNAL_SERVER_ERROR;

        }

        /** @var mixed[] */
        $results = $serializer->serialize(['status' => $status, 'results' => $orderedRates, 'errors' => $validator->getErrors()], 'json');

        return new JsonResponse($results, $status, [], true);

    }
}