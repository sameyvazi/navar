<?php
namespace common\components;

use Yii;

class DigiFcCrawler
{
    /**
     * get instance of Crawler object for each url want to crawl
     * 
     * @return Crawler
     */
    public function getCrawler($url, $loadDom)
    {
        try
        {
            if ($loadDom == '1')
            {
                return $this->getWebsiteDomContent($url);
            }
            else
            {
                $crawler = new Crawler();
                $crawler->addHtmlContent($this->getWebsiteRawContent($url));
                return $crawler;
            }
        } catch (\Exception $ex) {
            Yii::error("Error loading target url: ({$this->id}) {$url}", 'Get Crawler');
            return false;
        }

    }
    
    protected function getWebsiteDomContent($url)
    {
        return (new Client())->request('GET', $url);
    }
    
    protected function getWebsiteRawContent($url)
    {
        return (new \GuzzleHttp\Client())->request('GET', $url, ['connect_timeout' => 60])->getBody();
        /*$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_USERAGENT, "digifc");
        $output = curl_exec($ch);
        curl_close($ch);
        var_dump($url, $output);exit;
        return $output;*/
    }
    
    protected function concateBaseUrlWithRelative($url, $relativeUrl)
    {
        /* return if already absolute URL */
        if (parse_url($relativeUrl, PHP_URL_SCHEME) != '') return $relativeUrl;

        /* queries and anchors */
        if (isset($relativeUrl[0]) && ($relativeUrl[0]=='#' || $relativeUrl[0]=='?')) return $url.$relativeUrl;

        /* parse base URL and convert to local variables:
           $scheme, $host, $path */
        extract(parse_url($url));

        /* remove non-directory element from path */
        $path = preg_replace('#/[^/]*$#', '', $path);

        /* destroy path if relative url points to root */
        if (isset($relativeUrl[0]) && $relativeUrl[0] == '/') $path = '';

        /* dirty absolute URL */
        $abs = "$host$path/$relativeUrl";

        /* replace '//' or '/./' or '/foo/../' with '/' */
        $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
        for($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {}

        /* absolute URL is ready! */
        return $scheme.'://'.$abs;
    }
    
    public function getLinks($url, $pattern, $loadDom)
    {
        
        if(!$crawler = $this->getCrawler($url, $loadDom))
        {
            return [];
        }
        $links = [];
        $crawler->filter($pattern)->each(function(Crawler $node) use (&$links, $url){
            $title = trim($node->text());
            $link = $node->attr('href');
            if ($link != '' && $title != '')
            {
                $link = $this->concateBaseUrlWithRelative($url, $link);
                $links[] = [
                    'label' => $this->normalizeText($title),
                    //'link' => trim($link),
                    'link' => urldecode(trim($link)),
                ];
            }
            
        });
        
        return $links;
    }
    
    public function getImage(Crawler $crawler, $url, $pattern)
    {
        $node = $crawler->filter($pattern)->first();
        if (!$node->count())
        {
            return '';
        }
        $image = $node->attr('src');
        if ($image != '')
        {
            return $this->concateBaseUrlWithRelative($url, $image);
        }
        
        return '';
    }
    
    public function encodeUrl($url)
    {
        
        $urlPart = parse_url($url);
        
        if (!isset($urlPart['host']))
        {
            return $url;
        }
        
        $newUrl = "{$urlPart['scheme']}://{$urlPart['host']}" . (isset($urlPart['port']) ? ":{$urlPart['port']}" : '');

        if (isset($urlPart['path']))
        {
            $path = explode('/', $urlPart['path']);
            $path = array_map(function($value){
                return urlencode($value);
            }, $path);
            $newUrl .= implode('/', $path);
        }

        if (isset($urlPart['query']))
        {
            parse_str($urlPart['query'], $params);
            $newUrl .= '?'.http_build_query($params, null, '&', PHP_QUERY_RFC3986);
        }

        return $newUrl;
    }
    
    public function getSectionText(Crawler $crawler, $pattern)
    {
        $node = $crawler->filter($pattern);
        if ($node->count())
        {
            return $this->normalizeText($node->text());
        }
        
        return '';
    }
    
    public function downloadImage($url)
    {
        
        $allowedExt = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
        try
        {
            $imageExt = substr(trim(strtolower(pathinfo($url, PATHINFO_EXTENSION))), 0, 3);
            if (!in_array($imageExt, $allowedExt))
            {
                return '';
            }
            
            if (empty($imageExt))
            {
                $imageExt = 'jpg';
            }
            
            $path = '';
            
            $fileName = "/" . uniqid(null, true) . ".{$imageExt}";
            
            $path .= "/{$fileName}";
            $opts = [
                'http' => [
                    'method' => "GET",
                    'user_agent' => 'digifc',
                ]
            ];

            $file = fopen ($url, 'rb', false, stream_context_create($opts));
            if ($file) {
                $newf = fopen ($path, 'wb');
                if ($newf) {
                    while(!feof($file)) {
                        fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                    }
                }
            }
            if ($file) {
                fclose($file);
            }
            if ($newf) {
                fclose($newf);
            }
            
            return $fileName;
        } catch (\Exception $ex) {
            return false;
        }
        
    }
    
    protected function normalizeText($text)
    {
        return str_replace('ي', 'ی', trim($text));
    }
}

