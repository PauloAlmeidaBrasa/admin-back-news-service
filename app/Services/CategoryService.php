<?php

namespace App\Services;


// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\BaseService;


use App\Models\Category;


Class CategoryService extends BaseService {

    /**
     * @param int $clientId
     * @return array{
     *     status: bool,
     *     code: string,
     *     message: string,
     *     data: array<int, array{name: string, description: string}>|null
     * }
     */
    public function categoryByClientId(
        $clientId,
        ?int $categoryId = null,
        bool $paginate = false,
        int $perPage = 15
    ) {
        try {

            $query = Category::where('client_id', $clientId)
                ->orderBy('created_at', 'desc')
                ->select(['id','name', 'description']);

            if (!is_null($categoryId)) {
                $query->where('id', $categoryId);
            }

            $data = $paginate
                ? $query->paginate($perPage)->toArray()
                : $query->get()->toArray();

            return $this->success($data);
        } catch (\Throwable $th) {
            Log::error('categoryService error: ' . $th->getMessage());
            return $this->error();
        }
    }

    public function create($userData){

        try {
            
            $user = Category::create([
                'title' => $userData["title"],
                'subtitle' => $userData["subtitle"],
                'text' => $userData["text"],
                'category' => $userData["category"],
                'client_id' => $userData["client_id"]
            ]);
            return $this->success(null,'category added '.$user->name);
        } catch (\Throwable $th) {
            Log::error('categoryService error: ' . $th->getMessage().''. $th->getLine());
            return $this->error();
        }
    
    }
    public function delete($categoryID){

        try {

            $category = Category::find($categoryID);

            if(!$category) {
                return $this->error('category not found', 'NOTFOUND');
            }

            $checkSameClient = $this->checkSameClient($category->client_id);

            if(!$checkSameClient) { 
                return $this->error('category is not from the same client', 'NOTFOUND');
            }


            $category->delete(); 
            return $this->success(null, 'category deleted successfully');
        } catch (\Throwable $th) {
            Log::error('categoryService error: ' . $th->getMessage().''. $th->getFile() .''. $th->getLine());
            return $this->error();
        }
    
    }

    public function update($categoryFields){

        try {

            $category = Category::find($categoryFields['category_ID']);

            if(!$category) {
                return $this->error('category not found', 'NOTFOUND');
            }


            $checkSameClient = $this->checkSameClient($category->client_id);

            if(!$checkSameClient) { 
                return $this->error('category is not from the same client', 'NOTFOUND');
            }

            $fieldsToUpdate = array_filter(
                $categoryFields,
                fn($key) => in_array($key, $category->getFillable()),
                ARRAY_FILTER_USE_KEY
            );


            $category->update($fieldsToUpdate); 
            return $this->success(null, 'category updated successfully');
        } catch (\Throwable $th) {
            Log::error('categoryService error: ' . $th->getMessage().''. $th->getFile() .''. $th->getLine());
            return $this->error();
        }
    
    }

}