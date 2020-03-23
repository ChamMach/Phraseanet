<?php

namespace Alchemy\Phrasea\Core\Event\Subscriber;

use Alchemy\Phrasea\Core\Event\Record\RecordEvents;
use Alchemy\Phrasea\Core\Event\Record\SubDefinitionCreatedEvent;
use Alchemy\Phrasea\Core\Event\Record\SubDefinitionCreationFailedEvent;
use Alchemy\Phrasea\Core\Event\Record\SubDefinitionsCreatedEvent;
use Alchemy\Phrasea\Model\Entities\WebhookEvent;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WebhookSubdefEventSubscriber implements EventSubscriberInterface
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function onSubdefCreated(SubDefinitionCreatedEvent $event)
    {
        $eventData = [
            'databox_id'    => $event->getRecord()->getDataboxId(),
            'record_id'     => $event->getRecord()->getRecordId(),
            'subdef'        => $event->getSubDefinitionName()
        ];

        $this->app['manipulator.webhook-event']->create(
            WebhookEvent::RECORD_SUBDEF_CREATED,
            WebhookEvent::RECORD_SUBDEF_TYPE,
            $eventData
        );
    }

    public function onSubdefCreationFailed(SubDefinitionCreationFailedEvent $event)
    {
        $eventData = [
            'databox_id'    => $event->getRecord()->getDataboxId(),
            'record_id'     => $event->getRecord()->getRecordId(),
            'subdef'        => $event->getSubDefinitionName()
        ];

        $this->app['manipulator.webhook-event']->create(
            WebhookEvent::RECORD_SUBDEF_FAILED,
            WebhookEvent::RECORD_SUBDEF_TYPE,
            $eventData
        );
    }

    public function onSubdefsCreated(SubDefinitionsCreatedEvent $event)
    {
        $eventData = [
            'databox_id'    => $event->getRecord()->getDataboxId(),
            'record_id'     => $event->getRecord()->getRecordId(),
            'subdef_count'  => count($event->getMedia())
        ];

        $this->app['manipulator.webhook-event']->create(
            WebhookEvent::RECORD_SUBDEFS_CREATED,
            WebhookEvent::RECORD_SUBDEF_TYPE,
            $eventData
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            RecordEvents::SUB_DEFINITION_CREATED            => 'onSubdefCreated',
            RecordEvents::SUB_DEFINITIONS_CREATED           => 'onSubdefsCreated',
            RecordEvents::SUB_DEFINITION_CREATION_FAILED    => 'onSubdefCreationFailed'
        ];
    }
}
