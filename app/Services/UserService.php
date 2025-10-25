<?php

namespace App\Services;


// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
    }
    public function delete($userID,$requesterClientID){

        try {

            $user = User::find($userID);

            if(!$user) {
                return null;
            }

            $userClientID = $user["client_id"];
            if($userClientID != $requesterClientID){ 
                return null;
            }

            $user->delete(); 
            return $user; 
        } catch (\Throwable $th) {
            // dd(__LINE__);
            // dd($th->getMessage());
            Log::error([
                'errorMessage' =>  $th->getMessage(),
                'file' => $th->getFile(),
                'numberrrrr' => $th->getLine()
            ]);
            return false;
        }

        // dd(vars: $user);

        // $query = \DB::getQueryLog();
        // dd($query[0]['query'], $query[0]['bindings']);    
    }

}