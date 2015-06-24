<?php

/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2015 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Alchemy\Phrasea\SearchEngine\SearchEngineOptions;
use Alchemy\Phrasea\SearchEngine\SearchEngineSuggestion;
use Alchemy\Phrasea\Application;
use Alchemy\Phrasea\Border\File;
use Alchemy\Phrasea\Border\Attribute\Status;
use Alchemy\Phrasea\Border\Manager as BorderManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Alchemy\Phrasea\Core\PhraseaEvents;
use Alchemy\Phrasea\Core\Event\RecordEdit;

class API_V1_adapter extends API_V1_Abstract
{
    /**
     * API Version
     *
     * @var string
     */
    protected $version = '1.4.1';

    /**
     * Application context
     *
     * @var Application
     */
    protected $app;

    const OBJECT_TYPE_USER = 'http://api.phraseanet.com/api/objects/user';
    const OBJECT_TYPE_STORY = 'http://api.phraseanet.com/api/objects/story';
    const OBJECT_TYPE_STORY_METADATA_BAG = 'http://api.phraseanet.com/api/objects/story-metadata-bag';

    public static $extendedContentTypes = array(
        'json' => array('application/vnd.phraseanet.record-extended+json'),
        'yaml' => array('application/vnd.phraseanet.record-extended+yaml'),
        'jsonp' => array('application/vnd.phraseanet.record-extended+jsonp'),
    );

    /**
     * API constructor
     *
     * @param  Application    $app The application context
     * @return API_V1_adapter
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * Retrieve  http status error code according to the message
     *
     * @param Request $request
     * @param string  $error
     * @param string  $message
     *
     * @return API_V1_result `
     */
    public function get_error_message(Request $request, $error, $message)
    {
        $result = new API_V1_result($this->app, $request, $this);
        $result->set_error_message($error, $message);

        return $result;
    }

    /**
     * Retrieve  http status error message according to the http status error code
     * @param  Request       $request
     * @param  int           $code
     * @return API_V1_result
     */
    public function get_error_code(Request $request, $code)
    {
        $result = new API_V1_result($this->app, $request, $this);
        $result->set_error_code($code);

        return $result;
    }

    /**
     * Get the current version
     *
     * @return string
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Return an array of key-values informations about scheduler
     *
     * @param  Application    $app The silex application
     * @return \API_V1_result
     */
    public function get_scheduler(Application $app)
    {
        $result = new \API_V1_result($app, $app['request'], $this);

        if (false === $app['phraseanet.configuration']['main']['task-manager']['enabled']) {
            $ret = array('state' => 'disabled');
        } else {
            $taskManager = $app['task-manager'];
            $ret = $taskManager->getSchedulerState();
            $ret['state'] = $ret['status'];
            unset($ret['qdelay'], $ret['status']);
        }

        if (isset($ret['updated_on']) && null !== $ret['updated_on']) {
            $ret['updated_on'] = $ret['updated_on']->format(DATE_ATOM);
        }

        $result->set_datas(array('scheduler' => $ret));

        return $result;
    }

    /**
     * Get a list of phraseanet tasks
     *
     * @param Application $app The API silex application
     *
     * @return \API_V1_result
     */
    public function get_task_list(Application $app)
    {
        $result = new \API_V1_result($app, $app['request'], $this);

        $taskManager = $app['task-manager'];
        $tasks = $taskManager->getTasks();

        $ret = array();
        foreach ($tasks as $task) {
            $ret[] = $this->list_task($task);
        }

        $result->set_datas(array('tasks' => $ret));

        return $result;
    }

    /**
     * Get information about an identified task
     *
     * @param Application $app
     * @param             $taskId
     *
     * @return API_V1_result
     */
    public function get_task(Application $app, $taskId)
    {
        $result = new \API_V1_result($app, $app['request'], $this);

        $taskManager = $app['task-manager'];

        $ret = array(
            'task' => $this->list_task($taskManager->getTask($taskId))
        );

        $result->set_datas($ret);

        return $result;
    }

    /**
     * Start a specified task
     *
     * @param Application $app
     * @param             $taskId
     *
     * @return API_V1_result
     */
    public function start_task(Application $app, $taskId)
    {
        $result = new \API_V1_result($app, $app['request'], $this);

        $taskManager = $app['task-manager'];

        $task = $taskManager->getTask($taskId);
        if (!in_array($task->getState(), array(\task_abstract::STATE_TOSTART, \task_abstract::STATE_STARTED))) {
            $task->setState(\task_abstract::STATE_TOSTART);
        }

        $result->set_datas(array('task' => $this->list_task($task)));

        return $result;
    }

    /**
     * Stop a specified task
     *
     * @param Application $app
     * @param             $taskId
     *
     * @return API_V1_result
     */
    public function stop_task(Application $app, $taskId)
    {
        $result = new API_V1_result($app, $app['request'], $this);

        $taskManager = $app['task-manager'];

        $task = $taskManager->getTask($taskId);
        if (!in_array($task->getState(), array(\task_abstract::STATE_TOSTOP, \task_abstract::STATE_STOPPED))) {
            $task->setState(\task_abstract::STATE_TOSTOP);
        }
        $result->set_datas(array('task' => $this->list_task($task)));

        return $result;
    }

    /**
     * Update a task property
     *  - name
     *  - autostart
     *
     * @param Application $app
     * @param             $taskId
     *
     * @return API_V1_result
     * @throws API_V1_exception_badrequest
     */
    public function set_task_property(Application $app, $taskId)
    {
        $result = new API_V1_result($app, $app['request'], $this);

        $title = $app['request']->get('title');
        $autostart = $app['request']->get('autostart');

        if (null === $title && null === $autostart) {
            throw new \API_V1_exception_badrequest();
        }

        $taskManager = $app['task-manager'];

        $task = $taskManager->getTask($taskId);

        if ($title) {
            $task->setTitle($title);
        }

        if ($autostart) {
            $task->setActive(!!$autostart);
        }

        $result->set_datas(array('task' => $this->list_task($task)));

        return $result;
    }

    /**
     * Provide
     *  - cache information
     *  - global values information
     *  - configuration information
     *
     * @param Application $app
     *
     * @return API_V1_result
     */
    public function get_phraseanet_monitor(Application $app)
    {
        $result = new API_V1_result($app, $app['request'], $this);

        $result->set_datas(array_merge($this->get_config_info($app), $this->get_cache_info($app), $this->get_gv_info($app)));

        return $result;
    }

    /**
     * Get detailed about databoxes
     *
     * @param Request $request
     *
     * @return API_V1_result
     */
    public function get_databoxes(Request $request)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $result->set_datas(array("databoxes" => $this->list_databoxes()));

