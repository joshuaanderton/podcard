<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class Sendable
{
    public function __construct($data)
    {
        $this->body = $data['body'];

        if (!empty($data['to_number'])) :
            $this->from_number = !empty($data['from_number']) ? $data['from_number'] : '17787712443';
            $this->to_number   = $data['to_number'];

            return $this;
        endif;

        $this->from_email     = (!empty($data['from_name']) ? str_replace(' ', '.', strtolower($data['from_name'])) : 'send') . '@upscri.be';
        $this->from_name      = $data['from_name'];

        $this->reply_to_email = $data['from_email'];
        $this->reply_to_name  = $this->from_name;

        $this->subject        = $data['subject'];
        $this->preheader      = !empty($data['preheader'])  ? $data['preheader']  : '';
        $this->recipients     = $data['recipients'];

        if (!\App::environment('production')) :
            Log::info('Trying to send email to ' . count($this->recipients) . ' email addresses from non-prod server. ' . route('user.unsubscribe', ['auth_token' => \App\User::find(1)->auth_token]));
            $admin_user = \App\User::find(1);
            $this->recipients = [[
                'to_name'     => $admin_user->fullName() . ' (' . env('APP_ENV') . ')',
                'to_email'    => $admin_user->email,
                'custom_args' => ['is_test' => 'true', 'user_id' => $admin_user->id, 'contact_id' => $admin_user->id],
                'custom_subs' => ['*|UNSUBSCRIBE|*' => "<a href=\"" . route('user.unsubscribe', ['auth_token' => $admin_user->auth_token]) . "\" target=\"_blank\">Unsubscribe</a>"]
            ]];
            $this->to_number = '7788235778';
        endif;
    }

    public function email()
    {
        $responses   = $message_ids = [];
        $sendgrid    = new \SendGrid(env('SENDGRID_API_KEY'));
        $from        = new \SendGrid\Mail\From($this->from_email, $this->from_name);
        $subject     = new \SendGrid\Mail\Subject($this->subject);
        $body_plain  = new \SendGrid\Mail\PlainTextContent($this->body);
        $body        = new \SendGrid\Mail\HtmlContent(view('mail.email-send', ['body' => $this->body, 'preheader' => $this->preheader])->render());

        try {
            foreach (array_chunk($this->recipients, 1000) as $rs) :
                $recipients = [];

                if (!empty($rs[0]['custom_subs'])) :
                    foreach ($rs[0]['custom_subs'] as $key => $val) :
                        $rs[0]['custom_subs'][$key] = strval($val);
                    endforeach;
                endif;

                $mail = new \SendGrid\Mail\Mail(
                    $from,
                    new \SendGrid\Mail\To($rs[0]['to_email'], !empty($rs[0]['to_name']) ? $rs[0]['to_name'] : ''),
                    $subject,
                    $body_plain,
                    $body,
                    $rs[0]['custom_subs']
                );

                $mail->setReplyTo(new \SendGrid\Mail\ReplyTo(
                    $this->reply_to_email,
                    $this->reply_to_name
                ));

                foreach($rs as $r) :
                    if (empty($r['to_email'])) continue;

                    $p = new \SendGrid\Mail\Personalization;

                    $p->addTo(new \SendGrid\Mail\To(
                        $r['to_email'],
                        !empty($r['to_name']) ? $r['to_name'] : ''
                    ));

                    if (!empty($r['custom_subs'])) :
                        foreach ($r['custom_subs'] as $key => $val) :
                            $p->addSubstitution($key, strval($val));
                        endforeach;
                    endif;

                    if (!empty($r['custom_args'])) :
                        foreach ($r['custom_args'] as $key => $val) :
                            $p->addCustomArg(new \SendGrid\Mail\CustomArg($key, strval($val)));
                        endforeach;
                    endif;

                    $mail->addPersonalization($p);
                endforeach;

                $resp = $sendgrid->send($mail);

                if (\App::environment('development')) :
                    Log::error(print_r($resp, true));
                else :
                    Log::error(join(' ', [
                        'Sending ' . count($this->recipients) . ' emails',
                        $resp->statusCode(),
                        print_r([$resp->body()], true)
                    ]));
                endif;

                foreach ($resp->headers() as $header) :
                    if (strpos($header, 'X-Message-Id') === false) continue;

                    $message_id = explode(':', $header);
                    if (!empty($message_id[1])) :
                        $message_ids[] = trim($message_id[1]);
                    endif;
                endforeach;

                $responses[] = $resp;
            endforeach;

            return [
                'count' => count($this->recipients),
                'responses' => $responses,
                'message_ids' => implode(',', $message_ids),
            ];

        } catch (\Exception $e) {
            Log::error(join(' ', [str_replace(base_path(), '', $e->getFile()) . ':' . $e->getLine(), $e->getMessage()]));
        }

        return false;
    }

    public function sms()
    {
        $twilio = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));

        $message = $twilio->messages->create(
            $this->to_number,
            ['from' => $this->from_number, 'body' => $this->body]
        );

        return $message;
    }

    public function events(\Request $request)
    {
        $events = json_decode($request->getContent());
        $email_events = [];

        foreach ($events as $event) :
            if (!empty($event->is_test)) continue;

            $email_event = [
                'email_id'            => (!empty($event->email_id))   ? $event->email_id   : false,
                'contact_id'          => (!empty($event->contact_id)) ? $event->contact_id : false,
                'url'                 => (!empty($event->url))        ? $event->url        : '',
                'name'                => $event->event,
                'created_at'          => date('Y-m-d h:i:s', $event->timestamp),
                'sendgrid_message_id' => $event->sg_message_id,
                'data'                => json_encode($event),
            ];

            // Deprecated meta keys
            if (!empty($event->wp_email_post_id)) $email_event['email_id']   = $event->wp_email_post_id;
            if (!empty($event->up_signup))        $email_event['contact_id'] = $event->up_signup;

            $email_events[] = $email_event;
        endforeach;

        return $email_events;
    }
}