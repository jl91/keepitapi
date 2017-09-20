<?php

namespace ApiKeep\Controller;

use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ApiController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, $args)
    {

        $method = $request->getMethod();
        $method = "process{$method}";

        return $this->$method($request, $response, $args);

    }

    public function processGET(Request $request, Response $response, array $args = [])
    {
        // Sample log message
        $this->logger->info("Slim-Skeleton '/' route");

        $headers = new \Slim\Http\Headers([
            'Content-Type' => 'application/json'
        ]);

        $file = realpath(implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            '..',
            '..',
            'data',
            'wrangle.json'
        ]));

        $data = file_get_contents($file);
        $queryParams = $request->getQueryParams();
        if (!empty($queryParams)) {
            $data = $this->sortData($data, $queryParams);
        }

        $response->headers = $headers;
        $response->withStatus(200);
        $response->write($data);
        return $response;

    }

    public function sortData(string $data, array $queryParams)
    {
        $data = json_decode($data);

        $sort = $queryParams['sort'];
        $sortType = 'plus';
        if (strpos($sort, '-') !== false) {
            $sortType = 'minus';
        }
        $sortField = str_replace(['-'], '', $sort);


        usort($data, function ($a, $b) use ($sortField) {
            if (!property_exists($a, $sortField)) {
                throw new \InvalidArgumentException("Property {$sortField} does not exists", 500);
            }

            return $a->$sortField == $b->$sortField ? 0 : ($a->$sortField > $b->$sortField) ? 1 : -1;
        });

        if ($sortType == 'minus') {
            $data = array_reverse($data);
        }

        return json_encode($data);

    }

    public function processPOST(Request $request, Response $response, array $args = [])
    {

    }

    public function processPUT(Request $request, Response $response, array $args = [])
    {

    }

    public function processDELETE(Request $request, Response $response, array $args = [])
    {

    }

}