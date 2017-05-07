<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Repository\TeamJoinRepository;
use App\Repository\TeamRepository;
use App\Services\UploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $title = $req->input('title');

        $result = TeamRepository::read(
            [
                'where' => ['title' => ['like', '%'.$title.'%']],
                'has_like' => true
            ]
        );

        return success($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $data = $req->except('expect_score');

        DB::beginTransaction();
        session()->put('user.id', '2157');

        //添加活动
        $data['user_id'] = session()->get('user.id');
        $data['ctime'] = Carbon::now()->toDateTimeString();
        $data['starttime'] = strtotime($data['starttime']);
        $result = TeamRepository::store($data);

        //上传图片(可以仿照趣组队thinkphp后台，那里是上传了cover以及多张picture，处理较为复杂)
        $path = UploadService::upload(
            $req,
            ['name' => 'cover', 'position' => "upload/img/user{$data['user_id']}/team/"]
        );

        //添加封面图
        TeamRepository::updateData(
            ['cover' => $path],
            ['id' => $result['id']]
        );

        //发布人自动加入活动
        TeamJoinRepository::store(array(
            'team_user_id' => $data['user_id'],
            'team_id' => $result['id'],
            'user_id' => $data['user_id'],
            'expect_score' => $req->input('expect_score'),
            'is_read' => 1
        ));

        DB::commit();
        return success(['team_id' => $result['id']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = TeamRepository::teamDetail($id);
//        $comment = Team::find(33);
//        echo $comment->user->username;
        return success($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $data = $req->only('title', 'intro', 'phone', 'num_max',
            'starttime', 'timelast', 'address', 'group_num', 'logo_id');

        DB::beginTransaction();

        //修改活动信息
        $data['starttime'] = strtotime($data['starttime']);
        TeamRepository::updateData($data, ['id' => $id]);

        //上传图片(可以仿照趣组队thinkphp后台，那里是上传了cover以及多张picture，处理较为复杂)
        $path = UploadService::upload(
            $req,
            ['name' => 'cover', 'position' => "upload/img/user{$data['user_id']}/team/"]
        );

        //TODO 修改封面图(PUT模式不支持上传图片，要单开个接口)

        DB::commit();
        return success();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TeamRepository::destroyData(['id' => $id]);

        return success();
    }
}
