<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models;
use App\Repository\ActJoinRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActController extends Controller
{
    public function __construct()
    {
        $this->middleware('must_login');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Act\AddActRequest $request)
    {
        $act_data = $request->only("title", "phone", "name_format", "logo_id", "intro");

        $time_address = $request->only("time", "address");

        $time = json_decode($time_address['time'], 1);

        $address = json_decode($time_address['address'], 1);

        $single_time = $single_address = false;
        if (1 == count($time) && 1 == count($address)) {
            $act_data['vote_state'] = 1;
            $single_time = $single_address = true;
        }

        //如下没有进行try catch
        DB::beginTransaction();

        $result = Models\User::find(session()->get('user.id'))->act()->create($act_data);

        //时间、地点插入时是批量插入的，不能使用关联模型
        Models\ActTime::addTime($result->id, $time, $single_time);

        Models\ActAddress::addAddress($result->id, $address, $single_address);

        DB::commit();
        return success();
    }

    public function response(Requests\Act\ResponseActRequest $request)
    {
        $data = $request->only("actid", "time_voted", "address_voted", "name_format");

        $user = Models\User::find(2158);

        $act = Models\Act::find($data['actid']);

        //        DB::beginTransaction();

        $user->user_acts()->save($act, ['name_format' => $data['name_format']]);

        //responseActTime
        Models\ActTime::response($data['time_voted']);
        // responseActAddress
        Models\ActAddress::response($data['address_voted']);
        // addActJoinnum
        $act->join_num += 1;
        $act->save();

        return success();
    }

    public function reResponse(Requests\Act\ResponseActRequest $request)
    {
        $data = $request->only("actid", "time_voted", "address_voted", "name_format");

        $where = ['where' => ['actid' => $data['actid'], 'userid' => session()->get('user.id')]];

        DB::beginTransaction();

        //将joinact表的原先的时间地点票数降1
        if ($act_join = ActJoinRepository::read($where, true)) {
            if ($act_join['time_voted']) {
                Models\ActTime::where(['id' => $act_join['time_voted']])->increment('votes');
            }
            if ($act_join['address_voted']) {
                Models\ActAddress::where(['id' => $act_join['address_voted']])->increment('votes');
            }
        }

        //先判断该id是否属于当前act，防止恶意修改其他活动的time的votes
        if(
            (count(Models\ActTime::where(['id' => $data['time_voted'], 'actid' => $data['actid']])->get()) == 0)
            &&
            (count(Models\ActAddress::where(['id' => $data['address_voted'], 'actid' => $data['actid']])->get()) == 0)
        ) {
            return fail('传递的时间和地点id不存在');
        }

        Models\ActTime::where(['id' => $data['time_voted'], 'actid' => $data['actid']])->increment('votes');

        Models\ActAddress::where(['id' => $data['address_voted'], 'actid' => $data['actid']])->increment('votes');

        ActJoinRepository::updateData($data, ['id' => $act_join['id']]);

        DB::commit();

        return success();
    }

    public function join()
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function actInvited()
    {

    }

    public function timeVote()
    {

    }

    public function joinDetail()
    {

    }

    public function createQRcode()
    {

    }


    public function createUserResponse()
    {

    }

    public function createrRejectJoin()
    {

    }


    public function myAct()
    {

    }

}
