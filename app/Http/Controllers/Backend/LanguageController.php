<?php

/**
 * LanguageController
 *
 * This is the controller for the shop languages
 * @author Matthijs Neijenhuijs <matthijs@hideyo.io>
 * @version 0.1
 */


use Hideyo\Ecommerce\Framework\Repositories\LanguageRepositoryInterface;

class LanguageController extends BaseController
{
    
    public function __construct(LanguageRepositoryInterface $language)
    {
        $this->language = $language;
    }

    public function index()
    {
        if (Request::wantsJson()) {

            return \Datatable::collection($this->language->selectAll())
                ->showColumns('id', 'language')
                ->addColumn('actions', function ($model) {
                    $deleteLink = \Form::deleteajax('/language/'. $model->id, 'Delete', '', array('class'=>'btn btn-default btn-sm btn-icon btn-danger'));
                    $links = '<a href="/language/'.$model->id.'/edit" class="btn btn-default btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Edit</a>  '.$deleteLink;
                
                    return $links;
                })
                ->searchColumns('language')
                ->orderColumns('id', 'language')
                ->make();


        }
        
        return \View::make('language.index')->with('language', $this->language->selectAll());
    }

    public function create()
    {
        return \View::make('language.create')->with(array());
    }

    public function store()
    {

        $result  = $this->language->create(Request::all());
 
        if ($result->id) {
            Log::info('Tax '.$result->name.' inserted');
            // Notification::success('The product was inserted.');
            return Redirect::route('language.index');
        } else {
            Notification::error('field are required');
        }

        return Redirect::back()->withInput()->withErrors($result->errors()->all());
    }

    public function edit($languageId)
    {
        return \View::make('language.edit')->with(array('language' => $this->language->find($languageId)));
    }

    public function update($languageId)
    {
        $result  = $this->language->updateById(Request::all(), $languageId);

        if ($result->errors()->all()) {
            return Redirect::back()->withInput()->withErrors($result->errors()->all());
        } else {
            Log::info('Tax '.$result->name.' updated');
            //Notification::success('The product was updated.');
            return Redirect::route('language.index');
        }
    }

    public function destroy($languageId)
    {
        $result  = $this->language->destroy($languageId);

        if ($result) {
            Notification::success('The product was deleted.');
            return Redirect::route('language.index');
        }
    }
}
