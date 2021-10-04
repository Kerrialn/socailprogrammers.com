<?php

namespace App\Service;

use App\Entity\Event;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Stream;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class StaticGoogleMapService
{

    public function __construct(
        private Client $client,
        private Filesystem $filesystem
    ) {
    }

    public function getStaticImage(Event $event, string $key) : string
    {
        $params = [
            'query' => [
                'center' => $event->getLatitude() . ',' . $event->getLongitude(),
                'size' => '600x300',
                'markers' => 'size:mid|color:red|scale:1',
                'maptype' => 'roadmap',
                'zoom' => 12,
                'format' => 'jpg',
                'key' => $key
            ]
        ];

        $response = $this->client->request( Request::METHOD_GET, 'https://maps.googleapis.com/maps/api/staticmap?', $params);

        $title = str_replace(' ', '-', $event->getTitle());
        $content = $response->getBody()->getContents();
        $path = __DIR__.'/../../public/image/maps/'.$title.'.jpg';

        $this->filesystem->dumpFile($path, $content);

        return $path;
    }
}
