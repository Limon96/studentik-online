<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = 'Студентик – сервис консультации и сопровождения студентов в учёбе';
        $description = 'Консультация, подбор литературы и другая помощь по всем учебным дисциплинам';
        $keywords = '';
        $current_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $domain = $_SERVER['SERVER_NAME'];

        return view('welcome', compact(
            'title',
            'description',
            'keywords',
            'current_url',
            'domain'
        ));
    }

    public function test()
    {
        dd(
            $this->checkEmail('nicker08@inbox.ru'),
            $this->checkEmail('nick.prokopenko@rambler.ru'),
            $this->checkEmail('asdasdgd@asdfs.asdbnm'),
        );
    }

    private function getCredentialsFromSendBox()
    {
        $url = 'https://mailer-api.i.bizml.ru/oauth/access_token';

        $params = array(
            'grant_type' => 'client_credentials',
            'client_id' => 'dda6451755d20dbb9920074cc7c01d10',
            'client_secret' => '419c6a52cfe3d23be91db89fdb5739d6',
        );
        $result = file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        )));

        return $this->jsonDecode($result);
    }

    private function jsonDecode($data)
    {
        return json_decode($data);
    }

    private function checkEmail($email)
    {
        $credentials = $this->getCredentialsFromSendBox();

        $url = 'https://mailer-api.i.bizml.ru/verifier-service/get-single-result/?email=' . urlencode($email);

        $result = file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method'  => 'GET',
                'header'  => "Authorization: {$credentials->token_type} {$credentials->access_token}"
            )
        )));

        $result = $this->jsonDecode($result);

        return $result;
    }
}
