<?php

use Alchemy\Phrasea\Application;
use Alchemy\Phrasea\Model\Entities\ApiApplication;
use Doctrine\ORM\EntityManager;

class patch_414PHRAS3555 implements patchInterface
{
    /** @var string */
    private $release = '4.1.4';

    /** @var array */
    private $concern = [base::APPLICATION_BOX, base::DATA_BOX];

    /**
     * {@inheritdoc}
     */
    public function get_release()
    {
        return $this->release;
    }

    /**
     * {@inheritdoc}
     */
    public function getDoctrineMigrations()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function require_all_upgrades()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function concern()
    {
        return $this->concern;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(base $base, Application $app)
    {
        if ($base->get_base_type() === base::DATA_BOX) {
            $this->patch_databox($base, $app);
        }
        elseif ($base->get_base_type() === base::APPLICATION_BOX) {
            $this->patch_appbox($base, $app);
        }

        return true;
    }

    private function patch_databox(base $databox, Application $app)
    {
    }

    private function patch_appbox(base $databox, Application $app)
    {
        /** @var EntityManager $em */
        $em = $app['orm.em'];

        $thirdPartyApplications = $app['repo.api-applications']->findAll();
        $listenedEvents = [
            'record.subdef.created',
            'record.subdef.creation_failed',
            'user.deleted',
            'user.registration.granted',
            'user.registration.rejected',
            'new_feed_entry',
            'order.created',
            'order.delivered',
            'order.denied'
        ];

        /** @var ApiApplication $thirdPartyApplication */
        foreach ($thirdPartyApplications as $thirdPartyApplication) {
            $thirdPartyApplication->setListenedEvents($listenedEvents);
            $em->persist($thirdPartyApplication);
        }

        $em->flush();
    }
}
