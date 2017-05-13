<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models;
use App\Repository\ActAddressRepository;
use App\Repository\ActJoinRepository;
use App\Repository\ActRepository;
use App\Repository\ActTimeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActController extends Controller
{
    public function __construct()
    {
        //        $this->middleware('must_login');
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
        if (
            (count(Models\ActTime::where(['id' => $data['time_voted'], 'actid' => $data['actid']])->get()) == 0)
            &&
            (count(Models\ActAddress::where(['id' => $data['address_voted'], 'actid' => $data['actid']])->get()) == 0)
        ) {
            return fail(trans('time_and_address_not_belong_to_act'));
        }

        Models\ActTime::where(['id' => $data['time_voted'], 'actid' => $data['actid']])->increment('votes');

        Models\ActAddress::where(['id' => $data['address_voted'], 'actid' => $data['actid']])->increment('votes');

        ActJoinRepository::updateData($data, ['id' => $act_join['id']]);

        DB::commit();

        return success();
    }

    public function createUserResponse(Requests\Act\CreateUserResponseRequest $requests)
    {
        $data = $requests->only('id', 'timeid', 'addressid');

        ActRepository::checkExist(
            ['id' => $data['id'], 'userid' => session()->get('user.id')], 'tip.not_act_creater'
        );

        //判断该time/address是否属于当前act
        ActTimeRepository::checkExist(['actid' => $data['id'], 'id' => $data['timeid']], 'tip.timeid_forbid');
        ActAddressRepository::checkExist(['actid' => $data['id'], 'id' => $data['addressid']], 'tip.addressid_forbid');

        array_filter_except($data);

        \DB::transaction(function () use ($data) {
            if ($data['timeid'])
                ActTimeRepository::updateData(['choose' => 1], $data['timeid']);
            if ($data['addressid'])
                Models\ActAddress::where(['id' => $data['addressid']])->update(['choose' => 1]);

            Models\Act::where(['id' => $data['id']])->update(['vote_state' => 1]);
        });

        return success();
    }

    public function myAct(Request $request)
    {
        $limit = $request->input('count');

        $result = ActRepository::myAct(session()->get('user.id'), $limit);

        return success($result);
    }

    public function join(Requests\Act\JoinActRequest $request)
    {
        $data = $request->only('name_format', 'id');

        $data['actid'] = $data['id'];
        //checkexist
        $data['userid'] = session()->get('user.id');

        ActJoinRepository::checkExist(['actid' => $data['id'], 'userid' => $data['userid']], 'tip.already_join_act', 1);

        //transaction
        DB::beginTransaction();

        //add join num
        Models\Act::where(['id' => $data['id']])->increment('join_num');

        //join
        ActJoinRepository::store($data);

        DB::commit();
        return success();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = session()->get('user.id');
        $result = ActRepository::detail($id, $user_id);

        $result['createuser'] = $result['user']['username'];

        $result['is_createuser'] = $result['userid'] == $user_id ? 1 : 0;

        $result['is_joined'] = $result['act_user'][0]['power'] != 1 ? 1 : 0;

        $result['username'] = $result['user']['username'];
        unset($result['user']);

        if ($result['vote_state'] == 1) {
            foreach ($result['act_time'] as $item) {
                if ($item['choose'] == 1) {
                    $result['starttime'] = $item['starttime'];
                    $result['timelast'] = $item['timelast'];
                }
            }
        } else {
            foreach ($result['act_time'] as &$item) {

                $user_time_voted = explode('-', $result['act_user'][0]['time_voted']);

                $item['vote_for'] = in_array($item['id'], $user_time_voted) ? 1 : 0;
            }
        }

        unset($result['act_user']);
        return success($result);
    }

    public function timeVote()
    {
        //路由出错了
        $act_id = 394;
        $result = ActRepository::timeVote($act_id);

        return success($result);
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

    public function joinDetail()
    {

    }

    public function createQRcode()
    {

    }


    public function createrRejectJoin()
    {

    }


}
