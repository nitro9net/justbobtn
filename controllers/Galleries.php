<?php namespace Nitro9net\BlogPhotos\Controllers;

use Backend;
use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;
use Flash;
use RainLab\Blog\Models\Post as BlogPost;
use Redirect;

class Galleries extends Controller
{
    public $implement = [
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ImportExportController::class
    ];

    public $listConfig = '$/nitro9net/blogphotos/controllers/galleries/config_list.yaml';

    public $formConfig = '$/nitro9net/blogphotos/controllers/galleries/config_form.yaml';

    public $importExportConfig = '$/nitro9net/blogphotos/controllers/galleries/config_import_export.yaml';

    public $requiredPermissions = ['nitro9net.blogphotos.manage_galleries'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Nitro9net.BlogPhotos', 'blogphotos', 'galleries');
    }

    public function listExtendQuery($query)
    {
        $postIds = $this->getPostIdsWithGalleries();

        if (!$postIds) {
            $query->whereRaw('1 = 0');
            return;
        }

        $query->whereIn('id', $postIds);
    }

    public function onClearSelectedGalleries()
    {
        $checked = post('checked', []);

        if (!$checked || !is_array($checked)) {
            Flash::warning(trans('nitro9net.blogphotos::lang.messages.no_galleries_selected'));
            return $this->listRefresh();
        }

        foreach (BlogPost::whereIn('id', $checked)->get() as $post) {
            $post->gallery_images()->delete();
        }

        Flash::success(trans('nitro9net.blogphotos::lang.messages.galleries_deleted'));

        return $this->listRefresh();
    }

    public function update($recordId = null, $context = null)
    {
        $this->bodyClass = 'compact-container';

        return $this->asExtension('FormController')->update($recordId, $context);
    }

    public function onClearGallery()
    {
        $recordId = post('record_id');

        if ($post = BlogPost::find($recordId)) {
            $post->gallery_images()->delete();
            Flash::success(trans('nitro9net.blogphotos::lang.messages.gallery_deleted'));
        }

        return Redirect::to(Backend::url('nitro9net/blogphotos/galleries'));
    }

    protected function getPostIdsWithGalleries(): array
    {
        return \System\Models\File::where('attachment_type', BlogPost::class)
            ->where('field', 'gallery_images')
            ->pluck('attachment_id')
            ->unique()
            ->values()
            ->all();
    }
}
