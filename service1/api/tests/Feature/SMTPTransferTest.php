<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Artisan;
use packages\Domain\Basic\Mail\Address;
use packages\Domain\Basic\Mail\AddressList;
use packages\Domain\Basic\Mail\Content;
use packages\Domain\Basic\Mail\Header;
use packages\Domain\Basic\Mail\Message;
use packages\Domain\Basic\Mail\Subject;
use packages\Infrastructure\Transfer\Sample\SampleMailNotice;
use packages\Infrastructure\Transfer\SMTPSendRequest;
use packages\Infrastructure\Transfer\SMTPTransfer;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use packages\Domain\Basic\Mail\Mail as MailObj;
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
