<?php

declare(strict_types=1);

namespace App\Actions;

use App\Responders\HtmlResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class FaqAction.
 *
 * Handles rendering the FAQ page.
 */
class FaqAction
{
    /**
     * FaqAction constructor.
     *
     * @param HtmlResponder $responder The responder for rendering HTML content.
     */
    public function __construct(
        private readonly HtmlResponder $responder
    ) {
    }

    /**
     * Invokes the FAQ page action.
     *
     * @param Request $request The incoming server request.
     * @param Response $response The outgoing server response.
     *
     * @return Response The response object with the rendered FAQ page.
     */
    public function __invoke(Request $request, Response $response): Response
    {
        return $this->responder->respond($response, 'pages/faq.html');
    }
}
