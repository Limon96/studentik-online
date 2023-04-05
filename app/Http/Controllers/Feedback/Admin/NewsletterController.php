<?php

namespace App\Http\Controllers\Feedback\Admin;

use App\Dtos\MailDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Newsletter\NewsletterSendRequest;
use App\Jobs\Newsletter\MailingJob;
use App\Mail\Newsletter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Loader\AnnotationFileLoader;

class NewsletterController extends Controller
{

    public function index()
    {
        $templates = $this->getMailTemplates();

        return view('newsletter.admin.index', compact(
            'templates'
        ));
    }

    public function send(NewsletterSendRequest $request)
    {
        $data = $request->validated();

        $template = $data['body'];

        if ($data['template']) {
            $template = view($data['template'])->render();
        }

        $mailDto = new MailDto();
        $mailDto->subject = $data['subject'];
        $mailDto->body = $template;
        $mailDto->className = Newsletter::class;

        $job = new MailingJob($mailDto, $data);
        $job->delay(10);
        $this->dispatch($job);

        return redirect()
            ->route('admin.newsletter.index')
            ->with(['success' => 'Успешно отправлено']);
    }

    private function getMailTemplates()
    {
        $view_path = config('view.paths')[0];
        $view_path .= '/mail/completed_template/';

        $files = File::files($view_path);

        $result = [];
        foreach ($files as $file) {
            $result[] = $this->getViewInfo($file);
        }

        return $result;
    }

    private function getViewInfo($file)
    {
        $basename = str_replace('.blade.php', '', $file->getRelativePathname());

        return [
            'name' => $basename,
            'value' => 'mail.completed_template.' . $basename
        ];
    }

}
