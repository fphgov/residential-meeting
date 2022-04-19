<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use App\Middleware\UserMiddleware;
use App\Service\IdeaServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Log\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AdminSendEmailHandler implements RequestHandlerInterface
{
    private Logger $audit;

    private IdeaServiceInterface $ideaService;

    public function __construct(
        Logger $audit,
        IdeaServiceInterface $ideaService
    ) {
        $this->audit       = $audit;
        $this->ideaService = $ideaService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserMiddleware::class);

        $body = array_merge_recursive(
            $request->getParsedBody(),
            $request->getUploadedFiles(),
        );

        $error = false;

        try {
            $this->ideaService->importIdeaEmails(
                $body['file']->getStream()
            );
        } catch (IdeaNotFoundException $e) {
            $error = true;

            $this->audit->err('Import idea answer', [
                'extra' => $e->getMessage() . ' | (User ID: ' . $user->getId() . ')',
            ]);
        } catch (WorkflowStateNotFoundException $e) {
            $error = true;

            $this->audit->err('Import idea answer', [
                'extra' => $e->getMessage() . ' | (User ID: ' . $user->getId() . ')',
            ]);
        } catch (WorkflowStateExtraNotFoundException $e) {
            $error = true;

            $this->audit->err('Import idea answer', [
                'extra' => $e->getMessage() . ' | (User ID: ' . $user->getId() . ')',
            ]);
        } catch (Exception $e) {
            $error = true;

            $this->audit->err('Bad import idea answer file', [
                'extra' => $e->getMessage() . ' | ' . $user->getId(),
            ]);
        }

        if ($error) {
            return new JsonResponse([
                'errors' => 'Sikertelen importálás',
            ], 500);
        }

        return new JsonResponse([
            'data' => [
                'message' => 'Sikeres importálás'
            ],
        ]);
    }
}
