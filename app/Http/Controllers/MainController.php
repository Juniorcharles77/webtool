<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Page;
use App\Models\ToolView;
use App\Settings\GeneralSettings;
use App\Settings\LanguageSettings;
use App\Settings\ToolSlugSettings;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class MainController extends Base
{
    public function index(ToolSlugSettings $toolSlugSettings, GeneralSettings $generalSettings) {
        $settings = DB::table('settings')->where('group', 'LIKE', 'tool-%')->whereIn('name', ['title', 'summary', 'enabled'])->get([ 'name', 'group', 'payload' ]);

        $settings = $settings->groupBy(function ($item) {
            return $item->group. '.' . $item->name;
        });

        return view('homepage', [
            'title'       => trans('webtools/pages.homepage'),
            'description' => $generalSettings->websiteDescription,
            'keywords'    => $generalSettings->websiteKeywords,
            'categories'  => config('tools.categories'),
            'slugs'       => $toolSlugSettings,
            'toolOptions' => $settings->toArray()
        ]);
    }

    public function page(Page $page) {
        return view('page', [
            'title'       => $page->title,
            'description' => $page->seoDescription,
            'keywords'    => $page->seoKeywords, 
            'page'        => $page
        ]);
    }

    public function contact(GeneralSettings $generalSettings) {
        abort_if( ! $generalSettings->contactPage, 404, 'Not Found' );

        return view('contact', [
            'title'       => trans('webtools/pages.contact'),
            'description' => $generalSettings->contactDescription,
            'keywords'    => 'contact us, get in touch',
            'wire'        => true,
            'recaptcha'   => $generalSettings->recaptchaEnabled
        ]);
    }

    public function tool($slug, ToolSlugSettings $toolSlugs) {
        $categories = config('tools.categories');
        $tool       = null;
        $key        = null;
        
        // Resolve Tool from Slug
        foreach($toolSlugs->originalValues as $toolKey => $toolSlug)
            if( trim( strtolower( $slug ) ) == $toolSlug )
                $key = $toolKey;
            
        abort_if(!$key, 404, 'NOT FOUND');

        // Resolve Tool from Given Key
        $category = null;
        foreach($categories as $category) {
            if( isset($category[ 'tools' ][ $key ]) ) {
                $tool = $category[ 'tools' ][ $key ];
                break;
            }
        }
        
        abort_if($tool === null, 404, 'NOT FOUND');
            
        ToolView::addView($tool['name']);

        if( isset($tool['controller']) ) 
            return app($tool['controller'])->index();
        else {
            $settings = app($tool['settings']);

            abort_if(!$settings->enabled, 404, 'NOT FOUND');
            abort_if(!can_use($key), 403, 'UNAUTHORIZED');

            $related = $category['tools'];

            $keys = array_map(fn($tool) => 'tool-' . $tool['name'], $related);
            
            $toolOptions = DB::table('settings')->whereIn('group', $keys)->whereIn('name', ['title', 'summary', 'enabled'])->get([ 'name', 'group', 'payload' ])->groupBy(function ($item) {
                return $item->group. '.' . $item->name;
            });

            return view('modules.tool-base', [
                'title'         => $settings->title,
                'description'   => $settings->metaDescription,
                'keywords'      => $settings->metaKeywords,
                'tool'          => $tool,
                'toolSettings'  => $settings,
                'toolSlugs'     => $toolSlugs,
                'related'       => $related,
                'toolOptions'   => $toolOptions,

                'scripts'       => isset( $tool['scripts'] ) ? $tool['scripts'] : null,
                'styles'        => isset( $tool['styles'] ) ? $tool['styles'] : null,

                'wireComponent' => isset( $tool['component'] ) ? $tool['component'] : null,
                'wire'          => isset( $tool['component'] ),
                'view'          => isset( $tool['view'] ) ? $tool['view'] : null,
            ]);
        }
    }

    public function language(LanguageSettings $languageSettings, $code) {
        foreach($languageSettings->languages as $language) {
            if(strtolower($code) == strtolower(trim($language['code']))) {
                Cookie::queue( Cookie::forever('lang', strtolower($code)) );
                return redirect('/');
            }
        }

        Cookie::queue( Cookie::forever('lang', 'en') );

        return redirect('/');
    }

    public function theme($theme) {
        if($theme == 'light' || $theme == 'dark') {
            Cookie::queue( Cookie::forever('theme', $theme) );
            return redirect('/');
        } else
            Cookie::queue( Cookie::forever('theme', 'light') );

        return redirect('/');
    }

    public function blog(GeneralSettings $settings) {
        if(!$settings->blogSection)
            return abort(404, 'Not Found');

        return view('blog', [
            'title'       => $settings->blogTitle,
            'description' => $settings->blogDescription,
            'keywords'    => $settings->blogKeywords,

            'posts'       => BlogPost::select('title', 'slug', 'summary', 'thumbnail', 'created_at')->paginate(9)
        ]);
    }

    public function blogPost(GeneralSettings $settings, BlogPost $post) {
        if(!$settings->blogSection)
            return abort(404, 'Not Found');

        return view('blog-post', [
            'title' => $post->title,
            'description' => $post->summary,
            'keywords'    => $post->keywords,

            'post' => $post
        ]);
    }
}
