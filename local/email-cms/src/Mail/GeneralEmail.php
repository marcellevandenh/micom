<?php

namespace Surge\EmailCms\Mail;

use App\Models\Events;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Blade;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Surge\EmailCms\Models\EmailTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;


class GeneralEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public object $template,
        public array $placeholders,
        public string $recipient,
        public ?string $customBody = null
    ) {
        $this->validatePlaceholders();
    }

    public function envelope(): Envelope
    {
        $button_url = $this->replaceURLPlaceholders(
            $this->template->button_url,
            $this->placeholders
        );

        $renderData = $this->renderData();

        return new Envelope(
            subject: Blade::render($this->template->subject, $renderData),
            to: [$this->recipient]
        );
    }

    public function content(): Content
    {
        $button_url = $this->replaceURLPlaceholders(
            $this->template->button_url,
            $this->placeholders
        );
        $renderData = $this->renderData();
        // Logo handling
        $logo = null;
        if ($this->template->logo) {
            // $path = public_path('assets/nfansolid.png');
            // $logo = 'data:image/png;base64,' . base64_encode(file_get_contents($path));
            $logo = asset('assets/nfansolid.png');
        }

        $footer_path = public_path('assets/nfan_footer.png');
        // $footer_logo = 'data:image/png;base64,' . base64_encode(file_get_contents($footer_path));
        $footer_logo = asset('assets/nfan_footer.png');


        // 🎯 FIX: render body BEFORE passing to the view
        $bodyToRender = $this->customBody ?: $this->template->body;

        $renderedBody = Blade::render($bodyToRender, $this->placeholders);

        return new Content(
            view: 'emailcms::emails.general',
            with: [
                'body'        => $renderedBody,        // ← FIXED
                'button_url'  => $renderData['button_url'],
                'button_text' => $renderData['button_text'],
                'button'      => $renderData['button'],
                'logo'        => $logo,
                'footer_logo'  => $footer_logo
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function validatePlaceholders(): void
    {
        $required = array_unique(array_merge(
            $this->extractPlaceholders($this->template->subject),
            $this->extractPlaceholders($this->template->body)
        ));

        $allowedExtras = ['button', 'button_url', 'button_text'];

        $missing = array_diff($required, array_merge(array_keys($this->placeholders), $allowedExtras));

        // $missing = array_diff($required, array_keys($this->placeholders));

        if ($missing) {
            throw new \Exception("Missing placeholders: " . implode(', ', $missing));
        }
    }

    private function extractPlaceholders(string $text): array
    {
        preg_match_all('/{{\s*\$?([\w]+)\s*}}/', $text, $matches);
        return $matches[1]; // returns ['name', 'email', ...]
    }

    protected function replaceURLPlaceholders(?string $url, array $data): ?string
    {

        if (!$url) return null;
        // Replace {{key}} placeholders dynamically
        return preg_replace_callback('/\{\{\s*(\w+)\s*\}\}/', function ($matches) use ($data) {
            $key = $matches[1];
            return $data[$key] ?? $matches[0]; // leave unchanged if missing
        }, $url);
    }

    protected function renderData(): array
    {
        $button_url = $this->replaceURLPlaceholders(
            $this->template->button_url,
            $this->placeholders
        );

        return array_merge($this->placeholders, [
            'button_url'  => $button_url,
            'button_text' => $this->template->button_text ?? '',
            'button'      => ($button_url && $this->template->button_text)
                ? '<a class="button" style="display:inline-block;background:#145F48;color:#fff;padding:12px 20px;text-decoration:none;border-radius:6px;font-weight:bold;" href="'.$button_url.'">'
                    . e($this->template->button_text) .
                '</a>'
                : '',
        ]);
    }
}
