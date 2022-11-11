@extends('front.app')
@section('content')

    <main>
        <div class="container">
            <div class="row align-items-center flex-column api-page-title">
                <h1>Simple TalkEarn API (v1)</h1>
            </div>
            <div class="api-page">
                <ul>
                    <hr>
                    <li>
                        <p>GET all categories:</p>
                        <a href="{{ Request::url() . '/api/categories/'}}" target="_blank">
                            {{ Request::url() . '/api/categories/'}}
                        </a>
                    </li>
                    <li>
                        <p>GET category by id:</p>
                        <a href="{{ Request::url() . '/api/category/1'}}" target="_blank">
                            {{ Request::url() . '/api/category/{id}'}}
                        </a>
                    </li>
                    <hr>

                    <li>
                        <p>GET all experts:</p>
                        <a href="{{ Request::url() . '/api/experts'}}" target="_blank">
                            {{ Request::url() . '/api/experts'}}
                        </a>
                    </li>

                    <li>
                        <p>GET by category id:</p>
                        <a href="{{ Request::url() . '/api/experts?category_id=1'}}" target="_blank">
                            {{ Request::url() . '/api/experts?category_id=1'}}
                        </a>
                    </li>

                    <li>
                        <p>GET expert by id:</p>
                        <a href="{{ Request::url() . '/api/expert/1'}}" target="_blank">
                            {{ Request::url() . '/api/expert/{id}'}}
                        </a>
                    </li>

                    <li>
                        <p>GET featured experts:</p>
                        <a href="{{ Request::url() . '/api/experts/featured'}}" target="_blank">
                            {{ Request::url() . '/api/experts/featured'}}
                        </a>
                    </li>

                    <li>
                        <p>GET search expert:</p>
                        <a href="{{ Request::url() . '/api/experts/search?find=yoga'}}" target="_blank">
                            {{ Request::url() . '/api/experts/search?find=yoga'}}
                        </a>
                    </li>

                    <li>
                        <p>POST add expert:</p>
                        <a href="#" target="_blank">
                            {{ Request::url() . '/api/expert'}}
                        </a>
                    </li>
                </ul>

                <div>
                <h5>Example Value | Schema</h5>
<pre style="font-family: 'Colibri'; line-height: 1.5; font-size: 18px">
<code>
{
    "categoryId" : 0
    "name": "string",
    "description" : "string",
    "profession" : "string",
    "wallet" : "string",
    "region" : "string",
    "duration" : "string",
    "price" : 0,
    "image" : file,
    "videoUrl" : "string",
    "services" : [
        {
            "name" : "string",
            "image" : file,
        },
        {
            "name" : "string",
            "image" : file,
        },
        {
            "name" : "string",
            "image" : file,
        },
    ]
}
</code>
</pre>
                </div>
            </div>
        </div>
    </main>

@stop


<iframe width=\"100%\" height=\"315\" src=\"https://www.youtube.com/embed/2fknwD3NJLU\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>