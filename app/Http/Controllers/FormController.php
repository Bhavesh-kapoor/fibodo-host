<?php

namespace App\Http\Controllers;

use App\Http\Resources\FormResource;
use App\Http\Resources\FormTypeResource;
use App\Models\Form;
use Exception;
use Request;

class FormController extends Controller
{
    /**
     * Get All.
     */
    public function index()
    {
        try {
            return response()->success(
                'messages.success',
                FormResource::collection(Form::paginate())
            );
        } catch (Exception $e) {
            return respone()->error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * getFormTypes
     *
     * @param  mixed $form
     * @param  mixed $request
     * @return void
     */
    public function getFormTypes(Request $request, Form $form)
    {
        try {
            return response()->success(
                'messages.success',
                FormTypeResource::collection($form->formType()->paginate())
            );
        } catch (Exception $e) {
            return respone()->error($e->getMessage(), null, $e->getCode());
        }
    }
}
