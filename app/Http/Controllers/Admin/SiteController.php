<?php namespace App\Http\Controllers\Admin;

use App\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreateSiteRequest;
use App\Http\Requests\Validations\UpdateSiteRequest;

class SiteController extends Controller
{
    private $model;

    /**
     * construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = trans('app.site');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = Site::orderBy('order', 'asc')->get();

        $trashes = Site::onlyTrashed()->get();

        return view('admin.site.index', compact('sites', 'trashes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.site._create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSiteRequest $request)
    {
        $site = Site::create($request->all());

        return back()->with('success', trans('messages.created', ['model' => $this->model]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Site $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        return view('admin.site._edit', compact('site'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSiteRequest $request
     * @param Site $site
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSiteRequest $request, Site $site)
    {
        if( config('app.demo') == true && $site->id <= config('system.demo.sites', 4) )
            return back()->with('warning', trans('messages.demo_restriction'));

        $site->update($request->all());

        return back()->with('success', trans('messages.updated', ['model' => $this->model]));
    }

    /**
     * Trash the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param Site $site
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, Site $site)
    {
        if( config('app.demo') == true && $site->id <= config('system.demo.sites', 4) )
            return back()->with('warning', trans('messages.demo_restriction'));

        $site->delete();

        return back()->with('success', trans('messages.trashed', ['model' => $this->model]));
    }

    /**
     * Restore the specified resource from soft delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        Site::onlyTrashed()->findOrFail($id)->restore();

        return back()->with('success', trans('messages.restored', ['model' => $this->model]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param Site $site
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( config('app.demo') == true && $site->id <= config('system.demo.sites', 4) )
            return back()->with('warning', trans('messages.demo_restriction'));

        $site = Site::onlyTrashed()->findOrFail($id);

        $site->forceDelete();

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }


    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massTrash(Request $request)
    {
        if(config('app.demo') == true)
            return back()->with('warning', trans('messages.demo_restriction'));

        Site::whereIn('id', $request->ids)->delete();

        if($request->ajax())
            return response()->json(['success' => trans('messages.trashed', ['model' => $this->model])]);

        return back()->with('success', trans('messages.trashed', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massRestore(Request $request)
    {
        Site::onlyTrashed()->whereIn('id', $request->ids)->restore();

        if($request->ajax())
            return response()->json(['success' => trans('messages.restored', ['model' => $this->model])]);

        return back()->with('success', trans('messages.restored', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        if(config('app.demo') == true)
            return back()->with('warning', trans('messages.demo_restriction'));

        Site::withTrashed()->whereIn('id', $request->ids)->forceDelete();

        if($request->ajax())
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

    /**
     * Empty the Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyTrash(Request $request)
    {
        Site::onlyTrashed()->forceDelete();

        if($request->ajax())
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

}