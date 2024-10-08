<?php

function savehtml($url)
{
    if (!is_file(TMP.'/'.url2fn($url))) {
        savefile($url, TMP.'/'.url2fn($url));
    }
}

function savefile($url, $filename)
{
    if (!is_file($filename)) {
        print "$url\n";
        if (!is_dir(dirname($filename))) {
            mkdir(dirname($filename), 0755, true);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_REFERER, ROZHLAS);
        curl_setopt($ch, CURLOPT_USERAGENT, 'php '.phpversion());
        curl_setopt($ch, CURLOPT_ENCODING, '');
        file_put_contents($filename, curl_exec($ch));
        curl_close($ch);
    }
}

function asciize($str)
{
    $str = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $str));
    $str = preg_replace('/[^a-z0-9.]/', ' ', $str);
    $str = preg_replace('/\s\s+/', ' ', $str);
    $str = str_replace(' ', '-', trim($str));
    return $str;
}

function url2fn($url)
{
    $url = str_replace(ROZHLAS, '', $url);
    return preg_replace('/[^a-z0-9]*/', '', $url).'.html';
}

function get_img($url)
{
    $linky = array();

    $dom = new DOMDocument();
    $dom->loadHTML(file_get_contents(TMP.'/'.url2fn($url)));
    $xpath = new DOMXPath($dom);
    $obrazky = $xpath->query("//div[@id='article']/div[@class='image']");


    foreach ($obrazky as $obrazek) {
        $newdoc = new DOMDocument();
        $cloned = $obrazek->cloneNode(true);
        $newdoc->appendChild($newdoc->importNode($cloned, true));
        $chunk = new DOMXPath($newdoc);
        $nodeposition = count($xpath->query('preceding::*', $obrazek));


        $l = $chunk->query('//a');
        $d = $chunk->query('//p[@class="description"]/span');
        if ($d->item(0)) {
            $popis = $d->item(0)->nodeValue;
        }
        if ($l->item(0)) {
            $link = $l->item(0)->getAttribute("href");
            $orig = preg_replace('/(.*_obrazek\/[0-9]+)\-\-.*/', '\1', $link).'.jpeg';
            $id = basename($orig, '.jpeg');

            $size = getimagesize(TMP.'/'.$id.'.jpeg');

            $linky[$id] = array(
                'poradi' => $nodeposition,
                'id' => $id,
                'imgwidth' => $size[0],
                'imgheight' => $size[1],
                'orig' => $orig,
                'link' => $link,
                'popis' => $popis,
                'popis_ascii' => asciize($popis),
            );
        }
    }

    return $linky;
}

function get_mp3($url)
{
    $linky = array();
    $dom = new DOMDocument();
    $dom->loadHTML(file_get_contents(TMP.'/'.url2fn($url)));
    $xpath = new DOMXPath($dom);
    $audio = $xpath->query("//div[@class='audio']");

    foreach ($audio as $mp3) {
        $nodeposition = count($xpath->query('preceding::*', $mp3));
        $newdoc = new DOMDocument();
        $cloned = $mp3->cloneNode(true);
        $newdoc->appendChild($newdoc->importNode($cloned, true));
        $chunk = new DOMXPath($newdoc);

        $l = $chunk->query("//p[@class='embed']/a");

        $d = $chunk->query('//p[@class="description"]');
        if ($d->item(0)) {
            $popis = $d->item(0)->nodeValue;
        }

        if ($l->item(0)) {
            $link = $l->item(0)->getAttribute("href");
            $mp3 = preg_replace('/.*audio\/([0-9]+).*/', 'https://media.rozhlas.cz/_audio/\1.mp3', $link);
            array_push($linky, array(
            'poradi' => $nodeposition,
            'url' => $mp3,
            'id' => basename($mp3, '.mp3'),
            'popis' => $popis,
            ));
        }
    }
    return $linky;
}

function get_categories()
{
    $index = '/hlas/portal/';
    savehtml(ROZHLAS.$index);
    $linky = array();
    $dom = new DOMDocument();
    $dom->loadHTML(file_get_contents(TMP.'/'.url2fn($index)));
    $xpath = new DOMXPath($dom);
    $odkazy = $xpath->query("//div[@id='box-categories-savci' or @id='box-categories-hmyz' or @id='box-categories-obojzivelnici']/ul/li/h5/a");

    foreach ($odkazy as $odkaz) {
        $linky[$odkaz->getAttribute("href")] = $odkaz->nodeValue;
    }
    return $linky;
}

