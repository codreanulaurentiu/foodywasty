<?php
/**
 * Created by PhpStorm.
 * User: laurentiu
 * Date: 3/24/18
 * Time: 6:54 PM
 */

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\DependencyInjection\Container;

class MailService
{
    const ID = 'app.mail';

    const ORDER_NOTIFICATION = 'order-notification';

    public static $mails = array(
        self::ORDER_NOTIFICATION => 'mails/orderNotification.html.twig',
    );

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var String
     */
    protected $authKey;

    /**
     * @var String
     */
    protected $from;

    /**
     * @var String
     */
    protected $subject;

    /**
     * @var String
     */
    protected $body;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setAuthKey($authKey)
    {
        $this->authKey = $authKey;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function setFrom($from)
    {
        $this->from = $from;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param User $user
     * @param $mailType
     * @param array $params
     *
     * @todo check if we need $from parameter or always the same??
     */
    public function sendMail2(User $user, $mailType, $params = array())
    {
//        $to = $user->getEmail();
        $to = 'codreanulaurentiu@yahoo.com';


        $this->setupMail(
            $user,
            self::$mails[$mailType],
            $params
        );

        $message = \Swift_Message::newInstance()
            ->setSubject($this->getSubject())
            ->setFrom($this->getFrom())
            ->setTo($to)
            ->setBody(
                $this->getBody(),
                'text/html'
            );

        $transport = \Swift_SmtpTransport::newInstance('smtp.googlemail.com', 465, 'ssl')
            ->setUsername($this->getFrom())
            ->setPassword($this->getAuthKey());

        $mailer = \Swift_Mailer::newInstance($transport);

        $mailer->send($message);
    }

    /**
     * @param User $user
     * @param string $twigTemplate
     * @param array $params
     */
    public function setupMail($user, $twigTemplate, array $params = array())
    {
        /** @var Translator $translator */
//        $translator = $this->container->get('translator');
//        $translator->setLocale($user->getPreferredLocale());

        $body = $this->container->get('templating')->render($twigTemplate, $params);

        $start = strpos($body, '<title>') + strlen('<title>');
        $end = strpos($body, '</title>');
        $subject = substr($body, $start, $end - $start);

        $this->setSubject($subject);
        $this->setBody($body);
    }
}