<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact; 
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Muestra un listado del recurso.
     */
    public function index(Request $request)
    {
        $contactos = Contact::where('user_id',$request->user()->id)->get();
        foreach($contactos as $contacto){
            $contacto->avatar = $contacto->avatar ? url(Storage::url($contacto->avatar)):null;
        }
        return response()->json(['mensaje' => 'Lista De Contactos','datos' => $contacto],201);
    }

    /**
     * Almacena un recurso recién creado.
     */
    public function store(Request $request)
    {
         $datos = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        $rutaavatar = null;
        if($request->hasFile('avatar')){
            $rutaavatar = $request->file('avatar')->store('avatars','public');

        }
        $datos['avatar'] = $rutaavatar;
        $datos['user_id'] = $request->user()->id;
        $contactos = Contact::create($datos);
        $contactos->avatar = $rutaavatar ? url(Storage::url($rutaavatar)):null;
        return response()->json(['mensaje' =>'Contacto Creado Correctamente','datos' => $contactos ], 201);
    }
   /**
     * Muestra el recurso especificado.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     */
   public function update(Request $request, string $id)
    {
        $contacto = Contact::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $datos = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email',
            'address' => 'sometimes|required|string',
            'phone_number' => 'sometimes|required|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($contacto->avatar) {
                Storage::disk('public')->delete($contacto->avatar);
            }

            $datos['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $contacto->update($datos);

        $contacto->avatar = $contacto->avatar
            ? url(Storage::url($contacto->avatar))
            : null;

        return response()->json([
            'mensaje' => 'Contacto actualizado correctamente',
            'datos' => $contacto
        ], 200);
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     */
   public function destroy(Request $request, string $id)
    {
        $contacto = Contact::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $contacto->delete();

        return response()->json([
            'mensaje' => 'Contacto eliminado correctamente'
        ], 200);
    }
}
