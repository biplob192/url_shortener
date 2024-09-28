<?php

namespace App\Services;

use Exception;
use App\Models\Url;


class UrlService
{
    public function formateDataForIndexWithPaginate($request)
    {
        try {
            $perPage = $request->get('perPage', 5);
            $conditions = [
                'user_id' => auth()->id(),
                // Add more conditions if needed
            ];

            $search = $request->get('search', null);
            $searchableColumns = ['original_url', 'short_url'];

            // Define special searchable columns for specific column-based search
            $specialSearchableColumns = ['original_url', 'short_url'];

            // Collect any special search fields that are present in the request
            $specialSearch = [];
            foreach ($specialSearchableColumns as $column) {
                if ($request->has($column)) {
                    $specialSearch[$column] = $request->get($column);
                }
            }

            // Return all formatted data as an array
            return [
                'perPage' => $perPage,
                'conditions' => $conditions,
                'search' => $search,
                'searchableColumns' => $searchableColumns,
                'specialSearch' => $specialSearch,
            ];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }



    public function ajaxDestroy($id)
    {
        try {
            if (Url::find($id)->delete()) {
                return response()->json([
                    'success' => true,
                    'message' => "Deleted successfully.",
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function ajaxShow($id)
    {
        try {
            $url = Url::findOrFail($id);

            if ($url) {
                return response()->json([
                    'success' => true,
                    'message' => "Url retrieved successfully.",
                    'data'    => $url,
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
