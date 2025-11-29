<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Notices;

use App\Enums\NoticeType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notices\CreateRequest;
use App\Models\Notice;
use App\Services\FlashMessageService;
use Illuminate\Http\RedirectResponse;
use InvalidArgumentException;

/**
 * Class CreateSubmitController
 *
 * Handles the submission of the form for creating a new notice.
 * @package App\Http\Controllers\Admin\Notices
 */
class CreateSubmitController extends Controller
{
    /**
     * CreateSubmitController constructor.
     *
     * @param FlashMessageService $flash The service for displaying flash messages.
     */
    public function __construct(private readonly FlashMessageService $flash)
    {
    }

    /**
     * Handle the incoming request to create a new notice.
     *
     * Validates the request, creates a new notice under the given parent,
     * determines its type, saves it, and then redirects with a success message.
     *
     * @param CreateRequest $request The validated request containing the new notice's data.
     * @param Notice $parent The parent notice.
     * @return RedirectResponse A redirect response to the notice list.
     */
    public function __invoke(CreateRequest $request, Notice $parent): RedirectResponse
    {
        $notice = new Notice();

        $notice->parent_id = $parent->id;
        $notice->name = $request->validated('name');

        // if ($parent->type == NoticeType::Notice)
        // {
        //     $notice->type = NoticeType::Region;
        // }
        // elseif ($parent->type == NoticeType::Region)
        // {
        //     $notice->type = NoticeType::Unit;
        // }
        // else
        // {
        //     throw new InvalidArgumentException('Cannot create a sub-notice under the specified parent type.');
        // }

        $notice->save();

        Notice::resequenceAll();

        $this->flash->success('Notice Created Succesfully!');

        return redirect()->route('admin.notices.list');
    }
}
