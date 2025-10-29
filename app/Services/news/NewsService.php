<?php

namespace App\Services\news;


use Illuminate\Support\Facades\Log;
use App\Services\BaseService;


use App\Models\News;


Class NewsService extends BaseService {

    /**
     * @param int $clientId
     * @return array{
     *     status: bool,
     *     code: string,
     *     message: string,
     *     data: array<int, array{title: string, subtitle: string, text: string, category: int}>|null
     * }
     */
    public function newsByClientId(
        $clientId,
        ?int $newsId = null,
        bool $paginate = false,
        int $perPage = 15
    ) {
        try {
            $query = News::where('client_id', $clientId)
                ->orderBy('created_at', 'desc')
                ->select(['id','title', 'subtitle', 'text', 'category']);

            if (!is_null($newsId)) {
                $query->where('id', $newsId);
            }

            $data = $paginate
                ? $query->paginate($perPage)->toArray()
                : $query->get()->toArray();

            return $this->success($data);
        } catch (\Throwable $th) {
            Log::error('newsService error: ' . $th->getMessage());
            return $this->error();
        }
    }

    public function create($userData){

        try {
            
            $user = News::create([
                'title' => $userData["title"],
                'subtitle' => $userData["subtitle"],
                'text' => $userData["text"],
                'category' => $userData["category"],
                'client_id' => $userData["client_id"]
            ]);
            return $this->success(null,'news added '.$user->name);
        } catch (\Throwable $th) {
            Log::error('newsService error: ' . $th->getMessage().''. $th->getLine());
            return $this->error();
        }
    
    }
    public function delete($newsID){

        try {

            $news = news::find($newsID);

            if(!$news) {
                return $this->error('news not found', 'NOTFOUND');
            }

            $checkSameClient = $this->checkSameClient($news->client_id);

            if(!$checkSameClient) { 
                return $this->error('news is not from the same client', 'NOTFOUND');
            }


            $news->delete(); 
            return $this->success(null, 'news deleted successfully');
        } catch (\Throwable $th) {
            Log::error('newsService error: ' . $th->getMessage().''. $th->getFile() .''. $th->getLine());
            return $this->error();
        }
    
    }

    public function update($newsFields){

        try {

            $news = News::find($newsFields['news_ID']);

            if(!$news) {
                return $this->error('news not found', 'NOTFOUND');
            }


            $checkSameClient = $this->checkSameClient($news->client_id);

            if(!$checkSameClient) { 
                return $this->error('news is not from the same client', 'NOTFOUND');
            }

            $fieldsToUpdate = array_filter(
                $newsFields,
                fn($key) => in_array($key, $news->getFillable()),
                ARRAY_FILTER_USE_KEY
            );


            $news->update($fieldsToUpdate); 
            return $this->success(null, 'news updated successfully');
        } catch (\Throwable $th) {
            Log::error('newsService error: ' . $th->getMessage().''. $th->getFile() .''. $th->getLine());
            return $this->error();
        }
    
    }

}