function get_members($url)
{
    savehtml(ROZHLAS.$url);
    $linky = array();
    $dom = new DOMDocument();
    $dom->loadHTML(file_get_contents(TMP.'/'.url2fn($url)));
    $xpath = new DOMXPath($dom);
    $odkazy = $xpath->query("//div[@class='content']/*/ul/li/h3/a");

    foreach ($odkazy as $odkaz) {
        $linky[$odkaz->getAttribute("href")] = preg_replace('/ \(VIDEO\)$/', '', $odkaz->nodeValue);
    }
    return $linky;
}

function get_zvireinfo($url)
{
    $navrat = array();
    $dom = new DOMDocument();
    $dom->loadHTML(file_get_contents(TMP.'/'.url2fn($url)));
    $xpath = new DOMXPath($dom);
    $odstavce = $xpath->query("//div[@id='article']/p");
    $zakladniudaje = false;
    $poradi = 0;

    foreach ($odstavce as $odstavec) {
        $nodeposition = count($xpath->query('preceding::*', $odstavec));
        $text = trim(preg_replace(['(\s+)u', '(^\s|\s$)u'], [' ', ''], $odstavec->nodeValue));
        if (strlen($text) > 0 and !preg_match('/^Video:/', $text)) {
            array_push($navrat, array('poradi' => $nodeposition, 'text' => $text));
            if ($text == 'Základní údaje') {
                $zakladniudaje = true;
            } else {
                $poradi = $nodeposition;
            }
        }
    }

    if (!$zakladniudaje) {
        $all = $xpath->query("//div[@id='article']");
        $text = trim(preg_replace(['(\s+)u', '(^\s|\s$)u'], [' ', ''], $all[0]->nodeValue));
        if (preg_match('/Základní údaje/', $text)) {
            $text = preg_replace('/.*Základní údaje/s', '', $text);
            $text = trim(preg_replace('/Autor:.*/s', '', $text));
            $poradi = $poradi + count($navrat);
            array_push($navrat, array('poradi' => ($poradi), 'text' => 'Základní údaje'));
            $poradi = $poradi + count($navrat);
            array_push($navrat, array('poradi' => ($poradi), 'text' => $text));
        }
    }

    foreach ($navrat as $key => $value) {
        foreach ($navrat as $keyb => $valueb) {
            if ($value['text'] == $valueb['text'] and $key != $keyb) {
                unset($navrat[$key]);
            }
        }
    }

    return($navrat);
}

function get_nahravkyinfo($url)
{
    $navrat = array();
    $dom = new DOMDocument();
    $dom->loadHTML(file_get_contents(TMP.'/'.url2fn($url)));
    $xpath = new DOMXPath($dom);
    $odstavce = $xpath->query("(//div[@id='article']/div[contains(@style,'color: grey')]/span)[position()<last()]");
    foreach ($odstavce as $odstavec) {
        $text = trim(preg_replace(['(\s+)u', '(^\s|\s$)u'], [' ', ''], $odstavec->nodeValue));
        array_push($navrat, $text);
    }
    return($navrat);
}

function copyToDir($pattern, $dir)
{
    foreach (glob($pattern) as $file) {
        $dest = realpath($dir) . DIRECTORY_SEPARATOR . basename($file);
        if (is_file($file)) {
            copy($file, $dest);
        }
    }
}

function sort_by_jmeno($a, $b)
{
    $coll = collator_create('cs_CZ.UTF-8');
    return collator_compare($coll, $a['jmeno'], $b['jmeno']);
}

function sort_by_lat_jmeno($a, $b)
{
    $coll = collator_create('cs_CZ.UTF-8');
    return collator_compare($coll, $a['l'], $b['l']);
}

function get_rubrika($url)
{
    $navrat = array();
    $dom = new DOMDocument();
    $dom->loadHTML(file_get_contents(TMP.'/'.url2fn($url)));
    $xpath = new DOMXPath($dom);
    $rubrika = $xpath->query("//div[@id='article']/div[@class='date']/span[@class='navigation-rubrika']/a");
    foreach ($rubrika as $foo) {
        return(preg_replace('/ \(.*/', '', $foo->nodeValue));
    }
}
