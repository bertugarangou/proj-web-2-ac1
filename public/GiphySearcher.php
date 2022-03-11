<?php

    require_once __DIR__ . '/vendor/autoload.php';
    use GuzzleHttp\Client as Client;

    class GiphySearcher{
        private $url = 'api.giphy.com/v1/gifs/search';
        private $APIKey = "R0OsrTT4b64wOXbRAazkISyqoXbzWdsc";


        public function connect(string $input, string $language, int $limit): array{
            $client = new Client();
            $config = array('query' => ['api_key' => $this->APIKey, 'q' => $input, 'limit' => $limit, 'lang' => $language], 'verify' => false);

            $response = $client->request('GET', $this->url, $config);
            $response2 = $response->getBody()->getContents();
            return (json_decode($response2, true))['data'];
        }


    }
?>

