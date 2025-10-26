<?php

namespace App\Services;


// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\BaseService;


use App\Models\User;


Class UserService extends BaseService {

    /**
     * @param int $clientId
     * @return array{
     *     status: bool,
     *     code: string,
     *     message: string,
     *     data: array<int, array{name: string, email: string, created_date: string}>|null
     * }
     */
    public function allUsersByClientId(
        $clientId,
        bool $paginate = false,
        int $perPage = 15
    ) {
        try {
            $query = User::where('client_id', $clientId)
                ->orderBy('created_at', 'desc')
                ->select(['name', 'email', 'created_at']);

            $data = $paginate
                ? $query->paginate($perPage)->toArray()
                : $query->get()->toArray();

            return $this->success($data);
        } catch (\Throwable $th) {
            Log::error('UserService error: ' . $th->getMessage());
            return $this->error();
        }
    }

    public function create($userData){

        try {
            
            $user = User::create([
                'name' => $userData["name"],
                'email' => $userData["email"],
                'password' => $userData["password"],
                'client_id' => $userData["client_id"]
            ]);
            return $this->success(null,'user added '.$user->name);
        } catch (\Throwable $th) {
            Log::error('UserService error: ' . $th->getMessage());
            return $this->error();
        }
    
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
            Log::error([
                'errorMessage' =>  $th->getMessage(),
                'file' => $th->getFile(),
                'number' => $th->getLine()
            ]);
            return false;
        }
    
    }

}