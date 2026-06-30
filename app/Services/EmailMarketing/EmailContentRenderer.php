<?php

namespace App\Services\EmailMarketing;

use App\Models\EmailMarketing\EmailMessage;
use Illuminate\Support\Str;

class EmailContentRenderer
{
    public function prepare(EmailMessage $message): void
    {
        $unsubscribeUrl = route('email-marketing.unsubscribe', $message->tracking_token);
        $openPixelUrl = route('email-marketing.track.open', $message->tracking_token);

        $html = $this->rewriteLinks($message->html_body, $message);
        $html .= '<img src="'.$openPixelUrl.'?m='.$message->id.'" alt="" width="1" height="1" style="width:1px;height:1px;border:0;opacity:0;display:block">';
        $html .= '<p style="font-size:12px;color:#667085">Se nao quiser receber estes e-mails, ';
        $html .= '<a href="'.$unsubscribeUrl.'">descadastre-se aqui</a>.</p>';

        $message->html_body = $html;
        $message->text_body = trim((string) $message->text_body."\n\nDescadastro: ".$unsubscribeUrl);
    }

    private function rewriteLinks(string $html, EmailMessage $message): string
    {
        return (string) preg_replace_callback('/href=["\']([^"\']+)["\']/i', function (array $matches) use ($message) {
            $url = $matches[1];

            if (Str::startsWith($url, ['#', 'mailto:', 'tel:'])) {
                return $matches[0];
            }

            return 'href="'.route('email-marketing.track.click', $message->tracking_token).'?url='.urlencode($url).'"';
        }, $html);
    }
}
