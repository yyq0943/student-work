<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['roles', 'college'];

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'gender' => $user->gender,
            'picture' => $user->picture,
            'nickname' => $user->nickname,
            'is_super_admin' => $user->isSuperAdmin(),
            'gender_str' => $user->gender ? '女' : '男',
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString()
        ];
    }

    public function includeRoles(User $user)
    {
        $roles = $user->roles()->ancient()->get();
        return $this->collection($roles, new RoleTransformer());
    }

    public function includeCollege(User $user)
    {
        if (!!$user->college()->count()) {
            return $this->item($user->college, new CollegeTransformer());
        }
        return $this->null();
    }
}
