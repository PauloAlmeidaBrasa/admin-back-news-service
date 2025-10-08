<?php

namespace App\Services;


// use Illuminate\Support\Facades\DB;

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

        // \DB::enableQueryLog();

        return User::create([
            'name' => $userData["name"],
            'email' => $userData["email"],
            'password' => $userData["password"],
            'client_id' => $userData["client_id"]
        ]);
        // $query = \DB::getQueryLog();
        // dd($query[0]['query'], $query[0]['bindings']);    
    }

}