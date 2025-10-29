<?php

namespace App\Services\user;


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
    public function usersByClientId(
        $clientId,
        ?int $userId = null,
        bool $paginate = false,
        int $perPage = 15
    ) {
        try {
            $query = User::where('client_id', $clientId)
                ->orderBy('created_at', 'desc')
                ->select(['id','name', 'email', 'created_at', 'client_ID']);

            if (!is_null($userId)) {
                $query->where('id', $userId);
            }

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
            Log::error('UserService error: ' . $th->getMessage().''. $th->getLine());
            return $this->error();
        }
    
    }
    public function delete($userID,$requesterClientID){

        try {

            $user = User::find($userID);

            if(!$user) {
                return $this->error('User not found', 'NOTFOUND');
            }

            $checkSameClient = $this->checkSameClient($user->client_id);

            if(!$checkSameClient) { 
                return $this->error('User is not from the same client', 'NOTFOUND');
            }


            $user->delete(); 
            return $this->success(null, 'User deleted successfully');
        } catch (\Throwable $th) {
            Log::error('UserService error: ' . $th->getMessage().''. $th->getFile() .''. $th->getLine());
            return $this->error();
        }
    
    }

    public function update($userFields){

        try {

            $user = User::find($userFields['user_ID']);

            if(!$user) {
                return $this->error('User not found', 'NOTFOUND');
            }

            // dd( $user->client_id);

            $checkSameClient = $this->checkSameClient($user->client_id);

            if(!$checkSameClient) { 
                return $this->error('User is not from the same client', 'NOTFOUND');
            }

            $fieldsToUpdate = array_filter(
                $userFields,
                fn($key) => in_array($key, $user->getFillable()),
                ARRAY_FILTER_USE_KEY
            );


            $user->update($fieldsToUpdate); 
            return $this->success(null, 'User updated successfully');
        } catch (\Throwable $th) {
            Log::error('UserService error: ' . $th->getMessage().''. $th->getFile() .''. $th->getLine());
            return $this->error();
        }
    
    }

}