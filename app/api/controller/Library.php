<?php


namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\News as NewsService;
use GuzzleHttp\Client;

class Library extends ApiBase
{
    public function index()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://highschool.schoolpi.net/api/vocational_lists/index', [
            'query' => [
                'schoolid' => 23,
                'mark' => 4,
            ]
        ]);
        $body = json_decode($response->getBody()->getContents(), true);
        $collegeColor = ['#F7C065', '#4AC282', '#3694D2', '#95AEFE'];
        $majorColor = ['#FCC261', '#C8DF73', '#A3B1DE', '#F87B65', '#89CCA9'];
        foreach ($body['college'] as $key => &$value) {
            $value['color'] = $collegeColor[$key % 4];
        }
        foreach ($body['major'] as $key => &$value) {
            $value['color'] = $collegeColor[$key % 4];
            foreach ($value['data'] as $k => &$v) {
                $v['color'] = $majorColor[$k % 5];
            }
        }
        return Show::success($body);
    }
    
    public function read()
    {
        $mid = input('param.mid', 0, 'intval');
        $client = new Client();
        $response = $client->request('GET', 'https://highschool.schoolpi.net/api/vocational_lists/major_view', [
            'query' => [
                'schoolid' => 23,
                'mid' => $mid,
            ]
        ]);
        $body = json_decode($response->getBody()->getContents(), true);
        return Show::success($body);
    }
}
