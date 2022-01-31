<?php

namespace App\Providers;

use Facebook\Facebook;
use Session;
class FbRepository
{
    protected $facebook;

    public function __construct()
    {
        $this->facebook = new Facebook([
            'app_id' => 976552469932865,
            'app_secret' => '3c0ef43f259f94f4f9dce597b0d4cb89',
            'default_graph_version' => 'v2.10'
        ]);


    }

    public function redirectTo()
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        $permissions = [
            'pages_manage_posts',
            'pages_read_engagement'
        ];

        $redirectUri = config('app.url') . '/auth/facebook/callback';

        return $helper->getLoginUrl($redirectUri, $permissions);
    }
    public function handleCallback()
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        if (request('state')) {
            $helper->getPersistentDataHandler()->set('state', request('state'));
        }

        try {
            $accessToken = $helper->getAccessToken();
        } catch(FacebookResponseException $e) {
           return("Graph returned an error: {$e->getMessage()}");
        } catch(FacebookSDKException $e) {
          return("Facebook SDK returned an error: {$e->getMessage()}");
        }

        if (!isset($accessToken)) {
          return('Access token error');
        }

        if (!$accessToken->isLongLived()) {
            try {
                $oAuth2Client = $this->facebook->getOAuth2Client();
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (FacebookSDKException $e) {
              return("Error getting a long-lived access token: {$e->getMessage()}");
            }
        }

        Session::put('fb_user_access_token', (string) $accessToken->getValue());

        return $accessToken->getValue();

        //store acceess token in databese and use it to get pages
    }

    public function getPages($accessToken)
    {
    $pages = $this->facebook->get('/me/accounts', $accessToken);

    $pages = $pages->getGraphEdge()->asArray();
    $result = array();

    foreach ($pages as $page) {
    $dates = $this->facebook->get('/'.$page['id'].'?fields=start_info', $accessToken)->getGraphNode()->asArray();
    $page['start_info'] =$dates;
    array_push($result,$page);
}

return $result;


}

public function getPagePosts($idPage,$accessToken)
{
    $posts = $this->facebook->get('/'.$idPage.'/feed?fields=id,created_time,message,attachments{type}&limit=15', $accessToken)->getGraphEdge()->asArray();
    $postsS = $this->facebook->get('/'.$idPage.'/scheduled_posts', $accessToken)->getGraphEdge()->asArray();
    $post = [
        'postsP' => $posts,
        'postsS' => $postsS
     ];
     //dd($post);
    return $post;

}
public function post($accountId, $accessToken, $post)
{


    $postInfo= [
        'file'=> $post['file'],
        'time' => $post['time'],
        'content' => $post['content'],
        'schedule' => $post['schedule'],
    ];

    if($post['type'] == "img") {
    $result = $this->upload($accountId, $accessToken, $postInfo ,"photos");
}
else if ($post['type'] == "video") {

    $result = $this->upload($accountId, $accessToken, $postInfo ,"videos");

}
else {
    $data = [

        'message' => $post['content'],

    ];
    if($post['schedule']){
    $data = [
        'message' => $post['content'],
        'published' => false,
        'scheduled_publish_time'=> $post['time']

    ];

}
//dd($data);
    $result = $this->postData($accessToken, "$accountId/feed", $data);
}


    try {
        return $result;
    } catch (\Exception $exception) {
        //notify user about error
        return $exception;
    }
}

private function upload($accountId, $accessToken, $postInfo, $uploadtype)
    {

            if (file_exists($postInfo['file'])) {
                //dd("hi");
            $data = [
                    'message' => $postInfo['content'],
                    'source' => $this->facebook->fileToUpload($postInfo['file']),
                ];
            if($postInfo['schedule']==true){
            $data = [
                'message' => $postInfo['content'],
                'source' => $this->facebook->fileToUpload($postInfo['file']),
                'scheduled_publish_time'=> strtotime($postInfo['time']),
                'published' => false,
            ];

        }

            try {
                $response = $this->postData($accessToken, $accountId."/".$uploadtype, $data);
                if ($response) $attachments[] = $response['id'];
            } catch (\Exception $exception) {
               return ("Error while posting: {$exception->getMessage()}"."//".$exception->getCode());
            }
        }

        return $response;
    }


    private function postData($accessToken, $endpoint, $data)
    {
        try {
            $response = $this->facebook->post(
                $endpoint,
                $data,
                $accessToken
            );
            return $response->getGraphNode();

        } catch (FacebookResponseException $e) {
            return ($e->getMessage().".". $e->getCode());
        } catch (FacebookSDKException $e) {
            return($e->getMessage() .".". $e->getCode());
        }
    }


    public function delete($id,$accessToken)
    {
        try {
            $response = $this->facebook->delete(
                "$id/",
                $params = ['access_token' => $accessToken]
            );
            return $response->getGraphNode();

        } catch (FacebookResponseException $e) {
            return ($e->getMessage().".". $e->getCode());
        } catch (FacebookSDKException $e) {
            return($e->getMessage() .".". $e->getCode());
        }
    }
}
