<?php
return [

    /*
    |—————————————————————————————————————
    | statusCode tips
    |—————————————————————————————————————
    */
    '200'                               =>             '操作成功',
    '403'                               =>             '无权限操作',
    '404'                               =>             '什么也没有',
    '405'                               =>             '请求方式错误',
    '500'                               =>             '尴尬，服务器出错',



    /*
    |—————————————————————————————————————
    | logic tips
    |—————————————————————————————————————
    */
    //user
    'send_message_fail'                 =>             '短信发送失败',
    'code_error'                        =>             '验证码已失效或错误',
    'fail'                              =>             '操作失败',
    'login_success'                     =>             '登录成功',
    'validate_fail'                     =>             '账号或密码错误',
    'upload_picture_fail'               =>             '图片上传失败',
    'forget_password_send_message_fail' =>             '手机号错误',
    'origin_password_wrong'             =>             '原密码错误',
    'not_check_code'                    =>             '请先验证手机号',
    'not_login'                         =>             '未登录',

    //act
    'not_act_creater'                   => '不是该活动创建者',
    'time_and_address_not_belong_to_act'=> 'time_and_address_not_belong_to_act',
    'already_join_act'                  => '你已参加该活动',
    'timeid_forbid'                     => '该时间id不存在 或 不属于当前活动',
    'addressid_forbid'                  => '该地点id不存在 或 不属于当前活动',

    'nothing_update'                    =>             '信息未改动',
    'register_success'                  =>             '注册成功',
    'empty_file'                        =>             '未检测到上传文件',
    'refuse_extension'                  =>             '上传文件格式未被允许',
    'exceed_size'                       =>             '所上传文件太大',
    'type_error'                        =>             'type错误，请查看api文档',
    'check_user_non-exist'              =>             '对应用户不存在',
    'check_skill_non-exist'             =>             '对应技能不存在',
    'check_skill_user_exist'            =>             '用户已添加该技能',
    'check_skill_user_non-exist'        =>             '用户未添加该技能',
    'validation_tip'                    =>             '请检测并添加字段sometimes验证规则',
    'check_prize_exist'                 =>             '已存在该奖项',
    'check_prize_not-exist'             =>             '未添加该奖项',
    'CAPTAIN'                           =>             '团队创建者才能进行此操作',
    'LEADER'                            =>             '团队管理员才能进行此操作',
    'MEMBER'                            =>             '团队成员才能进行此操作',
    'check_member_exist'                =>             '已存在该成员',
    'already_sign'                      =>             '今天已签到',
    'weekly_group_permission'           =>             '只有周报创建者才能编辑删除',
    'check_item_status_exist'           =>             '该项目非招募状态',
    'check_item_type_exist'             =>             '项目类型不存在',
    'check_technology_exist'            =>             '开发者类型不存在',
    'check_item_master'                 =>             '非项目发布者',
    'filename_exceed'                   =>             '文件名不能超过64字符',
    'item_non-exist'                    =>             '项目不存在或已完成招募',
    'item_publisher'                    =>             '您为该项目发布者'
];