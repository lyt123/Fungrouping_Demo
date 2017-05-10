<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repository\TeamJoinRepository;
use App\Repository\TeamRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class joinTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $result = TeamJoinRepository::getJoinInfo(['team_id' => $id]);

        return success($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req, $team_id)
    {
        session()->put('user.id', 2157);//暂时填坑
        $data = $req->only('expect_score', 'phone');

        $data['team_id'] = $team_id;

        $data['user_id'] = session()->get('user.id');

        DB::beginTransaction();

        $result = TeamJoinRepository::store($data)->id;

        TeamRepository::updateData(['num_join' => DB::raw('num_join + 1')], ['id' => $team_id]);

        DB::commit();

        return success($result['id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
    public function destroy(Request $req, $team_id, $join_team_id)
    {
        session()->put('user.id', 2157);//暂时填坑

        if (TeamJoinRepository::read(['team_id' => $team_id], true)['user_id'] == session()->get('user.id')) {
            TeamJoinRepository::destroyData(['id' => $join_team_id]);

            TeamRepository::updateData(['num_join' => DB::raw('num_join - 1')], ['id' => $team_id]);

            DB::commit();
            return success();
        }

        return fail();
    }

    public function teamInvited()
    {
        //        $result = TeamJoinRepository::read(['where' => ['user_id' => session()->get('user.id')]]);

        $result = TeamJoinRepository::teamInvited(['user_id' => session()->get('user.id')]);

        return success($result);
    }
}
