<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ContextIO;

class DefaultController extends Controller
{
    /**
     * @Route("/app/example", name="index")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/app/context", name="context")
     */
    public function contextAction()
    {
        $contextIO = new ContextIO('190q5ano','YwshTnxWAbjLhjKN');
        $accountId = null;

        $result = '';
        // list your accounts
        $r = $contextIO->listAccounts();
        foreach ($r->getData() as $account) {
            if($account['id'] == '55c7953793ed1ab11e8b456a'){
                $result = join(", ", $account['email_addresses']) . "\n";
            }
        }
        return new Response($result);
    }

    /**
     * @Route("/app/content", name="content")
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
		
        $result = '';
        $args = array('to'=>'hotelbooking301@gmail.com', 'limit'=>10, 'include_body'=>1);
        $r = $contextIO->listMessages($accountId, $args);

        foreach ($r->getData() as $message) {
            $messageContent = $message['body'];
            $messageContent = $messageContent[0];
            $messageContent = $messageContent['content'];
            $result .= $messageContent;
            break;
        }

        return new Response($result);
    }

}
