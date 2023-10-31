<?php

namespace App\Controller;

use App\Security\Exception\TokenValidationException;
use App\Security\TokenValidator;
use App\Service\Exception\ProductNotFoundException;
use App\Service\Exception\ProductValidationFailedException;
use App\Service\Product\AddProductService;
use App\Service\Product\DeleteProductService;
use App\Service\Product\GetProductService;
use App\Service\Product\UpdateProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class ProductController extends AbstractController
{
    private GetProductService $getProductService;

    private AddProductService $addProductService;

    private UpdateProductService $updateProductService;

    private DeleteProductService $deleteProductService;

    private TokenValidator $tokenValidator;

    public function __construct(
        GetProductService $getProductService,
        AddProductService $addProductService,
        UpdateProductService $updateProductService,
        DeleteProductService $deleteProductService,
        TokenValidator $tokenValidator
    ) {
        $this->getProductService = $getProductService;
        $this->addProductService = $addProductService;
        $this->updateProductService = $updateProductService;
        $this->deleteProductService = $deleteProductService;
        $this->tokenValidator = $tokenValidator;
    }

    #[Route('/product/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getProduct(Request $request, int $id): Response
    {
        try {
            $token = str_replace("Bearer ", "", $request->headers->get('authorization'));
            $this->tokenValidator->validate($token);
            $product = $this->getProductService->getProduct($id);
        } catch (TokenValidationException $exception) {
            return new JsonResponse(['message' => 'Unauthorized access'], 403);
        } catch (ProductNotFoundException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], $exception->getCode());
        } catch (Throwable $e) {
            return new JsonResponse(['message' => 'Unexpected error'], 500);
        }
        return new JsonResponse($product->toArray());
    }

    #[Route('/product/{id}/{grammage}', requirements: ['id' => '\d+', 'grammage' => '\d+'], methods: ['GET'])]
    public function getProductPerGrammage(Request $request, int $id, int $grammage): Response
    {
        try {
            $token = str_replace("Bearer ", "", $request->headers->get('authorization'));
            $this->tokenValidator->validate($token);
            $product = $this->getProductService->getProductPerGrammage($id, $grammage);
        } catch (TokenValidationException $exception) {
            return new JsonResponse(['message' => 'Unauthorized access'], 403);
        } catch (ProductNotFoundException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], $exception->getCode());
        } catch (Throwable) {
            return new JsonResponse(['message' => 'Unexpected error'], 500);
        }
        return new JsonResponse($product->toArray());
    }

    #[Route('/product', requirements: ['_format' => 'json'], methods: ['POST'])]
    public function addProduct(Request $request): Response
    {
        try {
            $token = str_replace("Bearer ", "", $request->headers->get('authorization'));
            $this->tokenValidator->validate($token);
            $content = json_decode($request->getContent(), true);
            if ($content === null) {
                return new JsonResponse(['message' => 'Valid json required'], 412);
            }
            $this->addProductService->addProduct($content);
        } catch (TokenValidationException $exception) {
            return new JsonResponse(['message' => 'Unauthorized access'], 403);
        } catch (ProductValidationFailedException $exception) {
            return new JsonResponse(['message' => $exception->getMessage(), 'errors' => $exception->getErrors()],
                $exception->getCode());
        } catch (Throwable $e) {
            return new JsonResponse(['message' => 'Unexpected error', 500]);
        }
        return new JsonResponse(['message' => 'Product added'], 201);
    }

    #[Route('/product/{id}', requirements: ['id' => '\d+', '_format' => 'json'], methods: ['PUT'])]
    public function updateProduct(Request $request): Response
    {
        try {
            $token = str_replace("Bearer ", "", $request->headers->get('authorization'));
            $this->tokenValidator->validate($token);
            $content = json_decode($request->getContent(), true);
            if ($content === null) {
                return new JsonResponse(['message' => 'Valid json required'], 412);
            }
            $this->updateProductService->updateProduct($request->get('id'), $content);
        } catch (TokenValidationException $exception) {
            return new JsonResponse(['message' => 'Unauthorized access'], 403);
        } catch (ProductNotFoundException $exception) {
            return new JsonResponse($exception->getMessage(), $exception->getCode());
        } catch (ProductValidationFailedException $exception) {
            return new JsonResponse(
                ['message' => $exception->getMessage(), 'errors' => $exception->getErrors()], $exception->getCode()
            );
        } catch (Throwable) {
            return new JsonResponse(['message' => 'Unexpected error', 500]);
        }
        return new JsonResponse(['message' => 'Product updated'], 201);
    }

    #[Route('/product/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteProduct(Request $request): Response
    {
        try {
            $token = str_replace("Bearer ", "", $request->headers->get('authorization'));
            $this->tokenValidator->validate($token);
            $this->deleteProductService->deleteProduct($request->get('id'));
        } catch (TokenValidationException $exception) {
            return new JsonResponse(['message' => 'Unauthorized access'], 403);
        } catch (ProductNotFoundException $exception) {
            return new JsonResponse($exception->getMessage(), $exception->getCode());
        } catch (Throwable) {
            return new JsonResponse(['message' => 'Unexpected error', 500]);
        }
        return new JsonResponse(['message' => 'Product deleted'], 201);
    }
}