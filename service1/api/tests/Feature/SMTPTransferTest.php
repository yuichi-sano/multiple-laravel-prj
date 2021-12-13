<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Artisan;
use packages\domain\basic\mail\Address;
use packages\domain\basic\mail\AddressList;
use packages\domain\basic\mail\Content;
use packages\domain\basic\mail\Header;
use packages\domain\basic\mail\Message;
use packages\domain\basic\mail\Subject;
use packages\infrastructure\transfer\Sample\SampleMailNotice;
use packages\infrastructure\transfer\SMTPSendRequest;
use packages\infrastructure\transfer\SMTPTransfer;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use packages\domain\basic\mail\Mail as MailObj;
class SMTPTransferTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('flyway:testing');
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_メールが送信されること()
    {
        // 実際にはメールを送らないように設定
        Mail::fake();
        // メールが送られていないことを確認
        Mail::assertNothingSent();

        $from = new Address(new MailObj('test@example.com'),'');
        $to = new AddressList();
        $to->add(new Address(new MailObj('test1@example.com'),''));
        $head = new Header($from, $to);

        $subject = new Subject('test');
        $content = new Content(['testでメールします']);
        $message = new Message($subject, $content);
        $mailable = new SampleMailNotice($head,$message);
        SMTPTransfer::send(new SMTPSendRequest($head,$message,$mailable));

        $email = 'test1@example.com';
        // メッセージが指定したユーザーに届いたことをアサート
        Mail::assertSent(SampleMailNotice::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });

        // メールが1回送信されたことをアサート
        Mail::assertSent(SampleMailNotice::class, 1);

    }
}
