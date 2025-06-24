<?php

namespace App\Services;



use App\Models\User;


Class UserService {

    function allUsersByClientId(
        $clientId,
        bool $paginate = false, 
        int $perPage = 15
        ) {

        $query = User::where('client_id', $clientId)
                    ->orderBy('created_at', 'desc');

        return $paginate 
            ? $query->paginate($perPage)->toArray()
            : $query->get()->toArray();

    }

    public function create($userData){

        return User::create([
            'name' => $userData["name"],
            'email' => $userData["email"],
            'password' => $userData["password"]
        ]);
    }

}