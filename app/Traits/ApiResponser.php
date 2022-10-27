<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{
    private $pagedJSON = [
        'response' => [
            'status'  => '',
            'message' => ''
        ],
        'meta'    => [
            'has_next_page' => null,
            'count'         => 0,
            'per_page'      => 0,
            'total'         => 0,
            'message'       => ''
        ],
        'data'    => [],
    ];

    private $simpleJSON = [
        'response' => [
            'status' => '',
            'message' => '',
        ],
        'data'    => [],
    ];

    /**
     * Building success response
     * @param $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginateSuccessResponse($data, $code = Response::HTTP_OK)
    {
        $this->pagedJSON['response']['status'] = 'success';
        $this->pagedJSON['response']['message'] = '';
        $this->pagedJSON['data'] = $data->items();
        $this->pagedJSON['meta']['has_next_page'] = $data->nextPageUrl() != null;
        $this->pagedJSON['meta']['count'] = count($data);
        $this->pagedJSON['meta']['per_page'] = $data->perPage();
        $this->pagedJSON['meta']['total'] = $data->total();
        $this->pagedJSON['links']['first'] = $data->url(1);
        $this->pagedJSON['links']['last'] =  $data->url($data->lastPage());
        $this->pagedJSON['links']['prev'] = $data->previousPageUrl();
        $this->pagedJSON['links']['next'] = $data->nextPageUrl();
        return response()->json($this->pagedJSON, $code);
    }

    public function paginateSuccessResponseWithArrayData($paginate_data,$data=[],$code = Response::HTTP_OK)
    {
        $this->pagedJSON['response']['status'] = 'success';
        $this->pagedJSON['response']['message'] = '';
        $this->pagedJSON['data'] = $data;
        $this->pagedJSON['meta']['has_next_page'] = $paginate_data->nextPageUrl() != null;
        $this->pagedJSON['meta']['count'] = count($paginate_data);
        $this->pagedJSON['meta']['per_page'] = $paginate_data->perPage();
        $this->pagedJSON['meta']['total'] = $paginate_data->total();
        $this->pagedJSON['links']['first'] = $paginate_data->url(1);
        $this->pagedJSON['links']['last'] =  $paginate_data->url($paginate_data->lastPage());
        $this->pagedJSON['links']['prev'] = $paginate_data->previousPageUrl();
        $this->pagedJSON['links']['next'] = $paginate_data->nextPageUrl();
        return response()->json($this->pagedJSON, $code);
    }
    /**
     * 
     */
    public function successResponse($data = [] , $code = Response::HTTP_OK, $message = '')
    {
        $this->simpleJSON['response']['status'] = 'success';
        $this->simpleJSON['response']['message'] = $message;
        $this->simpleJSON['data'] = $data;
        return response()->json($this->simpleJSON, $code);
    }

    /**
     * 
     */
    public function errorResponse($message, $code)
    {
        $this->simpleJSON['response']['status'] = 'error';
        $this->simpleJSON['response']['message'] = $message;
        return response()->json($this->simpleJSON, $code);
    }

    /**
     * Pagination Constant Method
     */
    public function queryPaginate($query, $orderByKey='created_at', $orderByValue='desc', $paginateNumber=12)
    {
        return $query->orderBy($orderByKey, $orderByValue)->paginate($paginateNumber);
    }
}