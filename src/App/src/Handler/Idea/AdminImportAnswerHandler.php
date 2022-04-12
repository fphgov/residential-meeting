<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use App\Middleware\UserMiddleware;
use App\Service\IdeaAnswerServiceInterface;
use App\Model\IdeaAnswerImportModel;
use App\Exception\IdeaNotFoundException;
use App\Exception\WorkflowStateNotFoundException;
use App\Exception\WorkflowStateExtraNotFoundException;
use Laminas\Log\Logger;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exception;

use function array_merge_recursive;

final class AdminImportAnswerHandler implements RequestHandlerInterface
{
    private Logger $audit;

    private IdeaAnswerServiceInterface $ideaAnswerService;

    public function __construct(
        Logger $audit,
        IdeaAnswerServiceInterface $ideaAnswerService
    ) {
        $this->audit             = $audit;
        $this->ideaAnswerService = $ideaAnswerService;
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
            $this->ideaAnswerService->importIdeaAnswers(
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
