<?php

namespace Alchemy\Phrasea\Controller\Api\V3;

use Alchemy\Phrasea\Application\Helper\DispatcherAware;
use Alchemy\Phrasea\Application\Helper\JsonBodyAware;
use Alchemy\Phrasea\Controller\Api\Result;
use Alchemy\Phrasea\Controller\Controller;
use Alchemy\Phrasea\SearchEngine\SearchEngineInterface;
use Alchemy\Phrasea\SearchEngine\SearchEngineOptions;
use Alchemy\Phrasea\Utilities\Stopwatch;
use Symfony\Component\HttpFoundation\Request;

class V3SearchRawController extends Controller
{
    use JsonBodyAware;
    use DispatcherAware;

    public function helloAction(Request $request)
    {
        $stopwatch = new Stopwatch("controller");
        return Result::create($request, ['hello'])->createResponse([$stopwatch]);
    }

    public function searchRawAction(Request $request)
    {
        $stopwatch = new Stopwatch("controller");

        list($offset, $limit) = V3ResultHelpers::paginationFromRequest($request);

        $options = SearchEngineOptions::fromRequest($this->app, $request);
        $options->setFirstResult($offset);
        $options->setMaxResults($limit);

        $stopwatch->lap("set options");

        $this->getSearchEngine()->resetCache();

        $search_result = $this->getSearchEngine()->queryraw((string)$request->get('query'), $options);

        // queryraw returns also a stopwatch, get and remove it
        $stopwatch_es = $search_result['__stopwatch__'];
        unset($search_result['__stopwatch__']);

        $stopwatch->lap("queryraw");

        $this->getSearchEngine()->clearCache();

        $result = Result::create($request, $search_result);

        $stopwatch->lap("Result::create");

        return $result->createResponse([$stopwatch, $stopwatch_es]);
    }

    /**
     * @return SearchEngineInterface
     */
    private function getSearchEngine()
    {
        return $this->app['phraseanet.SE'];
    }
}
