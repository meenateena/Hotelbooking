<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ContextIO;
use PHPMailer;
use AppBundle\WitAPI\WitAPI;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/message/failure", name="failure_notification")
     */
    public function failureNotificationAction()
    {
        return new Response("OK");
    }

    /**
     * @Route("/message/callback", name="callback")
     */
    public function subjectAction()
    {
        $contextIO = new ContextIO('190q5ano','YwshTnxWAbjLhjKN');

        $r = $contextIO->listAccounts();
        $accountId = null;
        foreach ($r->getData() as $account) {
            $email_address = join(", ", $account['email_addresses']);
            if($email_address == 'hotelbooking301@gmail.com'){
                $accountId = $account['id'];
                break;
            }
        }

        $args = array('to'=>'hotelbooking301@gmail.com', 'limit'=>1, 'include_body'=>1);
        $r = $contextIO->listMessages($accountId, $args);
        $from = "";
        $subject = "";
        $name = "";

        foreach ($r->getData() as $message) {
            $from = $message['addresses']['from']['email'];
            $name = $message['addresses']['from']['name'];
            $subject = $message['subject'];
            $messageContent = $message['body'][0]['content'];
            break;
        }

        $wit = new WitAPI(array(
            'access_token' => 'WTUYJTH242XEQ4AAOBMGKL2PIJGZNXPT',
        ));

        $response = $wit->text_query($messageContent);
        $result = $response['code'];

        $mail = new PHPMailer;

        $mail->From = 'hotelbooking301@gmail.com';
        $mail->FromName = 'Hotel Booking';
        $mail->addAddress($from, $name);

        $mail->isHTML(true);

        $mail->Subject = 'Re: ' . $subject;
        $mail->Body    = 'Hi ' . $name . '<br>' . $result .'<br><br>Best regards<br><b>Hotel Bookings</b>';
        $mail->AltBody = $result;

        if(!$mail->send()) {
            return new Response('Error: ' . $mail->ErrorInfo);
        } else {
            return new Response('OK: ' . $messageContent);
        }

    }

}