        return $result;
    }

    /**
     * Get an API_V1_result containing the collections of a databox
     *
     * @param Request $request
     * @param int     $databox_id
     *
     * @return API_V1_result
     */
    public function get_databox_collections(Request $request, $databox_id)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $result->set_datas(
                array(
                    "collections" => $this->list_databox_collections(
                            $this->app['phraseanet.appbox']->get_databox($databox_id)
                    )
                )
        );

        return $result;
    }

    /**
     * Get an API_V1_result containing the status of a databox
     *
     * @param Request $request
     * @param int     $databox_id
     *
     * @return API_V1_result
     */
    public function get_databox_status(Request $request, $databox_id)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $result->set_datas(
            array(
                "status" => $this->list_databox_status($this->app['phraseanet.appbox']->get_databox($databox_id)->get_statusbits())
            )
        );

        return $result;
    }

    /**
     * Get an API_V1_result containing the metadatas of a databox
     *
     * @param Request $request
     * @param int     $databox_id
     *
     * @return API_V1_result
     */
    public function get_databox_metadatas(Request $request, $databox_id)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $result->set_datas(
                array(
                    "document_metadatas" =>
                    $this->list_databox_metadatas_fields(
                            $this->app['phraseanet.appbox']->get_databox($databox_id)
                                    ->get_meta_structure()
                    )
                )
        );

        return $result;
    }

    /**
     * Get an API_V1_result containing the terms of use of a databox
     *
     * @param Request $request
     * @param int     $databox_id
     *
     * @return API_V1_result
     */
    public function get_databox_terms(Request $request, $databox_id)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $result->set_datas(array(
            "termsOfUse" => $this->list_databox_terms($this->app['phraseanet.appbox']->get_databox($databox_id))
        ));

        return $result;
    }

    /**
     * Get detailed information about a record caption
     *
     * @param Request $request
     * @param         $databox_id
     * @param         $record_id
     *
     * @return API_V1_result
     */
    public function caption_records(Request $request, $databox_id, $record_id)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $record = $this->app['phraseanet.appbox']->get_databox($databox_id)->get_record($record_id);
        $fields = $record->get_caption()->get_fields();

        $ret = array('caption_metadatas' => array());

        foreach ($fields as $field) {
            $ret['caption_metadatas'][] = array(
                'meta_structure_id' => $field->get_meta_struct_id(),
                'name'              => $field->get_name(),
                'value'             => $field->get_serialized_values(";"),
            );
        }

        $result->set_datas($ret);

        return $result;
    }

    /**
     * Upload a record
     *
     * @param Application $app
     * @param Request     $request
     *
     * @return API_V1_result
     * @throws API_V1_exception_forbidden
     * @throws API_V1_exception_badrequest
     */
    public function add_record(Application $app, Request $request)
    {
        if (count($request->files->get('file')) == 0) {
            throw new API_V1_exception_badrequest('Missing file parameter');
        }

        if (!$request->files->get('file') instanceof Symfony\Component\HttpFoundation\File\UploadedFile) {
            throw new API_V1_exception_badrequest('You can upload one file at time');
        }

        $file = $request->files->get('file');
        /* @var $file Symfony\Component\HttpFoundation\File\UploadedFile */

        if (!$file->isValid()) {
            throw new API_V1_exception_badrequest('Datas corrupted, please try again');
        }

        if (!$request->get('base_id')) {
            throw new API_V1_exception_badrequest('Missing base_id parameter');
        }

        $collection = \collection::get_from_base_id($this->app, $request->get('base_id'));

        if (!$app['authentication']->getUser()->ACL()->has_right_on_base($request->get('base_id'), 'canaddrecord')) {
            throw new API_V1_exception_forbidden(sprintf('You do not have access to collection %s', $collection->get_label($this->app['locale.I18n'])));
        }

        $media = $app['mediavorus']->guess($file->getPathname());

        $Package = new File($this->app, $media, $collection, $file->getClientOriginalName());

        if ($request->get('status')) {
            $Package->addAttribute(new Status($app, $request->get('status')));
        }

        $session = new Entities\LazaretSession();
        $session->setUsrId($app['authentication']->getUser()->get_id());

        $app['EM']->persist($session);
        $app['EM']->flush();

        $reasons = $output = null;

        $callback = function ($element, $visa, $code) use (&$reasons, &$output) {
            if (!$visa->isValid()) {
                $reasons = array();

                foreach ($visa->getResponses() as $response) {
                    $reasons[] = $response->getMessage();
                }
            }

            $output = $element;
        };

        switch ($request->get('forceBehavior')) {
            case '0' :
                $behavior = BorderManager::FORCE_RECORD;
                break;
            case '1' :
                $behavior = BorderManager::FORCE_LAZARET;
                break;
            case null:
                $behavior = null;
                break;
            default:
                throw new API_V1_exception_badrequest(sprintf('Invalid forceBehavior value `%s`', $request->get('forceBehavior')));
                break;
        }
        $nosubdef = $request->get('nosubdefs')==='' || \p4field::isyes($request->get('nosubdefs'));
        $app['border-manager']->process($session, $Package, $callback, $behavior, $nosubdef);

        $ret = array(
            'entity' => null,
        );

        if ($output instanceof \record_adapter) {
            $ret['entity'] = '0';
            $ret['url'] = '/records/' . $output->get_sbas_id() . '/' . $output->get_record_id() . '/';
            $app['phraseanet.SE']->addRecord($output);

            $app['dispatcher']->dispatch(PhraseaEvents::RECORD_UPLOAD, new RecordEdit($output));
        }
        if ($output instanceof \Entities\LazaretFile) {
            $ret['entity'] = '1';
            $ret['url'] = '/quarantine/item/' . $output->getId() . '/';
        }

        $result = new API_V1_result($this->app, $request, $this);

        $result->set_datas($ret);

        return $result;
    }


    public function substitute_subdef(Application $app, Request $request)
    {
        $ret = array();

        if (count($request->files->get('file')) == 0) {
            throw new API_V1_exception_badrequest('Missing file parameter');
        }

        if (!$request->files->get('file') instanceof Symfony\Component\HttpFoundation\File\UploadedFile) {
            throw new API_V1_exception_badrequest('You can upload one file at time');
        }

        $file = $request->files->get('file');
        // @var $file Symfony\Component\HttpFoundation\File\UploadedFile

        if (!$file->isValid()) {
            throw new API_V1_exception_badrequest('Datas corrupted, please try again');
        }

        if (!$request->get('databox_id')) {
            throw new API_V1_exception_badrequest('Missing databox_id parameter');
        }
        if (!$request->get('record_id')) {
            throw new API_V1_exception_badrequest('Missing record_id parameter');
        }
        if (!$request->get('name')) {
            throw new API_V1_exception_badrequest('Missing name parameter');
        }

        $media = $app['mediavorus']->guess($file->getPathname());

        // @var $record \record_adapter
        $record = $this->app['phraseanet.appbox']->get_databox($request->get('databox_id'))->get_record($request->get('record_id'));
        $base_id = $record->get_base_id();
        $collection = \collection::get_from_base_id($this->app, $base_id);

        if (!$app['authentication']->getUser()->ACL()->has_right_on_base($base_id, 'canaddrecord')) {
            throw new API_V1_exception_forbidden(sprintf('You do not have access to collection %s', $collection->get_label($this->app['locale.I18n'])));
        }

        $adapt = $request->get('adapt')===null || !(\p4field::isno($request->get('adapt')));
        $ret['adapt'] = $adapt;
        $record->substitute_subdef($request->get('name'), $media, $app, $adapt);
        foreach ($record->get_embedable_medias() as $name => $media) {
            if ($name == $request->get('name') &&
                null !== ($subdef = $this->list_embedable_media($record, $media, $this->app['phraseanet.registry']))) {
                    $ret[] = $subdef;
            }
        }

        $result = new API_V1_result($this->app, $request, $this);

        $result->set_datas($ret);

        return $result;
    }

    /**
     * List quarantined items
     *
     * @param Application $app
     * @param Request     $request
     *
     * @return API_V1_result
     */
    public function list_quarantine(Application $app, Request $request)
    {
        $offset_start = max($request->get('offset_start', 0), 0);
        $per_page = min(max($request->get('per_page', 10), 1), 20);

        $baseIds = array_keys($app['authentication']->getUser()->ACL()->get_granted_base(array('canaddrecord')));

        $lazaretFiles = array();

        if (count($baseIds) > 0) {
            $lazaretRepository = $app['EM']->getRepository('Entities\LazaretFile');
            $lazaretFiles = $lazaretRepository->findPerPage($baseIds, $offset_start, $per_page);
        }

        $ret = array();

        foreach ($lazaretFiles as $lazaretFile) {
            $ret[] = $this->list_lazaret_file($lazaretFile);
        }

        $result = new API_V1_result($this->app, $request, $this);

        $result->set_datas(array(
            'offset_start'     => $offset_start,
            'per_page'         => $per_page,
            'quarantine_items' => $ret,
        ));

        return $result;
    }

    /**
     * Retrieve detailed information about a quarantined item
     *
     * @param             $lazaret_id
     * @param Application $app
     * @param Request     $request
     *
     * @return API_V1_result
     * @throws API_V1_exception_forbidden
     * @throws API_V1_exception_notfound
     */
    public function list_quarantine_item($lazaret_id, Application $app, Request $request)
    {
        $lazaretFile = $app['EM']->find('Entities\LazaretFile', $lazaret_id);

        if (null === $lazaretFile) {
            throw new \API_V1_exception_notfound(sprintf('Lazaret file id %d not found', $lazaret_id));
        }

        if (!$app['authentication']->getUser()->ACL()->has_right_on_base($lazaretFile->getBaseId(), 'canaddrecord')) {
            throw new \API_V1_exception_forbidden('You do not have access to this quarantine item');
        }

        $ret = array('quarantine_item' => $this->list_lazaret_file($lazaretFile));

        $result = new API_V1_result($this->app, $request, $this);

        $result->set_datas($ret);

        return $result;
    }

    /**
     * Search for results
     *
     * @param  Request        $request
     * @return \API_V1_result
     */
    public function search(Request $request)
    {
        $result = new API_V1_result($this->app, $request, $this);

        list($ret, $search_result) = $this->prepare_search_request($request);

        $ret['results'] = array('records' => array(), 'stories' => array());

        foreach ($search_result->getResults() as $record) {
            if ($record->is_grouping()) {
                $ret['results']['stories'][] = $this->list_story($record, $request->get('_extended'));
            } else {
                $ret['results']['records'][] = $this->list_record($record, $request->get('_extended'));
            }
        }

        /**
         * @todo donner des highlights
         */
        $result->set_datas($ret);

        return $result;
    }

    /**
     * Get an API_V1_result containing the results of a records search
     *
     * Deprecated in favor of search
     *
     * @param Request $request
     *
     * @return API_V1_result
     */
    public function search_records(Request $request)
    {
        $result = new API_V1_result($this->app, $request, $this);

        list($ret, $search_result) = $this->prepare_search_request($request);

        foreach ($search_result->getResults() as $record) {
            $ret['results'][] = $this->list_record($record, $request->get('_extended'));
        }

        /**
         * @todo donner des highlights
         */
        $result->set_datas($ret);

        return $result;
    }

    /**
     * Get an API_V1_result containing the baskets where the record is in
     *
     * @param Request $request
     * @param int     $databox_id
     * @param int     $record_id
     *
     * @return API_V1_result
     */
    public function get_record_related(Request $request, $databox_id, $record_id)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $that = $this;
        $baskets = array_map(function ($basket) use ($that) {
            return $that->list_basket($basket);
        }, (array) $this->app['phraseanet.appbox']
            ->get_databox($databox_id)
            ->get_record($record_id)
            ->get_container_baskets($this->app['EM'], $this->app['authentication']->getUser())
        );

        $record = $this->app['phraseanet.appbox']->get_databox($databox_id)->get_record($record_id);

        $stories = array_map(function ($story) use ($that, $request) {
            return $that->list_story($story, $request->get('_extended'));
        }, array_values($record->get_grouping_parents()->get_elements()));

        $result->set_datas(array(
            "baskets" => $baskets,
            "stories" => $stories,
        ));

        return $result;
    }

    /**
     * Get an API_V1_result containing the record metadatas
     *
     * @param Request $request
     * @param int     $databox_id
     * @param int     $record_id
     *
     * @return API_V1_result
     */
    public function get_record_metadatas(Request $request, $databox_id, $record_id)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $record = $this->app['phraseanet.appbox']->get_databox($databox_id)->get_record($record_id);

        $result->set_datas(array(
            "record_metadatas" => $this->list_record_caption($record->get_caption())
        ));

        return $result;
    }

    /**
     * Get an API_V1_result containing the record status
     *
     * @param Request $request
     * @param int     $databox_id
     * @param int     $record_id
     *
     * @return API_V1_result
     */
    public function get_record_status(Request $request, $databox_id, $record_id)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $databox = $this->app['phraseanet.appbox']->get_databox($databox_id);
        $record = $databox->get_record($record_id);

        $result->set_datas(array("status" => $this->list_record_status($databox, $record->get_status())));

        return $result;
    }

    /**
     * Get an API_V1_result containing the record embed files
     *
     * @param Request $request
     * @param int     $databox_id
     * @param int     $record_id
     *
     * @return API_V1_result
     */
    public function get_record_embed(Request $request, $databox_id, $record_id)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $record = $this->app['phraseanet.appbox']->get_databox($databox_id)->get_record($record_id);

        $ret = array();

        $devices = $request->get('devices', array());
        $mimes = $request->get('mimes', array());

        foreach ($record->get_embedable_medias($devices, $mimes) as $name => $media) {
            if (null !== $subdef = $this->list_embedable_media($record, $media, $this->app['phraseanet.registry'])) {
                $ret[] = $subdef;
            }
        }

        $result->set_datas(array("embed" => $ret));

        return $result;
    }

    /**
     * Get an API_V1_result containing the story embed files
     *
     * @param Request $request
     * @param int     $databox_id
     * @param int     $record_id
     *
     * @return API_V1_result
     */
    public function get_story_embed(Request $request, $databox_id, $record_id)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $record = $this->app['phraseanet.appbox']
                    ->get_databox($databox_id)
                    ->get_record($record_id);

        $ret = array();

        $devices = $request->get('devices', array());
        $mimes = $request->get('mimes', array());

        foreach ($record->get_embedable_medias($devices, $mimes) as $name => $media) {
            if (null !== $subdef = $this->list_embedable_media($record, $media, $this->app['phraseanet.registry'])) {
                $ret[] = $subdef;
            }
        }

        $result->set_datas(array("embed" => $ret));

        return $result;
    }

    public function set_record_metadatas(Request $request, $databox_id, $record_id)
    {
        $result = new API_V1_result($this->app, $request, $this);
        $record = $this->app['phraseanet.appbox']->get_databox($databox_id)->get_record($record_id);

        try {
            $metadatas = $request->get('metadatas');

            if (!is_array($metadatas)) {
                throw new Exception('Metadata should be an array');
            }

            foreach ($metadatas as $metadata) {
                if (!is_array($metadata)) {
                    throw new Exception('Each Metadata value should be an array');
                }
            }

            $record->set_metadatas($metadatas);

            $this->app['dispatcher']->dispatch(PhraseaEvents::RECORD_EDIT, new RecordEdit($record));

            $result->set_datas(array("record_metadatas" => $this->list_record_caption($record->get_caption())));
        } catch (\Exception $e) {
            $result->set_error_message(API_V1_result::ERROR_BAD_REQUEST, _('An error occurred'));
        }

        return $result;
    }

    /**
     * Set status to a record
     *
     * @param Request $request
     * @param         $databox_id
     * @param         $record_id
     *
     * @return API_V1_result
     */
    public function set_record_status(Request $request, $databox_id, $record_id)
    {
        $result = new API_V1_result($this->app, $request, $this);
        $databox = $this->app['phraseanet.appbox']->get_databox($databox_id);
        $record = $databox->get_record($record_id);
        $status_bits = $databox->get_statusbits();

        try {
            $status = $request->get('status');

            $data = strrev($record->get_status());

            if (!is_array($status)) {
                throw new API_V1_exception_badrequest();
            }
            foreach ($status as $n => $value) {
                if ($n > 31 || $n < 4) {
                    throw new API_V1_exception_badrequest();
                }
                if (!in_array($value, array('0', '1'))) {
                    throw new API_V1_exception_badrequest();
                }
                if (!isset($status_bits[$n])) {
                    throw new API_V1_exception_badrequest ();
                }

                $data = substr($data, 0, ($n)) . $value . substr($data, ($n + 2));
            }
            $data = strrev($data);

            $record->set_binary_status($data);

            $this->app['phraseanet.SE']->updateRecord($record);

            $this->app['dispatcher']->dispatch(PhraseaEvents::RECORD_EDIT, new RecordEdit($record));

            $result->set_datas(array("status" => $this->list_record_status($databox, $record->get_status())));
        } catch (\Exception $e) {
            $result->set_error_message(API_V1_result::ERROR_BAD_REQUEST, _('An error occurred'));
        }

        return $result;
    }

    /**
     * Move a record to another collection
     *
     * @param Request $request
     * @param         $databox_id
     * @param         $record_id
     *
     * @return API_V1_result
     * @throws API_V1_exception_unauthorized
     */
    public function set_record_collection(Request $request, $databox_id, $record_id)
    {
        if (!$this->app['authentication']->getUser()->ACL()->has_right_on_base($request->get('base_id'), 'canaddrecord')) {
            throw new \API_V1_exception_unauthorized('You are not authorized');
        }
        $result = new API_V1_result($this->app, $request, $this);
        $databox = $this->app['phraseanet.appbox']->get_databox($databox_id);
        $record = $databox->get_record($record_id);

        try {
            $collection = collection::get_from_base_id($this->app, $request->get('base_id'));
            $record->move_to_collection($collection, $this->app['phraseanet.appbox']);
            $result->set_datas(array("record" => $this->list_record($record, $request->get('_extended'))));
        } catch (\Exception $e) {
            $result->set_error_message(API_V1_result::ERROR_BAD_REQUEST, $e->getMessage());
        }

        return $result;
    }

    /**
     * Return detailed information about one record
     *
     * @param  Request       $request
     * @param  int           $databox_id
     * @param  int           $record_id
     * @return API_V1_result
     */
    public function get_record(Request $request, $databox_id, $record_id)
    {
        $result = new API_V1_result($this->app, $request, $this);
        $databox = $this->app['phraseanet.appbox']->get_databox($databox_id);
        try {
            $record = $databox->get_record($record_id);
            $result->set_datas(array('record' => $this->list_record($record, $request->get('_extended'))));
        } catch (NotFoundHttpException $e) {
            $result->set_error_message(API_V1_result::ERROR_BAD_REQUEST, _('Record Not Found'));
        } catch (\Exception $e) {
            $result->set_error_message(API_V1_result::ERROR_BAD_REQUEST, _('An error occurred'));
        }

        return $result;
    }

    /**
     * Return detailed information about one story
     *
     * @param  Request       $request
     * @param  int           $databox_id
     * @param  int           $story_id
     * @return API_V1_result
     */
    public function get_story(Request $request, $databox_id, $story_id)
    {
        $result = new API_V1_result($this->app, $request, $this);
        $databox = $this->app['phraseanet.appbox']->get_databox($databox_id);
        try {
            $story = $databox->get_record($story_id);
            $result->set_datas(array('story' => $this->list_story($story, $request->get('_extended'))));
        } catch (NotFoundHttpException $e) {
            $result->set_error_message(API_V1_result::ERROR_BAD_REQUEST, _('Story Not Found'));
        } catch (\Exception $e) {
            $result->set_error_message(API_V1_result::ERROR_BAD_REQUEST, _('An error occurred'));
        }

        return $result;
    }

    /**
     * Return the baskets list of the authenticated user
     *
     * @param  Request       $request
     * @return API_V1_result
     */
    public function search_baskets(Request $request)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $usr_id = $session = $this->app['authentication']->getUser()->get_id();

        $result->set_datas(array('baskets' => $this->list_baskets($usr_id)));

        return $result;
    }

    /**
     * Return a baskets list
     *
     * @param  int   $usr_id
     * @return array
     */
    protected function list_baskets($usr_id)
    {
        $repo = $this->app['EM']->getRepository('\Entities\Basket');
        /* @var $repo \Repositories\BasketRepository */

        $baskets = $repo->findActiveByUser($this->app['authentication']->getUser());

        $ret = array();
        foreach ($baskets as $basket) {
            $ret[] = $this->list_basket($basket);
        }

        return $ret;
    }

    /**
     * Create a new basket
     *
     * @param Request $request
     *
     * @return API_V1_result
     * @throws API_V1_exception_badrequest
     */
    public function create_basket(Request $request)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $name = $request->get('name');

        if (trim(strip_tags($name)) === '') {
            throw new API_V1_exception_badrequest('Missing basket name parameter');
        }

        $Basket = new \Entities\Basket();
        $Basket->setOwner($this->app['authentication']->getUser());
        $Basket->setName($name);

        $this->app['EM']->persist($Basket);
        $this->app['EM']->flush();

        $result->set_datas(array("basket" => $this->list_basket($Basket)));

        return $result;
    }

    /**
     * Delete a basket
     *
     * @param  Request $request
     * @param  int     $basket_id
     * @return array
     */
    public function delete_basket(Request $request, $basket_id)
    {
        $repository = $this->app['EM']->getRepository('\Entities\Basket');

        /* @var $repository \Repositories\BasketRepository */

        $Basket = $repository->findUserBasket($this->app, $basket_id, $this->app['authentication']->getUser(), true);
        $this->app['EM']->remove($Basket);
        $this->app['EM']->flush();

        return $this->search_baskets($request);
    }

    /**
     * Retrieve a basket
     *
     * @param  Request       $request
     * @param  int           $basket_id
     * @return API_V1_result
     */
    public function get_basket(Request $request, $basket_id)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $repository = $this->app['EM']->getRepository('\Entities\Basket');

        /* @var $repository \Repositories\BasketRepository */

        $Basket = $repository->findUserBasket($this->app, $basket_id, $this->app['authentication']->getUser(), false);

        $result->set_datas(
                array(
                    "basket"          => $this->list_basket($Basket),
                    "basket_elements" => $this->list_basket_content($Basket, $request->get('_extended'))
                )
        );

        return $result;
    }

    /**
     * Retrieve elements of one basket
     *
     * @param \Entities\Basket $Basket
     * @param bool             $extended
     *
     * @return array
     */
    protected function list_basket_content(\Entities\Basket $Basket, $extended = false)
    {
        $ret = array();

        foreach ($Basket->getElements() as $basket_element) {
            $ret[] = $this->list_basket_element($basket_element, $extended);
        }

        return $ret;
    }

    /**
     * Retrieve detailed information about a basket element
     *
     * @param \Entities\BasketElement $basket_element
     * @param bool                    $extended
     *
     * @return array
     */
    protected function list_basket_element(\Entities\BasketElement $basket_element, $extended = false)
    {
        $ret = array(
            'basket_element_id' => $basket_element->getId(),
            'order'             => $basket_element->getOrd(),
            'record'            => $this->list_record($basket_element->getRecord($this->app), $extended),
            'validation_item'   => null != $basket_element->getBasket()->getValidation(),
        );

        if ($basket_element->getBasket()->getValidation()) {
            $choices = array();
            $agreement = null;
            $note = '';

            foreach ($basket_element->getValidationDatas() as $validation_datas) {
                $participant = $validation_datas->getParticipant();
                $user = $participant->getUser($this->app);
                /* @var $validation_datas Entities\ValidationData */
                $choices[] = array(
                    'validation_user' => array(
                        'usr_id'         => $user->get_id(),
                        'usr_name'       => $user->get_display_name(),
                        'confirmed'      => $participant->getIsConfirmed(),
                        'can_agree'      => $participant->getCanAgree(),
                        'can_see_others' => $participant->getCanSeeOthers(),
                        'readonly'       => $user->get_id() != $this->app['authentication']->getUser()->get_id(),
                        'user'           => $this->list_user($user),
                    ),
                    'agreement'      => $validation_datas->getAgreement(),
                    'updated_on'     => $validation_datas->getUpdated()->format(DATE_ATOM),
                    'note'           => null === $validation_datas->getNote() ? '' : $validation_datas->getNote(),
                );

                if ($user->get_id() == $this->app['authentication']->getUser()->get_id()) {
                    $agreement = $validation_datas->getAgreement();
                    $note = null === $validation_datas->getNote() ? '' : $validation_datas->getNote();
                }

                $ret['validation_choices'] = $choices;
            }

            $ret['agreement'] = $agreement;
            $ret['note'] = $note;
        }

        return $ret;
    }

    /**
     * Change the name of one basket
     *
     * @param  Request       $request
     * @param  int           $basket_id
     * @return API_V1_result
     */
    public function set_basket_title(Request $request, $basket_id)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $name = $request->get('name');

        $repository = $this->app['EM']->getRepository('\Entities\Basket');

        /* @var $repository \Repositories\BasketRepository */

        $Basket = $repository->findUserBasket($this->app, $basket_id, $this->app['authentication']->getUser(), true);
        $Basket->setName($name);

        $this->app['EM']->merge($Basket);
        $this->app['EM']->flush();

        $result->set_datas(array("basket" => $this->list_basket($Basket)));

        return $result;
    }

    /**
     * Change the description of one basket
     *
     * @param  Request       $request
     * @param  type          $basket_id
     * @return API_V1_result
     */
    public function set_basket_description(Request $request, $basket_id)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $desc = $request->get('description');

        $repository = $this->app['EM']->getRepository('\Entities\Basket');

        /* @var $repository \Repositories\BasketRepository */

        $Basket = $repository->findUserBasket($this->app, $basket_id, $this->app['authentication']->getUser(), true);
        $Basket->setDescription($desc);

        $this->app['EM']->merge($Basket);
        $this->app['EM']->flush();

        $result->set_datas(array("basket" => $this->list_basket($Basket)));

        return $result;
    }

    /**
     * List all available feeds
     *
     * @param  Request       $request
     * @param  User_Adapter  $user
     * @return API_V1_result
     */
    public function search_publications(Request $request, User_Adapter $user)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $coll = Feed_Collection::load($this->app, $user);

        $data = array();
        foreach ($coll->get_feeds() as $feed) {
            $data[] = $this->list_publication($feed, $user);
        }

        $result->set_datas(array("feeds" => $data));

        return $result;
    }

    /**
     * @todo
     *
     * @param Request $request
     * @param int     $publication_id
     */
    public function remove_publications(Request $request, $publication_id)
    {

    }

    /**
     * Retrieve one feed
     *
     * @param  Request       $request
     * @param  int           $publication_id
     * @param  User_Adapter  $user
     * @return API_V1_result
     */
    public function get_publication(Request $request, $publication_id, User_Adapter $user)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $feed = Feed_Adapter::load_with_user($this->app, $user, $publication_id);

        $offset_start = (int) $request->get('offset_start', 0);
        $per_page = (int) $request->get('per_page', 5);

        $per_page = (($per_page >= 1) && ($per_page <= 100)) ? $per_page : 100;

        $data = array(
            'feed'         => $this->list_publication($feed, $user),
            'offset_start' => $offset_start,
            'per_page'     => $per_page,
            'entries'      => $this->list_publications_entries($feed, $request->get('_extended'), $offset_start, $per_page),
        );

        $result->set_datas($data);

        return $result;
    }

    /**
     * Aggregate feed entries
     *
     * @param Request      $request
     * @param User_Adapter $user
     *
     * @return API_V1_result
     */
    public function get_publications(Request $request, User_Adapter $user)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $restrictions = (array) ($request->get('feeds') ? : array());

        $feed = Feed_Aggregate::load_with_user($this->app, $user, $restrictions);

        $offset_start = (int) ($request->get('offset_start') ? : 0);
        $per_page = (int) ($request->get('per_page') ? : 5);

        $per_page = (($per_page >= 1) && ($per_page <= 20)) ? $per_page : 5;

        $result->set_datas(array(
            'total_entries' => $feed->get_count_total_entries(),
            'offset_start'  => $offset_start,
            'per_page'      => $per_page,
            'entries'       => $this->list_publications_entries($feed, $request->get('_extended'), $offset_start, $per_page),
        ));

        return $result;
    }

    /**
     * Retrieve information a feed entry
     *
     * @param Request      $request
     * @param              $entry_id
     * @param User_Adapter $user
     *
     * @return API_V1_result
     * @throws API_V1_exception_forbidden
     */
    public function get_feed_entry(Request $request, $entry_id, User_Adapter $user)
    {
        $result = new API_V1_result($this->app, $request, $this);

        $entry = Feed_Entry_Adapter::load_from_id($this->app, $entry_id);

        $collection = $entry->get_feed()->get_collection();

        if (null !== $collection && !$user->ACL()->has_access_to_base($collection->get_base_id())) {
            throw new \API_V1_exception_forbidden('You have not access to the parent feed');
        }

        $data = array('entry' => $this->list_publication_entry($entry),);

        $result->set_datas($data);

        return $result;
    }

    /**
     * Retrieve detailed information about one feed
     *
     * @param  Feed_Adapter $feed
     * @param  type         $user
     * @return array
     */
    protected function list_publication(Feed_Adapter $feed, $user)
    {
        return array(
            'id'            => $feed->get_id(),
            'title'         => $feed->get_title(),
            'subtitle'      => $feed->get_subtitle(),
            'total_entries' => $feed->get_count_total_entries(),
            'icon'          => $feed->get_icon_url(),
            'public'        => $feed->is_public(),
            'readonly'      => !$feed->is_publisher($user),
            'deletable'     => $feed->is_owner($user),
            'created_on'    => $feed->get_created_on()->format(DATE_ATOM),
            'updated_on'    => $feed->get_updated_on()->format(DATE_ATOM),
        );
    }

    /**
     * Retrieve all entries of one feed
     *
     * @param Feed_Abstract $feed
     * @param bool          $extended
     * @param int           $offset_start
     * @param int           $how_many
     *
     * @return array
     */
    protected function list_publications_entries(Feed_Abstract $feed, $extended = false, $offset_start = 0, $how_many = 5)
    {
        $entries = $feed->get_entries($offset_start, $how_many)->get_entries();

        $out = array();
        foreach ($entries as $entry) {
            $out[] = $this->list_publication_entry($entry, $extended);
        }

        return $out;
    }

    /**
     * Retrieve detailed information about one feed entry
     *
     * @param Feed_Entry_Adapter $entry
     * @param bool               $extended
     *
     * @return array
     */
    protected function list_publication_entry(Feed_Entry_Adapter $entry, $extended = false)
    {
        $items = array();
        foreach ($entry->get_content() as $item) {
            $items[] = $this->list_publication_entry_item($item, $extended);
        }

        return array(
            'id'           => $entry->get_id(),
            'author_email' => $entry->get_author_email(),
            'author_name'  => $entry->get_author_name(),
            'created_on'   => $entry->get_created_on()->format(DATE_ATOM),
            'updated_on'   => $entry->get_updated_on()->format(DATE_ATOM),
            'title'        => $entry->get_title(),
            'subtitle'     => $entry->get_subtitle(),
            'items'        => $items,
            'feed_id'      => $entry->get_feed()->get_id(),
            'feed_title'   => $entry->get_feed()->get_title(),
            'feed_url'     => '/feeds/' . $entry->get_feed()->get_id() . '/content/',
            'url'          => '/feeds/entry/' . $entry->get_id() . '/',
        );
    }

    /**
     * Retrieve detailed information about one feed  entry item
     *
     * @param Feed_Entry_Item $item
     * @param bool            $extended
     *
     * @return array
     */
    protected function list_publication_entry_item(Feed_Entry_Item $item, $extended = false)
    {
        $data = array(
            'item_id' => $item->get_id(),
            'record'  => $this->list_record($item->get_record(), $extended)
        );

        return $data;
    }

    /**
     * @todo
     * @param Request $request
     */
    public function search_users(Request $request)
    {

    }

    /**
     * @todo
     * @param Request $request
     * @param int     $usr_id
     */
    public function get_user_access(Request $request, $usr_id)
    {

    }

    /**
     * @todo
     * @param Request $request
     */
    public function add_user(Request $request)
    {

    }

    /**
     * Retrieve detailed information about one sub definition
     *
     * @param record_adapter    $record
     * @param media_subdef      $media
     * @param registryInterface $registry
     *
     * @return array|null
     */
    protected function list_embedable_media(\record_adapter $record, media_subdef $media, registryInterface $registry)
    {
        if (!$media->is_physically_present()) {
            return null;
        }

        if ($this->app['authentication']->isAuthenticated()) {
            if ($media->get_name() !== 'document' && false === $this->app['authentication']->getUser()->ACL()->has_access_to_subdef($record, $media->get_name())) {
                return null;
            } else if ($media->get_name() === 'document'
                && !$this->app['authentication']->getUser()->ACL()->has_right_on_base($record->get_base_id(), 'candwnldhd')
                && !$this->app['authentication']->getUser()->ACL()->has_hd_grant($record)) {
                return null;
            }
        }

        if ($media->get_permalink() instanceof media_Permalink_Adapter) {
            $permalink = $this->list_permalink($media->get_permalink(), $registry);
        } else {
            $permalink = null;
        }

        return array(
            'name'        => $media->get_name(),
            'substituted' => $media->is_substituted(),
            'created_on'  => $media->get_creation_date()->format(DATE_ATOM),
            'updated_on'  => $media->get_modification_date()->format(DATE_ATOM),
            'permalink'   => $permalink,
            'height'      => $media->get_height(),
            'width'       => $media->get_width(),
            'filesize'    => $media->get_size(),
            'devices'     => $media->getDevices(),
            'player_type' => $media->get_type(),
            'mime_type'   => $media->get_mime(),
        );
    }

    /**
     * Retrieve detailed information about one permalink
     *
     * @param  media_Permalink_Adapter $permalink
     * @param  registryInterface       $registry
     * @return type
     */
    protected function list_permalink(media_Permalink_Adapter $permalink, registryInterface $registry)
    {
        $downloadUrl = $permalink->get_url();
        $downloadUrl->getQuery()->set('download', '1');

        return array(
            'created_on'   => $permalink->get_created_on()->format(DATE_ATOM),
            'id'           => $permalink->get_id(),
            'is_activated' => $permalink->get_is_activated(),
            'label'        => $permalink->get_label(),
            'updated_on'   => $permalink->get_last_modified()->format(DATE_ATOM),
            'page_url'     => $permalink->get_page(),
            'download_url' => (string) $downloadUrl,
            'url'          => (string) $permalink->get_url()
        );
    }

    /**
     * Retrieve detailed information about one status
     *
     * @param  databox $databox
     * @param  string  $status
     * @return array
     */
    protected function list_record_status(databox $databox, $status)
    {
        $status = strrev($status);
        $ret = array();
        foreach ($databox->get_statusbits() as $bit => $status_datas) {
            $ret[] = array('bit'   => $bit, 'state' => !!substr($status, $bit, 1));
        }

        return $ret;
    }

    /**
     * List all field about a specified caption
     *
     * @param  caption_record $caption
     * @return array
     */
    protected function list_record_caption(caption_record $caption)
    {
        $ret = array();
        foreach ($caption->get_fields() as $field) {
            foreach ($field->get_values() as $value) {
                $ret[] = $this->list_record_caption_field($value, $field);
            }
        }

        return $ret;
    }

    /**
     * Retrieve information about a caption field
     *
     * @param caption_Field_Value $value
     * @param caption_field       $field
     *
     * @return array
     */
    protected function list_record_caption_field(caption_Field_Value $value, caption_field $field)
    {
        return array(
            'meta_id'           => $value->getId(),
            'meta_structure_id' => $field->get_meta_struct_id(),
            'name'              => $field->get_name(),
            'labels'           => array(
                'fr' => $field->get_databox_field()->get_label('fr'),
                'en' => $field->get_databox_field()->get_label('en'),
                'de' => $field->get_databox_field()->get_label('de'),
                'nl' => $field->get_databox_field()->get_label('nl'),
            ),
            'value'             => $value->getValue(),
        );
    }

    /**
     * Retrieve information about one basket
     *
     * @param  \Entities\Basket $basket
     * @return array
     */
    public function list_basket(\Entities\Basket $basket)
    {
        $ret = array(
            'basket_id'         => $basket->getId(),
            'owner'             => $this->list_user($basket->getOwner($this->app)),
            'created_on'        => $basket->getCreated()->format(DATE_ATOM),
            'description'       => (string) $basket->getDescription(),
            'name'              => $basket->getName(),
            'pusher_usr_id'     => $basket->getPusherId(),
            'pusher'            => $basket->getPusher($this->app) ? $this->list_user($basket->getPusher($this->app)) : null,
            'updated_on'        => $basket->getUpdated()->format(DATE_ATOM),
            'unread'            => !$basket->getIsRead(),
            'validation_basket' => !!$basket->getValidation()
        );

        if ($basket->getValidation()) {
            $users = array();

            foreach ($basket->getValidation()->getParticipants() as $participant) {
                /* @var $participant \Entities\ValidationParticipant */
                $user = $participant->getUser($this->app);

                $users[] = array(
                    'usr_id'         => $user->get_id(),
                    'usr_name'       => $user->get_display_name(),
                    'confirmed'      => $participant->getIsConfirmed(),
                    'can_agree'      => $participant->getCanAgree(),
                    'can_see_others' => $participant->getCanSeeOthers(),
                    'readonly'       => $user->get_id() != $this->app['authentication']->getUser()->get_id(),
                    'user'           => $this->list_user($user),
                );
            }

            $expires_on_atom = $basket->getValidation()->getExpires();

            if ($expires_on_atom instanceof DateTime) {
                $expires_on_atom = $expires_on_atom->format(DATE_ATOM);
            }

            $ret = array_merge(
                    array(
                'validation_users'          => $users,
                'expires_on'                => $expires_on_atom,
                'validation_infos'          => $basket->getValidation()->getValidationString($this->app, $this->app['authentication']->getUser()),
                'validation_confirmed'      => $basket->getValidation()->getParticipant($this->app['authentication']->getUser(), $this->app)->getIsConfirmed(),
                'validation_initiator'      => $basket->getValidation()->isInitiator($this->app['authentication']->getUser()),
                'validation_initiator_user' => $this->list_user($basket->getValidation()->getInitiator($this->app)),
                    ), $ret
            );
        }

        return $ret;
    }

    /**
     * Retrieve detailed information about one record
     *
     * @param record_adapter $record
     * @param bool           $extended
     *
     * @return array
     */
    public function list_record(record_adapter $record, $extended = false)
    {
        $technicalInformation = array();
        foreach ($record->get_technical_infos() as $name => $value) {
            $technicalInformation[] = array(
                'name'  => $name,
                'value' => $value
            );
        }

        $data =  array(
            'databox_id'             => $record->get_sbas_id(),
            'record_id'              => $record->get_record_id(),
            'mime_type'              => $record->get_mime(),
            'title'                  => $record->get_title(),
            'original_name'          => $record->get_original_name(),
            'updated_on'             => $record->get_modification_date()->format(DATE_ATOM),
            'created_on'             => $record->get_creation_date()->format(DATE_ATOM),
            'collection_id'          => $record->get_collection_id(),
            'base_id'                => $record->get_base_id(),
            'sha256'                 => $record->get_sha256(),
            'thumbnail'              => $this->list_embedable_media($record, $record->get_thumbnail(), $this->app['phraseanet.registry']),
            'technical_informations' => $technicalInformation,
            'phrasea_type'           => $record->get_type(),
            'uuid'                   => $record->get_uuid(),
        );

        if ($extended) {
            $subdefs = $caption = array();

            foreach ($record->get_embedable_medias(array(), array()) as $name => $media) {
                if (null !== $subdef = $this->list_embedable_media($record, $media, $this->app['phraseanet.registry'])) {
                    $subdefs[] = $subdef;
                }
            }

            foreach ($record->get_caption()->get_fields() as $field) {
                $caption[] = array(
                    'meta_structure_id' => $field->get_meta_struct_id(),
                    'name'              => $field->get_name(),
                    'value'             => $field->get_serialized_values(';'),
                );
            }

            $extendedData = array(
                'subdefs' => $subdefs,
                'metadata' => $this->list_record_caption($record->get_caption()),
                'status' => $this->list_record_status($record->get_databox(), $record->get_status()),
                'caption' =>  $caption
            );

            $data = array_merge($data, $extendedData);
        }

        return $data;
    }

    /**
     * Retrieve detailed information about one story
     *
     * @param record_adapter $story
     * @param bool           $extended
     *
     * @return array
     * @throws API_V1_exception_notfound
     */
    public function list_story(record_adapter $story, $extended = false)
    {
        if (!$story->is_grouping()) {
            throw new \API_V1_exception_notfound('Story not found');
        }

        $that = $this;
        $records = array_map(function (\record_adapter $record) use ($that, $extended) {
            return $that->list_record($record, $extended);
        }, array_values($story->get_children()->get_elements()));

        $caption = $story->get_caption();

        $format = function (caption_record $caption, $dcField) {

            $field = $caption->get_dc_field($dcField);

            if (!$field) {
                return null;
            }

            return $field->get_serialized_values();
        };

        return array(
            '@entity@'       => self::OBJECT_TYPE_STORY,
            'databox_id'     => $story->get_sbas_id(),
            'story_id'       => $story->get_record_id(),
            'updated_on'     => $story->get_modification_date()->format(DATE_ATOM),
            'created_on'     => $story->get_creation_date()->format(DATE_ATOM),
            'collection_id'  => phrasea::collFromBas($this->app, $story->get_base_id()),
            'thumbnail'      => $this->list_embedable_media($story, $story->get_thumbnail(), $this->app['phraseanet.registry']),
            'uuid'           => $story->get_uuid(),
            'metadatas'      => array(
                '@entity@'       => self::OBJECT_TYPE_STORY_METADATA_BAG,
                'dc:contributor' => $format($caption, databox_Field_DCESAbstract::Contributor),
                'dc:coverage'    => $format($caption, databox_Field_DCESAbstract::Coverage),
                'dc:creator'     => $format($caption, databox_Field_DCESAbstract::Creator),
                'dc:date'        => $format($caption, databox_Field_DCESAbstract::Date),
                'dc:description' => $format($caption, databox_Field_DCESAbstract::Description),
                'dc:format'      => $format($caption, databox_Field_DCESAbstract::Format),
                'dc:identifier'  => $format($caption, databox_Field_DCESAbstract::Identifier),
                'dc:language'    => $format($caption, databox_Field_DCESAbstract::Language),
                'dc:publisher'   => $format($caption, databox_Field_DCESAbstract::Publisher),
                'dc:relation'    => $format($caption, databox_Field_DCESAbstract::Relation),
                'dc:rights'      => $format($caption, databox_Field_DCESAbstract::Rights),
                'dc:source'      => $format($caption, databox_Field_DCESAbstract::Source),
                'dc:subject'     => $format($caption, databox_Field_DCESAbstract::Subject),
                'dc:title'       => $format($caption, databox_Field_DCESAbstract::Title),
                'dc:type'        => $format($caption, databox_Field_DCESAbstract::Type),
            ),
            'records'        => $records,
        );
    }

    /**
     * Retrieve information about current authenticated user
     *
     * @param Application $app
     * @param Request     $request
     *
     * @return API_V1_result
     */
    public function get_current_user(Application $app, Request $request)
    {
        $result = new API_V1_result($app, $request, $this);
        $result->set_datas(array(
            'user' => $this->list_user($app['authentication']->getUser()),
            'collections' => $this->list_user_collections($app['authentication']->getUser()),
        ));

        return $result;
    }

    private function list_user(\User_Adapter $user)
    {
        switch ($user->get_gender()) {
            case 2;
                $gender = 'Mr';
                break;
            case 1;
                $gender = 'Mrs';
                break;
            case 0;
                $gender = 'Miss';
                break;
        }

        return array(
            '@entity@'        => self::OBJECT_TYPE_USER,
            'id'              => $user->get_id(),
            'email'           => $user->get_email() ?: null,
            'login'           => $user->get_login() ?: null,
            'first_name'      => $user->get_firstname() ?: null,
            'last_name'       => $user->get_lastname() ?: null,
            'display_name'    => $user->get_display_name() ?: null,
            'gender'          => $gender,
            'address'         => $user->get_address() ?: null,
            'zip_code'        => $user->get_zipcode() ?: null,
            'city'            => $user->get_city() ?: null,
            'country'         => $user->get_country() ?: null,
            'phone'           => $user->get_tel() ?: null,
            'fax'             => $user->get_fax() ?: null,
            'job'             => $user->get_job() ?: null,
            'position'        => $user->get_position() ?: null,
            'company'         => $user->get_company() ?: null,
            'geoname_id'      => $user->get_geonameid() ?: null,
            'last_connection' => $user->get_last_connection() ? $user->get_last_connection()->format(DATE_ATOM) : null,
            'created_on'      => $user->get_creation_date() ? $user->get_creation_date()->format(DATE_ATOM) : null,
            'updated_on'      => $user->get_modification_date() ? $user->get_modification_date()->format(DATE_ATOM) : null,
            'locale'          => $user->get_locale() ?: null,
        );
    }

    private function list_user_collections(\User_Adapter $user)
    {
        $acl = $user->ACL();
        $rights = $acl->get_bas_rights();
        $bases = $acl->get_granted_base();

        $grants = array();

        foreach ($bases as $base) {
            $baseGrants = array();

            foreach ($rights as $right) {
                if (! $acl->has_right_on_base($base->get_coll_id(), $right)) {
                    continue;
                }

                $baseGrants[] = $right;
            }

            $grants[] = array(
                'databox_id' => $base->get_sbas_id(),
                'base_id' => $base->get_base_id(),
                'collection_id' => $base->get_coll_id(),
                'rights' => $baseGrants
            );
        }

        return $grants;
    }

    /**
     * List all databoxes of the current appbox
     *
     * @return array
     */
    protected function list_databoxes()
    {
        $ret = array();
        foreach ($this->app['phraseanet.appbox']->get_databoxes() as $databox) {
            if (!$this->app['authentication']->getUser()->ACL()->has_access_to_sbas($databox->get_sbas_id())) {
                continue;
            }
            $ret[] = $this->list_databox($databox);
        }

        return $ret;
    }

    /**
     * Retrieve CGU's for the specified databox
     *
     * @param  databox $databox
     * @return array
     */
    protected function list_databox_terms(databox $databox)
    {
        $ret = array();
        foreach ($databox->get_cgus() as $locale => $array_terms) {
            $ret[] = array('locale' => $locale, 'terms'  => $array_terms['value']);
        }

        return $ret;
    }

    /**
     * Retrieve detailed information about one databox
     *
     * @param  databox $databox
     * @return array
     */
    protected function list_databox(databox $databox)
    {
        $ret = array();

        $ret['databox_id'] = $databox->get_sbas_id();
        $ret['name']       = $databox->get_dbname();
        $ret['viewname']   = $databox->get_viewname();
        $ret['labels']     = array(
            'en' => $databox->get_label('en'),
            'de' => $databox->get_label('de'),
            'fr' => $databox->get_label('fr'),
            'nl' => $databox->get_label('nl'),
        );
        $ret['version']    = $databox->get_version();

        return $ret;
    }

    /**
     * List all available collections for a specified databox
     *
     * @param  databox $databox
     * @return array
     */
    protected function list_databox_collections(databox $databox)
    {
        $ret = array();

        foreach ($databox->get_collections() as $collection) {
            if (!$this->app['authentication']->getUser()->ACL()->has_access_to_base($collection->get_base_id())) {
                continue;
            }
            $ret[] = $this->list_collection($collection);
        }

        return $ret;
    }

    /**
     * Retrieve detailed information about one collection
     *
     * @param  collection $collection
     * @return array
     */
    protected function list_collection(collection $collection)
    {
        $ret = array(
            'base_id'       => $collection->get_base_id(),
            'collection_id' => $collection->get_coll_id(),
            'name'          => $collection->get_name(),
            'labels'        => array(
                'fr' => $collection->get_label('fr'),
                'en' => $collection->get_label('en'),
                'de' => $collection->get_label('de'),
                'nl' => $collection->get_label('nl'),
            ),
            'record_amount' => $collection->get_record_amount(),
        );

        return $ret;
    }

    /**
     * Retrieve information for a list of status
     *
     * @param  array $status
     * @return array
     */
    protected function list_databox_status(array $status)
    {
        $ret = array();
        foreach ($status as $n => $data) {
            $ret[] = array(
                'bit'        => $n,
                'label_on'   => $data['labelon'],
                'label_off'  => $data['labeloff'],
                'labels'     => array(
                    'en' => $data['labels_on_i18n']['en'],
                    'fr' => $data['labels_on_i18n']['fr'],
                    'de' => $data['labels_on_i18n']['de'],
                    'nl' => $data['labels_on_i18n']['nl'],
                ),
                'img_on'     => $data['img_on'],
                'img_off'    => $data['img_off'],
                'searchable' => !!$data['searchable'],
                'printable'  => !!$data['printable'],
            );
        }

        return $ret;
    }

    /**
     * List all metadatas field using a databox meta structure
     *
     * @param  databox_descriptionStructure $meta_struct
     * @return array
     */
    protected function list_databox_metadatas_fields(databox_descriptionStructure $meta_struct)
    {
        $ret = array();
        foreach ($meta_struct as $meta) {
            $ret[] = $this->list_databox_metadata_field_properties($meta);
        }

        return $ret;
    }

    /**
     * Retrieve information about one databox metadata field
     *
     * @param  databox_field $databox_field
     * @return array
     */
    protected function list_databox_metadata_field_properties(databox_field $databox_field)
    {
        $ret = array(
            'id'               => $databox_field->get_id(),
            'namespace'        => $databox_field->get_tag()->getGroupName(),
            'source'           => $databox_field->get_tag()->getTagname(),
            'tagname'          => $databox_field->get_tag()->getName(),
            'name'             => $databox_field->get_name(),
            'labels'           => array(
                'fr' => $databox_field->get_label('fr'),
                'en' => $databox_field->get_label('en'),
                'de' => $databox_field->get_label('de'),
                'nl' => $databox_field->get_label('nl'),
            ),
            'separator'        => $databox_field->get_separator(),
            'thesaurus_branch' => $databox_field->get_tbranch(),
            'type'             => $databox_field->get_type(),
            'indexable'        => $databox_field->is_indexable(),
            'multivalue'       => $databox_field->is_multi(),
            'readonly'         => $databox_field->is_readonly(),
            'required'         => $databox_field->is_required(),
        );

        return $ret;
    }

    /**
     * Get Information the cache system used by the instance
     *
     * @param Application $app
     *
     * @return array
     */
    protected function get_cache_info(Application $app)
    {
        $caches = array(
            'main'               => $app['cache'],
            'op_code'            => $app['opcode-cache'],
            'doctrine_metadatas' => $this->app['EM']->getConfiguration()->getMetadataCacheImpl(),
            'doctrine_query'     => $this->app['EM']->getConfiguration()->getQueryCacheImpl(),
            'doctrine_result'    => $this->app['EM']->getConfiguration()->getResultCacheImpl(),
        );

        $ret = array();

        foreach ($caches as $name => $service) {
            if ($service instanceof \Alchemy\Phrasea\Cache\Cache) {
                $ret['cache'][$name] = array(
                    'type'   => $service->getName(),
                    'online' => $service->isOnline(),
                    'stats'  => $service->getStats(),
                );
            } else {
                $ret['cache'][$name] = null;
            }
        }

        return $ret;
    }

    /**
     * Retrieve detailed information about a lazaret file
     *
     * @param \Entities\LazaretFile $file
     *
     * @return array
     */
    protected function list_lazaret_file(\Entities\LazaretFile $file)
    {
        $checks = array();

        if ($file->getChecks()) {
            foreach ($file->getChecks() as $checker) {

                $checks[] = $checker->getMessage();
            }
        }

        $usr_id = $user = null;
        if ($file->getSession()->getUser($this->app)) {
            $user = $file->getSession()->getUser($this->app);
            $usr_id = $user->get_id();
        }

        $session = array(
            'id'     => $file->getSession()->getId(),
            'usr_id' => $usr_id,
            'user'   => $user ? $this->list_user($user) : null,
        );

        return array(
            'id'                 => $file->getId(),
            'quarantine_session' => $session,
            'base_id'            => $file->getBaseId(),
            'original_name'      => $file->getOriginalName(),
            'sha256'             => $file->getSha256(),
            'uuid'               => $file->getUuid(),
            'forced'             => $file->getForced(),
            'checks'             => $file->getForced() ? array() : $checks,
            'created_on' => $file->getCreated()->format(DATE_ATOM),
            'updated_on' => $file->getUpdated()->format(DATE_ATOM),
        );
    }

    /**
     * Provide information about phraseanet configuration
     *
     * @param Application $app
     *
     * @return array
     */
    protected function get_config_info(Application $app)
    {
        $ret = array();

        $ret['phraseanet']['version'] = array(
            'name'   => $app['phraseanet.version']::getName(),
            'number' => $app['phraseanet.version']::getNumber(),
        );

        $ret['phraseanet']['environment'] = $app->getEnvironment();
        $ret['phraseanet']['debug'] = $app['debug'];
        $ret['phraseanet']['maintenance'] = $app['phraseanet.configuration']['main']['maintenance'];
        $ret['phraseanet']['errorsLog'] = $app['debug'];
        $ret['phraseanet']['serverName'] = $app['phraseanet.configuration']['main']['servername'];

        return $ret;
    }

    /**
     * Get detailed information for a task
     *
     * @param task_abstract $task
     *
     * @return array
     */
    protected function list_task(\task_abstract $task)
    {
        return array(
            'id'             => $task->getID(),
            'name'           => $task->getName(),
            'state'          => $task->getState(),
            'pid'            => $task->getPID(),
            'title'          => $task->getTitle(),
            'last_exec_time' => $task->getLastExecTime() ? $task->getLastExecTime()->format(DATE_ATOM) : null,
            'auto_start'     => !!$task->isActive(),
            'runner'         => $task->getRunner(),
            'crash_counter'  => $task->getCrashCounter()
        );
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return API_V1_result
     *
     * called by the route [POST] /stories/add/
     */
    public function add_story(Application $app, Request $request)
    {
        $content = $request->getContent();

        $data = @json_decode($content);

        if (JSON_ERROR_NONE !== json_last_error()) {
            $app->abort(400, 'Json response cannot be decoded or the encoded data is deeper than the recursion limit');
        }

        if (!isset($data->{'stories'})) {
            $app->abort(400, 'Missing "stories" property');
        }

        $schemaStory = $app['json-schema.retriever']->retrieve('file://'.$app['root.path'].'/lib/conf.d/json_schema/story.json');
        $schemaStoryRecord = $app['json-schema.retriever']->retrieve('file://'.$app['root.path'].'/lib/conf.d/json_schema/story_record.json');

        $storyData = $data->{'stories'};

        if (!is_array($storyData)) {
            $storyData = array($storyData);
        }

        $stories = array();
        foreach ($storyData as $data) {
            $stories[] = $this->_create_story($app, $data, $schemaStory, $schemaStoryRecord);
        }

        $result = new API_V1_result($app, $request, $this);

        $result->set_datas(array('stories' => array_map(function($story) {
            return sprintf('/stories/%s/%s/', $story->get_sbas_id(), $story->get_record_id());
        }, $stories)));

        return $result;
    }

    /**
     * @param Application $app
     * @param Request $request
     * @param $databox_id
     * @param $story_id
     * @return API_V1_result
     *
     * called by the route [POST] /stories/{databox_id}/{story_id}/addrecords
     */
    public function add_records_to_story(Application $app, Request $request, $databox_id, $story_id)
    {
        $content = $request->getContent();

        $data = @json_decode($content);

        if (JSON_ERROR_NONE !== json_last_error()) {
            $app->abort(400, 'Json response cannot be decoded or the encoded data is deeper than the recursion limit');
        }

        if (!isset($data->{'story_records'})) {
            $app->abort(400, 'Missing "story_records" property');
        }

        $schemaStoryRecord = $app['json-schema.retriever']->retrieve('file://'.$app['root.path'].'/lib/conf.d/json_schema/story_record.json');

        $recordsData = $data->{'story_records'};
        $story = new \record_adapter($app, $databox_id, $story_id);

        $records = $this->_add_records_to_story($app, $story, $recordsData, $schemaStoryRecord);

        $result = new API_V1_result($this->app, $request, $this);

        $result->set_datas(array('records' => $records));

        return $result;
    }


    /**
     * @param Application $app
     * @param Request $request
     * @param $databox_id
     * @param $story_id
     * @return API_V1_result
     *
     * called by route [POST] /stories/{databox_id}/{story_id}/setcover
     */
    public function set_story_cover(Application $app, Request $request, $databox_id, $story_id)
    {
        $content = $request->getContent();

        $data = @json_decode($content);

        if (JSON_ERROR_NONE !== json_last_error()) {
            $app->abort(400, 'Json response cannot be decoded or the encoded data is deeper than the recursion limit');
        }

        $schemaStoryCover = $app['json-schema.retriever']->retrieve('file://'.$app['root.path'].'/lib/conf.d/json_schema/story_cover.json');

        $app['json-schema.validator']->check($data, $schemaStoryCover);
        if (false === $app['json-schema.validator']->isValid()) {
            $app->abort(400, 'Request body contains not a valid "story cover" object');
        }

        $story = new \record_adapter($app, $databox_id, $story_id);

        // we do NOT let "_set_story_cover()" fail : pass false as last arg
        $record_key = $this->_set_story_cover($app, $story, $data->{'record_id'}, false);

        $result = new API_V1_result($this->app, $request, $this);

        $result->set_datas(array($record_key));

        return $result;
    }


    protected function _create_story(Application $app, $data, $schemaStory, $schemaStoryRecord)
    {
        $app['json-schema.validator']->check($data, $schemaStory);

        if (false === $app['json-schema.validator']->isValid()) {
            $app->abort(400, 'Request body does not contains a valid "story" object');
        }

        $collection = \collection::get_from_base_id($app, $data->{'base_id'});

        if (!$app['authentication']->getUser()->ACL()->has_right_on_base($collection->get_base_id(), 'canaddrecord')) {
            $app->abort(403, sprintf('You can not create a story on this collection %s', $collection->get_base_id()));
        }

        $story = \record_adapter::createStory($app, $collection);

        if (isset($data->{'title'})) {
            $story->set_original_name((string) $data->{'title'});
        }

        // set metadata for the story
        $metadatas = array();
        $thumbtitle_set = false;
        foreach ($collection->get_databox()->get_meta_structure() as $field) {
            // the title goes into the first 'thumbtitle' field
            if (isset($data->{'title'}) && !$thumbtitle_set && $field->get_thumbtitle()) {
                $metadatas[] = array(
                    'meta_struct_id' => $field->get_id(),
                    'meta_id' => null,
                    'value' => $data->{'title'}
                );
                $thumbtitle_set = true;
            }
            // if the field is set into data->metadatas, set it
            $meta = null;
            // the meta form json can be keyed by id or name
            if(isset($data->{'metadatas'}->{$field->get_id()})) {
                $meta = $data->{'metadatas'}->{$field->get_id()};
            }
            elseif(isset($data->{'metadatas'}->{$field->get_name()})) {
                $meta = $data->{'metadatas'}->{$field->get_name()};
            }
            if($meta !== null) {
                if(!is_array($meta)) {
                    $meta = array($meta);
                }
                foreach($meta as $value) {
                    $metadatas[] = array(
                        'meta_struct_id' => $field->get_id(),
                        'meta_id' => null,
                        'value' => $value
                    );
                }
            }
        }

        if(count($metadatas) > 0) {
            $story->set_metadatas($metadatas);
        }

        if (isset($data->{'story_records'})) {
            $recordsData = $data->{'story_records'};
            $this->_add_records_to_story($app, $story, $recordsData, $schemaStoryRecord);
        }

        return $story;
    }


    protected function _add_records_to_story(Application $app, \record_adapter $story, $recordsData, $schemaStoryRecord)
    {
        if (!is_array($recordsData)) {
            $recordsData = array($recordsData);
        }

        $cover_set = false;
        $records = array();
        foreach ($recordsData as $data) {
            $records[] = $this->_add_record_to_story($app, $story, $data, $schemaStoryRecord);
            if(!$cover_set && $data->{'use_as_cover'} === true) {
                // because we can try many records as cover source, we let it fail
                $cover_set = ($this->_set_story_cover($app, $story, $data->{'record_id'}, true) !== false);
            }
        }

        $app['dispatcher']->dispatch(PhraseaEvents::RECORD_EDIT, new RecordEdit($story));

        return $records;
    }

    protected function _add_record_to_story(Application $app, record_adapter $story, $data, $schemaStoryRecord)
    {
        $app['json-schema.validator']->check($data, $schemaStoryRecord);

        if (false === $app['json-schema.validator']->isValid()) {
            $app->abort(400, 'Request body contains not a valid "record story" object');
        }

        $databox_id = $data->{'databox_id'};
        $record_id = $data->{'record_id'};

        try {
            $record = new \record_adapter($app, $databox_id, $record_id);
        } catch (Exception_Record_AdapterNotFound $e) {
            $app->abort(404, sprintf('Record identified by databox_is %s and record_id %s could not be found', $databox_id, $record_id));
        }

        if (!$story->hasChild($record)) {
            $story->appendChild($record);
        }

        return $record->get_serialize_key();
    }

    protected function _set_story_cover(Application $app, \record_adapter $story, $record_id, $can_fail=false)
    {
        try {
            $record = new \record_adapter($app, $story->get_sbas_id(), $record_id);
        } catch (Exception_Record_AdapterNotFound $e) {
            $app->abort(404, sprintf('Record identified by databox_id %s and record_id %s could not be found', $story->get_sbas_id(), $record_id));
        }

        if (!$story->hasChild($record)) {
            $app->abort(404, sprintf('Record identified by databox_id %s and record_id %s is not in the story', $story->get_sbas_id(), $record_id));
        }

        if ($record->get_type() !== 'image') {
            // this can fail so we can loop on many records during story creation...
            if($can_fail) {
                return false;
            }
            $app->abort(403, sprintf('Record identified by databox_id %s and record_id %s is not an image', $story->get_sbas_id(), $record_id));
        }

        foreach ($record->get_subdefs() as $name => $value) {
            if (!in_array($name, array('thumbnail', 'preview'))) {
                continue;
            }
            $media = $app['mediavorus']->guess($value->get_pathfile());
            $story->substitute_subdef($name, $media, $app);
            $app['phraseanet.logger']($story->get_databox())->log(
                $story,
                \Session_Logger::EVENT_SUBSTITUTE,
                $name == 'document' ? 'HD' : $name,
                ''
            );
        }

        $app['dispatcher']->dispatch(PhraseaEvents::RECORD_EDIT, new RecordEdit($story));

        return $record->get_serialize_key();
    }

    /**
     *  Provide phraseanet global values
     *
     * @param Application $app
     *
     * @return array
     */
    protected function get_gv_info(Application $app)
    {
        try {
            $SEStatus = $app['phraseanet.SE']->getStatus();
        } catch (\RuntimeException $e) {
            $SEStatus = array('error' => $e->getMessage());
        }

        $binaries = $app['phraseanet.configuration']['binaries'];

        return array(
            'global_values' => array(
                'serverName'  => $app['phraseanet.registry']->get('GV_ServerName'),
                'title'       => $app['phraseanet.registry']->get('GV_homeTitle'),
                'keywords'    => $app['phraseanet.registry']->get('GV_metaKeywords'),
                'description' => $app['phraseanet.registry']->get('GV_metaDescription'),
                'httpServer'  => array(
                    'logErrors'       => $app['phraseanet.registry']->get('GV_log_errors'),
                    'phpTimezone'     => ini_get('date.timezone'),
                    'siteId'          => $app['phraseanet.configuration']['main']['key'],
                    'staticUrl'       => $app['phraseanet.registry']->get('GV_STATIC_URL'),
                    'defaultLanguage' => $app['phraseanet.registry']->get('id_GV_default_lng'),
                    'allowIndexing'   => $app['phraseanet.registry']->get('GV_allow_search_engine'),
                    'modes'           => array(
                        'XsendFile'                     => $app['phraseanet.configuration']['xsendfile']['enabled'],
                        'XsendFileMapping'              => $app['phraseanet.configuration']['xsendfile']['mapping'],
                        'H264PseudoStreaming'           => $app['phraseanet.configuration']['h264-pseudo-streaming']['enabled'],
                        'H264PseudoStreamingMapping'    => $app['phraseanet.configuration']['h264-pseudo-streaming']['mapping'],
                    )
                ),
                'maintenance' => array(
                    'alertMessage'   => $app['phraseanet.registry']->get('GV_message'),
                    'displayMessage' => $app['phraseanet.registry']->get('GV_message_on'),
                ),
                'webServices'    => array(
                    'googleApi'                   => $app['phraseanet.registry']->get('GV_google_api'),
                    'googleAnalyticsId'           => $app['phraseanet.registry']->get('GV_googleAnalytics'),
                    'i18nWebService'              => $app['phraseanet.registry']->get('GV_i18n_service'),
                    'recaptacha'                  => array(
                        'active'     => $app['phraseanet.registry']->get('GV_captchas'),
                        'publicKey'  => $app['phraseanet.registry']->get('GV_captcha_public_key'),
                        'privateKey' => $app['phraseanet.registry']->get('GV_captcha_private_key'),
                    ),
                    'youtube'    => array(
                        'active'       => $app['phraseanet.registry']->get('GV_youtube_api'),
                        'clientId'     => $app['phraseanet.registry']->get('GV_youtube_client_id'),
                        'clientSecret' => $app['phraseanet.registry']->get('GV_youtube_client_secret'),
                        'devKey'       => $app['phraseanet.registry']->get('GV_youtube_dev_key'),
                    ),
                    'flickr'       => array(
                        'active'       => $app['phraseanet.registry']->get('GV_flickr_api'),
                        'clientId'     => $app['phraseanet.registry']->get('GV_flickr_client_id'),
                        'clientSecret' => $app['phraseanet.registry']->get('GV_flickr_client_secret'),
                    ),
                    'dailymtotion' => array(
                        'active'       => $app['phraseanet.registry']->get('GV_dailymotion_api'),
                        'clientId'     => $app['phraseanet.registry']->get('GV_dailymotion_client_id'),
                        'clientSecret' => $app['phraseanet.registry']->get('GV_dailymotion_client_secret'),
                    )
                ),
                'navigator'    => array(
                    'active'   => $app['phraseanet.registry']->get('GV_client_navigator'),
                ),
                'office-plugin' => array(
                    'active'    => $app['phraseanet.registry']->get('GV_client_navigator'),
                ),
                'homepage' => array(
                    'viewType' => $app['phraseanet.registry']->get('GV_home_publi'),
                ),
                'report'   => array(
                    'anonymous' => $app['phraseanet.registry']->get('GV_anonymousReport'),
                ),
                'events'    => array(
                    'events'        => $app['phraseanet.registry']->get('GV_events'),
                    'notifications' => $app['phraseanet.registry']->get('GV_notifications'),
                ),
                'upload'        => array(
                    'allowedFileExtension' => $app['phraseanet.registry']->get('GV_appletAllowedFileEx'),
                ),
                'filesystem'           => array(
                    'noWeb'        => $app['phraseanet.registry']->get('GV_base_datapath_noweb'),
                ),
                'searchEngine' => array(
                    'configuration' => array(
                        'defaultQuery'     => $app['phraseanet.registry']->get('GV_defaultQuery'),
                        'defaultQueryType' => $app['phraseanet.registry']->get('GV_defaultQuery_type'),
                        'minChar'          => $app['phraseanet.registry']->get('GV_min_letters_truncation'),
                        'sort'             => $app['phraseanet.registry']->get('GV_phrasea_sort'),
                    ),
                    'engine'            => array(
                        'type'          => $app['phraseanet.SE']->getName(),
                        'status'        => $SEStatus,
                        'configuration' => $app['phraseanet.SE']->getConfigurationPanel()->getConfiguration(),
                    ),
                ),
                'binary'  => array(
                    'phpCli'            => isset($binaries['php_binary']) ? $binaries['php_binary'] : null,
                    'phpIni'            => $app['phraseanet.registry']->get('GV_PHP_INI'),
                    'swfExtract'        => isset($binaries['swf_extract_binary']) ? $binaries['swf_extract_binary'] : null,
                    'pdf2swf'           => isset($binaries['pdf2swf_binary']) ? $binaries['pdf2swf_binary'] : null,
                    'swfRender'         => isset($binaries['swf_render_binary']) ? $binaries['swf_render_binary'] : null,
                    'unoconv'           => isset($binaries['unoconv_binary']) ? $binaries['unoconv_binary'] : null,
                    'ffmpeg'            => isset($binaries['ffmpeg_binary']) ? $binaries['ffmpeg_binary'] : null,
                    'ffprobe'           => isset($binaries['ffprobe_binary']) ? $binaries['ffprobe_binary'] : null,
                    'mp4box'            => isset($binaries['mp4box_binary']) ? $binaries['mp4box_binary'] : null,
                    'pdftotext'         => isset($binaries['pdftotext_binary']) ? $binaries['pdftotext_binary'] : null,
                    'recess'            => isset($binaries['recess_binary']) ? $binaries['recess_binary'] : null,
                    'pdfmaxpages'       => $app['phraseanet.registry']->get('GV_pdfmaxpages'),),
                'mainConfiguration' => array(
                    'adminMail'          => $app['phraseanet.registry']->get('GV_adminMail'),
                    'viewBasAndCollName' => $app['phraseanet.registry']->get('GV_view_bas_and_coll'),
                    'chooseExportTitle'  => $app['phraseanet.registry']->get('GV_choose_export_title'),
                    'defaultExportTitle' => $app['phraseanet.registry']->get('GV_default_export_title'),
                    'socialTools'        => $app['phraseanet.registry']->get('GV_social_tools'),),
                'modules'            => array(
                    'thesaurus'          => $app['phraseanet.registry']->get('GV_thesaurus'),
                    'storyMode'          => $app['phraseanet.registry']->get('GV_multiAndReport'),
                    'docSubsitution'     => $app['phraseanet.registry']->get('GV_seeOngChgDoc'),
                    'subdefSubstitution' => $app['phraseanet.registry']->get('GV_seeNewThumb'),),
                'email'              => array(
                    'defaultMailAddress' => $app['phraseanet.registry']->get('GV_defaulmailsenderaddr'),
                    'smtp'               => array(
                        'active'   => $app['phraseanet.registry']->get('GV_smtp'),
                        'auth'     => $app['phraseanet.registry']->get('GV_smtp_auth'),
                        'host'     => $app['phraseanet.registry']->get('GV_smtp_host'),
                        'port'     => $app['phraseanet.registry']->get('GV_smtp_port'),
                        'secure'   => $app['phraseanet.registry']->get('GV_smtp_secure'),
                        'user'     => $app['phraseanet.registry']->get('GV_smtp_user'),
                        'password' => $app['phraseanet.registry']->get('GV_smtp_password'),
                    ),
                ),
                'ftp'      => array(
                    'active'        => $app['phraseanet.registry']->get('GV_activeFTP'),
                    'activeForUser' => $app['phraseanet.registry']->get('GV_ftp_for_user'),),
                'client'        => array(
                    'maxSizeDownload'         => $app['phraseanet.registry']->get('GV_download_max'),
                    'tabSearchMode'           => $app['phraseanet.registry']->get('GV_ong_search'),
                    'tabAdvSearchPosition'    => $app['phraseanet.registry']->get('GV_ong_advsearch'),
                    'tabTopicsPosition'       => $app['phraseanet.registry']->get('GV_ong_topics'),
                    'tabOngActifPosition'     => $app['phraseanet.registry']->get('GV_ong_actif'),
                    'renderTopicsMode'        => $app['phraseanet.registry']->get('GV_client_render_topics'),
                    'displayRolloverPreview'  => $app['phraseanet.registry']->get('GV_rollover_reg_preview'),
                    'displayRolloverBasket'   => $app['phraseanet.registry']->get('GV_rollover_chu'),
                    'collRenderMode'          => $app['phraseanet.registry']->get('GV_client_coll_ckbox'),
                    'viewSizeBaket'           => $app['phraseanet.registry']->get('GV_viewSizeBaket'),
                    'clientAutoShowProposals' => $app['phraseanet.registry']->get('GV_clientAutoShowProposals'),
                    'needAuth2DL'             => $app['phraseanet.registry']->get('GV_needAuth2DL'),),
                'inscription'             => array(
                    'autoSelectDB' => $app['phraseanet.registry']->get('GV_autoselectDB'),
                    'autoRegister' => $app['phraseanet.registry']->get('GV_autoregister'),
                ),
                'push'         => array(
                    'validationReminder' => $app['phraseanet.registry']->get('GV_validation_reminder'),
                    'expirationValue'    => $app['phraseanet.registry']->get('GV_val_expiration'),
                ),
            )
        );
    }

    /**
     * Prepare search request
     *
     * @param Request $request
     *
     * @return array
     */
    private function prepare_search_request(Request $request)
    {
        $options = SearchEngineOptions::fromRequest($this->app, $request);

        $offsetStart = (int) ($request->get('offset_start') ? : 0);
        $perPage = (int) $request->get('per_page') ? : 10;

        $query = (string) $request->get('query');

        $this->app['phraseanet.SE']->setOptions($options);
        $this->app['phraseanet.SE']->resetCache();

        $search_result = $this->app['phraseanet.SE']->query($query, $offsetStart, $perPage);

        foreach ($options->getDataboxes() as $databox) {
            $colls = array_map(function (\collection $collection) {
                return $collection->get_coll_id();
            }, array_filter($options->getCollections(), function (\collection $collection) use ($databox) {
                return $collection->get_databox()->get_sbas_id() == $databox->get_sbas_id();
            }));

            $this->app['phraseanet.SE.logger']->log($databox, $search_result->getQuery(), $search_result->getTotal(), $colls);
        }

        $this->app['phraseanet.SE']->clearCache();

        $ret = array(
            'offset_start'      => $offsetStart,
            'per_page'          => $perPage,
            'available_results' => $search_result->getAvailable(),
            'total_results'     => $search_result->getTotal(),
            'error'             => $search_result->getError(),
            'warning'           => $search_result->getWarning(),
            'query_time'        => $search_result->getDuration(),
            'search_indexes'    => $search_result->getIndexes(),
            'suggestions'       => array_map(function (SearchEngineSuggestion $suggestion) {
                return $suggestion->toArray();
            }, $search_result->getSuggestions()->toArray()),
            'results'           => array(),
            'query'             => $search_result->getQuery(),
        );

        return array($ret, $search_result);
    }
}