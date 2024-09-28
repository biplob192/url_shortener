<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Url;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\UrlService;
use App\Http\Requests\UrlRequest;
use App\Interfaces\UrlRepositoryInterface;

class UrlController extends Controller
{
    public function __construct(
        private UrlRepositoryInterface $urlRepository,
        private UrlService $urlService,
    ) {}


    public function index(Request $request)
    {
        try {
            if ($request->has('reset')) {
                return redirect()->route('urls.index');
            }

            $formatedData = $this->urlService->formateDataForIndexWithPaginate($request);

            $response = $this->urlRepository->indexWithPaginate(
                $formatedData['perPage'],
                $formatedData['conditions'],
                $formatedData['search'],
                $formatedData['searchableColumns'],
                $formatedData['specialSearch']
            );

            return view('urls.index', [
                'urls' => $response,
                'perPage' => $formatedData['perPage'],
                'search' => $formatedData['search'],
                'specialSearch' => $formatedData['specialSearch'],
            ]);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }


    public function show($id)
    {
        try {
            $response = $this->urlRepository->show($id);
            return view('urls.show', ['user' => $response['data']]);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }


    public function create()
    {
        try {
            return view('urls.create');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }


    public function store(Request $request)
    {
        try {
            // Validate the request and get the validated data
            $validatedData = UrlRequest::validate($request->all());

            $validatedData['short_url'] = url('/u/') . '/' . Str::random(6);
            $validatedData['user_id'] = auth()->id();

            // Create new short URL
            $response = $this->urlRepository->store($validatedData);

            return redirect()->route('urls.index')->with('success', $response['message']);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }


    public function edit($id)
    {
        try {
            $response = $this->urlRepository->show($id);
            return view('urls.edit', ['user' => $response['data']]);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validatedData = UrlRequest::validate($request->all());
            $response = $this->urlRepository->update($id, $validatedData);

            return redirect()->route('urls.index')->with('success', $response['message']);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $response = $this->urlRepository->delete($id);
            return redirect()->route('urls.index')->with('success', $response['message']);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }



    public function visitUrl($url)
    {
        try {
            $shortUrl = url('/u/') . '/' . $url;
            $urlData = Url::where('short_url', $shortUrl)->first();

            if ($urlData) {
                $urlData->url_visit_count += 1;
                $urlData->save();
            }

            return redirect()->away($urlData->original_url);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }


    public function ajaxShow($id)
    {
        return $this->urlService->ajaxShow($id);
    }


    public function ajaxDestroy($id)
    {
        return $this->urlService->ajaxDestroy($id);
    }
}
