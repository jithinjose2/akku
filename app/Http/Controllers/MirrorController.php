<?php
/**
 * Created by PhpStorm.
 * User: jithinjose
 * Date: 22/6/16
 * Time: 1:24 PM
 */

namespace Akku\Http\Controllers;

use Akku\Http\Requests;
use Illuminate\Http\Request;
use Thing, Setting;


class MirrorController  extends Controller
{
    public function index()
    {
        \Debugbar::disable();
        return view('mirror.index');
    }

    public function proxy(Request $request)
    {
        if($request->has('url')) {
            $url = $request->get('url');
            $url = urldecode($url);
            $url = file_get_contents($url);
            print_r($url);
        }
        die();
    }

    public function calendar()
    {
        $url = 'https://p01-calendarws.icloud.com/ca/subscribe/1/n6x7Farxpt7m9S8bHg1TGArSj7J6kanm_2KEoJPL5YIAk3y70FpRo4GyWwO-6QfHSY5mXtHcRGVxYZUf7U3HPDOTG5x0qYnno1Zr_VuKH2M';
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Accept-Language: en-US,en;q=0.8rn" .
                    "Accept-Encoding: gzip,deflate,sdchrn" .
                    "Accept-Charset:UTF-8,*;q=0.5rn" .
                    "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:19.0) Gecko/20100101 Firefox/19.0 FirePHP/0.4rn",
                "ignore_errors" => true	 //Fix problems getting data
            ),
            //Fixes problems in ssl
            "ssl" => array(
                "verify_peer"=>false,
                "verify_peer_name"=>false
            )
        );

        $context = stream_context_create($opts);
        $content = file_get_contents($url ,false,$context);

        //If http response header mentions that content is gzipped, then uncompress it
        foreach($http_response_header as $c => $h)
        {
            if(stristr($h, 'content-encoding') and stristr($h, 'gzip'))
            {
                //Now lets uncompress the compressed data
                $content = gzinflate( substr($content,10,-8) );
            }
        }

        echo $content;
        die();
    }
}