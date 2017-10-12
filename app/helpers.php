<?php

use App\Models\Setting;
use App\Models\TaskProgress;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

if (!function_exists('array_swap')) {

    function array_swap(&$array, $i, $j)
    {
        if ($i != $j && array_key_exists($i, $array) && array_key_exists($j, $array)) {
            $temp = $array[$i];
            $array[$i] = $array[$j];
            $array[$j] = $temp;
        }
        return $array;
    }

}


if (!function_exists('get_lead_official')) {

    /**
     * 获取责任人
     * @param $taskProgress
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|string|static|static[]
     */
    function get_lead_official($taskProgress)
    {
        if ($taskProgress instanceof Model) {
            $userIds = explode(',', $taskProgress->user_id);
        } else {
            $userIds = explode(',', $taskProgress);
        }

        if (array_first($userIds) != null) {
            if (strtolower(array_first($userIds)) == TaskProgress::$personnelSign) {
                return array_values([['id' => 'all', 'name' => '全体人员']]);
            } elseif (count($userIds) == 1) {
                $user = User::find(array_first($userIds), ['id', 'name']);
                return [$user];
            } elseif (count($userIds) > 1) {
                return User::whereIn('id', $userIds)->get(['id', 'name']);
            }
        } else {
            return null;
        }
    }

}

/*if (!function_exists('setting')) {
    function setting($name = null)
    {
        if (is_null($name)) {
            return app(Setting::class);
        }

        if (is_array($name)) {
            return app(Setting::class)->findByName($name);
        }
    }

}*/