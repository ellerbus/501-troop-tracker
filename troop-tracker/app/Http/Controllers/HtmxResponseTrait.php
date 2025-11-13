<?php

declare(strict_types=1);

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

/**
 * Provides helper methods for building HTMX responses within a controller.
 *
 * This trait offers a fluent-like interface for setting various HTMX response headers,
 * managing triggers, and composing responses from multiple Blade fragments.
 *
 * @see https://htmx.org/reference/#response_headers
 */
trait HtmxResponseTrait
{
    /** @var array<string, string|array|null> */
    protected array $htmxTriggers = [];
    /** @var array<string, string|array|null> */
    protected array $htmxTriggersAfterSettle = [];
    /** @var array<string, string|array|null> */
    protected array $htmxTriggersAfterSwap = [];
    /** @var string[] */
    protected array $htmxFragments = [];

    /**
     * Creates a response that triggers a client-side redirect.
     *
     * @param string $url The URL to redirect to.
     */
    public function htmxRedirect(string $url): Response
    {
        return response()->noContent()->header('HX-Redirect', $url);
    }

    /**
     * Creates a response that tells the client to refresh the page.
     */
    public function htmxRefresh(): Response
    {
        return response()->noContent()->header('HX-Refresh', 'true');
    }

    /**
     * Creates a response that pushes a new URL into the browser's history.
     *
     * @param string $url The URL to push.
     */
    public function htmxPushUrl(string $url): Response
    {
        return response()->noContent()->header('HX-Push-Url', $url);
    }

    /**
     * Creates a response that replaces the current URL in the browser's history.
     *
     * @param string $url The URL to replace the current one with.
     */
    public function htmxReplaceUrl(string $url): Response
    {
        return response()->noContent()->header('HX-Replace-Url', $url);
    }

    /**
     * Creates a response that specifies how the response content should be swapped.
     *
     * @param string $option The swap option (e.g., 'innerHTML', 'outerHTML').
     */
    public function htmxReswap(string $option): Response
    {
        return response()->noContent()->header('HX-Reswap', $option);
    }

    /**
     * Creates a response that specifies a new target for the content update.
     *
     * @param string $selector A CSS selector for the new target.
     */
    public function htmxRetarget(string $selector): Response
    {
        return response()->noContent()->header('HX-Retarget', $selector);
    }

    /**
     * Creates a response for use with the `hx-location` attribute.
     *
     * @param string|array<mixed> $payload The location payload.
     * @throws \JsonException
     */
    public function htmxLocation(string|array $payload): Response
    {
        $value = is_array($payload)
            ? json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES)
            : $payload;

        return response()->noContent()->header('HX-Location', $value);
    }

    /**
     * Adds an event to be triggered on the client.
     *
     * @param string $key The event name.
     * @param string|array<mixed>|null $body The event payload.
     */
    public function addTrigger(string $key, string|array|null $body = null): void
    {
        $this->htmxTriggers[$key] = $body;
    }

    /**
     * Adds an event to be triggered after the settling phase.
     *
     * @param string $key The event name.
     * @param string|array<mixed>|null $body The event payload.
     */
    public function addTriggerAfterSettle(string $key, string|array|null $body = null): void
    {
        $this->htmxTriggersAfterSettle[$key] = $body;
    }

    /**
     * Adds an event to be triggered after the swap phase.
     *
     * @param string $key The event name.
     * @param string|array<mixed>|null $body The event payload.
     */
    public function addTriggerAfterSwap(string $key, string|array|null $body = null): void
    {
        $this->htmxTriggersAfterSwap[$key] = $body;
    }

    /**
     * Renders a Blade fragment and sets it as the sole content for the response.
     * This will overwrite any previously added fragments.
     *
     * @param string $view The name of the Blade view.
     * @param string $fragment The name of the `@section` within the view.
     * @param array<string, mixed> $data Data to pass to the view.
     */
    public function renderFragment(string $view, string $fragment, array $data = []): void
    {
        $this->htmxFragments = [View::make($view, $data)->renderSections()[$fragment] ?? ''];
    }

    /**
     * Renders and adds a Blade fragment to the response content.
     * This allows for multiple fragments to be returned (out-of-band swaps).
     *
     * @param string $view The name of the Blade view.
     * @param string $fragment The name of the `@section` within the view.
     * @param array<string, mixed> $data Data to pass to the view.
     */
    public function addFragment(string $view, string $fragment, array $data = []): void
    {
        $this->htmxFragments[] = View::make($view, $data)->renderSections()[$fragment] ?? '';
    }

    /**
     * Adds a pre-rendered HTML string to the response content.
     *
     * @param string $html The raw HTML to add.
     */
    public function addRenderedFragment(string $html): void
    {
        $this->htmxFragments[] = $html;
    }

    /**
     * Builds the final HTMX response with all queued fragments and triggers.
     *
     * @param array<string, string> $headers Additional headers to add to the response.
     * @throws \JsonException
     */
    public function buildHtmxResponse(array $headers = []): Response
    {
        $content = implode('', $this->htmxFragments);
        $response = response($content);

        if (!empty($this->htmxTriggers))
        {
            $headers['HX-Trigger'] = $this->encodeTriggers($this->htmxTriggers);
        }

        if (!empty($this->htmxTriggersAfterSettle))
        {
            $headers['HX-Trigger-After-Settle'] = $this->encodeTriggers($this->htmxTriggersAfterSettle);
        }

        if (!empty($this->htmxTriggersAfterSwap))
        {
            $headers['HX-Trigger-After-Swap'] = $this->encodeTriggers($this->htmxTriggersAfterSwap);
        }

        foreach ($headers as $key => $value)
        {
            $response->header($key, $value);
        }

        return $response;
    }

    /**
     * Encodes an array of triggers into the appropriate string format for HTMX headers.
     *
     * @param array<string, mixed> $triggers
     * @throws \JsonException
     */
    protected function encodeTriggers(array $triggers): string
    {
        $hasValues = collect($triggers)->filter(fn($v) => !is_null($v))->isNotEmpty();

        return $hasValues
            ? json_encode($triggers, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES)
            : implode(',', array_keys($triggers));
    }
}