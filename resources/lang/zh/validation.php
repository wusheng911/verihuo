<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => '这个 :attribute must be accepted.',
    'active_url'           => '这个 :attribute 此链接无效.',
    'after'                => '这个 :attribute 必须在此日期之后 :date.',
    'alpha'                => '这个 :attribute 仅限字母.',
    'alpha_dash'           => '这个 :attribute 可以是字母数字符号的组合.',
    'alpha_num'            => '这个 :attribute 可以是字母和数字的组合.',
    'array'                => '这个 :attribute 必须是数字的组合.',
    'before'               => '这个 :attribute 必须在此日期之前 :date.',
    'between'              => [
        'numeric' => '这个 :attribute 必须介于 :min and :max.',
        'file'    => '这个 :attribute 必须介于 :min and :max kilobytes.',
        'string'  => '这个 :attribute 必须介于 :min and :max characters.',
        'array'   => '这个 :attribute 必须介于 :min and :max items.',
    ],
    'boolean'              => '这个 :attribute 字段必须是真或是假.',
    'confirmed'            => '这个 :attribute 确认不匹配.',
    'date'                 => '这个 :attribute 无效日期.',
    'date_format'          => '这个 :attribute 格式不匹配 :format.',
    'different'            => '这个 :attribute 和 :other 必须不同.',
    'digits'               => '这个 :attribute 必须 be :digits 数字.',
    'digits_between'       => '这个 :attribute 必须介于 :min 和 :max 数字.',
    'distinct'             => '这个 :attribute 现场有一个重复的值.',
    'email'                => '这个 :attribute 必须是一个有效的E-mail地址.',
    'exists'               => '这个 selected :attribute 是无效的.',
    'filled'               => '这个 :attribute 字段是必须的.',
    'image'                => '这个 :attribute 必须是一个图像.',
    'in'                   => '这个 selected :attribute 是无效的.',
    'in_array'             => '这个 :attribute 现场中不存在 :other.',
    'integer'              => '这个 :attribute 必须是一个整数.',
    'ip'                   => '这个 :attribute 必须是一个有效的IP地址.',
    'json'                 => '这个 :attribute 必须是有效的JSON字符串.',
    'max'                  => [
        'numeric' => '这个 :attribute 不得大于 :max.',
        'file'    => '这个 :attribute 不得大于 :max kilobytes.',
        'string'  => '这个 :attribute 不得大于 :max characters.',
        'array'   => '这个 :attribute 不得多于 :max items.',
    ],
    'mimes'                => '这个 :attribute 必须是类型的文件: :values.',
    'min'                  => [
        'numeric' => '这个 :attribute 必须至少 :min.',
        'file'    => '这个 :attribute 必须至少 :min kilobytes.',
        'string'  => '这个 :attribute 必须至少 :min characters.',
        'array'   => '这个 :attribute 必须至少 :min items.',
    ],
    'not_in'               => '这个 selected :attribute 是无效的.',
    'numeric'              => '这个 :attribute 必须是数字.',
    'present'              => '这个 :attribute 现场必须存在.',
    'regex'                => '这个 :attribute 格式无效.',
    'required'             => '这个 :attribute 字段是必须的.',
    'required_if'          => '这个 :attribute 字段是必须的当 :other is :value.',
    'required_unless'      => '这个 :attribute 字段是必须的除非 :other is in :values.',
    'required_with'        => '这个 :attribute 字段是必须的当 :values is present.',
    'required_with_all'    => '这个 :attribute 字段是必须的当 :values is present.',
    'required_without'     => '这个 :attribute 字段是必须的当 :values is not present.',
    'required_without_all' => '这个 :attribute 字段是必须的当没有 :values are present.',
    'same'                 => '这个 :attribute 和 :other 必须匹配.',
    'size'                 => [
        'numeric' => '这个 :attribute 必须 :size.',
        'file'    => '这个 :attribute 必须 :size kilobytes.',
        'string'  => '这个 :attribute 必须 :size characters.',
        'array'   => '这个 :attribute 必须包含 :size items.',
    ],
    'string'               => '这个 :attribute 必须是字符串.',
    'timezone'             => '这个 :attribute 应该是一个有效时区.',
    'unique'               => '这个 :attribute 已经存在.',
    'url'                  => '这个 :attribute 格式不正确.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'name' => [
            'required' => '姓名不能为空',
        ],
        'email' => [
            'required' => '邮箱不能为空',
        ],
        'password' => [
            'required' => '密码不能为空',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

    'entrust' => [
        'name.required' => '识别名不能为空',
        'name.unique' => '识别名称已经存在',
        'display_name.required' => '名称不能为空',
    ],

];
