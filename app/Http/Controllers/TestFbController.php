<?php

namespace App\Http\Controllers;

use App\Providers\FbRepository;
use Illuminate\Http\Request;

use Session;
use Illuminate\Support\Facades\Storage;



class TestFbController extends Controller
{
    protected $facebook;

    public function __construct()
    {
        $this->facebook = new FbRepository();
    }

    public function redirectToProvider()
    {

        return redirect($this->facebook->redirectTo());
        //dd(config('providers.facebook.app_id'));
    }

    public function handleProviderCallback()
    {


        $accessToken = $this->facebook->handleCallback();

        return redirect()->route('fb.index')->withStatus(__('You are Logged in'));

    }

    public function index()
    {
       //dd(Session::get('fb_user_access_token'));

        return view('TestFb.index');

    }

    public function indexPages()
    {
       //dd(Session::get('fb_user_access_token'));
       $data= $this->facebook->getPages(Session::get('fb_user_access_token'));


        return view('TestFb.indexPages' , ['data' => $data]);

    }

    public function indexPosts($id , $access)
    {

       $data= $this->facebook->getPagePosts($id,$access);

       //dd($data);

        return view('TestFb.indexPagePosts' , [
            'data' => $data,
            'id' => $id,
            'access' => $access

        ]);

    }

    public function createPost($id ,$access )
    {
        return view('TestFb.createpost' , [
            'id' => $id,
            'access' => $access
        ]);

    }

    public function savePost($id ,$access ,Request $req)
    {

        $schedule = false;
        if($req->submit == "sc"){
            $schedule= true;
            $req->validate([
            'ScTime' => 'required',
        ]);
        }


        $uploadType= "";
        $pathResult = "";

        if($req->img != null){
        $mime = $req->img->getMimeType();
        if(strstr($mime, "video/")){
            $uploadType= "video";
            }
            else if(strstr($mime, "image/")){
                $uploadType= "img";
            }
            else {
                return redirect()->route('fb.createPost', [ 'id'=> $id, 'access'=> $access])->withStatus(__('The file is not valid'));
            }


            $path = Storage::disk('local')->getAdapter()->getPathPrefix();



            $file =$req->img;
            $filename = $file->getClientOriginalName();
            $pathfile = $path ."public/files/";
            $fileresult=$file->move($pathfile, $filename);
            $pathResult = $pathfile."/".$filename;

        }
        $data = [
            'schedule' => $schedule,
            'type' => $uploadType,
            'file'=> $pathResult,
            'content' => $req->content,
            'time' =>  $req->ScTime
        ];
        $response= $this->facebook->post($id,$access,$data);
        if(is_string($response)){
        return redirect()->route('fb.createPost',['id'=>$id, 'access'=> $access])->withStatus(__($response));
    }
    else {
        return redirect()->route('fb.indexPagePosts',['id'=>$id, 'access'=> $access])->withStatus(__('The post was created successfully'));
    }
}

public function DeletePost($id ,$access , $idPage)
{
    $response = $this->facebook->delete($id,$access);
    //dd($response);
    return redirect()->route('fb.indexPagePosts',['id'=>$idPage, 'access'=> $access])->withStatus(__('The post was successfully removed'));

}


}
