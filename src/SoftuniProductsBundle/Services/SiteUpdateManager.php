<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 16.6.2017 г.
 * Time: 11:06
 */

namespace SoftuniProductsBundle\Services;


use Swift_Mailer;

class SiteUpdateManager
{
    private $mailer;

    public function __construct( Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notifyOfSiteUpdate()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Site update just happened!')
            ->setFrom('angelrovertov@gmail.com')
            ->setTo('gelerob@dir.bg')
            ->addPart(
                'Someone just updated the site. We told them: '
            );
        $this->mailer->send($message);

        return $message;
    }
}